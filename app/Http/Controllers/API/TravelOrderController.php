<?php

namespace App\Http\Controllers\API;

use App\Models\TravelOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TravelOrderResource;
use App\Notifications\OrderStatusChanged;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderStatusRequest;

class TravelOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = TravelOrder::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('destination')) {
            $query->where('destination', 'like', '%'.$request->destination.'%');
        }

        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
        }

        return TravelOrderResource::collection($query->paginate());
    }

    public function store(StoreTravelOrderRequest $request)
    {
        $order = Auth::user()->travelOrders()->create($request->validated());
        return new TravelOrderResource($order);
    }

    public function show(TravelOrder $travelOrder)
    {
        return new TravelOrderResource($travelOrder);
    }

    public function updateStatus(UpdateTravelOrderStatusRequest $request, TravelOrder $travelOrder)
    {
        if ($travelOrder->user_id === Auth::id()) {
            abort(403, 'Você não pode alterar o status do próprio pedido');
        }

        $originalStatus = $travelOrder->status;
        $travelOrder->update($request->validated());

        if ($originalStatus !== $travelOrder->status) {
            $travelOrder->user->notify(new OrderStatusChanged($travelOrder));
        }

        return new TravelOrderResource($travelOrder);
    }

    public function cancel(TravelOrder $travelOrder)
    {
        if ($travelOrder->status !== 'aprovado') {
            abort(400, 'Só é possível cancelar pedidos aprovados');
        }

        if (!$travelOrder->canBeCancelled()) {
            abort(400, 'Este pedido não pode mais ser cancelado');
        }

        $travelOrder->update(['status' => 'cancelado']);
        $travelOrder->user->notify(new OrderStatusChanged($travelOrder));

        return response()->noContent();
    }
}
