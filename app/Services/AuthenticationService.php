<?php

namespace App\Services;

use App\Models\Login;
use App\Models\User;
use App\Repositories\Interfaces\UserRepo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthenticationService {
    public function __construct(private UserRepo $repo, private JwtService $jwt) {}

    public function attemptLogin(Login $login) {
        $user = $this->repo->getUserByEmail($login->getEpost());

        if ($user && Hash::check($login->getLosenord(), $user->losenord)) {
            return $user;
        }
        return null;
    }

    /**
     * Access-token (kort livslängd)
     * Innehåller användar-id så att vi inte behöver läsa databasen
     * för att få reda på vem som är användaren
     * @param User $user
     * @return string
     */
    public function createAccessTokensForUser(User $user):string {
        $claims = [
            'sub' => (string)$user->id,
            'email' => $user->email,
            'roles' => $user->admin ? ['admin'] : []
        ];

        return $this->jwt->makeAccessToken($claims);
    }

    /**
     * Refreshtoken har lång livslängd, sparas hashad i databasen
     * @param User $user
     * @return string
     * @throws \Random\RandomException
     */
    public function createAndStoreRefreshToken(User $user):string {
        $raw=bin2hex(random_bytes(20));
        $hash=hash('SHA256', $raw);
        $expiresAt=Carbon::now()->addSeconds(env('REFRESH_TTL',10000));
        $user->refresh_token_hash=$hash;
        $user->refresh_token_expires_at=$expiresAt->toDateTimeString();

        // Uppdatera användaren
        $this->repo->update($user);
        return $raw;
    }

    public function validateRefreshTokenAndGetUser(string $rawToken):?User {
        $hash=hash('SHA256', $rawToken);
        $user=$this->repo->findUserByRefreshToken($hash);

        // Ingen användare hittades
        if(!$user) {
            return null;
        }

        // Expires är mindre än aktuell tid
        if($user->refresh_token_expires_at && strtotime($user->refresh_token_expires_at)<time()) {
            return null;
        }

        return $user;
    }

    public function revokeRefreshToken(User $user) {
        $user->refresh_token_hash=null;
        $user->refresh_token_expires_at=null;
        $this->repo->update($user);
    }
}
