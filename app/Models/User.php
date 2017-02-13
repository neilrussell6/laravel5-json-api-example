<?php namespace App\Models;

use App\Traits\Validatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use Validatable;

    protected $fillable     = ['name', 'email', 'password'];
    protected $hidden       = ['password', 'remember_token'];

    public $type            = 'users';
    public $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|confirmed',
    ];

    public function projects($fields = [])
    {
        return $this->belongsToMany('App\Models\Project')->select($fields);
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Models\Task');
    }
}
