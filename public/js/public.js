jQuery(document).ready(function(){
	jQuery(document).on('change', '.pcsponsor-filters select', function(){
		filterSponsorships();
	})

	jQuery(document).on('click', '.submit', function(){
		jQuery(this).parents('form').submit();
		return false;
	});

});

window.params = {};
var filterSponsorships = function() {
	// Get the values
	var query_string = [];
	if(jQuery('select[name=age]').val() != "0"){ query_string.push("age="+jQuery('select[name=age]').val()) }
	if(jQuery('select[name=gender]').val() != "0"){ query_string.push("gender="+jQuery('select[name=gender]').val()) }
	if(jQuery('select[name=location]').val() != "0"){ query_string.push("location="+jQuery('select[name=location]').val()) }

	location.href = "?"+query_string.join("&")
};
