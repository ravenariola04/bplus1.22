<?php

namespace App\Http\Controllers;
use Auth, Alert, App\User, App\Service;
use App\ServiceType, App\Expertise, Validator;
use Illuminate\Http\Request;
use App\Repositories\Crudable\GenericCrudRepository;
use App\Promo;
use DB;
// use Illuminate\Http\Request;

class PromoController extends Controller
{
    //
    protected $model;

    public function __construct(Service $service)
    {
        $this->model = new GenericCrudRepository($service);
        $this->middleware('auth');
    }

    public function index()
    {

        // $getAllWalkinEmployees = EmployeeWalkin::join('users as employees', 'employees.id', 'employee_walkin.employee_id')
        //     ->join('expertise', 'expertise.id', 'employees.expertise_id')
        //     ->select('employee_walkin.*', 'employees.firstname', 'employees.lastname', 'expertise.name as expertise')
        //     ->get();

        $getServices = Promo::join('services', 'services.id', 'promos.service_id')
            ->orderBy('promos.name')

            ->select(  'services.id as sid', 'promos.id as pid', 'promos.name as pname', 'promos.service_id as psid' )
            ->selectraw('group_concat(services.name) as gname')
            ->selectraw( 'group_concat(services.price) as gprice')
            ->get();
        
        $promos = Promo::groupBy('promos.name')->get();

        return view ('system/promo/index', compact('getServices', 'promos')) ;
    }

    public function create()
    {   
        // $services = $this->model->getServices();
        // return view ('system/promo/index', compact('services'));

		$hairstylists = User::join('expertise', 'expertise.id', 'users.expertise_id')
            ->select('users.firstname as firstname', 'users.lastname as lastname', 'expertise.name as expertise',
                    'users.id as id', 'expertise.id as expertise_id')
            ->where('role_id', User::IS_EMPLOYEE)->get();

        $service_types = ServiceType::all();
        $services = Service::all();
        $expertise = Expertise::all();
        return view ('system/promo/create', compact('hairstylists', 'service_types', 'services', 'expertise'));        
    }



    public function store(Request $request)
    {

        $current_day = date('Y-m-d');

        $this->validate($request, [
            'promo' => 'required', 'price' => 'required', 
            'service_id' => 'required'
        ]);

        /*$checkExistingWalkin = Walkin::where('user_id', $request->user_id) //employee_id
            ->where('walkin_time', $request->walkin_time)
            ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '=', $current_day)
            ->where('status', 'Pending')->first();
            
        if($checkExistingWalkin) {
            Alert::error('Walk-in has conflict!')->persistent("OK");
            return redirect()->back()->withInput(Input::all());
        }*/
        $i = 0; 

        foreach($request->service_id as $key => $v){
	            $createServiceWalkin = Promo::create([
	                'service_id' => $request->service_id[$i],
	                'name' => $request->promo,
            		'price' => $request->price
	            ]);
	        $i++;
	    }

        // $createPromo= Promo::create([
        //     'name' => $request->promo,
        //     'price' => $request->price,

	        
        // ]);

        //Insert multiple hairstylist and walkin id to pivot
        // $i = 0; 
        // foreach($request->employee_id as $key => $v){
        //     $createEmployeeWalkin = EmployeeWalkin::create([
        //         'employee_id' => $request->employee_id[$i],
        //         'walkin_id' => $createPromo->id
        //     ]);
        //     $i++;
        // }

        

        Alert::success('Promo has been Added!')->persistent("OK");
        return redirect()->route('promo.index');

    }
}
