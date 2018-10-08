<?php

namespace Barchart\Laravel\RememberAll;

use Carbon\Carbon;
use Illuminate\Auth\DatabaseUserProvider as BaseUserProvider;
use Barchart\Laravel\RememberAll\Contracts\Authenticatable as UserContract;

class DatabaseUserProvider extends BaseUserProvider implements UserProvider
{
    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $user = $this->conn->table($this->table)
                ->select($this->table.'.*')
                ->leftJoin('remember_tokens', 'remember_tokens.user', '=', $this->table.'.'.$user->getAuthIdentifierName())
                ->where($this->table.'.'.$user->getAuthIdentifierName(), $identifier)
                ->where('remember_tokens.token', $token)
                ->where('remember_tokens.expires_at', '<', Carbon::now())
                ->first();

        return $user ? $this->getGenericUser($user) : null;
    }

    /**
     * Add a token value for the "remember me" session.
     *
     * @param  string  $value
     * @param  int $expire
     * @return void
     */
    public function addRememberToken($identifier, $value, $expire)
    {
        $this->conn->table('remember_tokens')->create([
            'token' => $value,
            'user_id' => $identifier,
            'expires_at' => Carbon::now()->addMinutes($expire),
        ]);
    }

    /**
     * Replace "remember me" token with new token.
     *
     * @param  string $token
     * @param  string $newToken
     * @param  int $expire
     *
     * @return void
     */
    public function replaceRememberToken($identifier, $token, $newToken, $expire)
    {
        $model = $this->getModelByIdentifier($identifier);

        $this->conn->table('remember_tokens')
                ->where('user_id', $identifier)
                ->where('token', $token)
                ->update([
                    'token' => $newToken,
                    'expires_at' => Carbon::now()->addMinutes($expire);
                ]);
    }

    /**
     * Delete the specified "remember me" token for the given user.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return null
     */
    public function deleteRememberToken($identifier, $token)
    {
        $this->conn->table('remember_tokens')
                ->where('user_id', $identifier)
                ->where('token', $token)
                ->delete();
    }

    /**
     * Purge old or expired "remember me" tokens.
     *
     * @param  mixed $identifier
     * @param  bool $expired
     * @return null
     */
    public function purgeRememberTokens($identifier, $expired = false)
    {
        $query = $this->conn->table('remember_tokens')
                        ->where('user_id', $identifier)
                        ->where('remember_tokens.token', $token);

        if ($expired) {
            $query->where('expires_at', '<', Carbon::now());
        }

        $query->delete();
    }
}
