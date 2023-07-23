<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
            'name' => 'required|string',

            'password' => 'required|string|confirmed',
        ]);


        $user = User::create([
            'email' => $fields['email'],
            'name' => $fields['name'],

            'password' => bcrypt($fields['password'])
        ]);
        $user->assignRole('hr');
        // event(new Registered($user));
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {

            return response(["message" => "user does not exist"], 400);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        $user->getRoleNames();
        $user->getPermissionNames();
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function registerClient(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
            'entite' => 'required|string',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);


        $user = User::create([
            'email' => $fields['email'],
            'nom' => $fields['nom'],
            'prenom' => $fields['prenom'],
            'password' => bcrypt($fields['password'])
        ]);
        $user->assignRole('client');
        event(new Registered($user));
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'success' => true,
            'message' => "client created successfully",
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
