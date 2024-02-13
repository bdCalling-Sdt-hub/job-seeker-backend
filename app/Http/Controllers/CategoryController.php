<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|min:2|max:15|unique:categories',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();
        return response()->json([
            'message' => 'Category added Successfully',
            'data' => $category
        ]);
    }

    public function show_category()
    {
        $show_category = Category::get();
        if ($show_category) {
            return response()->json([
                'status' => 'success',
                'data' => $show_category
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => []
            ], 200);
        }
    }
}
