<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'gender',
        'age_category',
        'tags',
        'color',
        'image_path',
        'description',
    ];
}
