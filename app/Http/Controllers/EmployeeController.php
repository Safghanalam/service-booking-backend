<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class EmployeeController extends Controller
{
    protected $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new Employee();
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

        // FIXME: fix the complete controller
        // TODO: add avatar in migration and in controller handle that
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'email',
            'phone' => 'string',
            'is_active' => 'integer|in:0,1',
            'is_deleted' => 'integer|in:0,1',
            'social_links' => ''
        ]);
    }

    public function update() {}

    public function destroy() {}
}
