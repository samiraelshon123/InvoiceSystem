<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::get();
        $sections = Section::get();
        return view('Products.products', compact('sections', 'products'));
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
        $validatedData = $request->validate([
            'Product_name' => 'required|unique:products|max:255',
            'section_id' => 'required'
        ],[

            'Product_name.required' =>'يرجي ادخال اسم المنتج',
            'section_id.required' =>'يرجي ادخال اسم القسم',
            'Product_name.unique' =>'اسم المنتج مسجل مسبقا',


        ]);
        Product::create([
            'Product_name' => $request->Product_name,
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح ');
        return redirect()->route("products.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $Products = Product::find($request->pro_id);

        $Products->update([
        'Product_name' => $request->Product_name,
        'description' => $request->description,
        'section_id' => $request->section_name,
        ]);

        session()->flash('Edit', 'تم تعديل المنتج بنجاح');
        return redirect()->route("products.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $Products = Product::find($request->pro_id);
        $Products->delete();
        session()->flash('delete', 'تم حذف المنتج بنجاح');
        return back();
    }
}
