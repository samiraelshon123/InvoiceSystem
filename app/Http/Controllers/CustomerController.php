<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:customer_add', ['only' => ['create','store']]);
        $this->middleware('permission:customer_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:customer_delete', ['only' => ['destroy']]);

    }
    public function index(Request $request)
    {
        $data = Customer::orderBy('id','DESC')->paginate(5);

        return view('customer.index',compact('data'))
        ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function create()
        {
            $user = new Customer();
            $route = route('customers.store');
            return view('customer.form', compact('user', 'route'));

        }
/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
    public function store(Request $request)
    {
        $input = $this->validate($request, [
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'address' => 'required|string',
        'phone' => 'required',
        'type' => 'required'
        ]);



        $user = Customer::create($input);
        return redirect()->route('customers.index')
        ->with('success','تم اضافة العميل بنجاح');
    }

    // /**
    // * Display the specified resource.
    // *
    // * @param  int  $id
    // * @return \Illuminate\Http\Response
    // */
    public function show($id)
    {
    $user = Customer::find($id);
    return view('customer.show',compact('user'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $user = Customer::find($id);
        $route = route('customers.update', $id);
        return view('customer.form', compact('user', 'route'));

    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {

        $input = $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$id,
        'address' => 'string',
        'phone' => '',
        'type' => ''
        ]);
        $user = Customer::find($id);

            $user->update($input);


        return redirect()->route('customers.index')
        ->with('success','تم تحديث معلومات العميل بنجاح');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request)
    {
        Customer::find($request->user_id)->delete();
        return redirect()->route('customer.index')->with('success','تم حذف العميل بنجاح');
    }
}
