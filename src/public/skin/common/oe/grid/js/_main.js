"use strict";

(function ($) {
	
	$.fn.paginate = function() {
		var $t = this
			,page = 1
			,$form = $t.closest(".oe-form")
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
			,$form = $t.closest(".oe-form")
			,$pSize = $form.find("input[name$='[pageSize]']");

		$pSize.val(pSize);
		$form.find("input[name$='[clearPaginator]']").val(1);
		$form.submitForm();
	};
	
	$.fn.clearFilter = function() {
		var $t = this 
			,$form = $t.closest(".oe-form")
			,$filtering = $form.find(".oe-filtering input[type='text'], .oe-filtering select");
		
		if($filtering.length) {
			$filtering.removeClass("oe-filtering");
			$filtering.val("");
			$form.submitForm();
		}
	};
	
	$.fn.sort = function() {
		var $t = this 
			,$form = $t.closest(".oe-form")
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
			,$form = $(this).closest(".oe-form");
		
		$ctn.children("input[name^='opt']").val(opt);
		$ctn.children(".oe-btn-copt").children("span").html(opt);
		
		if($ctn.children('.form-control').val()) {
			$form.submitForm();
		}
	};
	
	$.fn.checkAll = function() {
		var $t = $(this)
			,checked = false
			,$items = $t.parents('.oe-table').find('.check-item');
		
		if($t.checked) {
			checked = true;
		} else {
			checked = false;			
		}
		$items.each(function() {
			$t.checked = checked;
		});			
	};
	
	$.fn.datepickerSet = function() {
		var $t = $(this)
			,format = $t.attr('data-format')
			,$form = $(this).closest(".oe-form");
		
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
		var $t = $(this);
	};
	
	$.fn.submitForm = function() {
		var $t = this
			,isAjax = $t.hasClass('oe-form-ajax') ? true : false;
		
		$t.addClass('loading');
		
		if(isAjax) {
			var action = $t.attr('action');
			$.ajax({
				url: action,
				data: $t.serialize(),
				method: 'post',
				dataType: 'html'
			})
			.success(function(d) {
				var gContent = $t.find('.oe-grid-content');
				$(gContent).html(d);
			})
			.error(function(d) {
				alert("Load data from server error");
				console.log(d);				
			})
			.complete(function() {
				$t.removeClass('loading');
			});
		} else {
			$t.submit();
		}
	};
	
} (jQuery));


$(function() {
	$(document).on("click", ".oe-paginator .pagination li a", function() {
		$(this).paginate();
	});
	
	$(document).on("change", ".oe-pagesize select", function() {
		$(this).pagesize();
	});
	
	$(document).on("click", ".oe-btn-clear-filter", function() {
		$(this).clearFilter();
	});
	
	$(document).on("click", ".oe-sortable", function() {
		$(this).sort();
	});
	
	$(document).on("click", ".oe-filters .oe-btn-change-opt", function() {
		$(this).openOperator();
	});
	
	$(document).on("click", ".oe-filters .oe-opt", function() {
		$(this).changeOperator();
	});
	
	$(document).on("focus", ".oe-filters .datepicker", function() {
		$(this).datepickerSet();
	});
	
	$(document).on("click", ".oe-grid .checkall", function() {
		$(this).checkAll();
	});
	
	$(document).on('change', '.oe-filter select.form-control', function() {
		var $form = $(this).closest(".oe-form");
		$form.submitForm();
	});
	
	$(document).on('click', '.oe-btn-filter', function() {
		var $form = $(this).closest(".oe-form");
		$form.submitForm();
	});
	
	$(document).on('blur', '.oe-filter input.form-control:not(.datepicker)', function() {
		if($(this).val()) {
			var $form = $(this).closest(".oe-form");
			$form.submitForm();			
		}
	});
	
	$(document).on('change', '.oe-filter select.form-control', function() {
		var $form = $(this).closest(".oe-form");
		$form.submitForm();
	});
	
	$(document).on('click', '.oe-filter .clear-filter', function() {
		$(this).parent('.oe-filter').children('.form-control').val('');
		var $form = $(this).closest(".oe-form");
		$form.submitForm();
	});	
	
});