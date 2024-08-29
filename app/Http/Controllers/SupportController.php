<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\UsersMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $usersMeta = UsersMeta::with('role')->where('user_id', $user->id)->first();

        $role_name = $usersMeta->role->name;

        if($role_name === "user")
            return response()->json(['message' => 'Unauthorized'], 403);

        $tickets = Ticket::all();

        return response()->json($tickets);
    }

    public function blockUser($user_id)
    {
        $adminMeta = UsersMeta::query()->with('role')->where('user_id', Auth::id())->first();

        if(!$adminMeta || !in_array($adminMeta->role_id,[1,4])){
            return response()->json(['error' => 'Sizda blokdan chiqarish huquqi yo\'q'], 403);
        }

        $usersMeta = UsersMeta::query()->where('user_id', $user_id)->first();

        if (!$usersMeta) {
            return response()->json(['error' => 'Foydalanuvchi topilmadi'], 404);
        }

        $usersMeta->blocked = true;
        $usersMeta->save();

        return response()->json([
            'message' => 'Foydalanuvchi blokdan chiqdi',
            'data' => $usersMeta
        ]);
    }

    public function unBlock($user_id)
    {
        $adminMeta = UsersMeta::query()->where('user_id', Auth::id())->first();

        if(!$adminMeta || !in_array($adminMeta->role_id,[1,4])){
            return response()->json(['error' => 'Sizda blokdan chiqarish huquqi yo\'q!'], 403);
        }

        $usersMeta = UsersMeta::query()->where('user_id', $user_id)->first();

        if (!$usersMeta) {
            return response()->json(['error' => 'Foydalanuvchi topilmadi'], 404);
        }

        $usersMeta->blocked = false;
        $usersMeta->save();

        return response()->json([
            'message' => 'Foydalanuvchi blokdan chiqarildi',
            'data' => $usersMeta
        ]);
    }
}
