$(function() {
	
	$('.commission-qunatum .commission-round').each(function() {
		var $tbody = $(this).find('.oe-grid table tbody');
		
		var ratio = 'USD';
		var sct = total = 0;
		
		$tbody.find('.sct').each(function() {
			sct += parseInt($(this).html());
		});
		
		$tbody.find('.total').each(function() {
			total += parseInt($(this).html());
		});
		
		var html = '<tr><td colspan="13"></td>';
		html += '<td class="text-center">'+ sct +'</td>';
		html += '<td colspan="2"></td>';
		html += '<td class="text-center">'+ ratio +'</td>';
		html +=	'<td class="text-center">'+ total +'</td>';
		
		$tbody.append(html);		
	});
	
	var addQuantumGridFooter = function() {
		var $quantumCommisionTmp = $('#grid-commissionQuantumTmp').find('.oe-grid table tbody:last-child');
		var sct = total = 0;
		
		$quantumCommisionTmp.find('.sct').each(function() {
			sct += parseInt($(this).html());
		});
		$quantumCommisionTmp.find('.total').each(function() {
			total += parseInt($(this).html());
		});
		
		var html = '<tr class="oe-grid-footer commission-quantum-tmp">';
		html += '<td colspan="13"></td>';
		html += '<td class="text-center has-content">'+ sct +'</td>';
		html += '<td colspan="4"></td>';
		html +=	'<td class="text-center has-content">'+ total +'</td>';
		html += '<td></td>';
		
		$quantumCommisionTmp.append(html);				
	};
	addQuantumGridFooter();
	$(document).on('update', '.oe-grid-form-ajax', function() {
		addQuantumGridFooter();				
	});
	
	$('.btn-submit').on('click', function() {
		$form = $(this).parents('form');
		if(confirm($(this).attr('data-confirm'))) {
			$form.submit();
		}
	});
	
});