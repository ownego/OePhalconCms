$(function() {
	
	/**
	 * Set sidebar toggle collapsed
	 */
	$(document).on('click', '.navbar-static-top .sidebar-toggle', function() {
		var collapsed = $('body').hasClass('sidebar-collapse') ? 1 : 0;
		$.post(baseUri + '/backend/index/setLeftbarCollapse', {collapsed:collapsed});
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
	
});