 jQuery.validator.addMethod('numericOnly', function (value) {
	return /[0-9 ]/.test(value);
}, 'Please only enter numeric values (0-9)');
jQuery(document).ready(function() {
	jQuery("#employee").validate({
		rules: {
			'__authentication[TYPO3][FLOW3][Security][Authentication][Token][UsernamePassword][username]':{
				required: true,
				email: true
			},
			'__authentication[TYPO3][FLOW3][Security][Authentication][Token][UsernamePassword][password]':{
				required: true,
				minlength: 5
			}
		},
		errorPlacement: function(error, element) {
			jQuery(element).text('');
		}
	});

    // validate signup form on keyup and submit
            jQuery("#newEmployee").validate({
                rules: {
                    'newEmployee[name][firstName]': "required",
                    'newEmployee[name][lastName]': "required",
                    'newEmployee[primaryElectronicAddress][identifier]':{required:true, email:true},
                    'password':{required:true, minlength:5 },
                    'newEmployee[address]': "required",
                    'newEmployee[contactNumber]': {required:true, numericOnly:true}
                },
                messages: {
                    'newEmployee[name][firstName]': "Please enter first name",
                    'newEmployee[name][lastName]': "Please enter last name",
                    'newEmployee[primaryElectronicAddress][identifier]': "Please enter valid email address",
                    'password':{required:"Please enter password",minlength:"Password should be of minimum 5 characters"},
                    'newEmployee[address]':"Please enter address",
                    'newEmployee[contactNumber]':{required:"Please enter Contact number",numericOnly:"Numbers only"}
                }

            });

		//Validates update form
            jQuery("#updateEmployee").validate({
                rules: {
                    'employee[name][firstName]': "required",
                    'employee[name][lastName]': "required",
                    'employee[address]': "required",
                    'employee[contactNumber]': {required:true, numericOnly:true}
                },
                messages: {
                    'employee[name][firstName]': "Please enter First name",
                    'employee[name][lastName]': "Please enter Last name",
                    'employee[address]':"Please enter address",
                    'employee[contactNumber]':{required:"Please enter Contact number",numericOnly:"Numbers only"}
                }

            });
});
