<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateName(Request $request)
    {
        $name = $request->input('name');

        $user = Auth::user();
        if ($user) {
            // Обновляем имя пользователя
            $user->name = $name;
            $user->save();
        }
    }
}
