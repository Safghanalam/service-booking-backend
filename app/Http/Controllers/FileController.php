<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function uploadImage($temp_path, $destination_path)
    {
        try {
            // Generate unique filename with extension
            $extension = pathinfo($temp_path, PATHINFO_EXTENSION) ?: 'jpg';
            $filename = Str::uuid() . '.' . $extension;

            // Full destination path inside storage/app/public
            $finalPath = $destination_path . '/' . $filename;

            // Put the file contents into storage
            Storage::disk('public')->put($finalPath, file_get_contents($temp_path));

            // Delete temp file if it still exists
            if (file_exists($temp_path)) {
                unlink($temp_path);
            }

            return $finalPath;
        } catch (\Exception $e) {
            return false;
        }
    }

    function deleteFile($file_path)
    {
        if (Storage::disk('public')->exists($file_path)) {
            Storage::disk('public')->delete($file_path);
        }
    }

    public function uploadUserAvatar($user, $temp_path)
    {
        try {
            // Generate unique filename with extension
            $extension = pathinfo($temp_path, PATHINFO_EXTENSION) ?: 'jpg';
            $filename = Str::uuid() . '.' . $extension;

            // Full destination path inside storage/app/public
            $finalPath = 'avatar/' . $filename;

            // Put the file contents into storage
            Storage::disk('public')->put($finalPath, file_get_contents($temp_path));

            // Delete temp file if it still exists
            if (file_exists($temp_path)) {
                unlink($temp_path);
            }

            if ($user->avatar) {
                $this->deleteFile($user->avatar);
            }

            return $finalPath;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function uploadEmployeeAvatar($employee = null, $temp_path)
    {
        try {
            // Generate unique filename with extension
            $extension = pathinfo($temp_path, PATHINFO_EXTENSION) ?: 'jpg';
            $filename = Str::uuid() . '.' . $extension;

            // Full destination path inside storage/app/public
            $finalPath = 'employee-avatar/' . $filename;

            // Put the file contents into storage
            Storage::disk('public')->put($finalPath, file_get_contents($temp_path));

            // Delete temp file if it still exists
            if (file_exists($temp_path)) {
                unlink($temp_path);
            }

            if ($employee && $employee->avatar) {
                $this->deleteFile($employee->avatar);
            }

            return $finalPath;
        } catch (\Exception $e) {
            return false;
        }
    }
}
