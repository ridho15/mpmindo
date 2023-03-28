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

$('.ps-cart--mini').load(storeUrl + 'cart/info');
$('#cart-mobile').load(storeUrl + 'cart/info/mobile');

var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: storeUrl + 'cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			success: function(json) {			
				if (json['redirect']) {
					location = json['redirect'];
				}
				
				if (json['success']) {
					$('#notification').html('<div class="alert alert-success" style="margin:5px 0;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + json['success'] + '</div>');
					$('.ps-cart--mini').load(storeUrl + 'cart/info');
					$('#cart-mobile').load(storeUrl + 'cart/info/mobile');
					$('.alert').fadeIn('slow');
					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: storeUrl + 'cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			success: function(json) {
				if (getUrlPath() == 'cart' || getUrlPath() == 'checkout') {
					location = 'cart';
				} else {
					$('.ps-cart--mini').load(storeUrl + 'cart/info');
					$('#cart-mobile').load(storeUrl + 'cart/info/mobile');
				}			
			}
		});			
	},
	'remove': function(key) {
		$.ajax({
			url: storeUrl + 'cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',			
			success: function(json) {
				if (getUrlPath() == 'cart' || getUrlPath() == 'checkout') {
					location = 'cart';
				} else {
					$('.ps-cart--mini').load(storeUrl + 'cart/info');
					$('#cart-mobile').load(storeUrl + 'cart/info/mobile');
				}
			}
		});			
	}
}

function quickView(product_id) {
	$.ajax({
		url : storeUrl + 'product/quick_view',
		data: 'product_id='+product_id,
		dataType: 'html',
		success: function(html) {
			$('body').append(html);
			$('#product-quickview').modal('show');
			$('#product-quickview').on('hidden.bs.modal', function (e) {
				$('#product-quickview').remove();
			});
		}
	});
}