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
                        <span class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                            {{ $current_role ? 'Authorization '.$current_role : 'Site Permissions' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
		<div class="m-portlet__body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
    				<thead>
    					<tr>
    						<th>Authorization</th>
                            <th>Is Read</th>
                            <th>Is Add</th>
                            <th>Is Edit</th>
                            <th>Is Delete</th>
                            <th>Is Status</th>
    					</tr>
    				</thead>

                    <tbody>
                        @if(count($sitepermissions))
                            <tr>
                                <td colspan="1">&nbsp;</td>
                                <td>
                                    <a href="{{ route('admin.sitepermissions.updateallsitepermissionstatus', ['status' => 'is_read', 'role_id' => $id]) }}" class="btn btn-sm btn-primary">All</a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.sitepermissions.updateallsitepermissionstatus', ['status' => 'is_add', 'role_id' => $id]) }}" class="btn btn-sm btn-primary">All</a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.sitepermissions.updateallsitepermissionstatus', ['status' => 'is_edit', 'role_id' => $id]) }}" class="btn btn-sm btn-primary">All</a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.sitepermissions.updateallsitepermissionstatus', ['status' => 'is_delete', 'role_id' => $id]) }}" class="btn btn-sm btn-primary">All</a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.sitepermissions.updateallsitepermissionstatus', ['status' => 'is_status', 'role_id' => $id]) }}" class="btn btn-sm btn-primary">All</a>
                                </td>
                            </tr>
                            @endif
                        @forelse($sitepermissions as $permission)
                        <tr>
                            <td>{{ $permission->manager->name }}</td>
                            <td class="is_read site_permission_status_1_{{ $permission->id}}">
                                <a href="javascript:;" onclick="updatesitepermissions(<?php echo $id;?>, <?php echo $permission->id; ?>, 1,  <?php echo $permission->is_read ? '1' : '0'; ?>)">
                                    <span class="btn btn-sm btn-{{ $permission->is_read == 1 ? 'success' : 'danger' }}">{{ $permission->is_read == 1 ? 'Yes' : 'No' }}</span>
                                </a>
                            </td>
                            <td class="is_add site_permission_status_2_{{ $permission->id}}">

                                <a href="javascript:;" onclick="updatesitepermissions(<?php echo $id;?>, <?php echo $permission->id; ?>, 2,  <?php echo $permission->is_add ? '1' : '0'; ?>)">
                                    <span class="btn btn-sm btn-{{ $permission->is_add == 1 ? 'success' : 'danger' }}">{{ $permission->is_add == 1 ? 'Yes' : 'No' }}</span>
                                </a>

                            </td>

                            <td class="is_edit site_permission_status_3_{{ $permission->id}}">
                                <a href="javascript:;" onclick="updatesitepermissions(<?php echo $id;?>, <?php echo $permission->id; ?>, 3,  <?php echo $permission->is_edit ? '1' : '0'; ?>)">
                                    <span class="btn btn-sm btn-{{ $permission->is_edit == 1 ? 'success' : 'danger' }}">{{ $permission->is_edit == 1 ? 'Yes' : 'No' }}</span>
                                </a>
                            </td>

                            <td class="is_delete site_permission_status_4_{{ $permission->id}}">
                                <a href="javascript:;" onclick="updatesitepermissions(<?php echo $id;?>, <?php echo $permission->id; ?>, 4,  <?php echo $permission->is_delete ? '1' : '0'; ?>)">
                                    <span class="btn btn-sm btn-{{ $permission->is_delete == 1 ? 'success' : 'danger' }}">{{ $permission->is_delete == 1 ? 'Yes' : 'No' }}</span>
                                </a>
                            </td>

                            <td class="is_status site_permission_status_5_{{ $permission->id}}">
                                <a href="javascript:;" onclick="updatesitepermissions(<?php echo $id;?>, <?php echo $permission->id; ?>, 5,  <?php echo $permission->is_status ? '1' : '0'; ?>)">
                                    <span class="btn btn-sm btn-{{ $permission->is_status == 1 ? 'success' : 'danger' }}">{{ $permission->is_status == 1 ? 'Yes' : 'No' }}</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="5">Data not found.</td>
                        </tr>
                        @endforelse
                    </tbody>
    			</table>
            </div>
		</div>
	</div>

@endsection

@push('js')
    <script type="text/javascript">

        //Generate csrf token for ajax request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /**
         * update site permission status
         */
        function updatesitepermissions(role_id, site_permission_id, status, current_status)
        {
            var role_id = role_id;
            var site_permission_id = site_permission_id;
            var status = status;
            var current_status = current_status;
            //$(".site_permission_status_"+status+"_"+site_permission_id+ "img.spingif").show();
            $.ajax({
                type: 'GET',
                data: {role_id:role_id,site_permission_id:site_permission_id,status:status,current_status:current_status},
                url: "{{route('admin.sitepermissions.ajaxSitePermissionStatus')}}",
                success: function(response){
                    if(response.error) {
                        alert(response.error);
                        return false;
                    }
                    $(".site_permission_status_"+status+"_"+site_permission_id).html(response.is_read);
                }

            });
        }
    </script>
@endpush
