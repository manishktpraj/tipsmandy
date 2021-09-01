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
<li class="m-nav__separator">-</li>
<li class="m-nav__item">
    <a href="" class="m-nav__link">
        <span class="m-nav__link-text">{{$subTitle}}</span>
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
                        {{$subTitle}}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        {!! $backButtons !!}
                    </li>
                </ul>
            </div>
        </div>

        <!--begin::Form-->
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.plans.edit', $plan->id)}}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="m-portlet__body">
                <div class="form-group m-form__group row @error('name') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Name *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="text" class="form-control m-input" name="name" value="{{ old('name', $plan->name) }}" maxlength="14" placeholder="Name" required="required" autofocus>
						<span class="m-form__help">14 Character Limit.</span>
                        @error('name')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group m-form__group row @error('daily_tips_limit') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Monthly Tips Limit *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="text" class="form-control m-input" name="daily_tips_limit" value="{{ old('daily_tips_limit', $plan->daily_tips_limit ?? '') }}" placeholder="Monthly Tips Limit" required="required">
                        @error('daily_tips_limit')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @include('admin.plans.edit_plan_price')

                @include('admin.plans.edit_more_featured')

                <div class="form-group m-form__group row">
                    <label class="col-form-label col-lg-3 col-sm-12">Segments *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        @foreach ($segments->chunk(5) as $segment)
                            <div class="m-checkbox-inline">
                                @foreach ($segment as $row_segment)
                                    @php
                                        $segmentCheckedcls = '';
                                        if($plan->getPlanSegmentDetail($plan->id, $row_segment)) {
                                            $segmentCheckedcls = 'checked';
                                        }
                                    @endphp
                                    <label class="m-checkbox col-lg-2">
                                        <input name="segments[]" type="checkbox" value="{{$row_segment}}" {{$segmentCheckedcls}}> {{$row_segment}}
                                        <span></span>
                                    </label>
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                </div>
                {{--
                <div class="form-group m-form__group row">
                    <label class="col-form-label col-lg-3 col-sm-12">Segments *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                      <div class="m-checkbox-inline">
                        @foreach (config('constants.segments') as $segment_key => $segment_value)
                        @php
                            $segmentCheckedcls = '';
                            if($plan->getPlanSegmentDetail($plan->id, $segment_value)) {
                            $segmentCheckedcls = 'checked';
                            }
                        @endphp
                        <label class="m-checkbox">
                          <input name="segments[]" type="checkbox" value="{{$segment_value}}" {{ $segmentCheckedcls }}> {{$segment_value}}
                          <span></span>
                        </label>
                        @endforeach
                      </div>
                    </div>
                </div>
                --}}

                <div id="segments_wrapper">
                    @foreach($plan->getMoreplansegments($plan->id) as $row_segments)
                    <input type="hidden" name="segments_value_id[]" value="{{$row_segments->id}}" >
                    <div class="form-group m-form__group row" id="remove_segments_wrapper">
                        <label class="col-form-label col-lg-3 col-sm-12">&nbsp;</label>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <input type="hidden" name="segments_id[]" value="{{$row_segments->id}}" >
                            <input type="text" class="form-control m-input" name="segmentsArrays[]" value="{{$row_segments->name}}" placeholder="Segments">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <button type="button" id="btn_remove_segment" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only" style="margin-top: 1px;"><i class="la la-trash-o"></i></button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-form-label col-lg-3 col-sm-12">&nbsp;</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <button type="button" class="btn m-btn--pill btn-outline-info m-btn m-btn--custom" id="btn_add_segment">Add more segment</button>
                    </div>
                </div>

            </div>

            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
                            {!! $renderButtons !!}
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--end::Form-->
    </div>

    <!--end::Portlet-->

@endsection


@push('js')

<script>

    jQuery(document).ready(function() {

        var segments_max_fields      = 30; //maximum input boxes allowed
        var segments_wrapper         = jQuery("#segments_wrapper"); //Fields wrapper
        var btn_add_segment      = jQuery("#btn_add_segment"); //Add button ID

        var sv = 0; //initlal text box count

        jQuery(btn_add_segment).click(function(e){ //on add input button click
            e.preventDefault();
            if(sv < segments_max_fields){ //max input box allowed

                sv++; //text box increment

                jQuery(segments_wrapper).append('<div class="form-group m-form__group row" id="remove_segments_wrapper"><label class="col-form-label col-lg-3 col-sm-12">&nbsp;</label><div class="col-lg-4 col-md-4 col-sm-12"><input type="text" class="form-control m-input" name="segmentsArray[]" value="" placeholder="Segments" required></div><div class="col-lg-4 col-md-4 col-sm-12"><button type="button" id="btn_remove_segment" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only" style="margin-top: 1px;"><i class="la la-trash-o"></i></button></div></div>'); //add input box

            }

        });

        //Remove specific div
        jQuery(segments_wrapper).on("click","#btn_remove_segment", function(e){ //user click on remove text
            e.preventDefault();
            jQuery(this).closest('#remove_segments_wrapper').remove();
            sv--;
        });
    });

</script>
@endpush
