<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['student.parent', 'student.currentCourse', 'extensions.creator'])
            ->latest();

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return SubscriptionResource::collection($query->paginate($request->get('per_page', 50)));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', Rule::in(['active', 'paused', 'cancelled', 'expired'])],
            'notes' => ['nullable', 'string'],
        ]);

        $subscription = Subscription::create([
            ...$validated,
            'status' => $validated['status'] ?? 'active',
        ]);

        $subscription->load(['student.parent', 'student.currentCourse', 'extensions.creator']);

        return response()->json([
            'success' => true,
            'message' => 'Абонемент создан',
            'data' => new SubscriptionResource($subscription),
        ], 201);
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['student.parent', 'student.currentCourse', 'extensions.creator']);

        return new SubscriptionResource($subscription);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'student_id' => ['sometimes', 'required', 'exists:students,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'required', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', Rule::in(['active', 'paused', 'cancelled', 'expired'])],
            'notes' => ['nullable', 'string'],
        ]);

        $subscription->update($validated);
        $subscription->load(['student.parent', 'student.currentCourse', 'extensions.creator']);

        return response()->json([
            'success' => true,
            'message' => 'Абонемент обновлен',
            'data' => new SubscriptionResource($subscription),
        ]);
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return response()->json([
            'success' => true,
            'message' => 'Абонемент удален',
        ]);
    }

    public function extend(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'days' => ['required', 'integer', 'min:1', 'max:365'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $subscription->extend(
            $validated['days'],
            $request->user(),
            $validated['reason'] ?? null,
        );

        $subscription->load(['student.parent', 'student.currentCourse', 'extensions.creator']);

        return response()->json([
            'success' => true,
            'message' => 'Абонемент продлен',
            'data' => new SubscriptionResource($subscription),
        ]);
    }
}
