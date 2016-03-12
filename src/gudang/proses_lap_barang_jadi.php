<?php
// proses laporan jurnal barang jadi
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "jurnalBarang"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelJurnalBarang").dataTable();
	});
	</script>
	<?php

	// cek transaksi
	$sqlCekTransaksi = "SELECT COUNT(*) AS cek FROM tb_jurnal_barang_jadi";
	$hasilCek = mysql_query($sqlCekTransaksi);
	$dataCek = mysql_fetch_array($hasilCek);
	if($dataCek['cek'] == "0"){
		echo "Belum ada transaksi";
		exit;
	}

	$no = 0;

	?>
<h3 align="center">Jurnal Barang (Jadi)</h3>
<div class="thumbnail">
	<div class="caption"> 
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelJurnalBarang">
	<thead>
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Status</th>
			<th>Keterangan</th>
			<th>Tanggal</th>
		</tr>
	</thead>
	<tbody>
	<?php
	// lihat kode_barang, nama_barang, dan stok_awal
	$hasilJurnal = mysql_query("SELECT * FROM tb_jurnal_barang_jadi");
	while ($dataJurnal = mysql_fetch_array($hasilJurnal)) {
		$no++;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $dataJurnal['kode_barang']; ?></td>
			<td><?php echo $dataJurnal['nama_barang']; ?></td>
			<td><?php echo $dataJurnal['jumlah']." ".$dataJurnal['nama_satuan']; ?></td>
			<td><?php echo $dataJurnal['status']; ?></td>
			<td><?php echo $dataJurnal['keterangan']; ?></td>
			<td><?php echo $dataJurnal['tanggal']; ?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
	</tfoot>
</table>
	</div>
</div>
	<?php
}

elseif ($action == "autoTahun"){
	$sqlAutoTahun = "SELECT DISTINCT(YEAR(tanggal)) AS tahun FROM tb_jurnal_barang_jadi";
	$hasil = mysql_query($sqlAutoTahun);
	?>
	<option value="--">Tahun</option>
	<?php
	while($data = mysql_fetch_array($hasil)){
		$tahun = $data['tahun'];
		echo '<option value="'.$tahun.'">'.$tahun.'</option>';
	}
}

elseif($action == "filter"){
	$bulanA = $_POST['pBulanA'];
	$tahunA = $_POST['pTahunA'];
	$bulanB = $_POST['pBulanB'];
	$tahunB = $_POST['pTahunB'];

	$blnA = array("","January","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	$blnB = array("","January","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelFilterJurnalBarang").dataTable();
	});
	</script>
	<?php
	$no = 0;

	$hasilFilterJurnal = mysql_query("SELECT * FROM tb_jurnal_barang_jadi 
								WHERE 
								MONTH(tanggal) >= '$bulanA' AND YEAR(tanggal) >= '$tahunA'
								AND 
								MONTH(tanggal) <= '$bulanB' AND YEAR(tanggal) <= '$tahunB'");
	?>
<p align="center"><?php echo "Filter mulai <b>".$blnA[$bulanA]." ".$tahunA."</b> sampai <b>".$blnB[$bulanB]." ".$tahunB."</b>"; ?></p>
<br>
<div class="thumbnail">
	<div class="caption"> 
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelFilterJurnalBarang">
	<thead>
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Status</th>
			<th>Keterangan</th>
			<th>Tanggal</th>
		</tr>
	</thead>
	<tbody>
	<?php
	while ($dataFilterJurnal = mysql_fetch_array($hasilFilterJurnal)) {
		$no++;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $dataFilterJurnal['kode_barang']; ?></td>
			<td><?php echo $dataFilterJurnal['nama_barang']; ?></td>
			<td><?php echo $dataFilterJurnal['jumlah']." ".$dataFilterJurnal['nama_satuan']; ?></td>
			<td><?php echo $dataFilterJurnal['status']; ?></td>
			<td><?php echo $dataFilterJurnal['keterangan']; ?></td>
			<td><?php echo $dataFilterJurnal['tanggal']; ?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
	</tfoot>
</table>
	</div>
</div>
	<?php
}


?>