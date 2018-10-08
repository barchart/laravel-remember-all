<?php

namespace Barchart\Laravel\RememberAll;

use Carbon\Carbon;
use Illuminate\Auth\EloquentUserProvider as BaseUserProvider;
use Barchart\Laravel\RememberAll\Contracts\Authenticatable as UserContract;

class EloquentUserProvider extends BaseUserProvider implements UserProvider
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
        if (! $model = $this->getModelByIdentifier($identifier)) {
            return null;
        }

        $rememberTokens = $model->rememberTokens()->where('expires_at', '>', Carbon::now())->get();

        foreach ($rememberTokens as $rememberToken) {
            if (hash_equals($rememberToken->token, $token)) {
                return $model;
            }
        }
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
        $model = $this->getModelByIdentifier($identifier);

        if ($model) {
            $model->rememberTokens()->create([
                'token' => $value,
                'expires_at' => Carbon::now()->addMinutes($expire),
            ]);
        }
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

        if ($model) {
            $model->rememberTokens()->where('token', $token)->update([
                'token' => $newToken,
                'expires_at' => Carbon::now()->addMinutes($expire),
            ]);
        }
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
        $model = $this->getModelByIdentifier($identifier);

        if ($model && $token = $model->rememberTokens()->where('token', $token)->first()) {
            $token->delete();
        }
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
        $model = $this->getModelByIdentifier($identifier);

        if ($model) {
            $query = $model->rememberTokens();

            if ($expired) {
                $query->where('expires_at', '<', Carbon::now());
            }

            $query->delete();
        }
    }

    /**
     * Gets the user based on their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getModelByIdentifier($identifier)
    {
        $model = $this->createModel();

        return $model->where($model->getAuthIdentifierName(), $identifier)->first();
    }
}
