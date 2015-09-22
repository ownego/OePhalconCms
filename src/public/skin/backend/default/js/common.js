"use strict"

$(function() {
	
	$(document).on('click', '.navbar-static-top .navbar-btn', function() {
		var $leftbar = $('.wrapper .left-side');
		var collapsed = $leftbar.hasClass('collapse-left') ? 1 : 0;
		$.post(baseUri + '/backend/index/setLeftbarCollapse', {collapsed:collapsed});
	});
	
	$('#contract_years,#monthly_amount').on('keyup focusout',function(){
		var year = $('#contract_years').val();
		var month = $('#monthly_amount').val();
		
		var total = 0;
		var withdraw = 0;
		
		if (isNaN(year) || isNaN(month)) {
			total = 0;
			withdraw = 0;
		}
		else {
			total =year*month*12;
			withdraw = 24*month;
		}
		
		$('#total').val(total);
		$('#first_withdraw').val(withdraw);
	});


	// Plan content ad create QT 
	$('#formContractDetail #payment_method').change(function(){
		if ($(this).val() == 2) { // bankstransfer
			$('#card_type, #card_company, #card_number, #card_expired').parent().css('display','none');
			$('#card_type, #card_company, #card_number, #card_expired').val('');
		} else { //credit card
			$('#card_type, #card_company, #card_number, #card_expired').parent().css('display','block');
		}
	})

	// next step confirm at contract view
	var nextStepConfirm = function(){
		var txt;
		var flagConfirm = confirm("Do you want to continue?");
		
		if (flagConfirm == false) {
			$('#nextStepForm').on('submit', function(e) {
				return false;
			});
		}
	}

	var addRequirement = function(selector, flag) {
		selector.forEach(function(val){

			if (flag == true) {
				$(val).parent().css('display','block');

				if ($(val).parent().find('.required').length == 0)
					$(val).parent().find('label').append("<i class='required'> * </i>");	
			} else {
				$(val).parent().css('display','none');			
			}
			
		})
	}


	// system
	$('.acltree input[type=checkbox]').on('click', function(event){
		if($(this).is(":checked"))
			$(this).parent().find('input').prop('checked', true);
		else
			$(this).parent().find('input').prop('checked', false);
	});

	$('.acltree .fa').on('click', function(event){
		$(this).parent().find('>ul').toggle();
		if($(this).parent().find('>ul').is(':visible')){
			$(this).removeClass().addClass('fa icon-eblow-minus');
		} else {
			$(this).removeClass().addClass('fa icon-eblow-plus');
		}
	});

	// add new payment period row
	$('#formPaymentPeriodBtn button').on('click', function(){
		if ($('#status option:selected').val() !=8) {
			alert("State contract must be release policy");
			return false;
		}
		var number = $('.tempGroupFromDb').length;
		var rowData = $('#formPaymentPeriodBaseData').html();
		rowData = rowData.replace(/number1/g,number+$('#formPaymentPeriod .form-groups').length);
		$('#formPaymentPeriod').append(rowData);
	});
	
	
	/**
	 * Submit grid form to another action
	 */
	$(document).on('click', '.btn-grid-submit', function() {
		var action = baseUri + $(this).attr('data-action'),
				grid = $(this).attr('data-grid'),
				$form = $(grid).find('.oe-grid-form'),
				formAction = $form.attr('action'),
				formMethod = $form.attr('action');
		
		$('.check-item').each(function(e) {
			// Check selected is not null here
		});
		
		$form.attr('method', 'GET');
		$form.attr('action', action);
		$form.submit();
		$form.attr('action', formAction);
		$form.attr('method', formMethod);
	});
	
	$(document).on('click', '.form-multiple-payment-period .exclude', function() {
			$(this).parents('tr').remove();
	});
	
});