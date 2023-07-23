<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
            'name' => 'required|string',

            'password' => 'required|string',
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
    public function registerStudent(Request $request)
    {

        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
            'name' => 'required|string',

            'password' => 'required|string',
        ]);


        $user = User::create([
            'email' => $fields['email'],
            'name' => $fields['name'],

            'password' => bcrypt($fields['password'])
        ]);
        $user->assignRole('student');
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

    public function uploadResume(Request $request)
    {

        $id = Auth::user()->id;
        $request->validate([
            'resume' => 'required|mimes:pdf',
        ]);

        if ($file = request()->file('resume')) {
            $name = $file->getClientOriginalName();
            $file->move(public_path('offre_commercial/'), $name);
            $url = url('/offre_commercial/' . $name);

            $user = User::find($id);
            $user->resumeurl = $url;
            $user->save();

            return response()->json(['message' => 'Resume uploaded successfully']);
        }

        return response()->json(['message' => 'File upload failed'], 500);
    }
}
