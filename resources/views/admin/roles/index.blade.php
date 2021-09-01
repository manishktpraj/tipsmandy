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
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        {!!$renderButtons!!}
                    </li>
                </ul>
            </div>
        </div>
		<div class="m-portlet__body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
    				<thead>
    					<tr>
    						<th width="1%">#</th>
                            <th>Title</th>
    						<th>Actions</th>
    					</tr>
    				</thead>

                    <tbody>
                        @php $i =0 ; @endphp
                        @forelse($roles as $role)
                        @php $i++; @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$role->name}}</td>
                            <td>
                                @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_edit'))
                                <a href="{{route('admin.sitepermissions.index', [$role->id])}}" class="btn btn-sm btn-outline-success m-btn m-btn--icon">
                                    <span>
                                        <i class="la la-edit"></i>
                                        <span>Edit Permissions</span>
                                    </span>
                                </a>
                                &nbsp;
                                @endif
                                @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_delete'))
                                <a href="{{route('admin.roles.delete', [$role->id])}}" onclick="return confirm('Are you sure you want to delete?');" class="btn btn-sm btn-outline-warning m-btn m-btn--icon">
                                    <span>
                                        <i class="la la-trash"></i>
                                        <span>Delete</span>
                                    </span>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="4">Data not found.</td>
                        </tr>
                        @endforelse
                    </tbody>
    			</table>
            </div>
		</div>
	</div>

@endsection

@push('js')
@endpush
