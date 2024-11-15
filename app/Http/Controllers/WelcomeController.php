<?php

namespace App\Http\Controllers;

use App\Models\Flighttype;
use App\Models\Flight;
use App\Models\Contact;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{


	 public function index()
    {

        $types=Flighttype::get();
        $dates=Flight::distinct('date')->get(['id','date']);
    	return view('welcome',compact('types','dates'));
    }


    public function flight_search(Request $request)
    {

        request()->validate([
            'flight_type'=>'required|string|max:191',
            'from_country'=>'required|string|max:191',
            'to_country'=>'required|string|max:191',
            'class'=>'required|string|max:191',
            'person'=>'required|string|max:191',
            'date'=>'required|string|max:191',
        ]);

        $flight_type=Flighttype::find($request->flight_type);


        if ($request->class=="Businesss")
        {
        
          $flights=Flight::where('flight_type',$flight_type->flight_type)->where('from_country',$request->from_country)->where('to_country',$request->to_country)->where('date',date('Y-m-d',strtotime($request->date)))->where('business_seat','>=',$request->person)->first();  
       }else{

           $flights=Flight::where('flight_type',$flight_type->flight_type)->where('from_country',$request->from_country)->where('to_country',$request->to_country)->where('date',date('Y-m-d',strtotime($request->date)))->where('business_seat','>=',$request->person)->first(); 
       }

       $ft=$flight_type->flight_type;
       $ft_id=$flight_type->id;
       $fc=$request->from_country;
       $tc=$request->to_country;
       $dt=date('d/m/Y',strtotime($request->date));
       $cls=$request->class;
       $psn=$request->person;


       return view('front.flight-search',compact('flights','ft','ft_id','fc','tc','dt','cls','psn'));
    }



    public function about()
    {
    	return view('front.about');
    }

   

     public function contact()
    {
    	return view('front.contact');
    }

     public function store_contact(Request $request)
    {
       

        request()->validate([
            'name'=>'required|string|max:191',
            'email'=>'required|string|max:191',
            'subject'=>'required|string|max:191',
            'message'=>'required|string',
          ]);


        $contact=new Contact();
        $contact->name=$request->name;
        $contact->email=$request->email;
        $contact->subject=$request->subject;
        $contact->message=$request->message;
        $contact->save();

        return back()->with('success','Your message send successfully!.');

    }


    public function privacy_policy()
    {
       return view('front.privacy-policy');
    }

    public function term_condition()
    {
       return view('front.term-condition');
    }




}
