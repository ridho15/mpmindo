<html>
<style>
	html {
		margin:150px 50px 50px;
	}
	body {
		font-family: 'Arial';
		font-size: 12px;
	}
	small {
		font-size: 11px;
		color: #555555;
	}
	.header,
	.footer {
		width: 100%;
		text-align: left;
		position: fixed;
	}
	.header {
		top: -135px;
	}
	.footer {
		bottom: 0px;
		font-size: 10px;
		color: #333;
		font-style: italic;
	}
	.pagenum:before {
		content: counter(page);
	}
	table {
  border-collapse: collapse;
  border-spacing: 0;
}

th {
  text-align: left;
}

.table {
  width: 100%;
  margin-bottom: 20px;
}

.table thead > tr > th,
.table tbody > tr > th,
.table tfoot > tr > th,
.table thead > tr > td,
.table tbody > tr > td,
.table tfoot > tr > td {
  padding: 8px;
  line-height: 1.428571429;
  vertical-align: top;
  border-top: 1px solid #dddddd;
}

.table thead > tr > th {
  vertical-align: bottom;
  border-bottom: 2px solid #dddddd;
  background-color: #efefef;
}

.table caption + thead tr:first-child th,
.table colgroup + thead tr:first-child th,
.table thead:first-child tr:first-child th,
.table caption + thead tr:first-child td,
.table colgroup + thead tr:first-child td,
.table thead:first-child tr:first-child td {
  border-top: 0;
}

.table tbody + tbody {
  border-top: 2px solid #dddddd;
}

.table .table {
  background-color: #ffffff;
}

.table-bordered {
  border: 1px solid #dddddd;
}

.table-bordered > thead > tr > th,
.table-bordered > tbody > tr > th,
.table-bordered > tfoot > tr > th,
.table-bordered > thead > tr > td,
.table-bordered > tbody > tr > td,
.table-bordered > tfoot > tr > td {
  border: 1px solid #dddddd;
}

.table-bordered > thead > tr > th,
.table-bordered > thead > tr > td {
  border-bottom-width: 2px;
}

.table > thead > tr > td.active,
.table > tbody > tr > td.active,
.table > tfoot > tr > td.active,
.table > thead > tr > th.active,
.table > tbody > tr > th.active,
.table > tfoot > tr > th.active,
.table > thead > tr.active > td,
.table > tbody > tr.active > td,
.table > tfoot > tr.active > td,
.table > thead > tr.active > th,
.table > tbody > tr.active > th,
.table > tfoot > tr.active > th {
  background-color: #f5f5f5;
}

.table > thead > tr > td.success,
.table > tbody > tr > td.success,
.table > tfoot > tr > td.success,
.table > thead > tr > th.success,
.table > tbody > tr > th.success,
.table > tfoot > tr > th.success,
.table > thead > tr.success > td,
.table > tbody > tr.success > td,
.table > tfoot > tr.success > td,
.table > thead > tr.success > th,
.table > tbody > tr.success > th,
.table > tfoot > tr.success > th {
  background-color: #dff0d8;
  border-color: #d6e9c6;
}

.table > thead > tr > td.danger,
.table > tbody > tr > td.danger,
.table > tfoot > tr > td.danger,
.table > thead > tr > th.danger,
.table > tbody > tr > th.danger,
.table > tfoot > tr > th.danger,
.table > thead > tr.danger > td,
.table > tbody > tr.danger > td,
.table > tfoot > tr.danger > td,
.table > thead > tr.danger > th,
.table > tbody > tr.danger > th,
.table > tfoot > tr.danger > th {
  background-color: #f2dede;
  border-color: #eed3d7;
}

.table-hover > tbody > tr > td.danger:hover,
.table-hover > tbody > tr > th.danger:hover,
.table-hover > tbody > tr.danger:hover > td {
  background-color: #ebcccc;
  border-color: #e6c1c7;
}

.table > thead > tr > td.warning,
.table > tbody > tr > td.warning,
.table > tfoot > tr > td.warning,
.table > thead > tr > th.warning,
.table > tbody > tr > th.warning,
.table > tfoot > tr > th.warning,
.table > thead > tr.warning > td,
.table > tbody > tr.warning > td,
.table > tfoot > tr.warning > td,
.table > thead > tr.warning > th,
.table > tbody > tr.warning > th,
.table > tfoot > tr.warning > th {
  background-color: #fcf8e3;
  border-color: #fbeed5;
}

.table-hover > tbody > tr > td.warning:hover,
.table-hover > tbody > tr > th.warning:hover,
.table-hover > tbody > tr.warning:hover > td {
  background-color: #faf2cc;
  border-color: #f8e5be;
}

