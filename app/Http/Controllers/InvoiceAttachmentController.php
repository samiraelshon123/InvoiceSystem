<?php

namespace App\Http\Controllers;

use App\Models\InvoiceAttachment;
use Illuminate\Http\Request;

class InvoiceAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [

            'file_name' => 'mimes:pdf,jpeg,png,jpg',

            ], [
                'file_name.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg',
            ]);

            $image = $request->file('file_name');
            $file_name = $image->getClientOriginalName();

            $attachments = InvoiceAttachment::create([
               'file_name' => $file_name,
               'invoice_number' => $request->invoice_number,
               'invoice_id' => $request->invoice_id,
               'Created_by' => auth()->user()->name,
            ]);

            // move pic
            $imageName = $request->file_name->getClientOriginalName();
            $request->file_name->move(public_path('/assets/upload/invoice_attachment/'. $request->invoice_number), $imageName);

            session()->flash('Add', 'تم اضافة المرفق بنجاح');
            return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceAttachment  $invoiceAttachment
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceAttachment $invoiceAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceAttachment  $invoiceAttachment
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceAttachment $invoiceAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoiceAttachment  $invoiceAttachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceAttachment $invoiceAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceAttachment  $invoiceAttachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceAttachment $invoiceAttachment)
    {
        //
    }
}
