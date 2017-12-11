<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'user_id', 'inviter_id', 'patient_id', 'email', 'token',
    ];
}
