var adminUrl = $('base').attr('href');

$(document).ready(function() {
	$('.summernote').summernote({
		height: 300,
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'clear']],
			['fontname', ['fontname']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'image', 'video']],
			['view', ['fullscreen', 'codeview']]
		],
		buttons: {
	   		image: function() {
				var ui = $.summernote.ui;
				var button = ui.button({
					contents: '<i class="fa fa-image" />',
					tooltip: "Image Manager",
					click: function () {
						$(this).parents('.note-editor').find('.note-editable').focus();
						$('#modal-image').remove();
						$.ajax({
							url: adminUrl + '/tool/image_manager',
							dataType: 'html',
							success: function(html) {
								$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
								$('#modal-image').modal('show');
							}
						});						
					}
				});
				return button.render();
			}
		}
	});
	
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
				url: adminUrl + '/tool/image_manager',
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