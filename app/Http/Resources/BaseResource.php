<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Базовые поля, которые есть у всех моделей
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Добавляем success и meta к ответу
     */
    public function with(Request $request): array
    {
        return [
            'success' => true,
            'meta' => [
                'api_version' => '1.0',
                'timestamp' => now()->toISOString(),
            ],
        ];
    }

    /**
     * Для коллекций добавляем дополнительную информацию
     */
    public static function collection($resource)
    {
        return parent::collection($resource)->additional([
            'success' => true,
            'meta' => [
                'api_version' => '1.0',
                'timestamp' => now()->toISOString(),
            ],
        ]);
    }
}
