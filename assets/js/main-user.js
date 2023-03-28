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
					$('.dropdown-toggle-cart').dropdown('toggle');
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
					$('.dropdown-toggle-cart').dropdown('toggle');
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
					$('.dropdown-toggle-cart').dropdown('toggle');
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

$('#cart-block').on('show.bs.dropdown', function () {
	$('#cart-block .cart-content').load('cart/info #cart-content-ajax');
	$('#cart-block .cart-count').load('cart/info #cart-count-ajax');
});

$('#cart-block .cart-count').load('cart/info #cart-count-ajax');

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