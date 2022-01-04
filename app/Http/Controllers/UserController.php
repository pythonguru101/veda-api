<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
	// User Register
	public function register(Request $request) {
		$validator = $request->validate([
			"name"  =>  "required",
			"email"  =>  "required|email|unique:users",
			"password"  =>  "required|confirmed"
		]);

		$inputs = $request->all();
		$inputs["password"] = Hash::make($request->password);

		$user = User::create($inputs);

		return response()->json([
			"status" => "success",
			"message" => "Success! registration completed",
			"data" => $user
		]);
	}

	// User login
	public function login(Request $request)
	{
		$request->validate([
			"email" =>  "required|email",
			"password" =>  "required",
		]);

		$user = User::where("email", $request->email)->first();

		if (is_null($user)) {
			return response()->json(["status" => "failed", "message" => "Failed! email not found"]);
		}

		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			$user = Auth::user();
			$token = $user->createToken('token')->plainTextToken;

			return response()->json(["status" => "success", "login" => true, "token" => $token, "data" => $user]);
		} else {
			return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! invalid password"]);
		}
	}

	// User Detail
	public function user()
	{
		return response()->json(["status" => "success", "data" => Auth::user()]);
	}
}
