<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceArchieveController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:invoices_archive', ['only' => ['index', 'update', 'destroy']]);

    }
    public function index(){
        $invoices = Invoice::onlyTrashed()->get();
        return view('Invoices.Archive_Invoices', compact('invoices'));
    }
    public function update(Request $request){
        $id = $request->invoice_id;
        $flight = Invoice::withTrashed()->where('id', $id)->restore();
        session()->flash('restore_invoice');
        return redirect()->route('invoices.index');
    }
    public function destroy(Request $request){
        $invoices = Invoice::withTrashed()->where('id',$request->invoice_id)->first();
         $invoices->forceDelete();
         session()->flash('delete_invoice');
         return redirect()->route('invoices_archive.index');
    }
}
