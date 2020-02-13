@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

@if(Request::is('g3/branches*'))
<h1>{{__('general.Branches')}} - {{$company->name}} - {{__('general.Outsourceds')}}</h1>
@else
<h1>{{__('general.Companies')}} - {{$company->name}} - {{__('general.Outsourceds')}}</h1>
@endif

@stop

@section('content')



@if(Request::is('g3/branches*'))

<a href="{{route('branches.outsourceds.add', $company->id)}}" class="btn btn-app pull-right datatable">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>

<a href="{{route('branches')}}" class="btn btn-app pull-right">
	<i class="fa fa-arrow-left"></i> {{__('general.Branches')}}
</a>

@else

<a href="{{route('companies.outsourceds.add', $company->id)}}" class="btn btn-app pull-right datatable">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>

<a href="{{route('companies')}}" class="btn btn-app pull-right">
	<i class="fa fa-arrow-left"></i> {{__('general.Companies')}}
</a>

@endif

<div class="col-xs-12">
	<div class="box">
		
		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover">
				<thead>
					<tr>
						
						<th>{{__('general.Name')}}</th>
						<th>{{__('general.cpf')}}</th>
						<th>{{__('general.rg')}}</th>
						<th>{{__('general.Borndate')}}</th>
						<th>{{__('general.Active')}}</th>
						<th>{{__('general.Documents')}}</th>
						<th>{{__('general.Services')}}</th>
						<th>{{__('general.Edit')}}</th>
						<th>{{__('general.Delete')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($employees as $employee)
					
					<tr>
						
						
						<td><a href="{{route('employees.employee', $employee->id)}}">{{ $employee->name}}</a></td>
						<td>{{ $employee->cpf}}</td>
						<td>{{ $employee->rg}}</td>
						<td>{{ date('d/m/Y', strtotime($employee->borndate))}}</td>
						@if ($employee->pivot->fl_ready == 1)
						@if ($employee->pivot->dt_ready_sent != null)
						
						<td><i class="fa  fa-check text-green " style="font-size: 32px"></i></td>
						@else
						<td><a href="{{route('company.outsourceds.activate', [$company->id, $employee->id])}}"><i class="fa  fa-check text-green " style="font-size: 32px"></i></a></td>
						
						@endif
						@else
						<td><a href="{{route('company.outsourceds.activate', [$company->id, $employee->id])}}"><i class="fa  fa-times text-red " style="font-size: 32px"></i></a></td>
						@endif
						
						
						<td> <a href="{{route('employees.documents', $employee->id)}}">
							
							<i class="fa  fa-folder text-yellow " style="font-size: 32px"></i>
							
							
						</a></td>
						<td> <a href="{{route('employees.services', $employee->id)}}">
							
							<i class="fa  fa-list " style="font-size: 32px"></i>
							
							
						</a></td>
					</a></td>
					<td>
						<a class="	" href="{{route('employees.edit', $employee->id )}}">
							
							<i class="fa fa-pencil text-green" style="font-size: 32px"></i>
							
						</a>
					</td>
					<td>
						
						<a class="	" href="{{route('employees.detach', $employee->id)}}">
							
							<i class="fa fa-trash text-red" style="font-size: 32px"></i>
							
						</a>
						
					</td>
				</tr>
				
				
				@endforeach
				
			</tbody></table>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
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
