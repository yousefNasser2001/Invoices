<?php

namespace App\Http\Controllers;

use App\Models\InvoicesAttachments;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoicesAttachmentsController extends Controller
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
        $validator = Validator::make($request->all(), [
            'file' => 'mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        try {
            $image = $request->file('file_name');
            $file_name = $image->getClientOriginalName();

            $attachments = new InvoicesAttachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $request->invoice_number;
            $attachments->invoice_id = $request->invoice_id;
            $attachments->created_by = Auth::user()->name;
            $attachments->save();

            // move pic
            $imageName = $request->file_name->getClientOriginalName();
            $request->file_name->move(public_path('Attachments/' . $request->invoice_number), $imageName);

            flash('تم اضافة المرفق بنجاح')->success();
            return back();


        } catch (Exception $th) {
            return $this->error();
        }

    }


    public function show(InvoicesAttachments $invoicesAttachments)
    {
        return back();
    }


    public function edit(InvoicesAttachments $invoicesAttachments)
    {
        return back();
    }

    public function update(Request $request, InvoicesAttachments $invoicesAttachments)
    {
        return back();
    }


    public function destroy(InvoicesAttachments $invoicesAttachments)
    {
        return back();
    }

    public function error($message = null): RedirectResponse
    {
        flash(translate($message ?? 'messages.Wrong'))->error();
        return back();
    }
}
