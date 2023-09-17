<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesAttachments;
use App\Models\InvoicesDetails;
use App\Models\Section;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::orderByDesc('id')->get();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::pluck('id', 'section_name');
        return view('invoices.add_invoices', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'product' => 'required|string|max:255',
            'section' => 'required',
            'amount_collection' => 'required|numeric|min:0',
            'amount_commission' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'value_vat' => 'required|numeric|min:0',
            'rate_vat' => 'required|min:0',
            'total' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        try {
            Invoice::create([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'product' => $request->product,
                'section_id' => $request->section,
                'amount_collection' => $request->amount_collection,
                'amount_commission' => $request->amount_commission,
                'discount' => $request->discount,
                'value_vat' => $request->value_vat,
                'rate_vat' => $request->rate_vat,
                'total' => $request->total,
                'status' => 'غير مدفوعة',
                'value_status' => 0,
                'note' => $request->note,

            ]);

            $invoice_id = Invoice::latest()->first()->id;
            InvoicesDetails::create([
                'invoice_id' => $invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => 'غير مدفوعة',
                'value_status' => 0,
                'note' => $request->note,
                'user' => (Auth::user()->name),
            ]);

            if ($request->hasFile('pic')) {

                $invoice_id = Invoice::latest()->first()->id;
                $image = $request->file('pic');
                $file_name = $image->getClientOriginalName();
                $invoice_number = $request->invoice_number;

                $attachments = new InvoicesAttachments();
                $attachments->file_name = $file_name;
                $attachments->invoice_number = $invoice_number;
                $attachments->created_by = Auth::user()->name;
                $attachments->invoice_id = $invoice_id;
                $attachments->save();

                // move pic
                $imageName = $request->pic->getClientOriginalName();
                $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
            }

            flash('تم اضافة الفاتورة بنجاح')->success();
            return back();
        } catch (Exception $e) {
            return $this->error();
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $sections = Section::pluck('id', 'section_name');
        return view('invoices.edit_invoice', compact('sections', 'invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'product' => 'required|string|max:255',
            'section' => 'required',
            'amount_collection' => 'required|numeric|min:0',
            'amount_commission' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'value_vat' => 'required|numeric|min:0',
            'rate_vat' => 'required|min:0',
            'total' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->update([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'product' => $request->product,
                'section_id' => $request->section,
                'amount_collection' => $request->amount_collection,
                'amount_commission' => $request->amount_commission,
                'discount' => $request->discount,
                'value_vat' => $request->value_vat,
                'rate_vat' => $request->rate_vat,
                'total' => $request->total,
                'note' => $request->note,
            ]);

            flash('تم تحديث الفاتورة بنجاح')->success();
            return back();
        } catch (Exception $e) {
            return $this->error();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();

            if ($invoice) {
                return response()->json([
                    'status' => 'success',
                    'message' => translate('messages.Deleted'),
                ]);
            }
        } catch (Exception) {
            return response()->json([
                'status' => 'error',
                'message' => translate('messages.Wrong'),
            ]);
        }

        // try {
        //     $invoice = Invoice::find($request->invoice_id);
        //     $invoice->delete();
        //     flash('تم حذف الفاتورة بنجاح')->success();
        //     return back();
        // } catch (Exception) {
        //     return $this->error();
        // }

    }

    public function getProducts($id)
    {
        $products = DB::table('products')->where('section_id', $id)->pluck('product_name', 'id');
        return json_encode($products);
    }

    public function verifiedPayment($id): JsonResponse
    {
        try {
            $invoice = Invoice::find($id);
            if ($invoice) {
                if ($invoice->value_status == 1) {
                    return Response()->json([
                        'status' => 'error',
                        'message' => 'لا يمكن دفع الديون المدفوعة',
                    ]);
                } else {
                    $invoice->update([
                        'value_status' => 1,
                        'status' => 'مدفوعة',
                    ]);
                    InvoicesDetails::create([
                        'invoice_id' => $invoice->id,
                        'invoice_number' => $invoice->invoice_number,
                        'product' => $invoice->product,
                        'section' => $invoice->section,
                        'status' => 'مدفوعة',
                        'value_status' => 1,
                        'note' => $invoice->note,
                        'payment_date' => now(),
                        'user' => (Auth::user()->name),
                    ]);

                    return Response()->json([
                        'status' => 'success',
                        'message' => 'تمت عملية السداد بنجاح',
                    ]);
                }
            }

            return Response()->json([
                'status' => 'error',
                'message' => 'Not Found',
            ], 404);

        } catch (Exception $e) {
            return Response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function error($message = null): RedirectResponse
    {
        flash(translate($message ?? 'messages.Wrong'))->error();
        return back();
    }
}
