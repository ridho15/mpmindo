<div class="card card-default">
	<div class="card-body"><h5 class="card-title">Ringkasan</h5>
	<table class="table">
		<?php foreach ($totals as $total) {?>
		<tr>
			<td class="text-right" style="padding-left:0px;"><?=$total['title']?></td>
			<td class="text-right" style="padding-left:0px;"><strong><?=$total['text']?></strong></td>
		</tr>
		<?php }?>
	</table>
	</div>
	<?php if ($shipping_address) {?>
	<div class="card-footer">
		<dl>
			<dt>Dikirim ke:</dt>
			<dd><?=$shipping_address?></dd>
		</dl>
	</div>
	<?php }?>
</div>