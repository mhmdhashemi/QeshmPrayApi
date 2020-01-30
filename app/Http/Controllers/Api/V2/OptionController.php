<?php

namespace App\Http\Controllers\Api\V2;

use App\Comment;
use App\Guid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Speaker;
use Exception;

class OptionController extends Controller
{
    private function Reply($data, $message, $status)
    {
        return response([
            'data'      => $data,
            'message'   => $message,
            'status'    => $status
        ]);
    }

    public function Comment(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'comment' => 'required',
            ]);
            Comment::create($validData);
            return $this->Reply('', 'Received.', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function Sounds()
    {
        $sounds = Speaker::all();
        return $this->Reply($sounds, 'Done.', 'succeed');
    }

    public function Guids()
    {
        return $this->Reply(Guid::all(), '', 'succeed');
    }

    public function GetGuid(Request $request)
    {
        try {
            $validData = $this->validate($request, [
                'id'      => 'required|integer|exists:guids',
            ]);
            $guid = Guid::find($validData['id']);
            return $this->Reply($guid, '', 'succeed');
        } catch (Exception $e) {
            return $this->Reply('', $e->getMessage(), 'error');
        }
    }

    public function Update()
    {
        $data = [
            'version' => 2
        ];
        return $this->Reply($data, '', 'succeed');
    }
}
