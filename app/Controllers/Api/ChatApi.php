<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ChatRoomModel;
use App\Models\ChatMessageModel;
use App\Models\UserModel;

class ChatApi extends BaseController
{
    private function getInput(): array
    {
        return $this->request->getJSON(true) ?? $this->request->getPost() ?? [];
    }

    public function rooms()
    {
        if (!$this->request->user) {
            return json_response(null, 401, 'Unauthorized');
        }

        $userId = $this->request->user->sub;

        $rooms = (new ChatRoomModel())
            ->select('chat_rooms.*, users.username, users.profile_picture')
            ->join('users', 'users.id = chat_rooms.owner_id')
            ->where('chat_rooms.user_id', $userId)
            ->findAll();

        $result = [];
        foreach ($rooms as $room) {
            $lastMsg = (new ChatMessageModel())
                ->where('room_id', $room['id'])
                ->orderBy('created_at', 'DESC')
                ->first();

            $result[] = [
                'id'           => $room['id'],
                'owner_id'     => $room['owner_id'],
                'owner_name'   => $room['username'],
                'owner_avatar' => $room['profile_picture']
                    ? base_url('img/user/' . $room['profile_picture'])
                    : null,
                'last_message' => $lastMsg['message'] ?? null,
                'last_time'    => $lastMsg ? $lastMsg['created_at'] : null,
            ];
        }

        return json_response($result);
    }

    public function messages($roomId)
    {
        if (!$this->request->user) {
            return json_response(null, 401, 'Unauthorized');
        }

        $userId = $this->request->user->sub;

        $room = (new ChatRoomModel())->find($roomId);
        if (!$room) {
            return json_response(null, 404, 'Room tidak ditemukan');
        }

        if ($room['user_id'] != $userId) {
            return json_response(null, 403, 'Akses ditolak');
        }

        $messages = (new ChatMessageModel())
            ->where('room_id', $roomId)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $result = [];
        foreach ($messages as $msg) {
            $result[] = [
                'id'        => $msg['id'],
                'type'      => $msg['type'],
                'message'   => $msg['message'],
                'sender_id' => $msg['sender_id'],
                'time'      => date('Y-m-d H:i:s', strtotime($msg['created_at'])),
            ];
        }

        return json_response($result);
    }

    public function send()
    {
        if (!$this->request->user) {
            return json_response(null, 401, 'Unauthorized');
        }

        $input = $this->getInput();

        $rules = [
            'room_id'  => 'required|numeric',
            'message'  => 'required',
        ];

        if (!$this->validate($rules, $input)) {
            return json_response(null, 400, $this->validator->getErrors());
        }

        $userId  = $this->request->user->sub;
        $roomId  = $input['room_id'];
        $message = $input['message'];

        $room = (new ChatRoomModel())->find($roomId);
        if (!$room) {
            return json_response(null, 404, 'Room tidak ditemukan');
        }

        if ($room['user_id'] != $userId) {
            return json_response(null, 403, 'Akses ditolak');
        }

        (new ChatMessageModel())->insert([
            'room_id'     => $roomId,
            'type'        => 'user',
            'message'     => $message,
            'sender_id'   => $userId,
            'receiver_id' => $room['owner_id'],
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return json_response(null, 201, 'Pesan terkirim');
    }
}
