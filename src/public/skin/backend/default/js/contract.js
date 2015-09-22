$(document).ready(function(){
	payment_method_LS = $('#box-body #payment_method').val();
	payment_method_QT = $('.qt #payment_method').val();

	if (payment_method_LS == 1) {
		addRequirement(['#card_number','#card_expired'], true);
	} else if (payment_method_LS == 2) {
		addRequirement(['#card_number','#card_expired'], false);
	}

	if (payment_method_QT == 1) {
		addRequirement(['#card_number','#card_expired', 
						'#card_type', '#card_company'], true);
	} else if (payment_method_QT == 2) {
		addRequirement(['#card_number','#card_expired', 
						'#card_type', '#card_company'], false);
	}
})

$('#payment_method').change(function(){
	if ($(this).val() == 1) {
		addRequirement(['#card_number','#card_expired'], true);
	} else {
		addRequirement(['#card_number','#card_expired'], false);
	}
})


$('.qt #payment_method').change(function(){
	if ($(this).val() == 1) {
		addRequirement(['#card_number','#card_expired', 
						'#card_type', '#card_company'], true);
	} else {
		addRequirement(['#card_number','#card_expired', 
						'#card_type', '#card_company'], false);
	}
})
// check value before submit
$('.btn-submit').on('click', function(){
	if ($('#payment_method').val() == 1) {
		card_number = addRequirement('#card_number');
		card_expired = addRequirement('#card_expired');
		card_type = addRequirement('#card_type');
		card_company = addRequirement('#card_company');
		
		return card_number&&card_expired&&card_type&&card_company;
	} 
	return true;
});
