<?php

namespace App\Http\Controllers;

use App\Events\SocketMessage;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function byUser(User $user)
    {
        $mess = Message::where('sender_id', Auth::id())
            ->where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Home', [
            'selectedConversation' => $user->toConversationArray(),
            'messages' => MessageResource::collection($mess),
        ]);
    }
    public function byGroup(Group $group)
    {
        $mess = Message::where('group_id', $group->id)
            ->latest()
            ->paginate(10);

        return Inertia::render('Home', [
            'selectedConversation' => $group->toConversationArray(),
            'messages' => MessageResource::collection($mess)
        ]);
    }

    public function loadOlder(Message $mess)
    {
        if ($mess->group_id) {
            $messages = Message::where('created_at', '<', $mess->created_at)
                ->where('group_id', $mess->group_id)
                ->latest()
                ->paginate(10);
        } else {
            $messages = Message::where('created_at', '<', $mess->created_at)
                ->where(function ($query) use ($mess) {
                    $query->where('sender_id', $mess->sender_id)
                        ->where('receiver_id', $mess->receiver_id)
                        ->orWhere('sender_id', $mess->receiver_id)
                        ->where('receiver_id', $mess->sender_id);
                });
        }
    }

    public function store(StoreMessageRequest $req) {
        $data = $req->validated();
        $data['sender_id'] = Auth::id();
        $receiverId = $data['receiver_id'] ?? null;
        $groupId = $data['group_id'] ?? null;

        $files = $data['attachments'] ?? [];

        $message = Message::create($data);

        $attachments = [];
        if($files){
            foreach ($files as $file) {
                $direct = 'atttachments/'. Str::random(32);
                Storage::makeDirectory($direct);

                $model = [
                    'message_id' => $message->id,
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'path' => $file->store($direct, 'public')
                ];

                $attachment = MessageAttachment::create($model);
                $attachments[] = $attachment;
            }
            $message->attachments = $attachments;
        }

        if($receiverId){
            Conversation::updateConversationWithMessage($receiverId, Auth::id(), $message);
        }

        if($groupId){
            Group::updateGroupWithMessage($groupId, $message);
        }

        SocketMessage::dispatch($message);

        return new MessageResource($message);
    }

    public function destroy(Message $mess) {
        if($mess->sender_id !== Auth::id()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $mess->delete();

        return response('', 204);
    }
}
