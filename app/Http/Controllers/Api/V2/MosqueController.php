<?php

namespace App\Http\Controllers\Api\V2;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V2\MosqueCollection;
use App\Http\Resources\V2\Mosque as MosqueResource;
use App\Mosque;
use Exception;

class MosqueController extends Controller
{
    private function Reply($data, $message, $status)
    {
        return response([
            'data'      => $data,
            'message'   => $message,
            'status'    => $status
        ]);
    }

    public function UpdateTimes(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id'  => 'required|integer',
                'fajr'       => 'nullable|integer',
                'zohr'       => 'nullable|integer',
                'asr'        => 'nullable|integer',
                'maghrib'    => 'nullable|integer',
                'isha'       => 'nullable|integer',
                'eq_fajr'    => 'nullable|integer',
                'eq_zohr'    => 'nullable|integer',
                'eq_asr'     => 'nullable|integer',
                'eq_maghrib' => 'nullable|integer',
                'eq_isha'    => 'nullable|integer',
            ]);
            if (auth()->user()->responsible()->where('mosque_id', $validData['mosque_id'])->get()->isEmpty()) {
                return $this->Reply('', 'Not Found.', 'error');
            }
            Mosque::find($validData['mosque_id'])->update($validData);
            return $this->Reply('', 'Updated.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function GetCities()
    {
        return $this->Reply(City::all(), '', 'succeed');
    }

    public function GetCity(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'id'      => 'required|integer|exists:cities',
            ]);
            $city = City::find($validData['id']);
            return $this->Reply($city, '', 'succeed');;
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function GetMosques()
    {
        $active_mosques = Mosque::where('status', '1')->orderBy('favorite_cont', 'desc')->get();
        return new MosqueCollection($active_mosques);
    }

    public function GetMosque(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'id'      => 'required|integer|exists:mosques',
            ]);
            $mosque = Mosque::find($validData['id']);
            return new MosqueResource($mosque);
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function AddMosque(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'city_id'    => 'required|integer|exists:cities,id',
                'name'       => 'required|string|max:50',
                'imam'       => 'nullable|string|max:50',
                'vertical'   => 'nullable|string|max:50',
                'horizontal' => 'nullable|string|max:50'
            ]);
            $validData['address'] = $validData['vertical'] . '-' . $validData['horizontal'];
            Mosque::create($validData);
            return $this->Reply('', 'Submited.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function GetFavorites()
    {
        try {
            $user = auth()->user();
            $mosques = [];
            foreach ($user->favorite as $fave) {
                array_push($mosques, $fave->mosque_id);
            }
            return new MosqueCollection(Mosque::find($mosques));
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function AddFavorites(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id' => 'required|integer|exists:mosques,id',
            ]);
            auth()->user()->favorite()->create($validData);
            $mosque = Mosque::find($validData['mosque_id']);
            $counter['favorite_cont'] = $mosque->favorite_cont + 1;
            $mosque->update($counter);
            return $this->Reply('', 'Submited.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function DeleteFavorites(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id' => 'required|integer|exists:mosques,id',
            ]);
            auth()->user()->favorite()->delete($validData);
            $mosque = Mosque::find($validData['mosque_id']);
            $counter['favorite_cont'] = $mosque->favorite_cont - 1;
            $mosque->update($counter);
            return $this->Reply('', 'Deleted.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function GetTimes(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'id'   => 'required|integer',
                'date' => 'required|date'
            ]);
            $updated_mosque = Mosque::find($validData['id']);
            if ($updated_mosque->updated_at > $validData['date']) {
                return new MosqueResource($updated_mosque);
            } else {
                return $this->Reply('', 'Not Updated.', 'succeed');
            }
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    // Submit New Responsible for One User:
    public function AddResbonsible(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id' => 'required|integer|exists:mosques,id',
            ]);
            auth()->user()->responsible()->create($validData);
            return $this->Reply('', 'Submited.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    // Get All Submited Responsibles with Counter:
    public function AllResbonsibles()
    {
        try {
            $mosques = Mosque::all();
            $all_data = [];
            foreach ($mosques as $mosque) {
                $data = [
                    'id'      => $mosque->id,
                    'name'    => $mosque->name,
                    'counter' => $mosque->responsible()->count()
                ];
                array_push($all_data, $data);
            }
            return $this->Reply($all_data, '', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    // Get Responsibles from One User:
    public function GetResbonsibles()
    {
        try {
            $user = auth()->user();
            $mosques = [];
            foreach ($user->responsible as $responsible) {
                array_push($mosques, $responsible->mosque_id);
            }
            return new MosqueCollection(Mosque::find($mosques));
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function GetFajr(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id' => 'required|integer|exists:mosques,id',
            ]);
            $mosque = Mosque::find($validData['mosque_id']);
            $data = [
                'azan'   => $mosque->fajr,
                'eqama'  => $mosque->eq_fajr
            ];
            return $this->Reply($data, '', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function GetZohr(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id' => 'required|integer|exists:mosques,id',
            ]);
            $mosque = Mosque::find($validData['mosque_id']);
            $data = [
                'azan'   => $mosque->zohr,
                'eqama'  => $mosque->eq_zohr
            ];
            return $this->Reply($data, '', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function GetAsr(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id' => 'required|integer|exists:mosques,id',
            ]);
            $mosque = Mosque::find($validData['mosque_id']);
            $data = [
                'azan'   => $mosque->asr,
                'eqama'  => $mosque->eq_asr
            ];
            return $this->Reply($data, '', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function GetMaghrib(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id' => 'required|integer|exists:mosques,id',
            ]);
            $mosque = Mosque::find($validData['mosque_id']);
            $data = [
                'azan'   => $mosque->maghrib,
                'eqama'  => $mosque->eq_maghrib
            ];
            return $this->Reply($data, '', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function GetIsha(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id' => 'required|integer|exists:mosques,id',
            ]);
            $mosque = Mosque::find($validData['mosque_id']);
            $data = [
                'azan'   => $mosque->isha,
                'eqama'  => $mosque->eq_isha
            ];
            return $this->Reply($data, '', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }
}
