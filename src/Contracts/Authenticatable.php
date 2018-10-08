<?php

namespace Barchart\Laravel\RememberAll\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as BaseAuthenticatable;

interface Authenticatable extends BaseAuthenticatable
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName();

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier();

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword();

    /**
     * Get the "remember me" session tokens for the user.
     *
     * @return string
     */
    public function rememberTokens();
}
