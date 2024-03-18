<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;
class UserController extends Controller
{
    public function dummy()
    {
        return ['test'=>'check'];
    }

    public function createUser(Request $request)
    {
        $post = $request->all();
        $post['user_isActive'] = 1;
       
        $customMessages = [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
        ];
        $validator = Validator::make($post, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required',
            'gender' => 'required',
            'mobile' => 'required',
            'dob' => 'required',
            'user_isActive'=>'required'
        ], $customMessages);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
             $post['password'] = Hash::make($post['password']);
            $user = User::create($post);
            return ['success' => 'true','user'=>$user];
        }
    }



    public function updateUser(Request $request)
    {
        $post = $request->all();

        $customMessages = [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
        ];

        $rules = [
            'id' => 'required',
            'name' => 'required',
            'role_id' => 'required',
            'gender' => 'required',
            'mobile' => 'required',
            'dob' => 'required',
            'user_isActive' => 'required',
        ];

        if ($user = User::find($post['id'])) {
            if ($user->email != $post['email']) {
                $rules['email'] = 'required|email|unique:users,email';
            } else {
                $rules['email'] = 'required|email';
            }
        }

        $validator = Validator::make($post, $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $updatedUser = User::where('id', $post['id'])->update($post);

        return ['success' => true, 'message' => 'User updated successfully', 'user' => $updatedUser];
    }

    public function login(Request $request)
    {
        $user= User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }
    
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        
        return response($response, 201);
    }

    public function getUser()
    {
        // return ['check'=>'check'];
        $user = Auth::user();

        return response()->json(['user' => $user]);
    }

}
