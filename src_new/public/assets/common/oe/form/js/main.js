"use strict";

(function ($) {
	
	$.fn.datepickerSet = function() {
		var $t = $(this)
			,format = $t.attr('data-format');
		var date = new Date();
		
		var rangeFlag = $t.attr('year-range');
		
		if (rangeFlag == true) {
			var startDate = $t.attr('start-date') ? $t.attr('start-date') :1900 ;
			var endDate = $t.attr('end-date') ? $t.attr('end-date') : date.getFullYear();
			
			$t.datepicker({
				autoclose: true,
				todayHighlight: true,
				dateFormat: format,
				yearRange: startDate+":"+endDate,
				changeYear: true,
				changeMonth: true,
				minDate : $t.attr('from-now') == true ? date : '-1000000', 
			});
		} else {
			$t.datepicker({
				autoclose: true,
				todayHighlight: true,
				dateFormat: format,
			    onSelect: function(selectedDate) {
			    	  	
			    }
			});
		}
				
	};
	
} (jQuery));


$(function() {
	
	$(document).on("focus", ".datepicker", function() {
		$(this).datepickerSet();
	});
	
});