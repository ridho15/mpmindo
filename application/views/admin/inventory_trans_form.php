<?php $inventory_row = 0; ?>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
<script type="text/javascript">
	$('#form select').select2();
	$('#submit').bind('click', function() {
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'post',
			dataType: 'json',
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				
				if (json['warning']) {
					$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['warning']+'</div>');
				}
				
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\'], select[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\'], select[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
					}
				} else if (json['success']){
					$('#form-modal').modal('hide');
					$.notify({
						title: '<strong><?=lang('text_success')?></strong><br>',
						message: json['success'],
						icon: 'fa fa-check' 
					},{
						type: "success"
					});
				}
			}
		});
	});
	
	$('select[name=\'type\']').trigger('change');
	
	$('input[name=\'date_added\']').datepicker({language:'id', autoclose:true});
	
	function autoComplete(inventory_row) {
		$('input[name=\'inventories['+inventory_row+'][product]\']').typeahead({
			source: function(query, process) {
				$.ajax({
					url: "<?=admin_url('product/auto_complete')?>",
					data: 'filter_name=' + query ,
					type: 'get',
					dataType: 'json',
					success: function(json) {
						products = [];
						map = {};
						$.each(json, function(i, product) {
							map[product.name] = product;
							products.push(product.name);
						});
						process(products);
					}
				});
			},
			updater: function (item) {
				$(this).val(map[item].name);
				$('input[name=\'inventories['+inventory_row+'][product_id]\']').val(map[item].product_id);
				
				return map[item].name;
			},
			minLength: 1
		});
	}
	
	var inventory_row = "<?=$inventory_row?>";
	
	function addInventory() {
		html  = '<tr id="inventory-row'+inventory_row+'">';
		html += '<td><input type="text" autocomplete="off" name="inventories['+inventory_row+'][product]" value="" class="form-control"><input type="hidden" name="inventories['+inventory_row+'][product_id]" value=""></td>';
		html += '<td><input type="text" name="inventories['+inventory_row+'][quantity]" value="" class="form-control text-right"></td>';
		html += '<td class="text-right"><a onclick="$(\'#inventory-row'+inventory_row+'\').remove();" class="btn btn-danger btn-flat"><i class="fa fa-remove"></i></a></td>';
		html += '</tr>';
		
		$('#inventory-add').before(html);
		autoComplete(inventory_row);
		inventory_row++;
	}

	// $('#tabdetail').bind('click', function() {
	// 	$('#stockData').html('');
	// 	$.ajax({
	// 		url: "<?=admin_url('article/select_article')?>",
	// 		data: $('#form').serialize(),
	// 		type: 'post',
	// 		dataType: 'json',
	// 		success: function(json) {
	// 			if (json['warning']) {
	// 				$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['warning']+'</div>');
	// 			}
	// 			else {
	// 				if(json['content']) {
	// 					$('#stockData').html(json['content']);
	// 				}
	// 			}
	// 		}
	// 	});
	// });
</script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<div id="notification"></div>
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="inventory_trans_id" value="<?=$inventory_trans_id?>">
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('label_date')?></label>
					<div class="col-sm-3">
						<input type="text" name="date_added" value="<?=$date_added?>" class="form-control" data-date-format="dd-mm-yyyy" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('label_from')?></label>
					<div class="col-sm-9">
						<select name="warehouse_from" class="form-control">
							<?php foreach ($warehouse_froms as $key => $from) {?>
							<?php if ($key == $warehouse_from) {?>
								<option value="<?=$key?>" selected="selected"><?=$from?></option>
							<?php } else {?>
								<option value="<?=$key?>"><?=$from?></option>
							<?php }?>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('label_to')?></label>
					<div class="col-sm-9">
						<select name="warehouse_to" class="form-control">
							<?php foreach ($warehouse_tos as $key2 => $to) {?>
							<?php if ($key2 == $warehouse_to) {?>
								<option value="<?=$key2?>" selected="selected"><?=$to?></option>
							<?php } else {?>
								<option value="<?=$key2?>"><?=$to?></option>
							<?php }?>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('label_note')?></label>
					<div class="col-sm-9">
						<input type="text" name="description" value="<?=$description?>" class="form-control"/>
					</div>
				</div>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><?=lang('column_item')?></th>
							<th><?=lang('column_quantity')?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($inventories as $inventory) { ?>
						<?php if ($inventory['quantity'] > 0) {?>
						<tr id="inventory-row<?=$inventory_row?>">
							<td><input type="text" name="inventories[<?=$inventory_row?>][product]" value="<?=$inventory['product']?>" class="form-control"><input type="hidden" name="inventories[<?=$inventory_row?>][product_id]" value="<?=$inventory['product_id']?>" class="form-control"/></td>
							<td><input type="text" name="inventories[<?=$inventory_row?>][quantity]" value="<?=$inventory['quantity']?>" class="form-control text-right"></td>
							<td class="text-right"><a onclick="$('#inventory-row<?=$inventory_row?>').remove(); return false;" class="btn btn-danger btn-flat"><i class="fa fa-remove"></i></a></td>
						</tr>
						<script type="text/javascript">autoComplete(<?=$inventory_row?>);</script>
						<?php $inventory_row++; ?>
						<?php } ?>
						<?php } ?>
						<tr id="inventory-add">
							<td colspan="2"></td>
							<td class="text-right"><a onclick="addInventory();" class="btn btn-success btn-flat"><i class="fa fa-plus"></i></a></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>