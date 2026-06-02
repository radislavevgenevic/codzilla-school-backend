<?php

namespace App\Http\Controllers\Api\Parent;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $childIds = $request->user()->children()->pluck('id');

        $subscriptions = Subscription::whereIn('student_id', $childIds)
            ->with(['student.currentCourse', 'extensions.creator'])
            ->orderByRaw("case when status = 'active' then 0 else 1 end")
            ->orderBy('end_date')
            ->get();

        return SubscriptionResource::collection($subscriptions);
    }

    public function show(Request $request, Subscription $subscription)
    {
        $ownsSubscription = $request->user()
            ->children()
            ->where('students.id', $subscription->student_id)
            ->exists();

        if (!$ownsSubscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $subscription->load(['student.currentCourse', 'extensions.creator']);

        return new SubscriptionResource($subscription);
    }
}
