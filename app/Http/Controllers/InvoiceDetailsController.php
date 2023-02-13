<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\InvoiceDetails;
use App\Models\InvoicePayment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Response;

class InvoiceDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

        $this->middleware('permission:حذف المرفق', ['only' => ['destroy']]);
        $this->middleware('permission:عرض الفاتوره', ['only' => ['edit']]);
        $this->middleware('permission:تحميل المرفق', ['only' => ['get_file']]);
        $this->middleware('permission:عرض المرفق', ['only' => ['open_file']]);

    }
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
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceDetails $invoiceDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification = Notification::where('invoice_id', $id)->first();
        if($notification){
            $notification->update(['is_seen' => 1]);
        }
        $invoices = Invoice::find($id);
        $details = InvoiceDetails::where('id_Invoice', $id)->get();
        $attachments = InvoiceAttachment::where('invoice_id', $id)->get();
        $invoice_payment = InvoicePayment::where('invoice_id', $id)->get()->last();

       return view('Invoices.invoice_details', compact('invoices', 'details', 'attachments', 'invoice_payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceDetails $invoiceDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $invoices = InvoiceAttachment::find($request->id_file);
        FacadesFile::delete(public_path('assets/upload/invoice_attachment/'.$request->invoice_number.'/'.$request->file_name));
        $invoices->delete();

        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

    public function get_file($invoice_number,$file_name)

    {
        $file= public_path('assets/upload/invoice_attachment/'.$invoice_number.'/'.$file_name);
        return Response::download($file);
    }

    public function open_file($invoice_number,$file_name)

    {
        $file= public_path('assets/upload/invoice_attachment/'.$invoice_number.'/'.$file_name);
        return response()->file($file);
    }
}

