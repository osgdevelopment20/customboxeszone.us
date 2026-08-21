<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ContactDataTable;
use App\Http\Controllers\Controller;
use App\Imports\ContactImport;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class MarketingController extends Controller
{
    public function index(ContactDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.marketing.contacts.index');
    }

    public function create(){
        return view('backend.pages.marketing.contacts.create');
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|min:2|max:255',
            'phone' => 'required',
        ]);

        $contacts = new Contact();
        $contacts->name = $request->name;
        $contacts->email = $request->email;
        $contacts->phone = $request->phone;
        $contacts->message = $request->message;
        $contacts->save();
        return redirect()->route('dashboard.marketing-contact.index')->with('success', 'Contact Save successfully');
    }


    public function edit(Contact $contact){
        return view('backend.pages.marketing.contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact){

        $request->validate([
            'name' => 'required|min:2|max:255',
            'phone' => 'required',
        ]);

        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;
        $contact->save();
        return redirect()->route('dashboard.marketing-contact.index')->with('success', 'Contact Update successfully');
    }


    public function delete(Contact $contact){
        $contact->delete();
        return redirect()->route('dashboard.marketing-contact.index')->with('success', 'Contact Delete successfully');
    }


    public function importContacts(Request $request){
        $request->validate([
            'import_contacts' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ContactImport, $request->file('import_contacts'));
        return redirect()->route('dashboard.marketing-contact.index')->with('success', 'Contact Import successfully');
    }
}
