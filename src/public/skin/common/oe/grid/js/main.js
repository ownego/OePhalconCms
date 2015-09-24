"use strict";

(function ($) {
	
	$.fn.paginate = function() {
		var $t = this
			,page = 1
			,$form = $t.closest(".oe-grid-form")
			,$page = $form.find("input[name$='[page]']")
			,cPage = parseInt($page.val())
			,tPage = parseInt($form.find(".last").attr("data-page"));
		
		if($t.hasClass("item")) {
			page = $t.html();
		} 
		else if($t.hasClass("last")) {
			page = tPage;
		} 
		else if($t.hasClass("next")) {
			page = cPage + 1;
			if(page > tPage) {
				page = tPage;
			}
		} 
		else if($t.hasClass("prev")) {
			page = cPage - 1;
			if(page == 0) {
				page = 1;
			}
		}
		
		$page.val(page);		
		$form.submitForm();
	};
	
	$.fn.pagesize = function() {
		var $t = this 
			,pSize = $t.val()
			,$form = $t.closest(".oe-grid-form")
			,$pSize = $form.find("input[name$='[pageSize]']");

		$pSize.val(pSize);
		$form.find("input[name$='[clearPaginator]']").val(1);
		$form.submitForm();
	};
	
	$.fn.clearFilter = function() {
		var $t = this 
			,$form = $t.closest(".oe-grid-form")
			,$filtering = $form.find(".oe-filtering input[type='text'], .oe-filtering select");
		
		if($filtering.length) {
			$filtering.removeClass("oe-filtering");
			$filtering.val("");
			$form.find("input[name$='[clearPaginator]']").val(1);
			$form.submitForm();
		}
	};
	
	$.fn.sort = function() {
		var $t = this 
			,$form = $t.closest(".oe-grid-form")
			,order = $t.attr("data-order")
			,$order = $form.find("input[name$='[order]']")
			,$orderBy = $form.find("input[name$='[orderBy]']");
	
		if(order) {
			$order.val(order);
			$orderBy.val($orderBy.val() == 1 ? 2 : 1);
			$form.submitForm();
		}		
	};
		
	$.fn.changeOperator = function() {
		var $t = this 		
			,$ctn = $t.parents(".oe-filter")
			,opt = $t.attr('data-opt')
			,$form = $(this).closest(".oe-grid-form");
		
		$ctn.children("input[name^='opt']").val(opt);
		$ctn.children(".oe-btn-copt").children("span").html(opt);
		
		if($ctn.children('.form-control').val()) {
			$form.submitForm();
		}
	};
	
	$.fn.toggleCheckAll = function() {
		var $items = $(this).parents('.oe-table').find('.check-item'),
			check = $(this).is(':checked');

		$.each($items, function(index, item) {
			$(item).prop('checked', check);
		});
	};
	
	$.fn.datepickerGrid = function() {
		var $t = $(this)
			,format = $t.attr('data-format')
			,$form = $(this).closest(".oe-grid-form");
		
		$t.datepicker({
			autoclose: true,
			todayHighlight: true,
			dateFormat: format,
		    onSelect: function(selectedDate) {
		    	$form.submitForm();		    	
		    }
		});		
	};
	
	$.fn.exportData = function() {
		var $t = $(this)
			,ext = $t.attr('data-ext')
			,$form = $t.closest(".oe-grid-form");		
		console.log($t);
		$form.find('#export-ext').val(ext);
		$form.find('#export').val(1);
		$form.submit();
	};
	
	$.fn.submitForm = function(clearPaginator) {
		var $t = this
			,isAjax = $t.hasClass('oe-grid-form-ajax') ? true : false;
		
		$t.addClass('loading');
		
		if(clearPaginator) {
			$t.find("input[name$='[clearPaginator]']").val(1);
		}
		
		if(isAjax) {
			var action = $t.attr('action');
			$.ajax({
				url: action,
				data: $t.serialize(),
				method: 'post',
				dataType: 'html'
			})
			.success(function(d) {
				$t.html(d);
				$t.trigger('update');
			})
			.error(function(d) {
				alert("Load data from server error");
				console.log(d);				
			})
			.complete(function() {
				$t.removeClass('loading');
				$t.find('.checkall').toggleCheckAll();
			});
		} else {
			$t.submit();
		}
	};
	
} (jQuery));


$(function() {
	$(document).on("click", ".oe-paginator a", function() {
		$(this).paginate();
	})
	.on("change", ".oe-pagesize select", function() {
		$(this).pagesize();
	})
	.on("click", ".oe-btn-clear-filter", function() {
		$(this).clearFilter();
	})
	.on("click", ".oe-sortable", function() {
		$(this).sort();
	})
	.on("click", ".oe-filters .oe-btn-change-opt", function() {
		$(this).openOperator();
	})
	.on("click", ".oe-filters .oe-opt", function() {
		$(this).changeOperator();
	})
	.on("focus", ".oe-filters .datepicker", function() {
		$(this).datepickerGrid();
	})
	.on("change", ".oe-grid .checkall", function() {
		$(this).toggleCheckAll();
	})
	.on("click", ".oe-grid-export .export-ext", function() {
		$(this).exportData();
	})
	.on('click', '.oe-btn-filter', function() {
		var $form = $(this).closest(".oe-grid-form");
		$form.submitForm(true);
	})
	.on('blur', '.oe-filter input.form-control:not(.datepicker)', function() {
		if($(this).val()) {
			var $form = $(this).closest(".oe-grid-form");
			$form.submitForm(true);
		}
	})
	.on('change', '.oe-filter select.form-control', function() {
		var $form = $(this).closest(".oe-grid-form");
		$form.submitForm(true);
	})
	.on('click', '.oe-filter .clear-filter', function() {
		$(this).parent('.oe-filter').children('.form-control').val('');
		var $form = $(this).closest(".oe-grid-form");
		$form.submitForm(true);
	});	
	
});