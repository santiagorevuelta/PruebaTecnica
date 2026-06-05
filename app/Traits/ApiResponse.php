<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    protected function success(mixed $data, string $message = '', int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];

        if ($data instanceof ResourceCollection) {
            $payload['data'] = $data->collection;
            $payload['meta'] = [
                'current_page' => $data->resource->currentPage(),
                'last_page'    => $data->resource->lastPage(),
                'per_page'     => $data->resource->perPage(),
                'total'        => $data->resource->total(),
            ];
        } elseif ($data instanceof JsonResource) {
            $payload['data'] = $data;
        } else {
            $payload['data'] = $data;
        }

        return response()->json($payload, $status);
    }

    protected function created(mixed $data, string $message = 'Recurso creado exitosamente'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        $payload = ['success' => false, 'message' => $message];

        if (! empty($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    protected function notFound(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->error($message, 404);
    }
}
