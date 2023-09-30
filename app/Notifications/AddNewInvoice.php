<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AddNewInvoice extends Notification
{
    use Queueable;
    private $invoice;

    public function __construct(Invoice $invoice)
    {
        return $this->invoice = $invoice;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'id' => $this->invoice->id,
            'title' => 'تم اضافة فاتورة جديد بواسطة :',
            'user' => Auth::user()->name,

        ];
    }

    public function toBroadcast(Object $notifiable)
    {
        return new BroadcastMessage(
            [
                'invoice_id' => $this->invoice->id,
                'title' => 'تم اضافة فاتورة جديد بواسطة :',
                'user' => Auth::user()->name,

            ]
        );
    }
}
