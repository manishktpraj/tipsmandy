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
			{{--

			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item">
                        {!!$renderButtons!!}
					</li>
				</ul>
			</div>
			--}}

		</div>
		<div class="m-portlet__body">
            <!--begin: table -->
			<table class="table table-striped- table-hover table-checkable">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Actions</th>
					</tr>
				</thead>
				
				<tbody>
					@php $i = 0; @endphp
					@forelse($pages as $page)
					@php $i++; @endphp
						<tr>
							<td>{{$i}}</td>
							<td>{{$page->name}}</td>
							<td>
								@if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'manage-pages', 'is_edit'))
								<a href="{{route('admin.pages.edit', [$page->id])}}" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="la la-edit"></i></a>
								@endif
							</td>
						</tr>
					@empty
					<tr>
						<td colspan="3" class="text-center">Data not found.</td>
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
