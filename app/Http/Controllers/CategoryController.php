<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function getActiveCategories()
    {
        $categories = $this->categoryModel
            ->where('is_active', 1)
            ->get();

        if ($categories) {
            return response()->json([
                'success' => true,
                'message' => 'Succesfully fetched active categories',

            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data'    => [],
            ], 500);
        }
    }

    public function getParentCategories()
    {
        $categories = $this->categoryModel
            ->where('parent', 0)
            ->where('is_active', 1)
            ->get();


        if ($categories) {
            return response()->json([
                'success' => true,
                'message' => 'Succesfully fetched parent categories',
                'data'    => $categories,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data'    => [],
            ], 500);
        }
    }

    public function getSubCategories()
    {
        $categories = $this->categoryModel
            ->whereNot('parent', 0)
            ->where('is_active', 1)
            ->get();

        if ($categories) {
            return response()->json([
                'success' => true,
                'message' => 'Succesfully fetched sub-categories',
                'data'    => $categories,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data'    => [],
            ], 500);
        }
    }

    public function getCategoriesByParentId(Request $request)
    {
        $id = $request->id;
        $categories = $this->categoryModel
            ->where('parent', $id)
            ->where('is_active', 1)
            ->get();

        if ($categories) {
            return response()->json([
                'success' => true,
                'message' => 'Succesfully fetched children categories',
                'data'    => $categories,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data'    => [],
            ], 500);
        }
    }


    // Admin Routes Functions
    public function getAllCategories(Request $request)
    {
        $categories = $this->categoryModel->get();
        if ($categories) {
            return response()->json([
                'success' => true,
                'message' => 'Succesfully fetched all categories',
                'data'    => $categories,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data'    => [],
            ], 500);
        }
    }

    public function getAllSubCategories(Request $request)
    {
        $categories = $this->categoryModel->whereNot('parent', 0)->get();
        if ($categories) {
            return response()->json([
                'success' => true,
                'message' => 'Succesfully fetched all sub-categories',
                'data'    => $categories,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data'    => [],
            ], 500);
        }
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name',
            'parent' => 'required|integer',
            'is_featured' => 'required|in:0,1',
            'is_active'   => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 422);
        }

        // Get validated data once
        $validated = $validator->validated();

        $category = $this->categoryModel->create($validated);
        if ($category) {
            return response()->json([
                'success' => true,
                'message' => 'Category added successfully',
                'data' => $category
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => []
            ], 500);
        }
    }

    public function updateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|string',
            'parent' => 'required|integer',
            'is_featured' => 'required|in:0,1',
            'is_active'   => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 422);
        }

        // Get validated data once
        $validated = $validator->validated();

        if ($this->categoryModel->where('id', $request->id)->update($validated)) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $this->categoryModel->where('id', $validated['id'])->get()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => $this->categoryModel->where('id', $validated['id'])->get()
            ], 500);
        }
    }

    public function deleteCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validate();

        if ($validated) {
            $this->categoryModel->where('id', $request->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted succesfully',
                'data' => []
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => []
            ], 500);
        }
    }
}
