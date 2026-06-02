<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SubscriptionExtensionResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'days' => $this->days,
            'previous_end_date' => $this->previous_end_date?->format('Y-m-d'),
            'new_end_date' => $this->new_end_date?->format('Y-m-d'),
            'reason' => $this->reason,
            'attendance_id' => $this->attendance_id,
            'is_automatic' => $this->attendance_id !== null,
            'created_by' => $this->created_by,
            'creator' => new UserResource($this->whenLoaded('creator')),
        ]);
    }
}
