/**
 * Set default delete confirmation box.
 *
 */
function confirm_click(e){
    var r = confirm("Are you sure you want to delete?");
    if (r == true) {
        $(e).closest('form').submit();
    }
}

function NumericValidation(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if (charCode > 31 && (charCode < 46 || charCode > 57) )
        return false;

    return true;
}

/**
 *
 * Show image preview before upload
 */
loadFile = function(event, id) {
    var output = document.getElementById(id);
    output.src = URL.createObjectURL(event.target.files[0]);
};
