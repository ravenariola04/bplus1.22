<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Billing;
use App\Payment;
use Auth, DB, Alert;
use App\BillingService;

class CustomerPaymentsController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function customerViewAllPayments() {
    	$payments = Payment::join('users as customers', 'customers.id', 'payments.customer_id')
			->select('payments.total_amount as total_amount', 'payments.amount_paid as amount_paid',
                    'payments.change', 'customers.firstname as customer_firstname', 'customers.lastname as customer_lastname',
					'payments.created_at as created_at', 'payments.id as id')
			->where('payments.customer_id', Auth::user()->id)
			->get();

    	return view ('customers/payment/viewAllPayments', 
    		compact('payments'));
    }
}
