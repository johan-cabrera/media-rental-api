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
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    // Exportar todas las reservas en un CSV
    public function exportCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reservas_' . date('Y-m-d') . '.csv"',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // formato para caracteres especiales
            fputs($handle, "\xEF\xBB\xBF");

            // Cabeceras del archivo csv 
            fputcsv($handle, [
                'ID',
                'Espacio Publicitario',
                'Tipo',
                'Cliente',
                'Correo Cliente',
                'Fecha Inicio',
                'Fecha Fin',
                'Total ($)',
                'Estado',
                'Fecha Creación',
            ], ',');

            // Carga de registros
            Booking::with(['media', 'customer'])
                ->latest('id')
                ->lazy(100)
                ->each(function ($booking) use ($handle) {
                    fputcsv($handle, [
                        $booking->id,
                        $booking->media?->name ?? 'N/A',
                        $booking->media?->type ?? 'N/A',
                        $booking->customer?->name ?? 'N/A',
                        $booking->customer?->email ?? 'N/A',
                        $booking->starts_at,
                        $booking->ends_at,
                        number_format($booking->total_price, 2, '.', ''),
                        $booking->status,
                        $booking->created_at?->format('Y-m-d H:i') ?? '',
                    ], ',');
                });

            fclose($handle);
        }, 'reservas_' . date('Y-m-d') . '.csv', $headers);
    }
}