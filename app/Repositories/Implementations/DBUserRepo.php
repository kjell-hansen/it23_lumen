<?php

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Repositories\Interfaces\UserRepo;

class DBUserRepo implements UserRepo {
    /**
     * @inheritDoc
     */
    public function all():array {
        return User::all()->all();
    }

    /**
     * @inheritDoc
     */
    public function get(string $id):?User {
        return User::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function add(User $user):void {
        $user->save();
    }

    /**
     * @inheritDoc
     */
    public function update(User $user):void {
        $user->update();
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id):void {
        User::destroy($id);
    }

    /**
     * @inheritDoc
     */
    public function getUserByEmail(string $email):?User {
        return User::query()->where('epost', $email)->firstOrFail();
    }

    public function findUserByRefreshToken(string $token):?User {
        return User::query()->where('refresh_token_hash',$token)->firstOrFail();
    }
}
