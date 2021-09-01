@extends('admin.layouts.master')

@section('subheader_title', $pageTitle)

@section('content_subheader')
<!-- BEGIN: Subheader -->
<li class="m-nav__separator">-</li>
<li class="m-nav__item">
	<a href="javascript:;" class="m-nav__link">
		<span class="m-nav__link-text">Dashboard</span>
	</a>
</li>
<!-- END: Subheader -->
@endsection

@section('content')

	<div class="m-portlet m-portlet--mobile">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						{{$subTitle}}
					</h3>
				</div>
			</div>
		</div>
		<div class="m-portlet__body">
            <!--begin: table -->
			<table class="table table-striped- table-hover table-checkable">
				<thead>
					<tr>
						<th>#</th>
						<th>Ticket Id</th>
						<th>Name</th>
						<th>Email</th>
						<th>Phone No</th>
						<th>Message</th>
					</tr>
				</thead>
				
				<tbody>
					@php $i = 0; @endphp
					@forelse($supports as $row)
					@php $i++; @endphp
						<tr>
							<td>{{$i}}</td>
							<td>{{$row->ticket_id}}</td>
							<td>{{$row->name}}</td>
							<td>{{$row->email}}</td>
							<td>{{$row->phone}}</td>
							<td>{{$row->message}}</td>
						</tr>
					@empty
					<tr>
						<td colspan="5" class="text-center">Data not found.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

@endsection

@push('js')

	<script type="text/javascript">

        $(document).ready(function(){

		});

    </script>

@endpush
