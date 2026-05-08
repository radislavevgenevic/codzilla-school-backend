<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,

            // Вычисляемые поля
            'is_admin' => $this->role === 'admin',
            'is_parent' => $this->role === 'parent',
            'avatar' => $this->getAvatarUrl(), // если есть метод в модели

            // Загруженные связи (только если есть)
            'children' => StudentResource::collection($this->whenLoaded('children')),
            'children_count' => $this->whenCounted('children'),

            // Скрываем email для не-админов (если нужно)
            'email_private' => $this->when($request->user()?->isAdmin(), $this->email),
        ]);
    }

    /**
     * Дополнительные данные для коллекции пользователей
     */
    public static function collection($resource)
    {
        return parent::collection($resource)->additional([
            'stats' => [
                'total' => $resource->total() ?? $resource->count(),
                'admins' => $resource->where('role', 'admin')->count(),
                'parents' => $resource->where('role', 'parent')->count(),
            ]
        ]);
    }
}
