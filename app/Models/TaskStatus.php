<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Один статус может принадлежать многим задачам.
    // В модели Task::class связь строится по полю status_id
    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
