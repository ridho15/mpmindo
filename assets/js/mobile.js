$(function () {
	"use strict";

	//Activate tooltips
	$("[data-toggle='tooltip']").tooltip();

	$("[data-toggle='utility-menu']").click(function() {
		$(this).next().slideToggle(300);
		$(this).toggleClass('open');
		return false;
	});

	// Login Page Flipbox control
	$('#toFlip').click(function() {
		loginFlip();
		return false;
	});

	$('#noFlip').click(function() {
		loginFlip();
		return false;
	});

	// Navbar height : Using slimscroll for sidebar
	if ($('body').hasClass('fixed') || $('body').hasClass('only-sidebar')) {
		$('.sidebar').slimScroll({
			height: ($(window).height() - $(".main-header").height()) + "px",
			color: "rgba(0,0,0,0.8)",
			size: "3px"
		});
	}
	else {
		var docHeight = $(document).height();
		$('.main-sidebar').height(docHeight);
	}
});

// Sidenav prototypes
$.pushMenu = {
	activate: function (toggleBtn) {

	//Enable sidebar toggle
	$(toggleBtn).on('click', function (e) {
		e.preventDefault();

		//Enable sidebar push menu
		if ($(window).width() > (767)) {
			if ($("body").hasClass('sidebar-collapse')) {
				$("body").removeClass('sidebar-collapse').trigger('expanded.pushMenu');
			} else {
				$("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
			}
		}
		//Handle sidebar push menu for small screens
		else {
			if ($("body").hasClass('sidebar-open')) {
				$("body").removeClass('sidebar-open').removeClass('sidebar-collapse').trigger('collapsed.pushMenu');
			} else {
				$("body").addClass('sidebar-open').trigger('expanded.pushMenu');
			}
		}
		if ( $('body').hasClass('fixed') && $('body').hasClass('sidebar-mini') && $('body').hasClass('sidebar-collapse')) {
			$('.sidebar').css("overflow","visible");
			$('.main-sidebar').find(".slimScrollDiv").css("overflow","visible");
		}
		if ($('body').hasClass('only-sidebar')) {
			$('.sidebar').css("overflow","visible");
			$('.main-sidebar').find(".slimScrollDiv").css("overflow","visible");
		};
	});

	$(".content-wrapper").click(function () {
		//Enable hide menu when clicking on the content-wrapper on small screens
		if ($(window).width() <= (767) && $("body").hasClass("sidebar-open")) {
			$("body").removeClass('sidebar-open');
		}
	});
	}
};
$.tree = function (menu) {
  var _this = this;
  var animationSpeed = 200;
  $(document).on('click', menu + ' li a', function (e) {
	//Get the clicked link and the next element
	var $this = $(this);
	var checkElement = $this.next();

	//Check if the next element is a menu and is visible
	if ((checkElement.is('.treeview-menu')) && (checkElement.is(':visible'))) {
	  //Close the menu
	  checkElement.slideUp(animationSpeed, function () {
		checkElement.removeClass('menu-open');
		//Fix the layout in case the sidebar stretches over the height of the window
		//_this.layout.fix();
	  });
	  checkElement.parent("li").removeClass("active");
	}
	//If the menu is not visible
	else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
	  //Get the parent menu
	  var parent = $this.parents('ul').first();
	  //Close all open menus within the parent
	  var ul = parent.find('ul:visible').slideUp(animationSpeed);
	  //Remove the menu-open class from the parent
	  ul.removeClass('menu-open');
	  //Get the parent li
	  var parent_li = $this.parent("li");

	  //Open the target menu and add the menu-open class
	  checkElement.slideDown(animationSpeed, function () {
		//Add the class active to the parent li
		checkElement.addClass('menu-open');
		parent.find('li.active').removeClass('active');
		parent_li.addClass('active');
	  });
	}
	//if this isn't a link, prevent the page from being redirected
	if (checkElement.is('.treeview-menu')) {
	  e.preventDefault();
	}
  });
};
// Activate sidenav treemenu 
$.tree('.sidebar');
$.pushMenu.activate("[data-toggle='offcanvas']");

function loginFlip () {
	$('.login-box').toggleClass('flipped');
}

// Button Loading Plugin

$.fn.loadingBtn = function( options ) {

	var settings = $.extend({
			text: "Loading"
		}, options );
	this.html('<span class="btn-spinner"></span> ' + settings.text + '');
	this.addClass("disabled");
};

