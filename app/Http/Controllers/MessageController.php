<?php
namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($message, 201);
    }

    public function inbox($id)
{
    $messages = Message::where(function ($query) use ($id) {
        $query->where('receiver_id', auth()->id())
              ->where('sender_id', $id);
    })
    ->orWhere(function ($query) use ($id) {
        $query->where('receiver_id', $id)
              ->where('sender_id', auth()->id());
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    
    Message::where('receiver_id', auth()->id())
           ->where('sender_id', $id)
           ->where('is_read', false)
           ->update(['is_read' => true]);

    return response()->json($messages);
}



    public function update(Request $request, $id)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $message = Message::where('id', $id)->where('sender_id', auth()->id())->first();

    if (!$message) {
        return response()->json(['error' => 'Message not found or unauthorized'], 404);
    }

    $message->update([
        'message' => $request->message,
    ]);

    return response()->json($message);
}

public function delete($id)
{
    $message = Message::where('id', $id)->where('sender_id', auth()->id())->first();

    if (!$message) {
        return response()->json(['error' => 'Message not found or unauthorized'], 404);
    }

    $message->delete();

    return response()->json(['success' => 'Message deleted successfully']);
}
}
