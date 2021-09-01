/**
 * Update plan activate/deactivate status
 *
 */
$(document).on('click', '._planStatus', function () {

    var _root = $('#root').attr('data-root');

    var $thisPlanid = $(this);
    var planId = $thisPlanid.data('val');
    $.ajax({
        type : 'GET',
        dataType: 'json',
        cache: false,
        url: _root + "/admin/plans/status",
        data: {plan_id : planId},
        beforeSend: function(){
            // Show container
            //$("._btn_status_"+planId).prop('disabled', true);
            $("._btn_status_"+planId).html('Updating...');
        },
        success : function(resp)
        {
            //toastr.clear();
            //console.log(resp);
            if(resp.error) {

                toastrMessages(resp.error, 4);
                $('._btn_status_'+planId).removeClass('m-badge--danger');
                $('._btn_status_'+planId).removeClass('m-badge--success');
                $('._btn_status_'+planId).addClass(resp.class);
                $('._btn_status_'+planId).html(resp.text);
                return false;
            }

            $('._btn_status_'+planId).removeClass('m-badge--danger');
            $('._btn_status_'+planId).removeClass('m-badge--success');
            $('._btn_status_'+planId).addClass(resp.class);
            $('._btn_status_'+planId).html(resp.text);
            toastrMessages(resp.success, 1);

        },
        complete:function(resp){
            // Hide image container
            //$("._btn_status_"+planId).prop('disabled', false);
            $("._btn_status_"+planId).html(resp.text);
        }
    });

});

    function toastrMessages(message, type) {
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        if(type==1) {
            toastr.success(message);
        }else if(type==2) {
            toastr.info(message);
        }else if(type==3) {
            toastr.warning(message);
        }else if(type==4) {
            toastr.error(message);
        }

    }

    function toastrMessagesBottomRight(message, type) {
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        if(type==1) {
            toastr.success(message);
        }else if(type==2) {
            toastr.info(message);
        }else if(type==3) {
            toastr.warning(message);
        }else if(type==4) {
            toastr.error(message);
        }

    }

