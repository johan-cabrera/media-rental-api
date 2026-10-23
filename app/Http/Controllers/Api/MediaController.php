<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaAvailabilityRequest;
use App\Http\Requests\MediaStoreRequest;
use App\Http\Requests\MediaUpdateRequest;
use App\Http\Resources\MediaResource;
use App\Models\Booking;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MediaController extends Controller
{
    // Listado de medios con paginado y filtros
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Media::query();

        // Filtro por tipo
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filtro por nombre o ubicacion
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Si se solicita, retornar registros sin paginar
        if ($request->boolean('all')) {
            $media = $query->latest('id')->get();
            return MediaResource::collection($media);
        }

        // Paginacion 
        $media = $query->latest('id')->paginate(10);

        return MediaResource::collection($media);
    }

    // Almacenar un medio
    public function store(MediaStoreRequest $request): JsonResponse
    {
        $media = Media::create($request->validated());

        return (new MediaResource($media))
            ->response()
            ->setStatusCode(201);
    }

    // Consultar un medio
    public function show(Media $media): MediaResource
    {
        return new MediaResource($media);
    }

    // Actualizar un medio
    public function update(MediaUpdateRequest $request, Media $media): MediaResource
    {
        $media->update($request->validated());

        return new MediaResource($media);
    }

    // Eliminar un medio
    public function destroy(Media $media): JsonResponse
    {
        $media->delete();

        return response()->json([
            'message' => 'Espacio publicitario eliminado exitosamente.',
        ], 200);
    }

    // Verificar disponibilidad de un medio en un rango de fechas
    public function checkAvailability(MediaAvailabilityRequest $request, Media $media): JsonResponse
    {
        $validated = $request->validated();

        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt   = Carbon::parse($validated['ends_at']);

        // Verifica que la fecha este disponible
        $isBooked = Booking::where('media_id', $media->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startsAt, $endsAt) {
                $query->where('starts_at', '<=', $endsAt->toDateString())
                        ->where('ends_at', '>=', $startsAt->toDateString());
            })
            ->exists();

        $days = $startsAt->diffInDays($endsAt) + 1;
        $estimatedPrice = $days * (float) $media->price_per_day;

        return response()->json([
            'media_id'        => $media->id,
            'available'       => !$isBooked,
            'starts_at'       => $startsAt->toDateString(),
            'ends_at'         => $endsAt->toDateString(),
            'total_days'      => $days,
            'estimated_price' => $estimatedPrice,
        ], 200);
    }
}