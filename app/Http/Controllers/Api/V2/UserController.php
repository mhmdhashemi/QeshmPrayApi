<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Support\Str;
use App\Http\Resources\V2\User as UserResource;
use App\Mosque;

class UserController extends Controller
{
    private function Reply($data, $message, $status)
    {
        return response([
            'data'      => $data,
            'message'   => $message,
            'status'    => $status
        ]);
    }

    public function Dashboard()
    {
        try {
            $user = auth()->user();
            $fajr = Mosque::find($user->fajr_id);
            $zohr = Mosque::find($user->zohr_id);
            $asr = Mosque::find($user->asr_id);
            $maghrib = Mosque::find($user->maghrib_id);
            $isha = Mosque::find($user->isha_id);
            $data = [
                ($fajr) ? [
                    'id'    => $fajr->id,
                    'name'  => $fajr->name,
                    'azan'  => $fajr->fajr,
                    'eqama' =>  $fajr->eq_fajr
                ] : '',
                ($zohr) ? [
                    'id'    => $zohr->id,
                    'name'  => $zohr->name,
                    'azan'  => $zohr->zohr,
                    'eqama' =>  $zohr->eq_zohr
                ] : '',
                ($asr) ? [
                    'id'    => $asr->id,
                    'name'  => $asr->name,
                    'azan'  => $asr->asr,
                    'eqama' =>  $asr->eq_asr
                ] : '',
                ($maghrib) ? [
                    'id'    => $maghrib->id,
                    'name'  => $maghrib->name,
                    'azan'  => $maghrib->maghrib,
                    'eqama' =>  $maghrib->eq_maghrib
                ] : '',
                ($isha) ? [
                    'id'    => $isha->id,
                    'name'  => $isha->name,
                    'azan'  => $isha->isha,
                    'eqama' =>  $isha->eq_isha
                ] : '',
            ];
            return $this->Reply($data, '', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function AddToDashboard(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'mosque_id' => 'required|int|exists:mosques,id',
                'type'      => 'required',
            ]);
            switch ($validData['type']) {
                case '0':
                    $data['fajr_id'] = $validData['mosque_id'];
                    auth()->user()->update($data);
                    break;
                case '1':
                    $data['zohr_id'] = $validData['mosque_id'];
                    auth()->user()->update($data);
                    break;
                case '2':
                    $data['asr_id'] = $validData['mosque_id'];
                    auth()->user()->update($data);
                    break;
                case '3':
                    $data['maghrib_id'] = $validData['mosque_id'];
                    auth()->user()->update($data);
                    break;
                case '4':
                    $data['isha_id'] = $validData['mosque_id'];
                    auth()->user()->update($data);
                    break;
                default:
                    return $this->Reply('', 'Invalid.', 'error');
            }
            return $this->Reply('', 'Updated.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function Register(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'name'      => 'required|string|min:3|max:50',
                'phone'     => 'required|string|size:11|unique:users',
                'password'  => 'required|string|min:4|max:120',
            ]);
            $validData['password'] = bcrypt($validData['password']);
            $validData['api_token'] = Str::random(120);
            $user = User::create($validData);
            $response = [
                'id'        => $user->id,
                'name'      => $user->name,
                'api_token' => $user->api_token,
            ];
            return $this->Reply($response, 'Registered.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function Login(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'phone'     => 'required|string|size:11|exists:users',
                'password'  => 'required|string|min:4|max:120'
            ]);
            if (!auth()->attempt($validData)) {
                return $this->Reply('', 'Unauthorized.', 'error');
            }
            $user = auth()->user();
            $data = [
                'id'        => $user->id,
                'name'      => $user->name,
                'api_token' => $user->api_token,
            ];
            return $this->Reply($data, 'Logged In.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function UpdateName(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'name' => 'required|string|min:3|max:50',
            ]);
            auth()->user()->update($validData);
            return $this->Reply('', 'Updated.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function UpdatePhone(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'phone' => 'required|string|size:11|unique:users'
            ]);
            auth()->user()->update($validData);
            return $this->Reply('', 'Updated.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function UpdatePassword(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'password' => 'required|string|min:4|max:120',
            ]);
            $validData['password'] = bcrypt($validData['password']);
            auth()->user()->update($validData);
            return $this->Reply('', 'Updated.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }
}
