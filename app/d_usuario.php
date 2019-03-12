<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class d_usuario extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'd_usuario';
    
    // public function getAuthPassword()
    // {
    //     return $this->contrasena;
    // }

    public function getAuthKey()
    {
        return $this->contrasena;
    }
    
 }
