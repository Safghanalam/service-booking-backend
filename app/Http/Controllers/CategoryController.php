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

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    public function getParentCategories()
    {
        $categories = $this->categoryModel
            ->where('parent', 0)
            ->where('is_active', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    public function getSubCategories()
    {
        $categories = $this->categoryModel
            ->whereNot('parent', 0)
            ->where('is_active', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    public function getCategoriesByParentId(Request $request)
    {
        $id = $request->id;
        $categories = $this->categoryModel
            ->where('parent', $id)
            ->where('is_active', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    public function getAllCategories(Request $request)
    {
        if ($request->user()->role_id == 1) {
            $categories = $this->categoryModel->get();
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => []
            ], 403);
        }
    }

    public function getAllSubCategories(Request $request)
    {
        if (isAdmin(($request->user()))) {
            $categories = $this->categoryModel->whereNot('parent', 0)->get();
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => []
            ], 403);
        }
    }

    public function addCategory(Request $request)
    {
        if (isAdmin($request->user())) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:categories,name',
                'parent' => 'required|integer',
                'is_featured' => 'required|in:0,1',
                'is_active'   => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ]);
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
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => []
            ], 403);
        }
    }

    public function updateCategory(Request $request)
    {
        if (isAdmin($request->user())) {
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
                    'message' => $validator->errors()
                ]);
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
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => []
            ], 403);
        }
    }

    public function deleteCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if (isAdmin($request->user())) {
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                    'data' => []
                ]);
            }

            $validated = $validator->validate();

            if ($validated) {
                $deletedCategory = $this->categoryModel->where('id', $request->id)->delete();

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
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => []
            ], 403);
        }
    }
}
