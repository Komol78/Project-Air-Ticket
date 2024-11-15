<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Post;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contacts()
    {
        $contacts=Contact::get();
        return view('admin.contact.contact',compact('contacts'));
    }


    public function contact_details($id)
    {
       $contact=Contact::find($id);
       $contact->status=1;
       $contact->save();
       return view('admin.contact.contact-details',compact('contact'));
    }


    public function contact_delete($id)
    {
       $contact=Contact::find($id);
       $contact->delete();
       return back()->with('success','This contact deleted successfully!.');
    }


}

