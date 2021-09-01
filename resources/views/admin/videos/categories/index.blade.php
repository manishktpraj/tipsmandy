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
	
	<!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Add Video Title
                    </h3>
                </div>
            </div>

        </div>

        <!--begin::Form-->
		@if(request('id'))
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.videos.categories.edit', $videoTitle->id)}}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="m-portlet__body">
				
				<div class="form-group m-form__group row @error('title') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Title *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="text" class="form-control m-input" name="title" value="{{ old('title', $videoTitle->title
						) }}" placeholder="Title" required="required" autofocus>
						@error('title')
						<div class="form-control-feedback">{{ $message }}</div>
						@enderror
                    </div>
                </div>
				
            </div>

            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
							<button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--end::Form-->
		@else
		<form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.videos.categories.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="m-portlet__body">
				
				<div class="form-group m-form__group row @error('title') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Title *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="text" class="form-control m-input" name="title" value="{{ old('title') }}" placeholder="Title" required="required" autofocus>
						@error('title')
						<div class="form-control-feedback">{{ $message }}</div>
						@enderror
                    </div>
                </div>
				
            </div>

            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
							<button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--end::Form-->
		@endif
    </div>

    <!--end::Portlet-->
	
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
						<th>Title</th>
						<th>Actions</th>
					</tr>
				</thead>
				
				<tbody>
					@php $i = 0; @endphp
					@forelse($categories as $category)
					@php $i++; @endphp
						<tr>
							<td>{{$i}}</td>
							<td>{{$category->title}}</td>
							<td>
								<a href="{{route('admin.videos.categories.edit', [$category->id])}}" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="la la-edit"></i></a>
								
								<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" href="{{route('admin.videos.categories.delete', [$category->id])}}"  onclick="return confirm('Are you sure you want to delete?');" title="Delete">
									<i class="la la-trash"></i>
								</a>
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
