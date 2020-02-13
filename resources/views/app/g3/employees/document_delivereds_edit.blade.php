
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Employees')}} - {{$data['employee']->name}} - {{__('general.Delivered')}} - {{__('general.Edit')}}</h1>
@stop

@php
	$delivered = $data['delivered'];
	
@endphp

@section('content')
<div class="col-md-12">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">{{__('general.Add')}} </h3>
		</div>
		
		<form id="add_form" method="post" role="form" action="" enctype="multipart/form-data">
			@csrf

<div class="box-body">		

<div class="form-group">
	<label>{{__('general.Deliver date')}}</label>
	<input type="" id="description" name="description" class="form-control" placeholder="dd/mm/aaaa" value="{{ $delivered->description }}">
</div>


<div class="form-group">
	<label>{{__('general.Expires')}}</label>
	<input type="" id="expiration" name="expiration" class="form-control" placeholder="dd/mm/aaaa" value="{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $delivered->expiration) }}">
</div>

@can('master')

<div class="form-group">
						<label>{{__('general.Company')}}</label>
						

						<select   name="company"  class="select2-responsive form-control" >
                   @foreach($clients as $client)
                   <option value="{{$client->id}}" @if($delivered->company_id == $client->id) selected @endif>{{$client->name}} - {{$client->cnpj}}</option>
                   @endforeach
                  </select>
					</div>
@else
<div class="form-group">
	
	<input type="hidden" name="company" class="form-control" " value="{{Auth()->User()->company_id}}">
</div>

@endcan


</div>





				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
		</div>
	</form>


	</div>


@section('js')
<script type="text/javascript">

$( document ).ready(function() {

	$('#expiration').mask('00/00/0000', {reverse: true});
	$('#description').mask('00/00/0000', {reverse: true});
	
});
</script>

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{!! $validator->selector('#add_form') !!}