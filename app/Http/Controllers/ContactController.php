<?php

namespace App\Http\Controllers;

use App\Models\contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(){
        $contact = contact::first();
       return view('Contact.index', compact('contact'));
    }
    public function edit($id){
        $contact = contact::find($id);
        return view('Contact.form', compact('contact'));
    }
    public function update(Request $request, $id){
        $data = $request->validate([
            'name' => 'string',
            'email' => 'email',
            'address' => 'string',
            'phone' => 'numeric'
        ]);
        $contact = contact::find($id);
        $contact->update($data);
        session()->flash('Contact_Update');
        return redirect()->route('contacts.index');
    }
}
