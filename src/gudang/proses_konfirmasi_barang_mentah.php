<?php
// proses konfirmasi barang mentah
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "listPermintaanBarang"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListPermintaanBarang").dataTable();
	});
	</script>
	<?php
	$hasilPermintaanBarang = mysql_query("SELECT * FROM tb_permintaan_produksi WHERE status_permintaan = 'Menunggu'");
	?>
<h3>List Permintaan Barang</h3>
<div class="thumbnail">
	<div class="caption">
<table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListPermintaanBarang">
	<thead>
		<tr>
			<th width="7%">No.</th>
			<th>No. SPI</th>
			<th>Tanggal</th>
			<th>Pengirim</th>
			<th>Option</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$no = 0;
	while ($data = mysql_fetch_array($hasilPermintaanBarang)) {
		$no++;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $data['no_spi']; ?></td>
			<td><?php echo $data['tanggal']; ?></td>
			<td><?php echo $data['pengirim']; ?></td>
			<td><button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatPermintaan('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span> Lihat</button></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
	</div>
</div>
	<?php
}

elseif ($action == "lihatPermintaan") {
	$kode = $_GET['pKode'];
	$hasilLihat1 = mysql_query("SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode'");
	$data1 = mysql_fetch_array($hasilLihat1);
	?>
	<table class="table">
		<tr>
			<td width="70%">&nbsp;</td>
			<th>No. SPI</th>
			<td>:</td>
			<td id="idNoSPI"><?php echo $data1['no_spi']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Pengirim</th>
			<td>:</td>
			<td><?php echo $data1['pengirim']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Tanggal</th>
			<td>:</td>
			<td><?php echo $data1['tanggal']; ?></td>
		</tr>
	</table>
	<?php
	$hasilLihat2 = mysql_query("SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$kode'");
	?>
	<table class="table">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Option</th>
		</tr>
	<?php
	$a = 0;
	while($data2 = mysql_fetch_array($hasilLihat2)){
		$a++;
		?>
		<tr>
			<td id="idPermintaanKe<?php echo $a; ?>"><?php echo $data2['permintaan_ke']; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah_satuan']." ".$data2['jenis_satuan']; ?></td>
			<td>
				<button id="btnKonfirmasi<?php echo $a; ?>" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> Konfirmasi</button> 
				<span id="idLabelSukses<?php echo $a; ?>" class="label label-success"><span class="glyphicon glyphicon-ok"></span> Terkonfirmasi</span>
				<span class="cek">0</span>
			</td>
		</tr>

		<script type="text/javascript">
		$(document).ready(function(){
			var ax = "<?php echo $a; ?>";
			// hide label
			$("#idLabelSukses"+ax).hide();
			$(".cek").hide();

			// button terkonfirmasi di klik
			$("#btnKonfirmasi"+ax).click(function(){
				var vNoSPI = $("#idNoSPI").html();
				var vPermintaanKe = $("#idPermintaanKe"+ax).html();
				$.ajax({
					type: "POST", 
					url: "../src/gudang/proses_konfirmasi_barang_mentah.php?action=konfirmasi",
					data:{
						pNoSPI:vNoSPI,
						pPermintaanKe:vPermintaanKe
					},
					cache: false,
					success: function(h){
						var hasilKonfirmasi = $.trim(h);
						if(hasilKonfirmasi == 'sukses'){
							$("#btnKonfirmasi"+ax).hide();
							$("#idLabelSukses"+ax).show();
						}
						else{
							alert(hasilKonfirmasi);
						}
					}
				});
				
			});
		});
		</script>
		<?php
	}
	?>
	</table>
	<?php
}

elseif($action == "listTerkonfirmasi"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListTerkonfirmasi").dataTable();
	});
	</script>
	<?php
	$hasilTerkonfirmasi = mysql_query("SELECT * FROM tb_permintaan_produksi WHERE status_permintaan = 'Terkonfirmasi'");
	?>
<h3>List Barang Terkonfirmasi</h3>
<div class="thumbnail">
	<div class="caption">
