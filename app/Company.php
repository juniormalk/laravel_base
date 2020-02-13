<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	public function employees()
	{
		return $this->belongsToMany('App\Employee')
		->withTimestamps();
	}
	
	public function clients()
	{
		return $this->belongsToMany('App\Company', 'company_client', 'company_id', 'client_id')
		->withTimestamps();
	}

	public function outsourceds()
	{
		return $this->belongsToMany('App\Employee', 'company_outsourced', 'company_id', 'employee_id')
		->withPivot('fl_ready', 'dt_ready_sent')
		->withTimestamps();
	}

		public function documents()
	{
	    return $this->belongsToMany('App\Document', 'company_document', 'company_id', 'document_id' )
		->withTimestamps();
	}

		public function branches()
	{
	    return $this->hasMany('App\Company', 'headquarter');
	}

	public function headquarter()
	{
	    return $this->belongsTo('App\Company', 'headquarter');
	}


	//protected $table = 'companies';
}
