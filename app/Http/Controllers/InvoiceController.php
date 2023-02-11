<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\InvoiceDetails;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddInvoice;
use File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

        $this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
        $this->middleware('permission:اضافة فاتورة', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل فاتوره', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
        $this->middleware('permission:تغير حالة الدفع', ['only' => ['show', 'Status_Update']]);
        $this->middleware('permission:الفواتير المدفوعة', ['only' => ['invoice_paid']]);
        $this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['invoice_unpaid']]);
        $this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['invoice_partial']]);
        $this->middleware('permission:طباعةالفاتورة', ['only' => ['Print_invoice']]);
        $this->middleware('permission:تصدير EXCEL', ['only' => ['export']]);

    }
    public function index()
    {
        $invoices = Invoice::get();
        return view('Invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::get();
        return view('Invoices.add_invoice', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'invoice_number' => 'required|integer',
            'invoice_Date' => 'required|date',
            'Due_date' => 'required|date',
            'product' => 'required',
            'Amount_collection' => 'required|integer',
            'Amount_Commission' => 'required|integer',
            'Discount' => 'required',
            'Value_VAT' => 'required',
            'Rate_VAT' => 'required',
            'Total' => 'required',
            'note' => 'string',
        ],[

            'invoice_number.required' =>'يرجي ادخال رقم الفاتوره',
            'invoice_Date.required' =>'يرجي ادخال تاريخ الفاتوره',
            'Due_date.required' =>'يرجي ادخال تاريخ الاستحقاق',
            'product.required' =>'يرجي ادخال اسم المنتج',
            'section_id.required' =>'يرجي ادخال القسم ',
            'Amount_collection.required' =>'يرجي ادخال مبلغ التحصيل ',
            'Amount_Commission.required' =>'يرجي ادخال مبلغ العمولة ',
            'Discount.required' =>'يرجي ادخال الخصم',
            'Value_VAT.required' =>'يرجي ادخال قيمة ضريبة القيمة المضافة ',
            'Rate_VAT.required' =>'يرجي ادخال نسبة ضريبة القيمة المضافة ',
            'Total.required' =>'يرجي ادخال الاجمالي ',
            'file_name.required' =>'يرجي ادخال الملف ',
            'invoice_number.integer' =>'رقم الفاتوره يجب ان يكون رقم',
            'invoice_Date.date' =>'تاريخ الفاتوره يجب ان يكون تاريخ',
            'Due_date.date' =>'تاريخ الاستحقاق يجب ان يكون تاريخ',
            'Amount_collection.integer' =>'مبلغ التحصيل يجب ان يكون رقم',
            'Amount_Commission.integer' =>'مبلغ العمولة يجب ان يكون رقم',
            'note.string' =>'   الملاحظات يجب ان تكون نص',


        ]);
        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        InvoiceDetails::create([
            'id_Invoice' => $invoice->id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoices = Invoice::find($id);
        return view('Invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = Invoice::find($id);
        $sections = Section::get();
        return view('Invoices.invoice_edit', compact('invoices', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $invoices = Invoice::find($id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = Invoice::find($id);
        $Details = InvoiceAttachment::where('invoice_id', $id)->first();

        if(!$request->id_page == 2){
            if (!empty($Details->invoice_number)) {

                File::deleteDirectory(public_path('assets/upload/invoice_attachment/'.$Details->invoice_number));
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
        $invoices = Invoice::where('Value_Status', 1)->get();
        return view('Invoices.invoices_paid', compact('invoices'));
    }
    public function invoice_unpaid(){
        $invoices = Invoice::where('Value_Status', 2)->get();
        return view('Invoices.invoices_unpaid', compact('invoices'));
    }
    public function invoice_partial(){
        $invoices = Invoice::where('Value_Status', 3)->get();
        return view('Invoices.invoices_Partial', compact('invoices'));
    }
    public function Print_invoice($id){
        $invoices = Invoice::find($id);
        return view('invoices.Print_invoice',compact('invoices'));
    }
    public function export()
    {
       // dd('ddd');
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

}
