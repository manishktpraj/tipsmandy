@extends('admin.layouts.master')

@push('css')
    <style type="text/css">
        tr.odd, tr.even{
            background: #fff;
        }
    </style>
@endpush
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

            <!--begin: Search Form -->
            <form class="m-form m-form--fit m--margin-bottom-20" method="get">
                <div class="row m--margin-bottom-20">
                    <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                        <label>Search by name, email:</label>
                        <input type="text" name="q" value="{{request('q')}}" class="form-control m-input" placeholder="E.g: name, email" data-col-index="0" autocomplete="q">
                    </div>
                    <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                        <label>Role:</label>
                        <select class="form-control m-input" name="role" data-col-index="1">
                            <option value="">Select</option>
                            @foreach($roles as $role)
                            <option value="{{$role->id}}" {{ request('role') == $role->id ? 'selected' : '' }}>{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-sm btn-brand m-btn m-btn--icon">
                           <span>
                                <i class="la la-search"></i>
                                <span>Search</span>
                            </span>
                        </button>
                        @if(request('q') || request('role'))
                        &nbsp;&nbsp;
                        <a href="{{route('admin.staffmembers.index')}}" class="btn btn-sm btn-secondary m-btn m-btn--icon">
                            <span>
                                <i class="la la-close"></i>
                                <span>Reset</span>
                            </span>
                        </a>
                        @endif
                    </div>
                </div>
            </form>

            <!--begin: Datatable -->
			<!--table class="table table-striped- table-hover table-checkable" id="m_table" style="border-collapse: separate !important; border-spacing: 0 15px !important;"-->

		</div>
	</div>
    <div class="m-portlets">
        <div class="m-portlet__body">
            <table class="table table-striped- table-checkable" id="m_table" style="border-collapse: separate !important; border-spacing: 0 15px !important;">

                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
@endsection

@push('js')

	<script type="text/javascript">

        $(document).ready(function(){

            $.extend( true, $.fn.dataTable.defaults, {
                "searching": false,
                "ordering": false,
                "pagingType": "full_numbers", //'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers
            });

			var m_table = $('#m_table').DataTable({
                'processing': true,
                'responsive': true,
                'serverSide': true,
                'pageLength': 10,
                'info': true,
                'lengthChange': false,
                'serverMethod': 'post',
                'ajax': {
                    'url':'{{route("admin.staffmembers.index")}}?q={{request("q")}}&role={{request("role")}}',
					'headers': {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
                },

                'columns': [
                    //{ data: 'id', name: 'id'},
                    { data: 'name', name: 'name'},
                    //{ data: 'email', name: 'email'},
                    { data: 'phone_no', name: 'phone_no', orderable: false, searchable: false},
                    { data: 'role', name: 'role', orderable: false, searchable: false},
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],

                'columnDefs': [
                    //{ width: 12, targets: 0 }
                ],

                language: {
                    emptyTable: "No data available",
                    lengthMenu: "Show _MENU_ entries.",
					searchPlaceholder: "Search by name, email or phone no"
                },
            });

            // Grab the datatables input box and alter how it is bound to events
            $(".dataTables_filter input")
                .unbind() // Unbind previous default bindings
                .bind("input", function(e) { // Bind our desired behavior
                    // If the length is 3 or more characters, or the user pressed ENTER, search
                    if(this.value.length >= 3 || e.keyCode == 13) {
                        // Call the API search function
                        m_table.search(this.value).draw();
                    }
                    // Ensure we clear the search if they backspace far enough
                    if(this.value == "") {
                        m_table.search("").draw();
                    }
                    return;
                });
		});

    </script>

@endpush
