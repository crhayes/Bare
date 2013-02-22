$(function() {
	// Toggle input values on and off when clicking on or off the inputs.
	$('input:not([type=submit])').live('focus', function() {
		if ($(this).val().trim() == $(this)[0].defaultValue ) {
			$(this).val('');
		}
	});
	$('input:not([type=submit])').live('blur', function() {
		if ($(this).val().trim() == '' ) {
			$(this).val($(this)[0].defaultValue);
		}
	});	
});