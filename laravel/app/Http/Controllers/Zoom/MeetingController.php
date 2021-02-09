<?php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ZoomJWT;
use GuzzleHttp\Client;
use App\Meeting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


class MeetingController extends Controller
{
    use ZoomJWT;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function list(Request $request)
    {
        $path = 'users/me/meetings';
        $response = $this->zoomGet($path);

        $data = json_decode($response->body(), true);
        $data['meetings'] = array_map(function(&$meeting) {
            $meeting['statr_at'] = $this->toUnixTimeStamp($meeting['start_time'], $meeting['timezone']);
            return $meeting;
        }, $data['meeting']);

        return [
            'success' => $response->ok(),
            'data' => $data,
        ];

    }

    public function new()
    {
        return view('meetings.new');
    }

    public function create(Request $request, Meeting $meeting)
    { 
        $this->zoomRequest(Meeting::create([
            'topic' => $request['topic'],
            'agenda' => $request['agenda'],
            'start_at' => $request['start_at'],
            'start_url' => $this->zoomRequest(''),
            'join_url' => $request['join_url'],
        ]));

        return redirect()->route('mypage.matching');
    }

    public function get(Request $request, string $id)
    {
        $path = 'meetings/'.$id;
        $response = $this->zoomGet($path);

        $data = json_decode($response->body(), true);
        if($response->ok()) {
            $data['start_at'] = $this->toUnixTimeStamp($data['start_time'], $data['timezone']);
        }

        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string',
            'start_time' => 'required|date',
            'agenda' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $path = 'meetings/' . $id;
        $response = $this->zoomPatch($path, [
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => (new \DateTime($data['start_time']))->format('Y-m-d\TH:i:s'),
            'duration' => 30,
            'agenda' => $data['agenda'],
            'settings' => [
                'host_video' => false,
                'participant_video' => false,
                'waiting_room' => true,
            ]
        ]);

        return [
            'success' => $response->status() === 204,
            'data' => json_decode($response->body(), true),
        ];
    }

    public function delete(Request $request, string $id)
    {
        $path = 'meetings/' . $id;
        $response = $this->zoomDelete($path);

        return [
            'success' => $response->status() === 204,
            'data' => json_decode($response->body(), true),
        ];
    }


}
