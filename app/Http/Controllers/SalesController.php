<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WalkinPayment, App\BillingEmployee;
use App\User, App\Billing, App\Payment, App\CommissionSetting;
// use App\Date;
class SalesController extends Controller
{
    //

    public function adminViewAllSales() {
		$payments = Payment::join('users as customers', 'customers.id', 'payments.customer_id')
			->select('payments.total_amount', 'payments.amount_paid', 'payments.change',
					'customers.firstname as customer_firstname', 'customers.lastname as customer_lastname',
					'payments.created_at', 'payments.id')
			// ->sum('payments.total_amount')
			->get();
		$totalamount = Payment::sum('total_amount');

        $walkinPayments = WalkinPayment::all();
		return view ('system/sales/adminViewAllSales', compact('payments', 'walkinPayments', 'totalamount'));
	}

	public function storeSalesDate(Request $request) {
        
    	$this->validate($request, [
    		'date_from' => 'required', 'date_to' => 'required'
    	]);

    	
    	$datef = date("Y-m-d h:i:s",strtotime($request->date_from));
    	$datet = date("Y-m-d h:i:s",strtotime($request->date_to));

    	$checkDate= Payment::whereBetween('created_at', array($datef, $datet))
		            ->selectRaw('sum(total_amount) as totals')	
		            ->selectRaw('created_at as ca')
		    		->get();
    	return view ('system/sales/adminAllSales', compact('checkDate')) ;

    }

     public function viewAllSales(Request $checkDate) {
		$payments = Payment::join('users as customers', 'customers.id', 'payments.customer_id')
			->select('payments.total_amount', 'payments.amount_paid', 'payments.change',
					'customers.firstname as customer_firstname', 'customers.lastname as customer_lastname',
					'payments.created_at', 'payments.id')
			// ->sum('payments.total_amount')
			->get();
		$totalamount = Payment::sum('total_amount');

        $walkinPayments = WalkinPayment::all();
		return view ('system/sales/adminAllSales', compact('checkDate'));
	}


}
