<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SubscriptionResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'student_id' => $this->student_id,
            'student' => new StudentResource($this->whenLoaded('student')),
            'name' => $this->name,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'status' => $this->status,
            'effective_status' => $this->effective_status,
            'status_text' => match ($this->effective_status) {
                'active' => 'Активный',
                'paused' => 'Приостановлен',
                'cancelled' => 'Отменен',
                'expired' => 'Истек',
                default => $this->effective_status,
            },
            'remaining_days' => $this->remaining_days,
            'notes' => $this->notes,
            'extensions' => SubscriptionExtensionResource::collection($this->whenLoaded('extensions')),
        ]);
    }
}
