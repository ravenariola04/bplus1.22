<?php
namespace App\Http\Controllers;
use App\Service;
use App\Expertise;
use App\Reservation;
use App\ServiceType;
use App\ReservationService;
use App\EmployeeReservation;
use Illuminate\Http\Request;
use App\User, Auth, Alert, DB;
use Illuminate\Support\Facades\Input;

class CustomerReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function viewAllReservations() {
        $reservations = Reservation::join('users', 'users.id', 'reservations.customer_id')
            ->leftjoin('users as users2', 'users2.id', 'reservations.processed_by')
            ->select('reservations.*', 'users.firstname as customer_firstname', 'reservations.created_at as date_added', 'users.lastname as customer_lastname', 'users2.firstname as processedByFirstname', 'users2.lastname as processedByLastname')
            ->where('reservations.customer_id', Auth::user()->id)
            ->get();

        $employeeReservations = EmployeeReservation::join('users as employees', 'employees.id', 'employee_reservation.employee_id')
            ->join('expertise', 'expertise.id', 'employees.expertise_id')
            ->select('employee_reservation.*', 'employees.firstname', 'employees.lastname', 'expertise.name as expertise')
            ->get();

        $getServices = ReservationService::join('services', 'services.id', 'reservation_service.service_id')
            ->get();

        return view ('customers/reservation/viewAllReservations', compact('reservations', 'getServices', 'employeeReservations'));
    }

    public function customerCancelReservation($reservation_id) {
        $reservation = Reservation::where('id', $reservation_id)->first();
        $reservation->status = 'Cancelled';
        $cancelled_by = Auth::user()->id;
        $reservation->processed_by = $cancelled_by;
        $reservation->save();

        Alert::success('Reservation Cancelled!')->persistent("OK");
        return redirect()->back();
    }

    public function addHomeServiceReservation() {
    	$service_types = ServiceType::all();
    	$services = Service::all();
    	$employees = User::join('expertise', 'expertise.id', 'users.expertise_id')
            ->select('users.firstname', 'users.lastname', 'users.id', 'expertise.name as expertise', 'expertise.id as expertise_id')
            ->where('role_id', User::IS_EMPLOYEE)->get();
        $expertise = Expertise::all();

    	return view ('customers/reservation/home-service/addHomeServiceReservation', 
    		compact('service_types', 'services', 'employees', 'expertise'));
    }

    public function storeHomeServiceReservation(Request $request) {

    	$this->validate($request, [
    		'reservation_date' => 'required', 'reservation_time' => 'required', 'address' => 'required', 
    		'employee_id' => 'required', 'service_id' => 'required'
    	]);

    	/*$checkReservationConflict = Reservation::where('reservation_time', $request->reservation_time)
            ->where('reservation_date', $request->reservation_date)
            ->where('employee_id', $request->employee_id)
            ->first();

        $checkReservationConflict1 = Reservation::where('reservation_time', $request->reservation_time)
            ->where('reservation_date', $request->reservation_date)
            ->where('customer_id', $request->customer_id)
            ->first();
        //check if customer is currently reserved with same date/time

        if($checkReservationConflict || $checkReservationConflict1) {
        	Alert::error('Home Service Reservation Has Conflict!')->persistent("OK");
	        return redirect()->back()->withInput(Input::all());
        } else { //IF THERE IS NO CONFLICT*/
        	$createHomeServiceReservation = Reservation::create([
        		'customer_id' => $request->customer_id,
        		'reservation_date' => $request->reservation_date,
        		'reservation_time' => $request->reservation_time,
        		'type' => 'Home Service',
        		'address' => $request->address,
        		'status' => 'Pending'
        	]);
        /*}*/

        //Insert multiple hairstylist and reservation id to pivot
        $i = 0; 
        foreach($request->employee_id as $key => $v){
            $createEmployeeReservation = EmployeeReservation::create([
                'employee_id' => $request->employee_id[$i],
                'reservation_id' => $createHomeServiceReservation->id
            ]);
            $i++;
        }

        $i = 0; 
        foreach($request->service_id as $key => $v){
            $createReservationServicePivot = ReservationService::create([
                'reservation_id' => $createHomeServiceReservation->id,
                'service_id' => $request->service_id[$i],
            ]);
            $i++;
        }

        Alert::success('Customer Home Service Reservation Successful!')->persistent("OK");
    	return redirect()->back();

    }

    public function addOnSpaReservation() {
    	$service_types = ServiceType::all();
    	$services = Service::all();
    	$employees = User::join('expertise', 'expertise.id', 'users.expertise_id')
            ->select('users.firstname', 'users.lastname', 'users.id', 'expertise.name as expertise', 'expertise.id as expertise_id')
            ->where('role_id', User::IS_EMPLOYEE)->get();
        $expertise = Expertise::all();

    	return view ('customers/reservation/on-spa/addOnSpaReservation',
    		compact('service_types', 'services', 'employees', 'expertise'));
    }

    public function storeOnSpaReservation(Request $request) {

    	$this->validate($request, [
    		'reservation_date' => 'required', 'reservation_time' => 'required', 'employee_id' => 'required', 'service_id' => 'required'
    	]);

    	/*$checkReservationConflict = Reservation::where('reservation_time', $request->reservation_time)
            ->where('reservation_date', $request->reservation_date)
            ->where('employee_id', $request->employee_id)->first();

        $checkReservationConflict1 = Reservation::where('reservation_time', $request->reservation_time)
        	->where('reservation_date', $request->reservation_date)
        	->where('customer_id', $request->customer_id)->first();
        //check if customer is currently reserved with same date/time

       	if($checkReservationConflict || $checkReservationConflict1) {
	            Alert::error('On Salon Reservation Has Conflict!')->persistent("OK");
	            return redirect()->back()->withInput(Input::all());
	        } else { //IF THERE IS NO CONFLICT*/
	        	$createOnSalonReservation = Reservation::create([
	        		'customer_id' => $request->customer_id,
	        		'reservation_date' => $request->reservation_date,
	        		'reservation_time' => $request->reservation_time,
	        		'type' => 'On Salon',
	        		'address' => 'Not Provided',
	        		'status' => 'Pending'
	        	]);

                //Insert multiple hairstylist and reservation id to pivot
                $i = 0; 
                foreach($request->employee_id as $key => $v){
                    $createEmployeeReservation = EmployeeReservation::create([
                        'employee_id' => $request->employee_id[$i],
                        'reservation_id' => $createOnSalonReservation->id
                    ]);
                    $i++;
                }

	        	$i = 0; 
		        foreach($request->service_id as $key => $v){
		            $createReservationServicePivot = ReservationService::create([
		                'reservation_id' => $createOnSalonReservation->id,
		                'service_id' => $request->service_id[$i],
		            ]);
		            $i++;
		        /*}*/

		        Alert::success('Customer On Salon Reservation Successful!')->persistent("OK");
    			return redirect()->back();
	        }
    }


}
