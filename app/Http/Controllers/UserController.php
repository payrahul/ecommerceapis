<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\FetchUserDetails;

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
            
            $post['mobile'] = (int)$post['mobile'];
            $post['password'] = Hash::make($post['password']);

            $user = User::create($post);
            if(!empty($user->id)){
                $userSettingData = [
                    'user_id'=>$user->id,
                    'isImageVisible'=>0,
                    'isUserPaid'=>0,
                    'us_isActive'=>0,
                ];
                $userSetting = UserSetting::create($userSettingData);
            }
            return ['userid'=>$userSetting];
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
            'email'=>'required',
            'role_id' => 'required',
            'gender' => 'required',
            'mobile' => 'required',
            'dob' => 'required',
            'user_isActive' => 'required',
        ];

        if ( isset($post['id']) && !empty($post['id'])) {
            $user = User::find($post['id']);
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
        $user = Auth::user();

        return response()->json(['user' => $user]);
    }

    public function getUserSettings()
    {
        $usersWithSettings = DB::table('users')
            ->join('user_settings', 'users.id', '=', 'user_settings.user_id')
            ->select('users.*', 'user_settings.*')
            ->get();
        
        return ['usersWithSettings'=>$usersWithSettings];
    }

    public function storeJobsQueues()
    {

        $userId = 12;
        $newInfo = [
            'name' => 'New Name3',
            'email' => 'new3@example.com',
            // Add other fields as needed
        ];
        // Artisan::call('queue:work');
        FetchUserDetails::dispatch($userId, $newInfo);
        exec('php /path/to/artisan queue:work --daemon');
        
        return response()->json(['status' => 'User update queued']);
    }

}
