<?php
namespace App\Http\Controllers;
use Auth, DB, Alert;
use App\BillingEmployee;
use App\ReservationService;
use App\EmployeeReservation;
use Illuminate\Http\Request;
use App\Billing, App\BillingService;
use App\Reservation, App\ServiceType;
use Illuminate\Support\Facades\Input;
use App\User, App\Service, App\Expertise, App\Vat, App\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ReservationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
	public function viewAllReservations() {
		$reservations = Reservation::join('users', 'users.id', 'reservations.customer_id')
			->leftjoin('users as users2', 'users2.id', 'reservations.processed_by')
			->select('reservations.*', 'users.firstname as customer_firstname', 'reservations.created_at as date_added', 'users.lastname as customer_lastname', 'users2.firstname as processedByFirstname', 'users2.lastname as processedByLastname')
			->get();

        $employeeReservations = EmployeeReservation::join('users as employees', 'employees.id', 'employee_reservation.employee_id')
            ->join('expertise', 'expertise.id', 'employees.expertise_id')
            ->select('employee_reservation.*', 'employees.firstname', 'employees.lastname', 'expertise.name as expertise')
            ->get();

		$getServices = ReservationService::join('services', 'services.id', 'reservation_service.service_id')
			->get();

		return view ('system/reservation/viewAllReservations', 
			compact('reservations', 'getServices', 'employeeReservations'));
	}

    public function addHomeServiceReservation() {
    	$service_types = ServiceType::all();
    	$services = Service::all();
    	$employees = User::join('expertise', 'expertise.id', 'users.expertise_id')
            ->select('users.id', 'users.firstname', 'users.lastname', 'expertise.name as expertise', 
                'expertise.id as expertise_id')
            ->where('role_id', User::IS_EMPLOYEE)->get();
        $expertise = Expertise::all();

    	return view ('system/reservation/home-service/addHomeServiceReservation', 
    		compact('service_types', 'services', 'employees', 'expertise'));
    }

    public function storeHomeServiceReservation(Request $request) {
        
    	$this->validate($request, [
    		'firstname' => 'required', 'lastname' => 'required', 'reservation_date' => 'required',
    		'reservation_time' => 'required', 'address' => 'required|max:255', 'employee_id' => 'required',
    		'service_id' => 'required'
    	]);

      
        $current_time = date('h:i:s a');
        // return date('h:i:s', strtotime($request->walkin_time));
        $post_time = date('h:i:s a', strtotime($request->reservation_time));

        if($post_time < $current_time){
            // return 'Fail';
            Alert::error('Invalid Time! Please input a valid time greater than the current time.')->persistent("OK");
            return $this->addHomeServiceReservation();
        }

    	 $checkCustomer = User::where('firstname', 'LIKE', "%$request->firstname%")
            ->where('lastname', 'LIKE', "%$request->lastname")->where('role_id', User::IS_CUSTOMER)
            ->select('users.id as customer_id')->first();

    	//if user exists
    	if($checkCustomer) {
    		
           


            $checkReservationConflict = Reservation::where('reservation_time', $request->reservation_time)
            ->where('reservation_date', $request->reservation_date)
            ->where('status', '!=', 'Cancelled')
            ->count('reservation_time');

            // $checkReservationConflict1 = Reservation::where('reservation_time', $request->reservation_time)
            //     ->where('reservation_date', $request->reservation_date)
            //     ->where('customer_id', $checkCustomer->customer_id)
            //     ->where('status', '!=', 'Cancelled')
            //     ->first();
            //check if customer is currently reserved with same date/time
            // return $checkReservationConflict;
            if($checkReservationConflict > 0 ) {
                Alert::error('Home Service Reservation Has Time Conflict!')->persistent("OK");
                return redirect()->back()->withInput(Input::all());
            } 
    		/*$checkReservationConflict = Reservation::where('reservation_time', $request->reservation_time)
            ->where('reservation_date', $request->reservation_date)
            ->where('employee_id', $request->employee_id)
            ->where('status', '!=', 'Cancelled')
            ->first();

            $checkReservationConflict1 = Reservation::where('reservation_time', $request->reservation_time)
            	->where('reservation_date', $request->reservation_date)
            	->where('customer_id', $checkCustomer->user_id)
                ->where('status', '!=', 'Cancelled')
            	->first();
            //check if customer is currently reserved with same date/time

	        if($checkReservationConflict || $checkReservationConflict1) {
	            Alert::error('Home Service Reservation Has Conflict!')->persistent("OK");
	            return redirect()->back()->withInput(Input::all());
	        } else { //IF THERE IS NO CONFLICT*/
	        	

                

                $lowercaseAddress = Str::lower($request->address);
                $contains = str_contains($lowercaseAddress, 'meycuayan bulacan');

                // if($contains ){
                    $createHomeServiceReservation = Reservation::create([
                    'customer_id' => $checkCustomer->customer_id,
                    'reservation_date' => $request->reservation_date,
                    'reservation_time' => $request->reservation_time,
                    'type' => 'Home Service',
                    'address' => $request->address,
                    'status' => 'Pending'
                    ]);

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

                    Alert::success('Home Service Reservation Successful!')->persistent("OK");
                    return redirect()->route('viewAllReservations');


                // }else{
                //     Alert::error('Home Service Reservation Address Not Accepted!')->persistent("OK");
                //     return redirect()->route('viewAllReservations');

                // }   
		        
	        /*}*/

    	} else { //if customer doesnt exist
    		
    		$generateEmail = $request->firstname.$request->lastname.'@gmail.com';

    		$createNewUser = User::create([
    			'role_id' => 2,
    			'firstname' => $request->firstname,
    			'lastname' => $request->lastname,
    			'email' => $generateEmail,
    			'password' => bcrypt('password'),
    			'contact_no' => '09123456789',
    			'address' => $request->address,
    			'gender' => 'male'
    		]);

    		/*$checkReservationConflict = Reservation::where('reservation_time', $request->reservation_time)
            ->where('reservation_date', '=', $request->reservation_date)
            ->where('employee_id', $request->employee_id)
            ->where('status', '!=', 'Cancelled')
            ->first();
            //check if employee is currently reserved with same date/time

            $checkReservationConflict1 = Reservation::where('reservation_time', $request->reservation_time)
            	->where('reservation_date', $request->reservation_date)
            	->where('customer_id', $createNewUser->customer_id)
                ->where('status', '!=', 'Cancelled')
            	->first();
            //check if customer is currently reserved with same date/time

	        if($checkReservationConflict || $checkReservationConflict1) {
	            Alert::error('Home Service Reservation Has Conflict!')->persistent("OK");
	            return redirect()->back()->withInput(Input::all());
	        } else { //IF THERE IS NO CONFLICT*/

                // $lowercaseAddress = Str::lower($request->address);

                // if($lowercaseAddress == "meycuayan bulacan"){

                // }
	        	$createHomeServiceReservation = Reservation::create([
	        		'customer_id' => $createNewUser->id,
	        		'reservation_date' => $request->reservation_date,
	        		'reservation_time' => $request->reservation_time,
	        		'type' => 'Home Service',
	        		'address' => $request->address,
	        		'status' => 'Pending'
	        	]);

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

		        Alert::success('Home Service Reservation Successful!')->persistent("OK");
    			return redirect()->route('viewAllReservations');
    		/*}*/
    	}

    }

    public function addOnSpaReservation() {
    	$service_types = ServiceType::all();
    	$services = Service::all();
    	$employees = User::join('expertise', 'expertise.id', 'users.expertise_id')
            ->select('users.id', 'users.firstname', 'users.lastname', 'expertise.name as expertise', 'expertise.id as expertise_id')
            ->where('role_id', User::IS_EMPLOYEE)->get();
        $expertise = Expertise::all();
            
    	return view ('system/reservation/on-spa/addOnSpaReservation', 
    		compact('service_types', 'services', 'employees', 'expertise'));
    }

    public function storeOnSpaReservation(Request $request) {

        $current_day = date('Y-m-d');
        $current_time = date('h:i:s a');
        // return date('h:i:s', strtotime($request->walkin_time));
        $post_time = date('h:i:s a', strtotime($request->reservation_time));

    	$this->validate($request, [
    		'firstname' => 'required', 'lastname' => 'required', 'reservation_date' => 'required',
    		'reservation_time' => 'required', 'employee_id' => 'required',
    		'service_id' => 'required'
    	]);

        if($post_time < $current_time){
            // return 'Fail';

            Alert::error('Invalid Time! Please input a valid time greater than the current time.')->persistent("OK");
            return $this->addOnSpaReservation();
        }

    	$checkCustomer = User::where('firstname', 'LIKE', "%$request->firstname%")
    		->where('lastname', 'LIKE', "%$request->lastname")
    		->where('role_id', User::IS_CUSTOMER)
    		->select('users.id as customer_id')
    		->first();

    	//if user exists
    	if($checkCustomer){
            $checkReservationConflict = Reservation::where('reservation_time', $request->reservation_time)
            ->where('reservation_date', $request->reservation_date)
            ->where('status', '!=', 'Cancelled')
            ->count('reservation_time');

            // $checkReservationConflict1 = Reservation::where('reservation_time', $request->reservation_time)
            //     ->where('reservation_date', $request->reservation_date)
            //     ->where('customer_id', $checkCustomer->customer_id)
            //     ->where('status', '!=', 'Cancelled')
            //     ->first();
            //check if customer is currently reserved with same date/time
            // return $checkReservationConflict;
            if($checkReservationConflict > 0 ) {
                Alert::error('Home Service Reservation Has Time Conflict!')->persistent("OK");
                return redirect()->back()->withInput(Input::all());
            } 

    		/*$checkReservationConflict = Reservation::where('reservation_time', $request->reservation_time)
            ->where('reservation_date', $request->reservation_date)
            ->where('employee_id', $request->employee_id)
            ->where('status', '!=', 'Cancelled')
            ->first();

            $checkReservationConflict1 = Reservation::where('reservation_time', $request->reservation_time)
            	->where('reservation_date', $request->reservation_date)
            	->where('customer_id', $checkCustomer->customer_id)
                ->where('status', '!=', 'Cancelled')
                ->first();*/
            //check if customer is currently reserved with same date/time

            /*if($checkReservationConflict || $checkReservationConflict1) {
	            Alert::error('On Salon Reservation Has Conflict!')->persistent("OK");
	            return redirect()->back()->withInput(Input::all());
	        } else { //IF THERE IS NO CONFLICT*/


	        	$createOnSalonReservation = Reservation::create([
	        		'customer_id' => $checkCustomer->customer_id,
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
		        }

		        Alert::success('On Salon Reservation Successful!')->persistent("OK");
    			return redirect()->route('viewAllReservations');
	        /*}*/
    	} else { //if customer doesnt exist
    		
    		$generateEmail = $request->firstname.$request->lastname.'@gmail.com';

    		$createNewUser = User::create([
    			'role_id' => 2,
    			'firstname' => $request->firstname,
    			'lastname' => $request->lastname,
    			'email' => $generateEmail,
    			'password' => bcrypt('password'),
    			'contact_no' => '09123456789',
    			'address' => 'Not Provided',
    			'gender' => 'male'
    		]);

    		/*$checkReservationConflict = Reservation::where('reservation_time', $request->reservation_time)
            ->where('reservation_date', '=', $request->reservation_date)
            ->where('employee_id', $request->employee_id)
            ->where('status', '!=', 'Cancelled')
            ->first();
            //check if employee is currently reserved with same date/time

            $checkReservationConflict1 = Reservation::where('reservation_time', $request->reservation_time)
            	->where('reservation_date', $request->reservation_date)
            	->where('customer_id', $createNewUser->id)
                ->where('status', '!=', 'Cancelled')
            	->first();
            //check if customer is currently reserved with same date/time

	        if($checkReservationConflict || $checkReservationConflict1) {
	            Alert::error('On Salon Reservation Has Conflict!')->persistent("OK");
	            return redirect()->back()->withInput(Input::all());
	        } else { //IF THERE IS NO CONFLICT*/
	        	$createOnSalonReservation = Reservation::create([
	        		'customer_id' => $createNewUser->id,
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
		        }

		        Alert::success('On Salon Reservation Successful!')->persistent("OK");
    			return redirect()->route('viewAllReservations');
    		/*}*/
    	}
    }

    public function adminApproveReservation($reservation_id) {
        
    	$reservation = Reservation::where('id', $reservation_id)->first();
    	$reservation->status = 'Approved';
    	$approved_by = Auth::user()->id;
    	$reservation->processed_by = $approved_by;
    	$reservation->save();


        $user = Reservation::join('users', 'users.id', 'reservations.customer_id')
            ->leftjoin('users as users2', 'users2.id', 'reservations.processed_by')
            ->select('reservations.*', 'users.firstname as customer_firstname', 'reservations.created_at as date_added', 'users.lastname as customer_lastname', 'users2.firstname as processedByFirstname', 'users2.lastname as processedByLastname', 'users2.email as email_user')
            ->first();
        // return $user->email_user;
         $user2 = DB::table('users')->select('email')->where('users.id','=', $reservation_id)->first();

        //get services from pivot
        $getServicesFromPivot = ReservationService::join('services', 'services.id', 'reservation_service.service_id')
            ->join('reservations', 'reservations.id', 'reservation_service.reservation_id')
            ->select('services.id as service_id', 'services.name as service_name', 'services.price as amount', 'reservation_service.reservation_id as reservation_id', 'reservations.customer_id as customer_id')
            ->where('reservation_service.reservation_id', $reservation_id)
            ->get();

        $getEmployeeReservationPivot = EmployeeReservation::where('reservation_id', $reservation_id)->get();
        
        //Insert to billing
        $insertToBillingTable = Billing::create([
            'customer_id' => $getServicesFromPivot[0]->customer_id,
            'cashier_id' => Auth::user()->id,
            'reservation_id' => $reservation_id,
            'status' => 'Waiting for Payment'
        ]);

        foreach($getEmployeeReservationPivot as $getEmployeeReservationPivot1){
            $billing_id[] = $insertToBillingTable->id;
            $employee_id[] = $getEmployeeReservationPivot1->employee_id;
        }

        for($i=0;$i<count($employee_id);$i++){
            BillingEmployee::create([
                'billing_id' => $billing_id[$i], 
                'employee_id' => $employee_id[$i], 
            ]);
        }

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


        $getTotalAmountDue = BillingService::select(DB::raw('SUM(billing_service.amount) as total'))
            ->where('billing_id', $insertToBillingTable->id)->first();

        $getAllServices = BillingService::join('services', 'services.id', 'billing_service.service_id')
            ->join('billing', 'billing.id', 'billing_service.billing_id')
            ->join('users as customers', 'customers.id', 'billing.customer_id')
            ->select('services.name as service_name', 'services.price', 'billing.id as billing_id',
                    'billing_service.created_at as created_at', 'customers.id as customer_id', 'services.id as id', 
                    'customers.firstname as customer_firstname', 'customers.lastname as customer_lastname')
            ->where('billing_service.billing_id', $insertToBillingTable->id)
            ->get();

        $vat = Vat::first();

        $BillingEmployees = BillingEmployee::join('users as employees', 'employees.id', 'billing_employee.employee_id')
            ->join('expertise', 'expertise.id', 'employees.expertise_id')
            ->select('billing_employee.*', 'employees.lastname', 'employees.firstname', 'expertise.name as expertise')
            ->where('billing_employee.billing_id', $insertToBillingTable->id)
            ->get();

        $sumBillingEmployeeServiceFee = BillingEmployee::join('users as employees', 'employees.id', 'billing_employee.employee_id')
            ->join('expertise', 'expertise.id', 'employees.expertise_id')
            ->select(DB::raw('SUM(expertise.service_fee) as totalServiceFee'))
            ->where('billing_employee.billing_id', $insertToBillingTable->id)
            ->first();

         // Mail::send('emails.welcome', $user, function($message) use ($user)
         //    {
         //        $message->from('BPLUS@gmail.com', "BPLUS");
         //        $message->subject(" Reservation Approved");
         //        $message->to($user['email_user']);
         //    });
       

    	// Alert::success('Reservation Approved!')->persistent("OK");
        return view ('system/reservation/viewAllReservationsDown', 
            compact('getTotalAmountDue', 'getAllServices', 'vat', 'BillingEmployees', 'sumBillingEmployeeServiceFee'));
    	// return redirect()->back();
    }

    public function adminCancelReservation($reservation_id) {
    	$reservation = Reservation::where('id', $reservation_id)->first();
    	$reservation->status = 'Cancelled';
    	$cancelled_by = Auth::user()->id;
    	$reservation->processed_by = $cancelled_by;
    	$reservation->save();

    	Alert::success('Reservation Cancelled!')->persistent("OK");
    	return redirect()->back();

    }


     public function adminPayBillingDown(Request $request) {

        $this->validate($request, [
            'amount_paid' => 'required'
        ]);

        //VALIDATE PAYMENT
        // if($request->amount_paid < $request->totalAmountDue) { //if amount paid < total amount due
        //     Alert::error('Invalid Amount! Please pay the total amount due.')->persistent("OK");
        //     return redirect()->back()->withInput(Input::all());
        // }

        //IF AMOUNT PAID IS LESSER THAN TOTAL AMOUNT TO BE PAID (MAY SUKLI)
        if($request->amount_paid >= $request->totalAmountDue) {
            $change = ($request->amount_paid - $request->totalAmountDue);
        }

        //INSERT PAYMENT
        $addPayment = Payment::create([
            'customer_id' => $request->customer_id,
            'total_amount' => $request->totalAmountDue,
            'amount_paid' => $request->amount_paid,
            'change' => $change
        ]);

        // //UPDATE BILLING STATUS TO PAID
        // $updateBillingStatus = Billing::find($request->billing_id);
        // $updateBillingStatus->status = 'Paid';
        // $updateBillingStatus->save();

        // $getBillingEmployee = BillingEmployee::where('billing_id', $request->billing_id)->get();
        // $employeeCount = count($getBillingEmployee);

        // //INSERT HAIRSTYLIST/EMPLOYEE COMMISSION
        // $getDefaultCommissionPercentage = CommissionSetting::first();
        // $percentage = $getDefaultCommissionPercentage->percentage;

        // //Convert our percentage value into a decimal.
        // $finalTotalAmountDue = $request->totalAmountDue - $request->totalServiceFee1;
        // $percentageInDecimal = $percentage / 100;
        // $totalEmployeeCommission = $percentageInDecimal * ($finalTotalAmountDue / $employeeCount);

        // $insertCommission = Commission::create([
        //     'commission' => $totalEmployeeCommission
        // ]);

        // foreach($getBillingEmployee as $getBillingEmployee1) {
        //     $commission_id[] = $insertCommission->id;
        //     $employee_id[] = $getBillingEmployee1->employee_id;
        // }

        // for($i=0;$i<count($employee_id);$i++){
        //     CommissionEmployee::create([
        //         'commission_id' => $commission_id[$i],
        //         'employee_id'   => $employee_id[$i],
        //     ]);
        // }

        // $getAllServices = BillingService::join('services', 'services.id', 'billing_service.service_id')
        //     ->select('services.id as service_id', 'services.name as service_name', 
        //             'services.price as price')
        //     ->where('billing_service.billing_id', $request->billing_id)
        //     ->get();

        // foreach($getAllServices as $getAllServices1){
        //     $service_id[] = $getAllServices1->service_id;
        // }

        // for($i=0;$i<count($service_id);$i++){
        //     CommissionService::create([
        //         'commission_id' => $insertCommission->id, 
        //         'service_id' => $service_id[$i]
        //     ]);
        // }

        Alert::success('Payment Successful! <br> Total Amount:&#8369;'.$request->totalAmountDue.'<br> Amount Paid:&#8369;'.$request->amount_paid.'<br>Change:&#8369;'.$change.'')->html()->persistent("OK");

            // return redirect()->route('viewReservationReceipt', ['billing_id' => $request->billing_id, 'amount_paid' => $request->amount_paid, 'change' => $change]);

        $reservations = Reservation::join('users', 'users.id', 'reservations.customer_id')
            ->leftjoin('users as users2', 'users2.id', 'reservations.processed_by')
            ->select('reservations.*', 'users.firstname as customer_firstname', 'reservations.created_at as date_added', 'users.lastname as customer_lastname', 'users2.firstname as processedByFirstname', 'users2.lastname as processedByLastname')
            ->get();

        $employeeReservations = EmployeeReservation::join('users as employees', 'employees.id', 'employee_reservation.employee_id')
            ->join('expertise', 'expertise.id', 'employees.expertise_id')
            ->select('employee_reservation.*', 'employees.firstname', 'employees.lastname', 'expertise.name as expertise')
            ->get();

        $getServices = ReservationService::join('services', 'services.id', 'reservation_service.service_id')
            ->get();

        return view ('system/reservation/viewAllReservations', 
            compact('reservations', 'getServices', 'employeeReservations'));
    }

}

