<?php

    namespace App\Repositories;

    use App\Models\User;

    class EloquentUserRepository implements UserRepository
    {
        public function getUserById($userId)
        {
            return User::find($userId);
        }
    }

?>