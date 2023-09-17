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

    public function index()
    {
        return back();
    }

    public function create()
    {
        return back();
    }

    public function store(Request $request)
    {
        return back();
    }

    public function show(InvoicesDetails $invoicesDetails)
    {
        return back();
    }

    public function edit($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $details = InvoicesDetails::where('invoice_id', $id)->get();
        $attachments = InvoicesAttachments::where('invoice_id', $id)->get();

        return view('invoices.invoice_details', compact('invoices', 'details', 'attachments'));

    }

    public function update(Request $request, InvoicesDetails $invoicesDetails)
    {
        return back();
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
