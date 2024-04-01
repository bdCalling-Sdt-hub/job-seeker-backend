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
            'category_name' => 'required|string|min:2|unique:categories',
            'category_image' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $category = new Category();
        $category->category_name = $request->category_name;
        if ($request->file('category_image')) {
            $category->category_image = $this->saveImage($request);
        }
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
                'category_image' => ''
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            if ($request->file('category_image')) {
                if (!empty($category->category_image)) {
                    $this->removeImage($category->category_image);
                }
                $category->category_image = $this->saveImage($request);
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

    protected function saveImage($request)
    {
        $image = $request->file('category_image');
        $imageName = rand() . '.' . $image->getClientOriginalExtension();
        $directory = 'adminAsset/category-image/';
        $imgUrl = $directory . $imageName;
        $image->move($directory, $imageName);
        return $imgUrl;
    }

    // Function to remove an image
    private function removeImage($imagePath)
    {
        // Check if the file exists before attempting to delete it
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}
