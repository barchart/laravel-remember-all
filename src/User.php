<?php

namespace Barchart\Laravel\RememberAll;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Barchart\Laravel\RememberAll\Contracts\Authenticatable as AuthenticatableContract;

class User extends Authenticatable implements AuthenticatableContract
{
    use EloquentAuthenticatable;
}

