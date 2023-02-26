<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\InvoiceDetails;
use App\Models\InvoicePayment;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddInvoice;
use File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\New_;

class InvoiceController extends Controller
{

    function __construct()
    {

        $this->middleware('permission:invoices_list', ['only' => ['index']]);
        $this->middleware('permission:invoice_add', ['only' => ['create','store']]);
        $this->middleware('permission:invoice_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:invoice_delete', ['only' => ['destroy']]);
        $this->middleware('permission:payment_status_change', ['only' => ['show', 'Status_Update']]);
        $this->middleware('permission:paid_invoices', ['only' => ['invoice_paid']]);
        $this->middleware('permission:unpaid_invoices', ['only' => ['invoice_unpaid']]);
        $this->middleware('permission:partial_invoices', ['only' => ['invoice_partial']]);
        $this->middleware('permission:invoice_print', ['only' => ['Print_invoice']]);
        $this->middleware('permission:excel_export', ['only' => ['export']]);

    }
    public function index()
    {
        $invoices = Invoice::get();
        $invoice_type = 'invoices';
        return view('Invoices.invoices', compact('invoices', 'invoice_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::get();
        $sections = Section::get();
        $invoice = New Invoice();
        $route = route('invoices.store');
        return view('Invoices.form_invoice', compact('sections', 'customers', 'invoice', 'route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['Status'] = 'غير مدفوعة';
        $validatedData['Value_Status'] = 2;
        $validatedData['note'] = $request->note;

        $invoice = Invoice::create($validatedData);
        InvoiceDetails::create([
            'id_Invoice' => $invoice->id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->section_id,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (auth()->user()->name),
        ]);

        if ($request->hasFile('pic')) {


            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new InvoiceAttachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = auth()->user()->name;
            $attachments->invoice_id = $invoice->id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic-> move(public_path('/assets/upload/invoice_attachment/'. $invoice_number), $imageName);

        }

        Notification::create([
            'type' => 'AddInvoice',
            'invoice_id' => $invoice->id,
            'data' => 'تم اضافة فاتورة جديد بواسطة : '.auth()->user()->name,
            'user_id' => auth()->user()->id
        ]);

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }


    public function show($id)
    {
        $invoices = Invoice::find($id);
        $invoice_payments = InvoicePayment::where('invoice_id', $id)->sum('paid');

        return view('Invoices.status_update', compact('invoices', 'invoice_payments'));
    }

    public function edit($id)
    {

        $invoice = Invoice::find($id);
        $sections = Section::get();
        $customers = Customer::get();
        $route = route('invoices.update', $id);
        return view('Invoices.form_invoice', compact('invoice', 'sections', 'customers', 'route'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceRequest $request, $id)
    {

        $validatedData = $request->validated();
        $validatedData['note'] = $request->note;
        $invoices = Invoice::find($id);

        $invoices->update($validatedData);


        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }

    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = Invoice::find($id);
        $Details = InvoiceAttachment::where('invoice_id', $id)->first();

        if(!$request->id_page == 2){
            if (!empty($Details->invoice_number)) {

                FacadesFile::deleteDirectory(public_path('assets/upload/invoice_attachment/'.$Details->invoice_number));
            }
            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return back();
        }else{
            $invoices->delete();
            session()->flash('archive_invoice');
            return back();
        }

    }
    public function getproducts($id)
    {
        $products = Product::where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }
    public function Status_Update(Request $request, $id){

        $invoices = Invoice::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([

                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            InvoiceDetails::create([

                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (auth()->user()->name),
            ]);
        }

        else {
            $request->validate([
                'paid' => 'required|numeric',
                'remainer' => 'required|numeric'
            ]);
            InvoicePayment::create([
                'paid' =>$request->paid,
                'remainer' => $request->remainer,
                'invoice_id' => $invoices->id
            ]);
            $invoices->update([

                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            InvoiceDetails::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (auth()->user()->name),
            ]);
        }

        session()->flash('Status_Update');
        return redirect()->route('invoices.index');

    }
    public function invoice_paid(){
        $invoice_type = 'paid';
        $invoices = Invoice::where('Value_Status', 1)->get();
        // return view('Invoices.invoices_paid', compact('invoices'));
        return view('Invoices.invoices', compact('invoices', 'invoice_type'));
    }
    public function invoice_unpaid(){
        $invoice_type = 'unpaid';
        $invoices = Invoice::where('Value_Status', 2)->get();
        // return view('Invoices.invoices_unpaid', compact('invoices'));
        return view('Invoices.invoices', compact('invoices', 'invoice_type'));
    }
    public function invoice_partial(){
        $invoice_type = 'partial';
        $invoices = Invoice::where('Value_Status', 3)->get();
        // return view('Invoices.invoices_Partial', compact('invoices'));
        return view('Invoices.invoices', compact('invoices', 'invoice_type'));
    }
    public function Print_invoice($id){
        $invoices = Invoice::find($id);
        $invoice_payments = InvoicePayment::where('invoice_id', $id)->get()->last();

        $sum = InvoicePayment::where('invoice_id', $id)->sum('paid');

        return view('invoices.Print_invoice',compact('invoices', 'invoice_payments', 'sum'));
    }
    public function export()
    {
       // dd('ddd');
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function payments($id){
        $invoice_payments = InvoicePayment::where('invoice_id', $id)->get();
        return view('Invoices.invoice_payments', compact('invoice_payments'));

    }

}
