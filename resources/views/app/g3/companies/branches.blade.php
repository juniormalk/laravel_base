@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{ __('general.Branches') }}</h1>

@stop

@section('content')



<a href="{{route('branches.attach')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>


<div class="col-xs-12">
	<div class="box">
		

		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>

					
					<th>{{__('general.Name')}}</th>
					<th>{{__('general.cnpj')}}</th>
					
					<th>{{__('general.Outsourceds')}}</th>
					<th>{{__('general.Providers')}}
					<th>{{__('general.Edit')}}</th>
					<th>{{__('general.Delete')}}</th>
				</tr>
			</thead>
			<tbody>
				@if($companies)
				@foreach ($companies as $company)

				<tr>

					
					<td>{{ $company->name}}</td>
					<td>{{ $company->cnpj}}</td>
					





	<td> 
		@if(Request::is('*g3/branches'))
			<a href="{{route('branches.outsourceds', $company->id)}}">
		@endif
		@if(Request::is('*companies*'))
			<a href="{{route('companies.branches.outsourceds',  [$owner->id, $company->id])}}">
		@endif
		<i class="fa fa-exchange " style="font-size: 32px"></i>
		</a>
		

	</td>

	<td>
		@if(Request::is('*g3/branches'))
			<a href="{{route('branches.clients', $company->id)}}">
		@endif

		@if(Request::is('*companies*'))
			<a href="{{route('companies.branches.clients', [$owner->id, $company->id])}}">
		@endif
		
		<i class="fa fa-industry " style="font-size: 32px"></i>
		</a>
	</td>	


<td>
	@if(Request::is('*g3/branches'))
			<a href="{{route('branches.edit', $company->id)}}">
		@endif

		@if(Request::is('*companies*'))
			<a href="{{route('companies.branches.edit', [$owner->id, $company->id])}}">
		@endif
	

		<i class="fa fa-pencil text-green" style="font-size: 32px"></i>

	</a>
</td>

<td> 
	@can('master')
	<a href="{{route('companies.delete', $company->id)}}">
	<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
	</a>
	@else
		<a href="{{route('branches.detach', ['id'=>$company->id])}}">
	<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
	</a>
	@endcan

</td>
</tr>


	@endforeach
	@endif

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
