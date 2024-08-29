<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\File;
use App\Models\UsersMeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        // Validatsiya
        // $request->validate([
        //     'ticket_id' => 'required|exists:tickets,id',
        //     'message' => 'required|string',
        //     'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        // ]);

        // Faylni saqlash
        $file_id = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('files', 'public');
            $fileModel = File::create([
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'file_url' => $path,
            ]);
            $file_id = $fileModel->id;
        }

        $usersMeta = UsersMeta::where('user_id', Auth::id())->first();

        $message = Message::create([
            'ticket_id' => $request->ticket_id,
            'users_meta_id' => $usersMeta->id,
            'message' => $request->message,
            'file_id' => $file_id,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'message' => 'Message successfully created',
            'data' => $message
        ], 201);
    }
}
