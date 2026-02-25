<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
    'task',
    'completed',
    'due_date',
    'priority'
];

}