<table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListTerkonfirmasi">
	<thead>
		<tr>
			<th width="7%">No.</th>
			<th>No. SPI</th>
			<th>Tanggal</th>
			<th>Pengirim</th>
			<th>Option</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$no = 0;
	while ($data = mysql_fetch_array($hasilTerkonfirmasi)) {
		$no++;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $data['no_spi']; ?></td>
			<td><?php echo $data['tanggal']; ?></td>
			<td><?php echo $data['pengirim']; ?></td>
			<td>
				<button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatTerkonfirmasi('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span> Lihat</button>
				<button type="button" title="Cetak" class="btn btn-default" onclick="javascript:printTerkonfirmasi('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-print"></span> Cetak</button>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
	</div>
</div>
	<?php
}

elseif ($action == "konfirmasi") {
	$kode = $_POST['pNoSPI'];
	$permintaanKe = $_POST['pPermintaanKe'];
	$hasilKonfirmasi = mysql_query("UPDATE tb_permintaan_produksi_detail SET
									status_permintaan = 'Terkonfirmasi'
									WHERE no_spi = '$kode' AND permintaan_ke = '$permintaanKe'");
	if($hasilKonfirmasi){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
}

elseif ($action == "konfirmasiSelesai") {
	$kode = $_GET['pNoSPI'];
	$hasilSelesai = mysql_query("UPDATE tb_permintaan_produksi SET
								status_permintaan = 'Terkonfirmasi'
								WHERE no_spi = '$kode'");

	// ambil data utama
	$ambilData1 = mysql_query("SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode' AND status_permintaan = 'Terkonfirmasi'");
	$dataRecord1 = mysql_fetch_array($ambilData1);

	// ambil data detail dan input jurnal
	$ambilData2 = mysql_query("SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$kode' AND status_permintaan = 'Terkonfirmasi'");
	while ($dataRecord2 = mysql_fetch_array($ambilData2)) {
		$inputJurnal = mysql_query("INSERT INTO tb_jurnal_transaksi VALUES ('', '$dataRecord1[no_spi]', '$dataRecord1[pengirim]', 'Barang Keluar', '$dataRecord1[tanggal]', '$dataRecord2[kode_barang]', '$dataRecord2[nama_barang]', '$dataRecord2[jumlah_satuan]', '$dataRecord2[jenis_satuan]', '$dataRecord2[jenis_satuan]', '$dataRecord2[isi]', '$dataRecord2[total_satuan]', 'Pengeluaran Internal', '$dataRecord2[permintaan_ke]')") or die(mysql_error());	
	}

	echo "Konfirmasi Selesai";
}

elseif ($action == "konfirmasiCancel") {
	$kode = $_GET['pNoSPI'];
	$hasilSelesai = mysql_query("UPDATE tb_permintaan_produksi_detail SET
								status_permintaan = 'Menunggu'
								WHERE no_spi = '$kode'");
	
	echo "Konfirmasi Dibatalkan";
}

elseif ($action == "printTerkonfirmasi") {
	$kode = $_GET['kode'];
	$sql1 = "SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode' AND status_permintaan = 'Terkonfirmasi'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
	<style>
	table
	{
	border-collapse:collapse;
	}
	table, td, th
	{
	border:0px solid black;
	}
	</style>
	<table class = "table" width="100%">
		<tr>
			<td>PT ONGKOWIJOYO</td>
			<td>&nbsp;</td>
			<td align="right">Jl.Gadang Selatan No.22 Malang, Jawa Timur 653149</td>
		</tr>
		<tr>
			<td>SURAT PENGANTAR INTERN</td>
			<td>&nbsp;</td>
			<td align="right">Telp:(0341)808181, Fax:(0341)808585 </td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;<td>
		</tr>
	</table>
	<table class = "table" width="70%" align="right" >
		<tr>
			<td width="10%">&nbsp;</td>
			<th align="left">No.SPI</th>
			<td id="kdKeluar">:&nbsp;<?php echo $data1['no_spi']; ?></td>
		</tr>
		<tr>
			<td width="10%">&nbsp;</td>
			<th><hr></th>
			<td><hr></td>
		</tr>
		<tr>
			<td width="50%">&nbsp;</td>
			<th align="left">Tanggal </th>
			<td>:&nbsp;<?php echo $data1['tanggal']; ?></td>
</tr>
	</table>
	<br>
	Kepada Yth. <b> <?php echo $data1['pengirim']; ?> </b>
	<br>
	<br>
	<br>
	Bersama Ini kami kirimkan bahan - bahan sebagai berikut :
	<hr>
	<?php
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$kode' AND status_permintaan = 'Terkonfirmasi'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table" width="100%">
		<tr>
			<td><b>No</b></td>
			<td><b>Kode Barang</b></td>
			<td><b>Nama Barang</b></td>
			<td><b>Jumlah Satuan</b></td>
			<td><b>Jenis Satuan</b></td>
			<td><b>Isi</b></td>
			<td><b>Total Satuan</b></td>
</tr>
<tr>
	<td> <hr></td>
	<td><hr></td>
	<td> <hr></td>
	<td><hr></td>
	<td> <hr></td>
	<td><hr></td>
	<td> <hr></td>
	<td> <hr></td>
</tr>
	<?php
	while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td><?php echo $data2['permintaan_ke']; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah_satuan']; ?></td>
			<td><?php echo $data2['jenis_satuan']; ?></td>
			<td><?php echo $data2['isi']; ?></td>
			<td><?php echo $data2['total_satuan']; ?></td>
		</tr>	
		<tr>
	<td> <hr></td>
	<td><hr></td>
	<td> <hr></td>
	<td><hr></td>
	<td> <hr></td>
	<td><hr></td>
	<td> <hr></td>
	<td> <hr></td>
	</tr>
		<?php
	}
	?>
	</table>
	<?php
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode' AND status_permintaan = 'Terkonfirmasi'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table" width="100%">
		<tr>
			<td width="20%"><b>Petugas Administrasi</b></td>
			<td width="20%"><b>Penerima</b></td>
			<td width="20%"><b>Pengirim</b></td>
			<td width="20%"><b>Staf</b></td>
		</tr>
	<?php
	while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td width="20%"><br><br><br><br><br><br></td>
			<td width="20%"><br><br><br><br><br><br></td>
			<td width="20%"><br><br><br><br><br><br></td>
			<td width="20%"><br><br><br><br><br><br></td>
		</tr>	
		<tr>
			<td width="20%"><u><b><?php echo $data2['admin']; ?></b></u></td>
			<td width="20%"><u><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></u></td>
			<td width="20%"><u><b><?php echo $data2['pengirim']; ?></b></u></td>
			<td width="20%"><u><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></u></td>
		</tr>	
		<?php
	}
	?>
	</table>
	<script type="text/javascript">
	window.print();
	</script>
	<?php
}

elseif ($action == "lihatTerkonfirmasi") {
	$kode = $_GET['pKode'];
	$hasilLihat1 = mysql_query("SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode'");
	$data1 = mysql_fetch_array($hasilLihat1);
	?>
	<table class="table">
		<tr>
			<td width="70%">&nbsp;</td>
			<th>No. SPI</th>
			<td>:</td>
			<td id="idNoSPI"><?php echo $data1['no_spi']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Pengirim</th>
			<td>:</td>
			<td><?php echo $data1['pengirim']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Tanggal</th>
			<td>:</td>
			<td><?php echo $data1['tanggal']; ?></td>
		</tr>
	</table>
	<?php
	$hasilLihat2 = mysql_query("SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$kode' AND status_permintaan = 'Terkonfirmasi'");
	?>
	<table class="table">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th></th>
		</tr>
	<?php
	$a = 0;
	while($data2 = mysql_fetch_array($hasilLihat2)){
		$a++;
		?>
		<tr>
			<td id="idPermintaanKe<?php echo $a; ?>"><?php echo $data2['permintaan_ke']; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah_satuan']." ".$data2['jenis_satuan']; ?></td>
			<td></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}

?>