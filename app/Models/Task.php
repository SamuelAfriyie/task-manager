<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'title',
        'desc',
        'status',
        'assigned_date',
        'project_id'
    ];

          /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'project_id',
        'updated_at'
    ];
}
