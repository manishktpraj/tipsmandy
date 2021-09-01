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
						<th>Referred by </th>
						<th>Referral Code </th>
						<th>Date when code used </th>
						<th>Account Details </th>
						<th>Pay </th>
						<th>Status </th>
						<th>Actions </th>
					</tr>
				</thead>
				
				<tbody>
					@php $i = 0; @endphp
					@forelse($supports as $row)
					@php $i++; @endphp
					
						<?php
					$user_details = DB::table('bankdetails')->where('user_id', $row->referral_userid)->first();
				?>
						<tr>
							<td>{{$i}}</td>
							<td>{{$row->name}}</td>
							<td>{{$row->referral_code}}</td>
							<td>{{$row->created_at}}</td>
								<td>Account Name: {{$user_details->account_number ?? ''}}
								<br>
							Account Number: {{$user_details->account_name ?? ''}}
								<br>
							Ifsc Code: {{$user_details->ifsc_code ?? ''}}
								<br>
							Account Type: {{$user_details->account_type ?? ''}}</td>
							<td><?php echo  count($supportscounts)*10.00; ?></td>
									<td><a class="recard-new deleterecipent" href="{{route('admin.referral-updatestatus', $row->referral_userid)}}" onclick="return confirm('Are you sure you want to Paid this User?')">Paid</a></td>
							
							<td><a href="{{route('admin.referral-userdetails', $row->referral_userid)}}" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details"><i class="la la-eye"></i></a></td>
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
