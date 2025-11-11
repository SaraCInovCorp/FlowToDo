<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'ativo', 'user_id'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
