<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Support\Facades\Validator;
use JsValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Employee;
use App\Document;
use App\Delivered;
use App\File;
Use Auth;


class CompanyController extends Controller
{

	protected $validationRules = [
		'name'=>'required|max:120',
		'cep'=>'required|min:9',
		'address'=>'required',
		'number'=>'required',
		'complement'=>'nullable',
		'neighborhood'=>'required',
		'citie'=>'required',
		'state'=>'required',
		'country'=>'required',
		'cnpj'=>'required|min:18',

	];

	public function index (Company $company){

		if (Auth::user()->hasRole('Master')) {

			$companies = $company->all()->where('fl_deleted', 0);

		}else{
			$companies = $company->find(Auth::user()->company_id)->clients;

		}

		return view('app.g3.companies.index', compact('companies'));

	}

	public function branches ($company = null){

		if (Auth::user()->hasRole('Master')) {
			$owner = Company::findOrFail($company);
			$companies = Company::find($company)->branches->where('fl_deleted', 0);

		}else{
			$owner = Auth::User()->company_id;
			$companies = Company::find(Auth::user()->company_id)->branches->where('fl_deleted', 0);
			//dd($companies);
		}

		return view('app.g3.companies.branches', compact('companies', 'owner'));

	}

	public function insert(Request $request, $cnpj = null, $brc=null )
	{
       //$cnpj ='';
		if ($request->input()) {
		$company = new Company;
		$company->name = $request ->input('name');
		$company->cep = $request ->input('cep');
		$company->address  = $request ->input('address');
		$company->number   = $request ->input('number');
		$company->complement   = $request ->input('complement');
		$company->neighborhood = $request ->input('neighborhood');
		$company->citie    = $request ->input('citie');
		$company->state    = $request ->input('state');
		$company->country  = $request ->input('country');
		$company->cnpj = $request ->input('cnpj');
		$company->manager_email = $request ->input('manager_email');
		$company->company_email = $request ->input('company_email');
		$company->abaco_email = $request ->input('abaco_email');
		$company->fl_aprove    = ($request ->input('fl_aprove'))? 1 : 0 ;
		$company->fl_active    = ($request ->input('fl_active'))? 1 : 0;
		$company->fl_billing   = ($request ->input('fl_billing'))? 1 : 0;
		$company->fl_client   =  ($request ->input('fl_client'))? 1 : 0;
		$company->company_id   = $request ->input('company_id');

		
       //dd($company);
		if(!Auth::user()->hasRole('Master')){
			if($request->is('g3/branches/add*')){
				//$branch = Company::find($brc);
				$company->headquarter = Auth::user()->company_id;
				$company->company_id   = Auth::user()->company_id;
				$company->save();
				return redirect()->route('branches')->with('alert-success',__('general.Branches').' '. $company->name.' '. __('general.has added successfully!'));
			}elseif($request->is('g3/branches/client/add*')){
				$company->company_id = $brc;
				$company->save();
				$owner = Company::find($brc); 
				$client = $company->id;
				$owner->clients()->attach($client);
				return redirect()->route('branches.clients', $brc)->with('alert-success',__('general.Company'). ' '. $company->name.' '. __('general.has added successfully!'));
			}else{
				$company->company_id = Auth::user()->company_id;
				$company->save();
				$owner = Company::find(Auth::user()->company_id); 
				$client = $company->id;
				$owner->clients()->attach($client);
				return redirect()->route('companies.index')->with('alert-success',__('general.Company'). ' '. $company->name.' '. __('general.has added successfully!'));
			}
		}else{
			$company->save();
		return redirect()->route('companies.index')->with('alert-success',__('general.Company').' '. $company->name.' '. __('general.has added successfully!'));
		}


		}
		$users = User::all();
		$companies = Company::all();
		$validator = JsValidator::make($this->validationRules);	
		return view('app.g3.companies.insert', compact('users', 'companies', 'cnpj', 'validator'));
	}

