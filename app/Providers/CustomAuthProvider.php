<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class CustomAuthProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        $query = $this->createModel()->newQuery();
        $query = $query->where([
            ['email', $credentials['email']],
            ['is_admin', 0],
            ['is_black', 0],
        ]);
        if (isset($credentials['is_login_line']) && $credentials['is_login_line']) {
            $query = $query->where('is_account_line', 1);
        }
        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param Authenticatable $authenticatable
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $authenticatable, array $credentials)
    {
        if (isset($credentials['is_login_line']) && $credentials['is_login_line']) {
            return true;
        }
        return Hash::check($credentials['password'], $authenticatable->password);
    }
}
