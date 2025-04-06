<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        // $category = Category::with(['todos'])->get();
        // return $category;
        return CategoryResource::collection(Category::with(['todos.user', 'todos'])->get());
    }
}
