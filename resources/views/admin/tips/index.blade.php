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
            <!--begin: Search Form -->
            <form class="m-form m-form--fit m--margin-bottom-20" method="get">
                <div class="row m--margin-bottom-20">
                    <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                        <label>Segment:</label>
                        <select class="form-control m-input" name="segment" data-col-index="0">
                            <option value="">Select</option>
                            @foreach($segments as $segment)
                            <option {{ request('segment') == $segment ? 'selected' : '' }} value="{{$segment}}">{{$segment}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                        <label>Date:</label>
                        <div class="input-daterange input-group" id="m_datepicker">
                            <input type="text" class="form-control m-input" name="from" value="{{request('from')}}" placeholder="From" data-col-index="1" />
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                            </div>
                            <input type="text" class="form-control m-input" name="to" value="{{request('to')}}" placeholder="To" data-col-index="2" />
                        </div>
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
                        &nbsp;&nbsp;
                        <a href="{{route('admin.tips.index')}}" class="btn btn-sm btn-secondary m-btn m-btn--icon">
                            <span>
                                <i class="la la-close"></i>
                                <span>Reset</span>
                            </span>
                        </a>

                    </div>
                </div>
            </form>
            <!--begin: Datatable -->
			<table class="table table-striped- table-hover table-checkable" id="m_table">
				<thead>
					<tr>
						<th>#</th>
						<th>Stock Name</th>
                        <th>Segment</th>
						<th>Created By</th>
						<th>Actions</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>

@endsection

@push('js')

	<script>

        $(document).ready(function(){

            $("#m_datepicker").datepicker({todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}});

            //Ajax datatable script
            $.extend( true, $.fn.dataTable.defaults, {
                "searching": false,
                //"bPaginate": false,
                "ordering": false,
                //"pagingType": "numbers", //Page number buttons only (1.10.8)
                //"pagingType": "simple", //'Previous' and 'Next' buttons only
                //"pagingType": "simple_numbers", //'Previous' and 'Next' buttons, plus page numbers
                //"pagingType": "full", //'First', 'Previous', 'Next' and 'Last' buttons
                "pagingType": "full_numbers", //'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers
                //"pagingType": "first_last_numbers", //'First' and 'Last' buttons, plus page numbers
                //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });

			var m_table = $('#m_table').DataTable({
                'processing': true,
                'responsive': true,
                'serverSide': true,
                'pageLength': 20,
                'info': true,
                'lengthChange': false,
                //'pagingType': 'full',
                'serverMethod': 'post',
                'ajax': {
                    'url':'{{route("admin.tips.index")}}?segment={{request("segment")}}&from={{request("from")}}&to={{request("to")}}',
					'headers': {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
                },

                'columns': [
                    { data: 'id', name: 'id'},
                    { data: 'name', name: 'name'},
                    { data: 'segment', name: 'segment'},
                    { data: 'created_by', name: 'created_by'},
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],

                'columnDefs': [
                    { width: 12, targets: 0 }
                ],

                language: {
                    emptyTable: "No data available",
                    lengthMenu: "Show _MENU_ entries.",
					searchPlaceholder: "Search by name"
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
