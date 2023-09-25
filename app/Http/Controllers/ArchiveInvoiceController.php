<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArchiveInvoiceController extends Controller
{

    public function index()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.archived_invoices', compact('invoices'));
    }

    public function create()
    {
        return back();
    }

    public function store(Request $request)
    {
        return back();
    }

    public function show(string $id)
    {
        return back();
    }

    public function edit(string $id)
    {
        return back();
    }

    public function update(Request $request, string $id)
    {
        try {
            $id = $request->invoice_id;
            $flight = Invoice::withTrashed()->where('id', $id)->restore();
            flash('تم الغاء ارشفة الفاتورة بنجاح')->success();
            return redirect(route('invoices.index'));

        } catch (Exception $e) {
            return $this->error();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $invoice = Invoice::withTrashed()->where('id', $request->invoice_id)->first();
            $invoice->forceDelete();
            flash('تم حذف الفاتورة بنجاح')->success();
            return back();
        } catch (Exception $e) {
            return $this->error();
        }
    }

    public function error($message = null): RedirectResponse
    {
        flash(translate($message ?? 'messages.Wrong'))->error();
        return back();
    }
}
