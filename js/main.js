$(document).ready(function(){
    
    //alert("hello world...");
    
    $("#OmniPHP_Form").validate({
        rules: {
                CellPhone: {
                        required: true,
                        phoneUS: true,
                        minlength: 12,
                        maxlength: 12
                }
        },
        messages: {
                CellPhone: {
                        required: "Please enter a phone",
                        phoneUS: "Enter phone in correct format",
                        minlength: "Your phone must consist of at least 12 characters",
                        maxlength: "Your phone cannot exceed 12 characters"
                }
        },
	errorLabelContainer: $("#OmniPHP_Form div.omniphp_validation_errors")
    });
    
});