	public function create(Request $request, $brc=null)
	{
		$company = new Company;
		$company->name = $request ->input('name');
		$company->cep = $request ->input('cep');
		$company->address  = $request ->input('address');
		$company->number   = $request ->input('number');
		$company->complement   = $request ->input('complement');
		$company->neighborhood = $request ->input('neighborhood');
		$company->citie    = $request ->input('citie');
		$company->state    = $request ->input('state');
		$company->country  = $request ->input('country');
		$company->cnpj = $request ->input('cnpj');
		$company->manager_email = $request ->input('manager_email');
		$company->manager_email = $request ->input('manager_email');
		$company->manager_email = $request ->input('manager_email');
		$company->fl_aprove    = ($request ->input('fl_aprove'))? 1 : 0 ;
		$company->fl_active    = ($request ->input('fl_active'))? 1 : 0;
		$company->fl_billing   = ($request ->input('fl_billing'))? 1 : 0;
		$company->fl_client   =  ($request ->input('fl_client'))? 1 : 0;
		

		
       //dd($company);
		if(!Auth::user()->hasRole('Master')){
			if($request->is('g3/branches/add*')){
				//$branch = Company::find($brc);
				$company->headquarter = $brc;
				$company->save();
				return redirect()->route('branches')->with('alert-success',__('general.Branches').' '. $company->name.' '. __('general.has added successfully!'));
			}elseif($request->is('g3/branches/client/add')){
				$company->save();
				$owner = Company::find($brc); 
				$client = $company->id;
				$owner->clients()->attach($client);
				return redirect()->route('branches.clients', $brc)->with('alert-success',__('general.Company'). ' '. $company->name.' '. __('general.has added successfully!'));
			}else{
				$company->save();
				$owner = Company::find(Auth::user()->company_id); 
				$client = $company->id;
				$owner->clients()->attach($client);
			}
		}else{
			$company->company_id   = $request ->input('company_id');
			$company->save();
		return redirect()->route('companies.index')->with('alert-success',__('general.Company').' '. $company->name.' '. __('general.has added successfully!'));
		}

	}

	public function show($id)
	{
	//
	}

	public function branchDetach($id)
	{
		$branch = Company::find($id);
		$branch->headquarter = null;
		$branch->save();

		return redirect()->route('branches')->with('alert-success', __('general.Company').' ' . $branch->name .' '. __('general.has removed successfully!'));
	}

	public function edit(Request $request, $id)
	{
		
		if ($request->input()) {
			$company = Company::findOrFail($id);
			$company->name          = $request ->input('name');
			$company->manager_email = $request ->input('manager_email');
			$company->company_email = $request ->input('company_email');
			$company->abaco_email = $request ->input('abaco_email');
			$company->cep           = $request ->input('cep');
			$company->address       = $request ->input('address');
			$company->number        = $request ->input('number');
			$company->complement        = $request ->input('complement');
			$company->neighborhood  = $request ->input('neighborhood');
			$company->citie         = $request ->input('citie');
			$company->state         = $request ->input('state');
			$company->country       = $request ->input('country');
			$company->cnpj          = $request ->input('cnpj');
			$company->headquarter 	= $request->input('headquarter');
			if (Auth::user()->hasRole('Master')) {
				$company->fl_aprove     = ($request ->input('fl_aprove'))? '1' : '0' ;
				$company->fl_active     = ($request ->input('fl_active'))? '1' : '0';
				$company->fl_billing    = ($request ->input('fl_billing'))? '1' : '0';
				$company->fl_client     = ($request ->input('fl_client'))? '1' : '0';
				$company->company_id    = $request ->input('company_id');
			}
			$company->save();

			if($request->is('g3/branches*')){
				return redirect()->route('branches')->with('message', 'Company updated successfully!');
			}else{
				return redirect()->route('companies.index')->with('message', 'Company updated successfully!');
			}
		}




		$users = User::all();
		//if(Auth::User()->company->clients()->find($id) || Auth::user()->hasRole('Master')){
			$headquarters = Company::where('fl_deleted', 0)->whereNotIn('id', [1,$id])->orderBy('name')->get();
			$company = Company::findOrFail($id);
			$validator = JsValidator::make($this->validationRules);

			return view('app.g3.companies.edit',compact('company', 'users','validator', 'headquarters'));
		//}else{
		//	return redirect()->route('companies.index')->withErrors(__('general.Permission denied'));; 
		//}

	}

