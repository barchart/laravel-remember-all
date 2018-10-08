<?php

namespace Barchart\Laravel\RememberAll;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable as BaseAuthenticatable;

trait EloquentAuthenticatable
{
    use BaseAuthenticatable;

    /**
     * Get the "remember me" session tokens for the user.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rememberTokens()
    {
        return $this->hasMany(RememberToken::class);
    }
}
