<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesAttachments;
use App\Models\InvoicesDetails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:' . DELETE_ATTACHEMENT_PERMISSION)->only('destroy');
    }

    public function edit($id)
    {

        // الطريقة الاولى

        // if ((request()->input('notification_id'))) {
        //     $user = auth()->user();
        //     $notificationId = request()->input('notification_id');

        //     $notification = DB::table('notifications')
        //         ->where('notifiable_id', $user->id)
        //         ->where('id', $notificationId)
        //         ->first();

        //     if ($notification) {
        //         $readAt = Carbon::now();

        //         DB::table('notifications')
        //             ->where('id', $notificationId)
        //             ->update(['read_at' => $readAt]);
        //     }
        // }

        // الطريقةالثانية

        $notificationId = request()->input('notification_id');

        if ($notificationId) {
            $user = auth()->user();
            $notification = $user->unreadNotifications->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
            }
        }

        $invoices = Invoice::where('id', $id)->first();
        $details = InvoicesDetails::where('invoice_id', $id)->get();
        $attachments = InvoicesAttachments::where('invoice_id', $id)->get();

        return view('invoices.invoice_details', compact('invoices', 'details', 'attachments'));

    }


    public function destroy(Request $request)
    {
        $invoices = InvoicesAttachments::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number . '/' . $request->file_name);
        flash('تم حذف المرفق بنجاح')->success();
        return back();
    }

    public function view_file($invoiceNumber, $attachmentId)
    {
        $attachment = InvoicesAttachments::where('invoice_number', $invoiceNumber)
            ->where('id', $attachmentId)
            ->firstOrFail();

        $filePath = public_path('Attachments/' . $invoiceNumber . '/' . $attachment->file_name);

        return response()->file($filePath);
    }

    public function download_file($invoiceNumber, $file_name)
    {
        $filePath = public_path('Attachments/' . $invoiceNumber . '/' . $file_name);

        return response()->download($filePath, $file_name);
    }

    public function error($message = null): RedirectResponse
    {
        flash(translate($message ?? 'messages.Wrong'))->error();
        return back();
    }

}