	public function update(Request $request, $id)
	{


		$company = Company::findOrFail($id);
		$company->name          = $request ->input('name');
		$company->manager_email = $request ->input('manager_email');
		$company->cep           = $request ->input('cep');
		$company->address       = $request ->input('address');
		$company->number        = $request ->input('number');
		$company->complement        = $request ->input('complement');
		$company->neighborhood  = $request ->input('neighborhood');
		$company->citie         = $request ->input('citie');
		$company->state         = $request ->input('state');
		$company->country       = $request ->input('country');
		$company->cnpj          = $request ->input('cnpj');
		if (Auth::user()->hasRole('Master')) {
			$company->fl_aprove     = ($request ->input('fl_aprove'))? '1' : '0' ;
			$company->fl_active     = ($request ->input('fl_active'))? '1' : '0';
			$company->fl_billing    = ($request ->input('fl_billing'))? '1' : '0';
			$company->fl_client     = ($request ->input('fl_client'))? '1' : '0';
			$company->company_id    = $request ->input('company_id');
		}
		$company->save();

		if($request->is('g3/branches*')){
			return redirect()->route('branches')->with('message', 'Company updated successfully!');
		}else{
			return redirect()->route('companies.index')->with('message', 'Company updated successfully!');
		}
	}

	public function delete($id)
	{

		$company = Company::findOrFail($id);

		return view('app.g3.companies.delete',compact('company'));
	}

	public function destroy(Request $request)
	{
		$company = Company::findOrFail($request->input('id'));
	 //$company = Company::findOrFail($id);
		$company->fl_deleted   = 1;
		$company->save();
		return redirect()->route('companies.index')->with('alert-success','Company has been deleted!');
	}
	
	public function employees(Request $request, $id, $brc = null){


		if (Auth::User()->can('master')) {
   // $outsurceds = Company::findOrFail(Auth::User()->company_id)->outsourceds->pluck('id')->toArray();
			$employees = Company::findOrFail($id)->employees()->where('fl_deleted', 0)->get();
			$company = Company::findOrFail($id);
		}else{
			if($request->is('g3/branches*')){
				$branch = Company::find($brc);
				$outsurceds = Company::findOrFail($brc)->outsourceds->where('fl_deleted', 0)->pluck('id')->toArray();
				$company = Company::findOrFail($id);
				$employees = Company::findOrFail($id)->employees()->whereIn('id', $outsurceds)->get();
			}else{
				$outsurceds = Company::findOrFail(Auth::User()->company_id)->outsourceds->where('fl_deleted', 0)->pluck('id')->toArray();
				$company = Company::findOrFail($id);
				$employees = Company::findOrFail($id)->employees()->whereIn('id', $outsurceds)->get();
			}
    //dd( $employees);
		}
    //dd($company);
		return view('app.g3.companies.employees', compact('employees', 'company', 'branch'));
	}

	public function employeesAdd(Request $request, $id, $brc = null)
	{
		if ($request->input()) {

			$employee = $request->input('employee'); 
			$cp = Company::find($id);
	    //$se = Service::find($services);
	    //dd($se);
			$cp->employees()->attach($employee);

			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.employees', ['id'=>$id, $brc])->with('alert-success','Employee has added successfully!');
			}else{
				return redirect()->route('companies.employees', ['id'=>$id])->with('alert-success','Employee has added successfully!');
			}
	    //dd($request->input('services'));
		}

