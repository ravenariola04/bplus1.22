<?php
namespace App\Http\Controllers;
use App\User;
use App\Billing;
use App\Reservation;
use Auth, DB, Alert;
use App\BillingService;
use App\ReservationService;
use App\EmployeeReservation;
use Illuminate\Http\Request;

class EmployeeReservationsController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function viewAllReservations() {
    	$reservations = Reservation::join('users', 'users.id', 'reservations.customer_id')
			->leftjoin('users as users2', 'users2.id', 'reservations.processed_by')
            ->join('employee_reservation', 'employee_reservation.reservation_id', 'reservations.id')
			->select('reservations.*', 'users.firstname as customer_firstname', 'reservations.created_at as date_added', 'users.lastname as customer_lastname', 'users2.firstname as processedByFirstname', 'users2.lastname as processedByLastname')
			->where('employee_reservation.employee_id', Auth::user()->id)
			->get();

        $employeeReservations = EmployeeReservation::join('users as employees', 'employees.id', 'employee_reservation.employee_id')
            ->join('expertise', 'expertise.id', 'employees.expertise_id')
            ->select('employee_reservation.*', 'employees.firstname', 'employees.lastname', 'expertise.name as expertise')
            ->get();

		$getServices = ReservationService::join('services', 'services.id', 'reservation_service.service_id')
			->get();

		return view ('employees/reservation/viewAllReservations', 
			compact('reservations', 'getServices', 'employeeReservations'));
    }

    public function employeeApproveReservation($reservation_id) {
    	$reservation = Reservation::where('id', $reservation_id)->first();
    	$reservation->status = 'Approved';
    	$approved_by = Auth::user()->id;
    	$reservation->processed_by = $approved_by;
    	$reservation->save();

    	//get services from pivot
        $getServicesFromPivot = ReservationService::join('services', 'services.id', 'reservation_service.service_id')
            ->join('reservations', 'reservations.id', 'reservation_service.reservation_id')
            ->select('services.id as service_id', 'services.name as service_name', 'services.price as amount', 'reservation_service.reservation_id as reservation_id', 'reservations.customer_id as customer_id',
                'reservations.employee_id as stylist_id')
            ->where('reservation_service.reservation_id', $reservation_id)
            ->get();

        //Insert to billing
        $insertToBillingTable = Billing::create([
            'customer_id' => $getServicesFromPivot[0]->customer_id,
            'employee_id' => $getServicesFromPivot[0]->stylist_id,
            'cashier_id' => Auth::user()->id,
            'status' => 'Waiting for Payment'
        ]);

        foreach($getServicesFromPivot as $getServicesFromPivot1){
            $billing_id[] = $insertToBillingTable->id;
            $service_id[] = $getServicesFromPivot1->service_id;
            $amount[] = $getServicesFromPivot1->amount;
        }

        for($i=0;$i<count($service_id);$i++){
            BillingService::create([
                'billing_id' => $billing_id[$i], 
                'service_id' => $service_id[$i], 
                'amount' => $amount[$i]
            ]);
        }

    	Alert::success('Reservation Approved!')->autoclose(1000);
    	return redirect()->back();
    }

    public function employeeCancelReservation($reservation_id) {
    	$reservation = Reservation::where('id', $reservation_id)->first();
    	$reservation->status = 'Cancelled';
    	$cancelled_by = Auth::user()->id;
    	$reservation->processed_by = $cancelled_by;
    	$reservation->save();

    	Alert::success('Reservation Cancelled!')->autoclose(1000);
    	return redirect()->back();

    }

}
