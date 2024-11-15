<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Flightbook;
use App\Models\Flighttype;
use Illuminate\Http\Request;

class FlightController extends Controller
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



    public function flight()
    {
    	$flights=Flight::get();
    	return view('admin.flight.flight',compact('flights'));
    }


     public function add_flight()
    {
    	$types=Flighttype::get();
    	return view('admin.flight.add-flight',compact('types'));
    }

    public function store_flight(Request $request)
    {
    	request()->validate([
    		'flight_type'=>'required|string|max:191',
    		'from_country'=>'required|string|max:191',
    		'to_country'=>'required|string|max:191',
    		'date'=>'required|string|max:191',
    		'business_seat'=>'required|string|max:191',
    		'regular_seat'=>'required|string|max:191',
    		'business_price'=>'required|string|max:191',
    		'regular_price'=>'required|string|max:191',
    	]);


        $flight_type=Flighttype::find($request->flight_type);

    	$flight=new Flight();
    	$flight->flight_type=$flight_type->flight_type;
    	$flight->flight_type_id=$flight_type->id;
    	$flight->from_country=$request->from_country;
    	$flight->to_country=$request->to_country;
    	$flight->date=$request->date;
    	$flight->business_seat=$request->business_seat;
    	$flight->regular_seat=$request->regular_seat;
    	$flight->business_price=$request->business_price;
    	$flight->regular_price=$request->regular_price;
    	$flight->save();


    	return back()->with('success','This flight  saved successfully.');	


    }


    public function edit_flight($id)
    {
        $flights=Flight::find($id);
        return view('admin.flight.edit-flight',compact('flights'));  
    }


    public function update_flight(Request $request)
    {
            request()->validate([
            'date'=>'required|string|max:191',
            'business_seat'=>'required|string|max:191',
            'regular_seat'=>'required|string|max:191',
            'business_price'=>'required|string|max:191',
            'regular_price'=>'required|string|max:191',
        ]);



        $flight= Flight::find($request->flight_id);
        $flight->date=$request->date;
        $flight->business_seat=$request->business_seat;
        $flight->regular_seat=$request->regular_seat;
        $flight->business_price=$request->business_price;
        $flight->regular_price=$request->regular_price;
        $flight->save();


        return back()->with('success','This flight  updated successfully.');  
    }



    public function delete_flight($id)
    {
        $flight= Flight::find($id);
        $flight->delete();
        return back()->with('success','This flight  deleted successfully.');  
    }


    public function book_flight($type)
    {

        if ($type=='booked') {
            $flights=Flightbook::where('status',1)->get();
        }else{
          $flights=Flightbook::where('status',0)->get();  
        }
        
        return view('admin.book-flight.book-flight',compact('flights'));
    }


    public function book_flight_status($id)
    {

        $bf=Flightbook::find($id);
        $fl=Flight::find($bf->flight_id);
        if ($bf->class="Business") {
          $fl->business_seat=$fl->business_seat-$bf->person;
        }else{
           $fl->regular_seat=$fl->regular_seat-$bf->person; 
        }

        $fl->save();

        $bf->status=1;

        $bf->save();
    

        return back()->with('success','This flight  booked successfully.');  
       

    }



    public function book_flight_details($id)
    {
       $flights=Flightbook::find($id);

       return view('admin.book-flight.book-flight-details',compact('flights'));
    }


    public function book_flight_delete($id)
    {

       $flights=Flightbook::find($id);
       $flights->delete();
       return back()->with('success','This book  flight deleted successfully.');  

    }

    public function flightReviews()
    {
        $flights=Flightbook::where('status',1)->get();
        return view('admin.book-flight.reviews',compact('flights'));
    }

}