$.fn.loadingBtnComplete = function( options ) {
	var settings = $.extend({
			html: "submit"
		}, options );
	this.html(settings.html);
	this.removeClass("disabled");
};

var adminUrl = $('base').attr('href');

$(document).ready(function() {
	var query = String(document.location).split('?');
	var arrPath = query[0].split('/').splice(0, 6);
	$('.sidebar-menu li a').each(function(i, val) {
		if (arrPath.join('/') === this.href) {
			$(this).closest('ul').parent().addClass('active');
			$(this).closest('ul').parent().closest('ul').parent().addClass('active');
			$(this).closest('ul').parent().closest('ul').parent().closest('ul').parent().addClass('active');
			$(this).closest('li').addClass('active');
		}
	});
	
	if (typeof summernote !== 'undefined') {
		$('.summernote').summernote({
			height: 300,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['view', ['fullscreen', 'codeview']]
			],
		});
	}
	
	$(document).delegate('a[data-toggle=\'image\']', 'click', function(e) {
		e.preventDefault();

		$('.popover').popover('hide', function() {
			$('.popover').remove();
		});

		var element = this;

		$(element).popover({
			html: true,
			placement: 'right',
			trigger: 'manual',
			content: function() {
				return '<button type="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
			}
		});

		$(element).popover('show');

		$('#button-image').on('click', function() {
			$('#modal-image').remove();

			$.ajax({
				url: adminUrl + '/image_manager',
				data: '&target=' + $(element).parent().find('input').attr('id') + '&thumb=' + $(element).attr('id'),
				dataType: 'html',
				beforeSend: function() {
					$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-image').prop('disabled', true);
				},
				complete: function() {
					$('#button-image i').replaceWith('<i class="fa fa-pencil"></i>');
					$('#button-image').prop('disabled', false);
				},
				success: function(html) {
					$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
					$('#modal-image').modal('show');
				}
			});

			$(element).popover('hide', function() {
				$('.popover').remove();
			});
		});

		$('#button-clear').on('click', function() {
			$(element).find('img').attr('src', $(element).find('img').attr('data-placeholder'));
			$(element).parent().find('input').attr('value', '');
			$(element).popover('hide', function() {
				$('.popover').remove();
			});
			
			parentElement = $(element).parent().parent();
			
			if (parentElement.hasClass('additional-image')) {
				parentElement.remove();
			}
		});
	});
	
	$(document).delegate('[data-toggle=\'tooltip\']', 'click', function(e) {
		$('body > .tooltip').remove();
	});
});

var storeUrl = $('base').attr('href');

function getUrlPath() {
    query = String(document.location).split('?');
	
	if (query[0]) {
	    value = query[0].slice(storeUrl.length);
	    if (value.length > 0) {
	       return urlPath = value;
	    } else {
	       return urlPath = '';
	    }
	}
}

var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			success: function(json) {			
				if (json['redirect']) {
					location = json['redirect'];
				}
				
				if (json['success']) {
					$('#cart-block').load('cart/info #cart-count-ajax');
				}
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			success: function(json) {
				if (getUrlPath() == 'cart' || getUrlPath() == 'checkout') {
					location = 'cart';
				} else {
					$('#cart-block').load('cart/info #cart-count-ajax');
				}			
			}
		});			
	},
	'remove': function(key) {
		$.ajax({
			url: 'cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',			
			success: function(json) {
				if (getUrlPath() == 'cart' || getUrlPath() == 'checkout') {
					location = 'cart';
				} else {
					$('#cart-block').load('cart/info #cart-count-ajax');
				}
			}
		});			
	}
}

$("[data-toggle='popover']").popover();

$('.dropdown').on('show.bs.dropdown', function() {
	$('body').append('<div class="dropdown-backdrop in"></div>');
});

$('.dropdown').on('hide.bs.dropdown', function() {
	$('body .dropdown-backdrop').remove();
});

$('.dropdown-login').on('show.bs.dropdown', function() {
	$('.dropdown-login .dropdown-menu div').load('user/login/popup');
});

$('#cart-block').load('cart/info #cart-count-ajax');

$('.navbar-form input[name=\'term\']').typeahead({
	source: function(query, process) {
		$.ajax({
			url: storeUrl.replace('user', '')+'search/ajax',
			data: 'query=' + query,
			type: 'get',
			dataType: 'json',
			success: function(json) {
				process(json);
			}
		});
	},
	updater: function (item) {
		window.location = item.href;
		//return item.name.toLowerCase();
	},
	minLength: 1
});