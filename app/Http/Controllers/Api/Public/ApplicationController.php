<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'student_age' => 'required|integer|min:3|max:18',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'required|email|max:255',
            'course_id' => 'required|exists:courses,id',
            'comment' => 'nullable|string',
        ]);

        $application = Application::create([
            ...$validated,
            'status' => 'new',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Заявка успешно отправлена',
            'application_id' => $application->id,
        ], 201);
    }
}
