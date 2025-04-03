<?php

namespace App\Notifications;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public TravelOrder $order)
    {
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Status do pedido de viagem alterado")
            ->line("O status do seu pedido para {$this->order->destination} foi alterado para {$this->order->status}.");
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'destination' => $this->order->destination,
            'status' => $this->order->status,
        ];
    }
}
