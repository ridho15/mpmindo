<div class="card" style="border:1px solid #ddd;">
	<h4>Ringkasan</h4>
	<table class="table">
		<?php foreach ($totals as $total) {?>
		<tr>
			<td class="text-right" style="padding-left:0px;"><?=$total['title']?></td>
			<td class="text-right" style="padding-left:0px;"><strong><?=$total['text']?></strong></td>
		</tr>
		<?php }?>
	</table>
	<div style="border:1px dashed #ccc; background:#f5f5f5; padding:15px;">
		<dl> 
			<?php if ($shipping_address) {?>
			<dt>Dikirim ke:</dt>
			<dd><?=$shipping_address?></dd>
			<?php }?>
		</dl>
	</div>
</div>