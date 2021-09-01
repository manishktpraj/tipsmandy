                <div class="form-group m-form__group row">
                    <label class="col-form-label col-lg-3 col-sm-12" for="name">Featured *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="40%">Image</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody class="tips_featured_wrapper">
                                <tr id="remove_tips_featured_wrapper">
                                    <td>
                                        <input type="file" class="form-control" name="featured_images[]" accept="image/*" required />
                                        <span class="m-form__help">Allowed formats - jpg, jpeg, png.</span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="featured_name[]" placeholder="Demo Text 1" required />
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-form-label col-lg-3 col-sm-12">&nbsp;</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <button type="button" class="btn m-btn--pill btn-outline-info m-btn m-btn--custom" id="btn_add_more_featured">Add more featured</button>
                    </div>
                </div>

@push('js')

<script>

    jQuery(document).ready(function() {

        var max_fields      = 30; //maximum input boxes allowed
        var tips_featured_wrapper         = jQuery(".tips_featured_wrapper"); //Fields wrapper
        var btn_add_more_featured      = jQuery("#btn_add_more_featured"); //Add button ID

        var pv = 0; //initlal text box count

        jQuery(btn_add_more_featured).click(function(e){ //on add input button click
            e.preventDefault();
            if(pv < max_fields){ //max input box allowed

                pv++; //text box increment

                jQuery(tips_featured_wrapper).append('<tr id="remove_tips_featured_wrapper"><td><input type="file" class="form-control" name="featured_images[]" accept="image/*" required /><span class="m-form__help">Allowed formats - jpg, jpeg, png.</span></td><td><input type="text" class="form-control" name="featured_name[]" placeholder="Demo Text 1" required /></td><td><button type="button" id="btn_remove_featured" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only" style="margin-top: 6px;"><i class="la la-trash-o"></i></button></td></tr>'); //add input box

            }

        });

        //Remove specific div
        jQuery(tips_featured_wrapper).on("click","#btn_remove_featured", function(e){ //user click on remove text
            if (confirm("Are you sure you want to delete...?")) {
                e.preventDefault();
                jQuery(this).closest('#remove_tips_featured_wrapper').remove();
                pv--;
            }
        });
    });

</script>
@endpush
