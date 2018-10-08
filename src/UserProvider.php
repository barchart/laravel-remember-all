<?php

namespace Barchart\Laravel\RememberAll;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as BaseProvider;

interface UserProvider extends BaseProvider
{
    /**
     * Add a token value for the "remember me" session.
     *
     * @param  string  $value
     * @param  int $expire
     * @return void
     */
    public function addRememberToken($identifier, $value, $expire);

    /**
     * Replace "remember me" token with a new token.
     *
     * @param  string $token
     * @param  string $newToken
     * @param  int $expire
     *
     * @return void
     */
    public function replaceRememberToken($identifier, $token, $newToken, $expire);

    /**
     * Delete the specified "remember me" token for the given user.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return null
     */
    public function deleteRememberToken($identifier, $token);

    /**
     * Purge old or expired "remember me" tokens.
     *
     * @param  mixed $identifier
     * @param  bool $expired
     * @return null
     */
    public function purgeRememberTokens($identifier, $expired = false);
}
