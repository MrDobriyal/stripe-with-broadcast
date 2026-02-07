<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{       
    // public function index(Request $request){
        
    //     $messages = Message::orderBy("created_at","desc")->paginate(10);
    //     return view("frontend.chat.index", compact("messages"));
    // }
    public function chat($userId)
{
    $receiverId = $userId;

    $messages = Message::where(function ($q) use ($receiverId) {
        $q->where('sender_id', auth()->id())
          ->where('receiver_id', $receiverId);
    })->orWhere(function ($q) use ($receiverId) {
        $q->where('sender_id', $receiverId)
          ->where('receiver_id', auth()->id());
    })->orderBy('created_at')->get();

    return view('frontend.chat.index', compact('messages', 'receiverId'));
}
    public function send(Request $request)
{
    $sender = auth()->user();

    $request->validate([
        'receiver_id' => 'required|integer',
        'message' => 'required|string'
    ]);

    // 1. Save message
    $message = Message::create([
        'sender_id' => $sender->id,
        'receiver_id' => $request->receiver_id,
        'message' => $request->message
    ]);

    // 2. Fire event (REAL-TIME)
    broadcast(new MessageSent($message))->toOthers();

    return response()->json([
        'status' => true,
        'message' => 'Message sent'
    ]);
}

}
