<div class="page-title">
	<div>
		<h1><?=lang('heading_title')?></h1>
		<p><?=lang('heading_subtitle')?></p>
	</div>
</div>
<section class="content">
	<div class="card">
		<div class="card-body">
			<div class="form-action">
				<a onclick="addForm(0);" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
				<a onclick="repair();" class="btn btn-info"><i class="fa fa-refresh"></i> Repair</a>
				<a id="btn-check-all" class="btn btn-default"><i class="fa fa-check-square-o"></i> Check All</a>
				<a id="btn-uncheck-all" class="btn btn-default"><i class="fa fa-square-o"></i> Unheck All</a>
				<a onclick="delRecord();" class="btn btn-default"><i class="fa fa-trash"></i> <?=lang('button_remove')?></a>
				<!-- <a onclick="synch();" class="btn btn-info"><i class="fa fa-download"></i> Copy Dari Sistem Inventory</a> -->
			</div>
			<div id="treeview"></div>
			<?=form_open(admin_url('category/delete'), 'id="checkable-output"')?>	
			</form>
		</div>
	</div>
</section>
<script type="text/javascript" charset="utf8" src="<?=base_url('assets/js/bootstrap-treeview.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var defaultData = <?=$categories?>;		
		
		var $checkableTree = $('#treeview').treeview({
			levels: 1,
			data: defaultData,
			showIcon: false,
			showCheckbox: true,
			showTags: true,
			onNodeChecked: function(event, node) {
				$('#checkable-output').prepend('<input type="hidden" name="category_id['+node.category_id+']" value="'+node.category_id+'">');
			},
			onNodeUnchecked: function (event, node) {
				$('input[name="category_id['+node.category_id+']"]').remove();
			}
		});

		$('#btn-check-all').on('click', function (e) {
			$checkableTree.treeview('checkAll', { silent: $('#chk-check-silent').is(':checked') });
		});

		$('#btn-uncheck-all').on('click', function (e) {
			$checkableTree.treeview('uncheckAll', { silent: $('#chk-check-silent').is(':checked') });
		});
	});
	
	function delRecord() {
		swal({
			title: "<?=lang('text_confirm')?>",
			text: "<?=lang('text_delete_confirm')?>",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "<?=lang('text_yes')?>",
			cancelButtonText: "<?=lang('text_no')?>",
			closeOnConfirm: false,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('category/delete')?>',
					type : 'post',
					data: $('#checkable-output').serialize(),
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("<?=lang('text_success')?>", json['success'], 'success');
							location.reload();
						} else if (json['error']) {
							swal("<?=lang('text_error')?>", json['error'], 'error');
						} else if (json['redirect']) {
							window.location = json['redirect'];
						}
					}
				});
			}
		});
	}
	
	function editForm(category_id) {
		$.ajax({
			url : "<?=admin_url('category/edit')?>",
			data: 'category_id='+category_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$.notify({
						title: '<strong><?=lang('text_error')?></strong><br>',
						message: json['error'],
						icon: 'fa fa-info' 
					},{
						type: "danger"
					});
				} else if (json['content']) {
					$('body').append('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
					$('#form-modal').on('hidden.bs.modal', function (e) {
						$('#form-modal').remove();
						location.reload();
					});	
				}
			}
		});
	}
	
	function addForm(parent_id) {
		$.ajax({
			url : "<?=admin_url('category/create')?>",
			data: 'parent_id='+parent_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$.notify({
						title: '<strong><?=lang('text_error')?></strong><br>',
						message: json['error'],
						icon: 'fa fa-info' 
					},{
						type: "danger"
					});
				} else if (json['content']) {
					$('body').append('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
					 $("[name='active']").click();
					$('#form-modal').on('hidden.bs.modal', function (e) {
						$('#form-modal').remove();
						location.reload();
					});	
				}
			}
		});
	}
	
	function repair() {
		$.ajax({
			url : "<?=admin_url('category/repair')?>",
			type : 'post',
			dataType: 'json',
			success: function(json) {
				if (json['success']) {
					$.notify({
						title: '<strong><?=lang('text_success')?></strong><br>',
						message: json['success'],
						icon: 'fa fa-check' 
					},{
						type: "success"
					});
				} else if (json['error']) {
					$.notify({
						title: '<strong><?=lang('text_error')?></strong><br>',
						message: json['error'],
						icon: 'fa fa-info' 
					},{
						type: "danger"
					});
				} else if (json['redirect']) {
					window.location = json['redirect'];
				}
				
				location.reload();
			}
		});
	}
	
	function synch() {
		swal({
			title: "<?=lang('text_confirm')?>",
			text: "Seluruh data nama kategori akan diset ulang menyesuaikan dengan data kategori di sistem inventory.",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "<?=lang('text_yes')?>",
			cancelButtonText: "<?=lang('text_no')?>",
			closeOnConfirm: false,
			closeOnCancel: true,
			showLoaderOnConfirm: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : "<?=admin_url('category/synch')?>",
					type : 'post',
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("<?=lang('text_success')?>", json['success'], 'success');
						} else if (json['error']) {
							swal("<?=lang('text_error')?>", json['error'], 'error');
						} else if (json['redirect']) {
							window.location = json['redirect'];
						}
						
						location.reload();
					}
				});
			}
		});
	}
</script>