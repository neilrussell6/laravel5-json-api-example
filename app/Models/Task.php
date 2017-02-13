<?php namespace App\Models;

use App\Traits\Validatable;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use Validatable;
    
    const STATUS_INCOMPLETE = 1;
    const STATUS_COMPLETE   = 2;
    const STATUS_TRASH      = 3;

    protected $fillable     = ['name', 'status'];
    protected $hidden       = [];
    protected $casts        = [
        'status' => 'integer',
    ];

    public $type = 'tasks';
    public $rules = [
        'name' => 'required'
    ];

    public function projects()
    {
        return $this->belongsToMany('App\Models\Project');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
