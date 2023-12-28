<?php

namespace App\Http\Controllers;

use App\Events\StoreMessageEvent;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index()
    {
        $messages = MessageResource::collection(Message::all())->resolve();
        return Inertia::render('Message/Index', compact('messages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'body' => 'required|string|max:255'
        ]);
        $data['user_id'] = auth()->user()->id;
        $message = Message::create($data);
        broadcast(new StoreMessageEvent($message))->toOthers();
        return MessageResource::make($message)->resolve();
    }
}
