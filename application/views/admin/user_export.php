<html>
	<head>
		<title>Customer Report</title>
		<link rel="stylesheet" href="<?=base_url('assets/css/print.css')?>">
	</head>
	<body>
		<table cellspacing="0">
			<thead>
				<tr>
					<td>ID</td>
					<td>Nama Lengkap</td>
					<td>Telephone</td>
					<td>Email</td>
					<td>Tanggal Daftar</td>
					<td>Saldo</td>
					<td>Status</td>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($reports as $report) {?>
				<tr>
					<td><?=$report['user_id']?></td>
					<td><?=$report['name']?></td>
					<td><?=$report['telephone']?></td>
					<td><?=$report['email']?></td>
					<td><?=$report['date_added']?></td>
					<td><?=$report['balance']?></td>
					<td><?=$report['status']?></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</body>
</html>