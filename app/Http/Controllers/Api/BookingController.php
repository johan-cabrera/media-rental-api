<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingStatusUpdateRequest;
use App\Http\Requests\BookingStoreRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Listado de reservas con paginado y filtros
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Booking::with(['media', 'customer']);

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filtro por medio 
        if ($request->filled('media_id')) {
            $query->where('media_id', $request->input('media_id'));
        }

        // Filtro por cliente 
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        $bookings = $query->latest('id')->paginate(10);

        return BookingResource::collection($bookings);
    }

    // Almacenar una reserva
    public function store(BookingStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt   = Carbon::parse($validated['ends_at']);

        // Validar que dos rangos de fechas no coincidan 
        $hasOverlap = Booking::where('media_id', $validated['media_id'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startsAt, $endsAt) {
                $query->where('starts_at', '<=', $endsAt->toDateString())
                        ->where('ends_at', '>=', $startsAt->toDateString());
            })
            ->exists();

        if ($hasOverlap) {
            return response()->json([
                'message' => 'El espacio publicitario ya se encuentra reservado en el rango de fechas seleccionado.',
                'errors'  => [
                    'dates' => ['Existe un conflicto de fechas con una reserva previa.'],
                ],
            ], 422);
        }

        // Verificar que exista ese medio
        $media = Media::findOrFail($validated['media_id']);
        
        // Calcular el total de dias y el precio total acumulado
        $daysTotal = $startsAt->diffInDays($endsAt) + 1;
        $totalPrice = $daysTotal * (float) $media->price_per_day;

        // Crear el registro en la base de datos
        $booking = Booking::create([
            'media_id'    => $validated['media_id'],
            'customer_id' => $validated['customer_id'],
            'starts_at'   => $startsAt->toDateString(),
            'ends_at'     => $endsAt->toDateString(),
            'total_price' => $totalPrice,
            'status'      => 'pending',
        ]);

        // Cargar las relaciones 
        $booking->load(['media', 'customer']);

        return (new BookingResource($booking))
            ->response()
            ->setStatusCode(201);
    }

    // Consultar una reserva
    public function show(Booking $booking): BookingResource
    {
        $booking->load(['media', 'customer']);

        return new BookingResource($booking);
    }

    // Actualizar el estado de una reserva
    public function updateStatus(BookingStatusUpdateRequest $request, Booking $booking): BookingResource
    {
        $booking->update($request->validated());

        $booking->load(['media', 'customer']);

        return new BookingResource($booking);
    }

    // Eliminar una reserva
    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();

        return response()->json([
            'message' => 'Reserva eliminada exitosamente.',
        ], 200);
    }
}