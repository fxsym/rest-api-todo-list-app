<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['categories_name'];

    public function todos(): BelongsToMany
    {
        return $this->belongsToMany(Todo::class, 'category_todo', 'category_id', 'todo_id')->distinct();
    }
}
