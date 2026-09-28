<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['service'])]
class ServiceAccessEvent extends Model
{
    public const SPKP_SPAK = 'spkp_spak';

    public const INTERNAL_INTEGRITY = 'internal_integrity';

}
