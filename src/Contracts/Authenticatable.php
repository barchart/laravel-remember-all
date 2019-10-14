<?php

namespace Barchart\Laravel\RememberAll\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as BaseAuthenticatable;

interface Authenticatable extends BaseAuthenticatable
{
    /**
     * Get the "remember me" session tokens for the user.
     *
     * @return string
     */
    public function rememberTokens();
}
