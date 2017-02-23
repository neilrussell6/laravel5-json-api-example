<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use NeilRussell6\Laravel5JsonApi\Traits\Validatable;

class Project extends Model
{
    use Validatable;
    
    const STATUS_INCOMPLETE = 1;
    const STATUS_COMPLETE   = 2;
    const STATUS_TRASH      = 3;

    protected $fillable     = ['user_id', 'name', 'status'];
    protected $hidden       = [];
    protected $casts        = [
        'status' => 'integer',
    ];

    public $type = 'projects';
    public $rules = [
        'name' => 'required'
    ];
    public $available_includes = ['editor', 'owner', 'tasks', 'users'];
    public $default_includes = ['tasks'];

    public function editors ()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('is_editor')->wherePivot('is_editor', true);
    }

    public function owner ()
    {
        return $this->belongsTo('App\Models\User', 'user_id'); // we would not need to provide a foreign key if the method was called 'user'
    }

    public function tasks ()
    {
        return $this->hasMany('App\Models\Task');
    }

    public function users ()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('is_editor');
    }
}
