<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class EmployeeController extends Controller
{
    protected $employeeModel;
    protected $fileController;

    public function __construct()
    {
        $this->employeeModel = new Employee();
        $this->fileController = new FileController;
    }
    public function index()
    {
        $employees = $this->employeeModel->where('is_active')->get();
        return response()->json([
            'success' => !isEmpty($employees),
            'data' => $employees
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'email',
            'phone' => 'string',
            'is_active' => 'integer|in:0,1',
            'social_links' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validate();

        if ($request->file('avatar')) {
            $avatarTempPath = $request->file('avatar')->getRealPath();
            $avatarPath = $this->fileController->uploadEmployeeAvatar(null, $avatarTempPath);
            $validated['avatar'] = $avatarPath;
        }

        if ($employee = $this->employeeModel->create($validated)) {
            return response()->json([
                'success' => true,
                'message' => 'Employee added successfully',
                'data' => $employee
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => []
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'email',
            'phone' => 'string',
            'is_active' => 'integer|in:0,1',
            'social_links' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validate();

        $employee = $this->employeeModel->where('id', $validated['id'])->get();

        if ($request->file('avatar')) {
            $avatarTempPath = $request->file('avatar')->getRealPath();
            $avatarPath = $this->fileController->uploadEmployeeAvatar($employee, $avatarTempPath);
            $validated['avatar'] = $avatarPath;
        }

        if ($employee = $this->employeeModel->update($validated)) {
            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully',
                'data' => $employee
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => []
            ], 500);
        }
    }

    public function destroy(Request $request) {}
}
