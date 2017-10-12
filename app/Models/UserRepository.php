<?php

namespace App\Models;

class UserRepository {

    public static function getByName(string $name) {
        return User::where('name', $name)->first();
    }

}