		$company = Company::find($id);
		if($request->is('g3/branches*')){
			$branch = Company::find($brc);
			$employees = $branch->outsourceds->where('fl_deleted', 0)->whereNotIn('id', $company->employees->pluck('id')->toArray());

			//dd($branch->outsourceds->where('fl_deleted', 0));
		}else{
			//$employees = $company->outsourceds;
			$employees = Employee::whereDoesntHave("companies", function($query) use ($id){
				$query->where('id', '=', $id);
			})->where('fl_deleted', 0)->get();
		}
	//dd($employees);
		return view('app.g3.companies.employees_add', compact('company', 'employees'));

	}


	public function employeesRemove(Request $request, $id, $employee_id, $brc=null)
	{
		if ($employee_id) {

			$employee = $employee_id; 
			$cp = Company::find($id);
	    //$se = Service::find($services);
	    //dd($se);
			$cp->employees()->detach($employee);
			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.employees', ['id'=>$id, $brc])->with('alert-success','Employee has removed successfully!');
			}else{
				return redirect()->route('companies.employees', ['id'=>$id])->with('alert-success','Employee has removed successfully!');
			}
	    //dd($request->input('services'));
		}
		$company = Company::find($id);
		$employees = Employee::whereDoesntHave("companies", function($query) use ($id){
			$query->where('id', '=', $id);
		})->get();
	//dd($employees);
		return view('app.g3.companies.employees_add', compact('company', 'employees'));

	}

	public function employeesCreate(Request $request, $id, $brc=null)
	{
		$validation = [
			'name'=>'required|max:120',
			'cpf'=>'required|min:14',
			'rg'=>'required',
			'borndate'=>'required',
		];
		if ($request->input()) {

			if ($request->input('allowed')) {
				$allowed = 1;
			}else{
				$allowed = 0;
			}
			$employee = new Employee;
			$employee->name = $request->input('name');
			$employee->cpf = $request->input('cpf');
			$employee->rg = $request->input('rg');
			$borndate = $request->input('borndate');
			$borndate = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $borndate);
			$employee->borndate = $borndate;
			$employee->allowed = $allowed;
			$employee->save();

			$company = Company::findOrFail($id);
			$company->employees()->attach($employee->id);
			if($request->is('g3/branches*')){
				$branch = Company::find($brc);
				$branch->outsourceds()->attach($employee->id);
			return redirect()->route('branches.clients.employees', [$id, $brc])->with('message', 'Employee created successfully!');
			}


			return redirect()->route('companies.employees', $id)->with('message', 'Employee created successfully!');
		} else {
			$validator = JsValidator::make($validation); 
			return view('app.g3.companies.employeeInsert', compact('id','validator'));
		}
	}



	public function outsourceds($id){


		if (Auth::user()->hasAnyRole('Master|Admin|G3 Master')) {
   // $outsurceds = Company::findOrFail(Auth::User()->company_id)->outsourceds->pluck('id')->toArray();
			$employees = Company::findOrFail($id)->outsourceds->where('fl_deleted', 0);
			$company = Company::findOrFail($id);
    //dd( $employees);
			return view('app.g3.companies.outsourceds', compact('employees', 'company'));
		}else{
			return view('home');
		}
    //dd($company);
	}


	public function outsourcedsAdd(Request $request, $id)
	{
		if ($request->input()) {

			$employee = $request->input('employee'); 
			$cp = Company::find($id);
	    //$se = Service::find($services);
	    //dd($se);
			$cp->outsourceds()->attach($employee);

			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.outsourceds', ['id'=>$id])->with('alert-success','Outsourced has added successfully!');
			}else{
				return redirect()->route('companies.outsourceds', ['id'=>$id])->with('alert-success','Outsourced has added successfully!');
			}
	    //dd($request->input('services'));
		}

		$company = Company::find($id);
		$employees = Employee::whereNotIn('id', $company->outsourceds->pluck('id'))->where('fl_deleted', 0)->get();
	//dd($employees);
		return view('app.g3.companies.outsourceds_add', compact('company', 'employees'));

	}
	public function outsourcedsRemove(Request $request, $id, $employee_id)
	{
		if ($employee_id) {

			$employee = $employee_id; 
			$cp = Company::find($id);
	    //$se = Service::find($services);
	    //dd($se);
			$cp->outsourceds()->detach($employee);
			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.outsourceds', ['id'=>$id])->with('alert-success','Outsourced has removed successfully!');
			}else{
				return redirect()->route('companies.outsourceds', ['id'=>$id])->with('alert-success','Outsourced has removed successfully!');
			}
	    //dd($request->input('services'));
		}


	}

	public function client($id)
	{

	   //$company = Company::find(2); 
	   //$client = Company::find(3);
	    //$se = Service::find($services);
	    //dd($se);
      //$company->clients()->attach($client);
      //dd($company->clients);
		if (Auth::User()->can('master')) {
			$companies = Company::find($id)->clients;  
			$owner = Company::find($id);  
			return view('app.g3.companies.clients', compact('companies', 'owner'));
		}elseif (Auth::User()->can('G3 Admin')){
			if (Company::find($id)->headquarter == Auth::User()->company_id) {
				# code...
				$companies = Company::find($id)->clients;  
				$branch = Company::find($id);  
				return view('app.g3.companies.index', compact('companies', 'branch'));
			}else{
				return redirect()->route('companies.branches')->withErrors('alert-success','This is not your branch!');
			}

		}

	}

	
	public function attach(Request $request, $id=0 ){
      //dd($id);
		if (Auth::user()->can('master')) {
			if ($request->input()) {

				$clients = $request->input('clients'); 
				$company = Company::find($request->input('company')); 
	    //$se = Service::find($services);
	    //dd($se);
				$company->clients()->sync($clients);
				return redirect()->route('companies.clients', [$company])->with('alert-success',__('general.has added successfully!'));
	    //dd($request->input('services'));


	//$se = Service::find($services);
       //dd($client);
			}else{

				$companies = Company::find($id)->whereNotIn('id', [1, $id])->whereIn('company_id', [1,$id])->where('fl_deleted', 0)->get();  
				$owner = Company::find($id);  
				$data = Array();
				$data['companies'] = $companies;
				$data['owner'] = $owner;
	  //dd($data);

				return view('app.g3.companies.attach_multiples', compact('data'));
			}
		}else{

			if ($request->input('cnpj')) {

				//dd(Request::url());

				$cnpj = $request->input('cnpj');
				if (Auth::user()->can('master')) {
					$company = Company::find($request->input('id')); 
				}else{

					if ($request->is('g3/branches/attach')) {
						if(count(Company::where('cnpj', $cnpj)->get())>0){
							//dd(count(Company::where('cnpj', $cnpj)->get()));
							$id = Company::where('cnpj', $cnpj)->limit(1)->get()[0]->id;
							$branch = Company::find($id); 
							$branch->headquarter = Auth::User()->company_id;
							$branch->save();

							return redirect()->route('branches')->with('alert-success',__('general.Branches')." ". $branch->name." ". __('general.has added successfully!'));
						}else{

							$cnpj = str_replace(array('.','/','-'), '', $cnpj);
							return redirect()->route('branches.add', $cnpj);

						}

					}

					if ($request->is('g3/branches/client*')) {
							if(count(Company::where('cnpj', $cnpj)->get())>0){
								//dd($client);
							$client = Company::where('cnpj', $cnpj)->limit(1)->get();
							$company = Company::find($request->input('id'));

							if($company->clients->find($client[0]->id)){
								return redirect()->route('branches.clients', $id)->withErrors(__('general.Company')." ".$client[0]->name ." ". __('general.already exists!'));


							}else{

							//$cnpj = str_replace(array('.','/','-'), '', $cnpj);
							//return redirect()->route('companies.branches.add', $cnpj)


							//$branch->headquarter = Auth::User()->company_id;
							//$branch->save();
							$company->clients()->attach($client);

							return redirect()->route('branches.clients', $id)->with('alert-success',__('general.Branches')." ". $client[0]->name." ". __('general.has added successfully!'));
						}
						}else{
							$cnpj = str_replace(array('.','/','-'), '', $cnpj);
							//die('here');
							return redirect()->route('branches.clients.add', [$cnpj, $id]);
						}

						

					}

					if ($request->is('*companies/client*'))
					{
						$company = Company::find($request->input('id')); 
					}else{
						$company = Company::find(Auth::user()->company_id);
					}

				}
				$client = Company::where('cnpj', $cnpj)->limit(1)->get();

				if(!$client->isEmpty()){

					if($company->clients->find($client[0]->id)){
						if (Auth::user()->can('master')) {
							return redirect()->route('companies.clients', $company)->withErrors(__('general.Company')." ". $client[0]->name." ". __('general.already exists!'));
						}else{

							if ($request->is('*companies/client*'))
							{
								return redirect()->route('companies.clients', $company)->withErrors(__('general.Company')." ". $client[0]->name." ". __('general.already exists!'));

							}else{
								
								return redirect()->route('companies', $company)->withErrors(__('general.Company')." ". $client[0]->name." ". __('general.already exists!'));

							}


						}

					}else{
						$company->clients()->attach($client);
						if (Auth::user()->can('master')) {
							return redirect()->route('companies.clients', $company)->with('alert-success',__('general.Company')." ". $client[0]->name." ". __('general.has added successfully!'));
						}else{

							if ($request->is('*companies/client*'))
							{
								return redirect()->route('companies.clients', $company)->with('alert-success',__('general.Company')." ". $client[0]->name." ". __('general.has added successfully!'));

							}else{
								return redirect()->route('companies')->with('alert-success',__('general.Company')." ". $client[0]->name." ". __('general.has added successfully!'));

							}
							
						}
					}



				}else{
		 //$users = User::all();
		 //$companies = Company::all();
					$cnpj = str_replace(array('.','/','-'), '', $cnpj);
					return redirect()->route('companies.add', $cnpj);
				}

	//$se = Service::find($services);
       //dd($client);
			}else{
				return view('app.g3.companies.attach', compact('id'));
			}

		}

	}

	public function detach(Request $request, $cp=0, $id, $brc=null)
	{

		if (Auth::user()->can('master')) {
			$company = Company::find($cp); 
		}else{
			if($request->is('g3/branches*')){
				$company = Company::find($brc); 
			}else{
				$company = Company::find(Auth::user()->company_id); 
			}
		}
       //$company = Company::find(Auth::user()->company_id); 
		$client = $id;
       //dd($client);
		$company->clients()->detach($client);
		if($request->is('g3/branches*')){
			return redirect()->route('branches.clients', $brc)->with('alert-success',__('general.Company').' '. __('general.has removed successfully!'));
		}else{
			return redirect()->route('companies.index')->with('alert-success',__('general.Company').' '. __('general.has removed successfully!'));

		}


	}

	public function documents(Request $request, $id, $brc=null)
	{
		
		
		if (Auth::user()->can('master')) {
			$company = Company::findOrFail($id);
			$documents = Company::findOrFail($id)->documents;
			return view('app.g3.companies.documents', compact('documents', 'company'));
		}else{
			//if(Auth::User()->company->clients->find($id)){
				$company = Company::findOrFail($id);
				$branch = Company::Find($brc);
				if ($request->is('g3/branches*')) {
					# code...
				$documents = Company::findOrFail($id)->documents->whereIn('company_id', [1,$brc]);
				}else{

				$documents = Company::findOrFail($id)->documents->whereIn('company_id', [1,Auth::User()->company_id]);
				}
				return view('app.g3.companies.documents', compact('documents', 'company', 'branch'));
			//}else{
				
			//	return redirect()->route('companies.index')->withErrors(__('general.Permission denied'));
			//}
		}
	}

	public function branchesClientDocuments($id, $cid)
	{
		
		

		if(Auth::User()->company->branches->find($id)->clients->find($cid)){
			$company = Company::findOrFail($cid);
			$documents = Company::findOrFail($cid)->documents->whereIn('company_id', [1,$id]);
				//dd($documents);
			$branch = $id;
			return view('app.g3.companies.branches_clients_documents', compact('documents', 'company', 'branch'));
		}else{

			return redirect()->route('companies.branches.client', [$id, $cid])->withErrors(__('general.Permission denied'));
		}
		
	}

	public function documentsAttach(Request $request, $id, $brc=null)
	{

		if ($request->input()){
			$company = Company::findOrFail($id);
			$documents = $request->input('documents');
			$company->documents()->syncWithoutDetaching($documents);

			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.documents', [$company->id, $brc])->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));
			}else{
				return redirect()->route('companies.documents', $company->id)->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));
			}
		}else{
			if(Auth::User()->can('master')){
				$company = Company::findOrFail($id);
				$documents = Document::whereNotIn('id', $company->documents->pluck('id')->toArray())->get();
				return view('app.g3.companies.documents_attach', compact('documents', 'company'));
			}else{
				$company = $company = Company::findOrFail($id);
				$branch = Company::find($brc);
				if ($request->is('g3/branches*')) {
					$documents = Document::whereNotIn('id', $company->documents->pluck('id')->toArray())->whereIn('company_id', [1,$brc])->get();
				}else{
					$documents = Document::whereNotIn('id', $company->documents->pluck('id')->toArray())->whereIn('company_id', [1,Auth::User()->company_id])->get();
				}

				return view('app.g3.companies.documents_attach', compact('documents', 'company', 'branch'));

			}
		}
		
	}



	public function branchesClientDocumentsAttach(Request $request, $id, $cid)
	{

		if ($request->input()){
			$company = Company::findOrFail($cid);

			$documents = $request->input('documents');
			$company->documents()->syncWithoutDetaching($documents);

			return redirect()->route('companies.branches.client.documents', [$id, $cid])->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));

		}else{

			$company = Company::findOrFail($cid);
			$documents = Document::whereNotIn('id', $company->documents->pluck('id')->toArray())->whereIn('company_id', [1,$id])->get();
			return view('app.g3.companies.branches_client_documents_attach', compact('documents', 'company'));


		}
		
	}

	public function deliveredsEdit(Request $request, $cid, $did, $brc=null)
	{

		$validation = [
			'description'=>'required|max:120',
			'expiration'=>'required|min:9',


		];
		
		if ($request->input()) {

			//dd($request->file);

			$delivered = Delivered::findOrFail($did);
			$delivered->description = $request->input('description');
			$expiration = $request->input('expiration');
			$expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
			$delivered->expiration = $expiration;
			//$delivered->document_id = $did;
			//$delivered->company_id = $cid;
			$delivered->save();


			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.documents', [$cid, $brc])->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
			}else{
				return redirect()->route('companies.documents', $cid)->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
			}
		}
		$branch=Company::find($brc);
		$data['company'] = company::find($cid);
		$data['delivered'] = Delivered::find($did);
		$validator = JsValidator::make($validation);
		return view('app.g3.companies.delivereds_edit', compact('data', 'validator', 'branch'));

	}

	public function deliveredsAdd(Request $request, $cid, $did, $brc=null)
	{

		$validation = [
			'description'=>'required|max:120',
			'expiration'=>'required|min:9',


		];
		
		if ($request->input()) {

			//dd($request->file);

			$delivered = new Delivered;
			$delivered->description = $request->input('description');
			$expiration = $request->input('expiration');
			$expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
			$delivered->expiration = $expiration;
			$delivered->document_id = $did;
			$delivered->company_id = $cid;
			$delivered->save();


			if ($request->hasFile('file')) {
				$name=$request->file->getClientOriginalName();
				$fileUpload = $request->file;
				$fileName=$request->file->getClientOriginalName();
				$upload = $fileUpload->store('public/uploads');


				$file = new File;
				$file->name = $name;
				$file->file = str_replace('public/uploads/', "", $upload);
				$file->save();

				$delivered->files()->attach($file);
			}


			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.documents', [$cid, $brc])->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
			}else{
				return redirect()->route('companies.documents', $cid)->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
			}
		}
		$branch = Company::find($brc);
		$data['company'] = company::find($cid);
		$data['document'] = Document::find($did);
		$validator = JsValidator::make($validation);
		return view('app.g3.companies.delivereds_add', compact('data', 'validator', 'branch'));

	}


	public function branchesClientDocumentsDeliveredsAdd(Request $request, $cid, $did, $bid)
	{

		$validation = [
			'description'=>'required|max:120',
			'expiration'=>'required|min:9',


		];
		
		if ($request->input()) {

			//dd($request->file);

			$delivered = new Delivered;
			$delivered->description = $request->input('description');
			$expiration = $request->input('expiration');
			$expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
			$delivered->expiration = $expiration;
			$delivered->document_id = $did;
			$delivered->company_id = $cid;
			$delivered->save();


			if ($request->hasFile('file')) {
				$name=$request->file->getClientOriginalName();
				$fileUpload = $request->file;
				$fileName=$request->file->getClientOriginalName();
				$upload = $fileUpload->store('public/uploads');


				$file = new File;
				$file->name = $name;
				$file->file = str_replace('public/uploads/', "", $upload);
				$file->save();

				$delivered->files()->attach($file);
			}



			return redirect()->route('companies.branches.client.documents', [$bid, $cid])->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
		}
		$data['company'] = company::find($cid);
		$data['document'] = Document::find($did);
		$validator = JsValidator::make($validation);
		return view('app.g3.companies.branchesClientDocumentsDelivereds_add', compact('data', 'validator'));

	}

	public function documentsDetach(Request $request)
	{
		if ($request->input()) {
			$id = $request->input('id');
			$cid = $request->input('cid');
			$company = Company::findOrFail($cid);
			$company->documents()->detach($id);
			return 1;
		}else{
			return 0;
		}


	}

	public function outourcedActivate($cp, $ep)
	{
		if(Auth::user()->can('master')){
			$company = Company::find($cp);
			$outsourced = $company->outsourceds()->find($ep);
			if($outsourced->pivot->fl_ready == 0){
				$company->outsourceds()->updateExistingPivot($outsourced, ['fl_ready' => 1]);
			}else{
				if($outsourced->pivot->dt_ready_sent == null){
					$company->outsourceds()->updateExistingPivot($outsourced, ['fl_ready' => 0]);
				}

			}
			//dd($company->id.' - ' .$outsourced->id. '-' . $outsourced->pivot->fl_ready);
			return back();
		}else{
			return back();
		}


	}

	public function fileUpload(Request $request, $cid, $did, $brc=null)
	{

		$validations = [
			'file'=>'required',
		];

		if ($request->input()) {

         //dd($request->file->getClientOriginalName());
			$name = $request->name;
			if ($name == "") {
				$name=$request->file->getClientOriginalName();
			}


			$delivered = Delivered::findOrFail($request->delivered);    

			$fileUpload = $request->file;
			$upload = $fileUpload->store('public/uploads');
        //dd($upload);

			$file = new File;
			$file->name = $name;
			$file->file = str_replace('public/uploads/', "", $upload);
			$file->save();

			$delivered->files()->attach($file);

			if ($request->is('g3/branches*')) {
				
				return redirect()->route('branches.clients.documents', [$cid, $brc])->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
			}else{

				return redirect()->route('companies.documents', $cid)->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
			}
		}
   // $data['employee'] =  Employee::find($eid);
		$branch=Company::find($brc);
		$data['delivered'] =  Delivered::find($did);
		$data['company'] = Company::find($cid);
		$validator = JsValidator::make($validations); 
		return view('app.g3.companies.documents_fileupload', compact('data', 'validator', 'branch'));

	}

	public function fileDelete(Request $request)
	{
		$id = $request->id;
		$cid = $request->cid;
		//dd($id);
		if ($request->input()) {
            //    echo ("O id é ". $id . 'e o' . $eid);


			if (Auth::User()->can('master')) {
				if($file = File::find($id)){
					$file->fl_deleted = 1;
					$file->save();    
					return 1;
				}else{
					return 0;
				}

			}else{

				if (Auth::User()->company->clients->find($cid)) {   
					if($file = File::find($id)){
						$file->fl_deleted = 1;
						$file->save();    
						return 1;
					}else{
						return 0;
					}
				} else {
					return 0;
				}
			}


		}
	}



}


