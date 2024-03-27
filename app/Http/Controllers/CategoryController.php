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
            'category_name' => 'required|string|min:2',
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

    public function showCategory()
    {
        $show_category = Category::get();
        if ($show_category) {
            return response()->json([
                'message' => 'success',
                'data' => $show_category
            ], 200);
        } else {
            return response()->json([
                'message' => 'success',
                'data' => []
            ], 200);
        }
    }

    public function updateCategory(Request $request)
    {
        $category = Category::where('id', $request->id)->first();
        if ($category) {
            $validator = Validator::make($request->all(), [
                'category_name' => 'string|min:2|max:20',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $category->category_name = $request->category_name;
            $category->update();
            return response()->json([
                'message' => 'package updated successfully',
                'data' => $category,
            ]);
        } else {
            return response()->json([
                'message' => 'Package not found',
                'data' => []
            ]);
        }

    }

    public function deleteCategory($id)
    {
        $category = Category::where('id', $id)->first();
        if ($category) {
            $category->delete();
            return response()->json([
                'message' => 'Category deleted successfully',
            ],200);
        }
        return response()->json([
            'message' => 'Category Not Found',
        ],404);
    }
}
