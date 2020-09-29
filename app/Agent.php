<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $guarded = [
        'id_user', 'id_branch',
    ];

    public function branch()
    {
        return $this->belongsTo('App\Branch', 'id_branch', 'id_branch');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id_user');
    }
}
