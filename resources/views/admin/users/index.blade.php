@extends('admin.layouts.master')

@push('css')
    <style type="text/css">
        tr.odd, tr.even{
            background: #fff;
        }

        .m-portlet {
            margin-bottom: 0rem;
        }

        .m-portlet .m-portlet__body {
            padding: 1rem 2.2rem;
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
        {{--
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        &nbsp;
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" class="btn btn-focus m-btn m-btn--custom m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-arrow-down"></i>
                                <span>Member Export</span>
                            </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" class="btn btn-focus m-btn m-btn--custom m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-arrow-up"></i>
                                <span>Member Import</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
        --}}

        <div class="m-portlet__body">

            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-1 order-xl-2 m--align-right">
                        <a href="{{route('admin.users.export')}}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-arrow-down"></i>
                                <span>Member Export</span>
                            </span>
                        </a>
                        <a href="{{route('admin.users.import')}}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-arrow-up"></i>
                                <span>Member Import</span>
                            </span>
                        </a>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->
        </div>
    </div>

    <table class="table table-striped- table-checkable" id="m_table" style="border-collapse: separate !important; border-spacing: 0 15px !important;">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th>Plan</th>
                <th>Plan Expiry Date</th>
            </tr>
        </thead>
    </table>


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
                    'url':'{{route("admin.users.index")}}?q={{request("q")}}&role={{request("role")}}',
					'headers': {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
                },

                'columns': [
                    //{ data: 'id', name: 'id'},
                    { data: 'name', name: 'name'},
                    //{ data: 'email', name: 'email'},
                    { data: 'phone_no', name: 'phone_no', orderable: false, searchable: false},
                    { data: 'plan', name: 'plan'},
                    { data: 'plan_expiry_date', name: 'plan_expiry_date'},
                    //{ data: 'action', name: 'action', orderable: false, searchable: false },
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
