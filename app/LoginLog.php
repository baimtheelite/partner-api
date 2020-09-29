<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $primaryKey = 'id_login_log';
    protected $table = 'login_log';

    public function User()
    {
        return $this->belongsTo('App\User');
    }
}
