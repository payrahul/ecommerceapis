<?php

    namespace App\Service;
    use App\Models\User;

    class MyService
    {
        public function performAction()
        {
            $users = User::select('id', 'name', 'email')->get();
            return response()->json(['user'=>$users]);
        }
    }