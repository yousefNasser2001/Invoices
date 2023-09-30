<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Invoice;
use App\Models\InvoicesAttachments;
use App\Models\InvoicesDetails;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddNewInvoice;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:' . INVOICES_LIST_PERMISSION)->only('index');
        $this->middleware('permission:' . PAID_INVOICES_PERMISSION)->only('paid_invoices');
        $this->middleware('permission:' . PARTIAL_PAID_INVOICES_PERMISSION)->only('partial_paid_invoices');
        $this->middleware('permission:' . UNPAID_INVOICES_PERMISSION)->only('unpaid_invoices');
        $this->middleware('permission:' . ARCHIVE_INVOICE_PERMISSION)->only('archive_invoice');
        $this->middleware('permission:' . PRINT_INVOICE_PERMISSION)->only('print_invoice');
        $this->middleware('permission:' . EXCEL_PERMISSION)->only('export');
        $this->middleware('permission:' . CHANGE_STATUS_PERMISSION)->only('update_status');
        $this->middleware('permission:' . CREATE_INVOICE_PERMISSION)->only('create', 'store');
        $this->middleware('permission:' . EDIT_INVOICE_PERMISSION)->only('edit', 'update');
        $this->middleware('permission:' . DELETE_INVOICE_PERMISSION)->only('destroy');
    }

    public function index()
    {
        $invoices = Invoice::orderByDesc('id')->get();
        return view('invoices.invoices', compact('invoices'));
    }

    public function create()
    {
        $sections = Section::pluck('id', 'section_name');
        return view('invoices.add_invoices', compact('sections'));
    }

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

        DB::beginTransaction();

        try {
            $invoice = Invoice::create([
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

            // $user = User::first();
            // Notification::send($user, new AddInvoice($invoice_id));

            $users = User::where('id', '!=', auth()->user()->id)->get();
            Notification::send($users, new AddNewInvoice($invoice));

            flash('تم اضافة الفاتورة بنجاح')->success();
            DB::commit();
            return back();
        } catch (Exception $e) {
            DB::rollback();
            return $this->error();
        }

    }

    public function show($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        return view('invoices.update_status', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $sections = Section::pluck('id', 'section_name');
        return view('invoices.edit_invoice', compact('sections', 'invoice'));
    }

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
            $invoice = Invoice::find($id);
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

    public function destroy($id)
    {
        try {
            $invoice = Invoice::find($id);
            $Details = InvoicesAttachments::find($id);

            if (!empty($Details->invoice_number)) {
                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }

            $invoice->forceDelete();
            if ($invoice) {
                return response()->json([
                    'status' => 'success',
                    'message' => translate('messages.Deleted'),
                ]);
            }
        } catch (Exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'dawdawd',
            ]);
        }
    }

    public function archive_invoice(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = Invoice::find($id);
        $invoices->delete();
        flash('تم ارشفة الفاتورة بنجاح')->success();
        return redirect()->route('archive.index');
    }

    public function getProducts($id)
    {
        $products = DB::table('products')->where('section_id', $id)->pluck('product_name', 'id');
        return json_encode($products);
    }

    public function update_status($id, Request $request)
    {
        $invoices = Invoice::find($id);

        if ($request->status === 'مدفوعة') {

            $invoices->update([
                'value_status' => 1,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);

            InvoicesDetails::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 1,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'value_status' => 2,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);
            InvoicesDetails::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 2,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }
        flash('تم تحديث حالة الفاتورة بنجاح')->success();
        return redirect()->route('invoices.index');
    }

    public function paid_invoices()
    {
        $invoices = Invoice::where('value_status', 1)->get();
        return view('invoices.paid_invoices', compact('invoices'));
    }

    public function unpaid_invoices()
    {
        $invoices = Invoice::where('value_status', 0)->get();
        return view('invoices.unpaid_invoices', compact('invoices'));
    }

    public function partial_paid_invoices()
    {
        $invoices = Invoice::where('value_status', 2)->get();
        return view('invoices.partial_paid_invoices', compact('invoices'));
    }

    public function print_invoice($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        return view('invoices.print_invoice', compact('invoice'));

    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function MarkAsRead_all(Request $request)
    {

        $userUnreadNotification = auth()->user()->unreadNotifications;

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }

    }

    public function error($message = null): RedirectResponse
    {
        flash(translate($message ?? 'messages.Wrong'))->error();
        return back();
    }
}
