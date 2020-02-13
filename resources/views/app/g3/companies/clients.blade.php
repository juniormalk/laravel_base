@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{$owner->name}} - {{ __('general.Providers') }}</h1>

@stop

@section('content')



<a href="{{route('companies.clients.attach', $owner->id)}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@if(Request::is('*companies/clients*'))
 	<a href="{{route('companies')}}" class="btn btn-app pull-right">
 	<i class="fa fa-arrow-left"></i> {{__('general.Companies')}}
</a>
@endif

@if(Request::is('*g3/branches/clients*'))
 	<a href="{{route('branches')}}" class="btn btn-app pull-right">
 	<i class="fa fa-arrow-left"></i> {{__('general.Branches')}}
</a>
@endif


<div class="col-xs-12">
	<div class="box">
		

		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>

					<th>id</th>
					<th>{{__('general.Name')}}</th>
					<th>{{__('general.Address')}}</th>
					<th>{{__('general.Number')}}</th>
					<th>{{__('general.Neighborhood')}}</th>
					<th>{{__('general.Citie')}}</th>
					<th>{{__('general.Neighborhood')}}</th>
					<th>{{__('general.Delete')}}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($companies as $company)

				<tr>

					<td>{{ $company->id}}</td>
					<td>{{ $company->name}}</td>
					<td>{{ $company->address}}</td>
					<td>{{ $company->number}}</td>
					<td>{{ $company->neighborhood}}</td>
					<td>{{ $company->citie}}</td>
					<td>{{ $company->neighborhood}}</td>




<td> 
	
		<a href="{{route('companies.detach', ['cp'=>$owner->id ,'id'=>$company->id])}}">
	<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
	</a>
	

</td>
</tr>


	@endforeach

</tbody></table>
</div>

</div>
@section('js')
<script type="text/javascript">

	$( document ).ready(function() {
		$('#datatable').DataTable( {
 			"initComplete": function(settings, json) {
    			$('div.dataTables_filter input').focus();
  			}
		});
	});
</script>


@endsection
@endsection
