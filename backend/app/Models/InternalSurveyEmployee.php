<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nip', 'name', 'is_active'])]
class InternalSurveyEmployee extends Model
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
