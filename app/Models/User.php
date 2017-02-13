<?php namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $type            = 'users';
    protected $fillable     = ['name', 'email', 'password'];
    protected $hidden       = ['password', 'remember_token'];

    public function projects($fields = [])
    {
        return $this->belongsToMany('App\Models\Project')->select($fields);
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Models\Task');
    }
}