@media (max-width: 768px) {
  .table-responsive {
    width: 100%;
    margin-bottom: 15px;
    overflow-x: scroll;
    overflow-y: hidden;
    border: 1px solid #dddddd;
  }
  .table-responsive > .table {
    margin-bottom: 0;
    background-color: #fff;
  }
  .table-responsive > .table > thead > tr > th,
  .table-responsive > .table > tbody > tr > th,
  .table-responsive > .table > tfoot > tr > th,
  .table-responsive > .table > thead > tr > td,
  .table-responsive > .table > tbody > tr > td,
  .table-responsive > .table > tfoot > tr > td {
    white-space: nowrap;
  }
  .table-responsive > .table-bordered {
    border: 0;
  }
  .table-responsive > .table-bordered > thead > tr > th:first-child,
  .table-responsive > .table-bordered > tbody > tr > th:first-child,
  .table-responsive > .table-bordered > tfoot > tr > th:first-child,
  .table-responsive > .table-bordered > thead > tr > td:first-child,
  .table-responsive > .table-bordered > tbody > tr > td:first-child,
  .table-responsive > .table-bordered > tfoot > tr > td:first-child {
    border-left: 0;
  }
  .table-responsive > .table-bordered > thead > tr > th:last-child,
  .table-responsive > .table-bordered > tbody > tr > th:last-child,
  .table-responsive > .table-bordered > tfoot > tr > th:last-child,
  .table-responsive > .table-bordered > thead > tr > td:last-child,
  .table-responsive > .table-bordered > tbody > tr > td:last-child,
  .table-responsive > .table-bordered > tfoot > tr > td:last-child {
    border-right: 0;
  }
  .table-responsive > .table-bordered > thead > tr:last-child > th,
  .table-responsive > .table-bordered > tbody > tr:last-child > th,
  .table-responsive > .table-bordered > tfoot > tr:last-child > th,
  .table-responsive > .table-bordered > thead > tr:last-child > td,
  .table-responsive > .table-bordered > tbody > tr:last-child > td,
  .table-responsive > .table-bordered > tfoot > tr:last-child > td {
    border-bottom: 0;
  }
}
</style>
<body>
<div class="header">
<div>
	<h3>ORDER PEMBELIAN(PO)</h3>
	<table class="table table-borderd">
				<tr>
					<td>Nomor</td>
					<td>: <?=$order_id?></td>
					<td rowspan="4" style="vertical-align:top; text-align:right;"><strong>CV. Alvamedia Teknologi</strong><br>
			Jl. Raya Cengkeh No.55 Malang<br>
			Telp. 08113405480</td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td>: <?=$date_added?></td>
					
				</tr>
				<tr>
					<td>Pemasok</td>
					<td>: <?=$name?></td>
					
				</tr>
				<tr>
					<td>Keterangan</td>
					<td>: <?=$comment?></td>
					
				</tr>
				<tr>
					<td>Halaman</td>
					<td>: <span class="pagenum"></span></td>
					
				</tr>
			</table>
</div>
</div>
<div>
	<div class="row-fluid">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th style="text-align:left;">#</th>
					<th style="text-align:left;">Nama Barang</th>
					<th style="text-align:right;">Qty</th>
					<th style="text-align:right;">Harga@</th>
					<th style="text-align:right;">Subtotal</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $num => $product) {?>
				<tr>
					<td><?=$num?></td>
					<td><?=$product['name']?></td>
					<td style="text-align:right;"><?=$product['quantity']?></td>
					<td style="text-align:right;"><?=$product['price']?></td>
					<td style="text-align:right;"><?=$product['total']?></td>
				</tr>
				<?php }?>
			</tbody>
			<tfoot>
				<?php foreach ($totals as $total) {?>
				<tr>
					<th style="text-align:right;" colspan="2"></th>
					<th style="text-align:right;"></th>
					<th style="text-align:right;"><?=$total['title']?></th>
					<th style="text-align:right;"><?=$total['text']?></th>
				</tr>
				<?php }?>
			</tfoot>
		</table>
		<div><i class="muted">Terbilang : <?=$total_spellout?></i></div>
		<table width="100%" cellspacing="0">
				<tr>
					<td style="text-align:center;width:30%;">Disetujui Oleh,<br><br><br><br><br></td>
					<td style="text-align:center;width:30%;"></td>
					<td style="text-align:center;width:30%;"></td>
					<td style="text-align:center;">Dibuat Oleh,<br><br><br><br><br></td>
				</tr>
				<tr>
					<td style="text-align:center;width:30%;"></td>
					<td></td>
					<td></td>
					<td style="text-align:center;width:30%;"></td>
				</tr>
			</table>
	</div>
</div>
<div class="footer">
<span>Terakhir diperbarui : <?=$date_modified?> WIB</span>
</div>
</div>
</body>
</html>