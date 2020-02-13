<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class AppController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function test()
    {
        $company = Company::find(3);
        
        $outsourced = $company->outsourceds()->find(2);
        //$company->outsourceds()->attach($outsourced->id, ['fl_ready' => 0]);

        $company->outsourceds()->updateExistingPivot($outsourced, ['fl_ready' => 1]);
        $company->outsourceds()->updateExistingPivot($outsourced, ['dt_ready_sent' => date('Y-m-d')]);
        //$outsourced->save();
        //$company->outsourceds()->find(2);
        dd($company->outsourceds()->find(2)->pivot->fl_ready);

    }
}
