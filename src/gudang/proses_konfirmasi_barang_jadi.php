<?php
// proses konfirmasi barang jadi
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "listMenunggu"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListMenunggu").dataTable();
	});
	</script>
	<?php
	$hasil = mysql_query("SELECT * FROM tb_pengiriman_produksi WHERE status_pengiriman = 'Menunggu'");
?>
<h3>List Pengiriman Barang (Menunggu)</h3>
<div class="thumbnail">
	<div class="caption">
<table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListMenunggu">
	<thead>
		<tr>
			<th width="7%">No.</th>
			<th>No. Kirim</th>
			<th>Tanggal</th>
			<th>Pengirim</th>
			<th>Option</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$no = 0;
	while ($data = mysql_fetch_array($hasil)) {
		$no++;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $data['no_kirim']; ?></td>
			<td><?php echo $data['tanggal']; ?></td>
			<td><?php echo $data['pengirim']; ?></td>
			<td><button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatPengiriman('<?php echo $data['no_kirim']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span> Lihat</button></td>
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

elseif($action == "listTerkonfirmasi"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListTerkonfirmasi").dataTable();
	});
	</script>
	<?php
	$hasil = mysql_query("SELECT * FROM tb_pengiriman_produksi WHERE status_pengiriman = 'Terkonfirmasi'");
?>
<h3>List Pengiriman Barang (Terkonfirmasi)</h3>
<div class="thumbnail">
	<div class="caption">
<table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListTerkonfirmasi">
	<thead>
		<tr>
			<th width="7%">No.</th>
			<th>No. Kirim</th>
			<th>Tanggal</th>
			<th>Pengirim</th>
			<th>Option</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$no = 0;
	while ($data = mysql_fetch_array($hasil)) {
		$no++;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $data['no_kirim']; ?></td>
			<td><?php echo $data['tanggal']; ?></td>
			<td><?php echo $data['pengirim']; ?></td>
			<td>
				<button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatTerkonfirmasi('<?php echo $data['no_kirim']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span> Lihat</button>
				<button type="button" title="Cetak" class="btn btn-default" onclick="javascript:printTerkonfirmasi('<?php echo $data['no_kirim']; ?>');"><span class="glyphicon glyphicon-print"></span> Cetak</button>
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

elseif ($action == "lihatPermintaan") {
	$kode = $_GET['pKode'];
	$hasilLihat1 = mysql_query("SELECT * FROM tb_pengiriman_produksi WHERE no_kirim = '$kode'");
	$data1 = mysql_fetch_array($hasilLihat1);
	?>
	<table class="table">
		<tr>
			<td width="70%">&nbsp;</td>
			<th>No. Kirim</th>
			<td>:</td>
			<td id="idNoKirim"><?php echo $data1['no_kirim']; ?></td>
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
	$hasilLihat2 = mysql_query("SELECT * FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kode'");
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
			<td id="idPengirimanKe<?php echo $a; ?>"><?php echo $data2['pengiriman_ke']; ?></td>
			<td><?php echo $data2['kode_barang_jadi']; ?></td>
			<td><?php echo $data2['nama_barang_jadi']; ?></td>
			<td><?php echo $data2['jumlah']." ".$data2['nama_satuan']; ?></td>
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
				var vNoKirim = $("#idNoKirim").html();
				var vPengirimanKe = $("#idPengirimanKe"+ax).html();
				
				$.ajax({
					type: "POST", 
					url: "../src/gudang/proses_konfirmasi_barang_jadi.php?action=konfirmasi",
					data:{
						pNoKirim:vNoKirim,
						pPengirimanKe:vPengirimanKe
					},
					cache: false,
					success: function(h){
						var hasilKonfirmasi = $.trim(h);
						if(hasilKonfirmasi == 'sukses'){
							$("#btnKonfirmasi"+ax).hide();
							$("#idLabelSukses"+ax).show();
							$(".cek").html("1");
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

elseif ($action == "konfirmasi") {
	$kode = $_POST['pNoKirim'];
	$pengirimanKe = $_POST['pPengirimanKe'];

	$hasilKonfirmasi = mysql_query("UPDATE tb_pengiriman_produksi_detail SET
									status_pengiriman = 'Terkonfirmasi'
									WHERE no_kirim = '$kode' AND pengiriman_ke = '$pengirimanKe'");
	if($hasilKonfirmasi){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
}

elseif ($action == "konfirmasiSelesai") {
	$kode = $_GET['pNoKirim'];
	$hasilSelesai = mysql_query("UPDATE tb_pengiriman_produksi SET
								status_pengiriman = 'Terkonfirmasi'
								WHERE no_kirim = '$kode'");

	// ambil data utama
	$ambilData1 = mysql_query("SELECT * FROM tb_pengiriman_produksi WHERE no_kirim = '$kode' AND status_pengiriman = 'Terkonfirmasi'");
	$dataRecord1 = mysql_fetch_array($ambilData1);

	// ambil data detail dan input jurnal
	$ambilData2 = mysql_query("SELECT * FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kode' AND status_pengiriman = 'Terkonfirmasi'");
	while ($dataRecord2 = mysql_fetch_array($ambilData2)) {
		$inputJurnal = mysql_query("INSERT INTO tb_jurnal_barang_jadi VALUES ('', '$dataRecord1[no_kirim]', '$dataRecord1[pengirim]', 'Barang Masuk', '$dataRecord1[tanggal]', '$dataRecord2[kode_barang_jadi]', '$dataRecord2[nama_barang_jadi]', '$dataRecord2[jumlah]', '$dataRecord2[nama_satuan]', '$dataRecord2[isi]', '$dataRecord2[satuan_kecil]', '$dataRecord2[total]', 'Pemasukan Barang Internal', '$dataRecord2[pengiriman_ke]')") or die(mysql_error());	
	}

	echo "Konfirmasi Selesai";
}

elseif ($action == "konfirmasiCancel") {
	$kode = $_GET['pNoKirim'];
	$hasilSelesai = mysql_query("UPDATE tb_pengiriman_produksi_detail SET
								status_pengiriman = 'Menunggu'
								WHERE no_kirim = '$kode'");
	
	if($hasilSelesai){
		echo "Konfirmasi dibatalkan !!";
	}
}

elseif ($action == "lihatTerkonfirmasi") {
	$kode = $_GET['pKode'];
	$hasilLihat1 = mysql_query("SELECT * FROM tb_pengiriman_produksi WHERE no_kirim = '$kode'");
	$data1 = mysql_fetch_array($hasilLihat1);
	?>
	<table class="table">
		<tr>
			<td width="70%">&nbsp;</td>
			<th>No. Kirim</th>
			<td>:</td>
			<td id="idNoSPI"><?php echo $data1['no_kirim']; ?></td>
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
	$hasilLihat2 = mysql_query("SELECT * FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kode' AND status_pengiriman = 'Terkonfirmasi'");
	?>
	<table class="table">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Total</th>	
			<th></th>
		</tr>
	<?php
	$a = 0;
	while($data2 = mysql_fetch_array($hasilLihat2)){
		$a++;
		?>
		<tr>
			<td><?php echo $data2['pengiriman_ke']; ?></td>
			<td><?php echo $data2['kode_barang_jadi']; ?></td>
			<td><?php echo $data2['nama_barang_jadi']; ?></td>
			<td><?php echo $data2['jumlah']." ".$data2['nama_satuan']; ?></td>
			<td><?php echo $data2['total']." ".$data2['satuan_kecil']; ?></td>
			<td></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}

elseif ($action == "printTerkonfirmasi") {
	$kode = $_GET['kode'];
	$sql1 = "SELECT * FROM tb_pengiriman_produksi WHERE no_kirim = '$kode' AND status_pengiriman = 'Terkonfirmasi'";
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
			<td>FAKTUR BARANG DITERIMA</td>
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
			<th align="left">No. Kirim</th>
			<td id="kdKeluar">:&nbsp;<?php echo $data1['no_kirim']; ?></td>
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
	Bersama Ini kami terima bahan - bahan sebagai berikut :
	<hr>
	<?php
	
	$sql2 = "SELECT * FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kode' AND status_pengiriman = 'Terkonfirmasi'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table" width="100%">
		<tr>
			<td><b>No</b></td>
			<td><b>Kode Barang</b></td>
			<td><b>Nama Barang</b></td>
			<td><b>Jumlah</b></td>
			<td><b>Jenis Satuan</b></td>
			<td><b>Isi</b></td>
			<td><b>Total</b></td>
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
			<td><?php echo $data2['pengiriman_ke']; ?></td>
			<td><?php echo $data2['kode_barang_jadi']; ?></td>
			<td><?php echo $data2['nama_barang_jadi']; ?></td>
			<td><?php echo $data2['jumlah']; ?></td>
			<td><?php echo $data2['nama_satuan']; ?></td>
			<td><?php echo $data2['isi']." ".$data2['satuan_kecil']; ?></td>
			<td><?php echo $data2['total']." ".$data2['satuan_kecil']; ?></td>
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
	
	$sql2 = "SELECT * FROM tb_pengiriman_produksi WHERE no_kirim = '$kode' AND status_pengiriman = 'Terkonfirmasi'";
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


?>