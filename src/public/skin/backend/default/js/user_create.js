var getAgency = function(id) {
	$.ajax({
		type: "POST",
		url: baseUri+'/backend/user/listAgency',
		method : 'post',
		data: {
				supervisor_id : id, 
			},
		success:function(data) {
			$('#agency_id').children().remove();
			$('#agency_id').append(data);
			getBusinessman($('#agency_id').val());
		}
	})
}

var getBusinessman = function(id) {
	$.ajax({
		type: "POST",
		url: baseUri+'/backend/user/listBusinessman',
		method : 'post',
		data: {
				agency_id : id, 
			},
		success:function(data) {
			$('#businessman_id').children().remove();
			$('#businessman_id').append(data);
		}
	})
}
$('#supervisor_id').change(function(){
	getAgency($(this).val());
});

$('#agency_id').change(function(){
	getBusinessman($(this).val());
});


$(document).ready(function(){
	document_type = $('#document_type').val();
	payment_method_LS = $('#planInfo2 #payment_method').val();
	payment_method_QT = $('#formContractDetail #payment_method').val();
	if (document_type == 1) { //passport
		addRequirement(['#passport_no'],true);
		addRequirement(['#driver_license', '#expire_date'],false);
	} else if (document_type == 2){
		addRequirement(['#passport_no'],false);
		addRequirement(['#driver_license', '#expire_date'],true);
	}

	if (payment_method_LS == 1) {
		addRequirement(['#card_number','#card_expire'], true);
	} else if (payment_method_LS == 2) {
		addRequirement(['#card_number','#card_expire'], false);
	}

	if (payment_method_QT == 1) {
		addRequirement(['#card_number','#card_expired', '#card_type',
							'#card_company'], true);
	} else if (payment_method_QT == 2) {
		addRequirement(['#card_number','#card_expired', '#card_type',
							'#card_company'], false);
	}

	if ($('#share_name').val() == 1) {
		$('.share-name').css('display','block');
	} else if ($('#formUser #share_name').val() == 0) {
		$('.share-name').css('display','none');
	}	
})

// life style step1
$('#formInfo #document_type').change(function(){
	var val = $(this).val();
	if (val ==1 ){ 
		addRequirement(['#passport_no'],true);
		addRequirement(['#driver_license', '#expire_date'],false);
	} else {
		addRequirement(['#passport_no'],false);
		addRequirement(['#driver_license', '#expire_date'],true);
	}
});

$('#planInfo2 #payment_method').change(function(){
	var val = $(this).val();
	
	if (val == 1) {
		$('#card_number, #card_expire').parent().css('display','block');
	} else {
		$('#card_number, #card_expire').parent().css('display','none');
	}
})

$('#formUser #share_name').change(function(){
	if ($(this).val() == 1) {
		$('.share-name').css('display','block');
	} else {
		$('.share-name').css('display','none');
	}
})

// $('.btn-save').on('click', function(){
	
// 	var emailExisted = true;
	
// 	if ($('#formInfo').html()) {
// 		if ($('#email').parent().find('.message-box').html())
// 			emailExisted = false;
// 		else 
// 			emailExisted = true;	
// 	}

// 	return emailExisted;
// });

// check mail exist

// $('#email').keyup(function(){
// 	var __self = this;
// 	$.ajax({
// 		type: "POST",
// 		url: baseUri+'/backend/user/checkEmailExist',
// 		method : 'post',
// 		data: {
// 				email : $(this).val().trim(), 
// 			},
// 		success:function(data) {
// 			if (data == "true") {
// 				if ($(__self).parent().find('.message-box').length == 0) {
// 					$(__self).parent().append("<div class='message-box'>"+
// 						"<span class='message-error'> Email registed</span></div>");
// 				}	
// 			} else {
// 				$(__self).parent().find('.message-box').remove();
// 			}
// 		}
// 	})
// });