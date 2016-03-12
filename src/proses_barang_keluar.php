<?php
// proses belanja
require_once("../../config/configuration.php");

$action = $_GET['action'];


if($action == "tambahKeluar"){
	$kodeKeluar = mysql_real_escape_string($_POST['pKodeKeluar']);
	$tanggalKeluar = mysql_real_escape_string($_POST['pTanggalKeluar']);
	$admin = mysql_real_escape_string($_POST['pAdmin']);
	$pengirim = mysql_real_escape_string($_POST['pPengirim']);
	$penerima = mysql_real_escape_string($_POST['pPenerima']);
	
	$sql = "SELECT COUNT(cek) AS x FROM tb_permintaan_produksi";
	$hasilcount = mysql_query($sql);
	$data = mysql_fetch_array($hasilcount);
	
	$sql = "INSERT INTO tb_permintaan_produksi VALUES('$kodeKeluar','$tanggalKeluar','$admin','$pengirim','$penerima','$data[x]++')";
	$hasil = mysql_query($sql);
	if($hasil){
		echo "ok";
	}
	else{
		echo "Kode Barang Keluar Salah";
	}
}

elseif($action == "test"){
	$kodenya = $_POST['kodenya'];
	echo $kodenya;
}
elseif($action == "tambahDetailKeluar"){
	// cek row terbaru
	$cek = "SELECT cek FROM tb_permintaan_produksi ORDER BY cek DESC LIMIT 1";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKodeKeluar = "SELECT no_SPI FROM tb_permintaan_produksi WHERE cek = '$dataCek[cek]'";
	$hasilKodeKeluar = mysql_query($sqlKodeKeluar);
	$dataTemp = mysql_fetch_array($hasilKodeKeluar);
	$kodeKeluar = $dataTemp['no_SPI'];

	$kodeBarang = mysql_real_escape_string($_POST['pKodeBarang']);
	$namaBarang = mysql_real_escape_string($_POST['pNamaBarang']);
	$jumlahSatuan = mysql_real_escape_string($_POST['pJumlahSatuan']);
	$jenisSatuan = mysql_real_escape_string($_POST['pJenisSatuan']);
	
	//echo 'dari php'.$jenisSatuan;
	$Isi = mysql_real_escape_string($_POST['pIsi']);
	$totalSatuan = mysql_real_escape_string($_POST['pTotalSatuan']);

	// counting detail belanja
	$sqlCount = "SELECT count(*) AS hitung FROM `tb_permintaan_produksi_detail` WHERE no_SPI = '$kodeKeluar'";
	$hasilCount = mysql_query($sqlCount);
	$dataCount = mysql_fetch_array($hasilCount);
	$countKeluar = $dataCount['hitung'];
	$countKeluar++;
	
	$sql = "INSERT INTO tb_permintaan_produksi_detail VALUES('$kodeKeluar','$kodeBarang', '$namaBarang', '$jumlahSatuan','$jenisSatuan','$Isi', '$totalSatuan','$countKeluar')";
	$hasil = mysql_query($sql) or die("gagal");
	if($hasil){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
}

elseif($action == "headerKeluar"){
	// cek row terbaru
	$cek = "SELECT cek FROM tb_permintaan_produksi ORDER BY cek DESC LIMIT 1";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sql = "SELECT * FROM tb_permintaan_produksi WHERE cek = '$dataCek[cek]'";
	$hasil = mysql_query($sql);
	$data = mysql_fetch_array($hasil);
	?>
	<table class="table" width="100%" border="1">
		<tr>
			<td width="75%">&nbsp;</td>
			<th>No.SPI</th>
			<td>:</td>
			<td id="kdKeluar"><?php echo $data['no_SPI']; ?></td>
		</tr>
		<tr>
			<td width="75%">&nbsp;</td>
			<th>Tanggal Barang Keluar</th>
			<td>:</td>
			<td><?php echo $data['tanggal_keluar']; ?></td>
		</tr>
		<tr>
			<td width="75%">&nbsp;</td>
			<th>Admin</th>
			<td>:</td>
			<td><?php echo $data['admin']; ?></td>
		</tr>
	</table>
	<?php
}

elseif($action == "listDetailKeluar"){
	// cek row terbaru
	$cek = "SELECT cek FROM tb_permintaan_produksi ORDER BY cek DESC LIMIT 1";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKodeKeluar = "SELECT no_SPI FROM tb_permintaan_produksi WHERE cek = '$dataCek[cek]'";
	$hasilKodeKeluar = mysql_query($sqlKodeKeluar);
	$dataKodeKeluar = mysql_fetch_array($hasilKodeKeluar);
	$kodeKeluar = $dataKodeKeluar['no_SPI'];

	$sql = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_SPI = '$kodeKeluar'";
	$hasil = mysql_query($sql);
	?>
	<table class="table" width="100%" border="1">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah Satuan</th>
			<th>Jenis Satuan</th>
			<th>Isi</th>
			<th>Total Satuan</th>
		</tr>
	<?php
	while($data = mysql_fetch_array($hasil)){
		?>
		<tr>
			<td id="idCountKeluar"><?php echo $data['keluar_ke']; ?></td>
			<td><?php echo $data['kode_barang']; ?></td>
			<td><?php echo $data['nama_barang']; ?></td>
			<td><?php echo $data['jumlah_satuan']; ?></td>
			<td><?php echo $data['jenis_satuan']; ?></td>
			<td><?php echo $data['isi']; ?></td>
			<td><?php echo $data['total_satuan']; ?></td>
			
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}

elseif($action == "") {
	
}

elseif($action == "autoKodeBarang"){
	$kode = $_GET['term'];
	$sql = "SELECT kode_barang FROM tb_barang WHERE kode_barang LIKE '$kode%'";
	$hasil = mysql_query($sql);
	while ($data = mysql_fetch_array($hasil)) {
		$hasilAutoKodeBarang[] = array('label' => $data['kode_barang'] , );
	}
	echo json_encode($hasilAutoKodeBarang);
}
elseif($action == "autoNamaBarang") {
	$kodeBarang = mysql_real_escape_string($_POST['pKodeBarang']);
	$sql = "SELECT count(nama_barang) AS cek, nama_barang FROM tb_barang WHERE kode_barang='$kodeBarang'";
	$hasil = mysql_query($sql);
	$data = mysql_fetch_array($hasil);
	if($data['cek'] == "0"){
		echo "gagal";
	}
	else{
		echo $data['nama_barang'];
	}

}

elseif($action == "cancelKeluar"){
	$kdKeluar = mysql_real_escape_string($_POST['pKdKeluar']);

	$sqlDetailKeluar = "DELETE FROM tb_permintaan_produksi_detail WHERE no_SPI = '$kdKeluar'";
	$hasilDelDetailKeluar = mysql_query($sqlDetailKeluar);
	if($hasilDelDetailKeluar){
		echo "Delete detail OK";
	}
	else{
		echo "Delete detail gagal";
	}

	$sqlKeluar = "DELETE FROM tb_permintaan_produksi WHERE no_SPI = '$kdKeluar'";
	$hasilDelKeluar = mysql_query($sqlKeluar);
	if($hasilDelKeluar){
		echo "Delete belanja OK";
	}
	else{
		echo "Delete belanja gagal";
	}
	
}

elseif($action == "listKeluar"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListKeluar").dataTable({
			"aaSorting": [[ 0, "desc" ]]
		});
	});
	</script>
	<?php
	$sql = "SELECT * FROM tb_permintaan_produksi ORDER BY cek ASC";
	$hasil = mysql_query($sql);
	?>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListKeluar">
		<thead>
			<tr>
				<th>Pengeluaran ke</th>
				<th>No. SPI</th>
				<th>Tanggal Pengeluaran</th>
				<th>Admin</th>
				<th>Option</th>
			</tr>
		</thead>
		<tbody>
	<?php
	while($data = mysql_fetch_array($hasil)){
			?>
			<tr>
				<td><?php echo $data['cek']+1; ?></td>
				<td><?php echo $data['no_SPI']; ?></td>
				<td><?php echo $data['tanggal_keluar']; ?></td>
				<td><?php echo $data['admin']; ?></td>
				<td width="175px">
					<button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatKeluar('<?php echo $data[no_SPI]; ?>');"><span class="glyphicon glyphicon-zoom-in"></span></button>
					<button type="button" title="Edit" class="btn btn-default" onclick="javascript:editKeluar('<?php echo $data[no_SPI]; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
					<button type="button" title="hapus" class="btn btn-default" onclick="javascript:hapusKeluar('<?php echo $data[no_SPI]; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
					<button type="button" title="print" class="btn btn-default" onclick="javascript:printKeluar('<?php echo $data[no_SPI]; ?>');"><span class="glyphicon glyphicon-print"></span></button>
				</td>
			</tr>
			<?php
	}
	?>
		</tbody>
		<thead>
		</thead>
	</table>
	<?php
}

elseif($action == "lihatKeluar"){
	$kode = mysql_real_escape_string($_POST['pKode']);
	$sql1 = "SELECT * FROM tb_permintaan_produksi WHERE no_SPI = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
	<table class="table">
		<tr>
			<td width="70%">&nbsp;</td>
			<th>No. SPI</th>
			<td>:</td>
			<td id="kdKeluar"><?php echo $data1['no_SPI']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Tanggal Pengeluaran</th>
			<td>:</td>
			<td><?php echo $data1['tanggal_keluar']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Admin</th>
			<td>:</td>
			<td><?php echo $data1['admin']; ?></td>
		</tr>
	</table>
	<?php
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_SPI = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table">
		<tr>
			<th>Kode Barang</th>
			<th>Jumlah Satuan</th>
			<th>Isi</th>
			<th>Total Satuan</th>
		</tr>
	<?php
	while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['jumlah_satuan']; ?></td>
			<td><?php echo $data2['isi']; ?></td>
			<td><?php echo $data2['total_satuan']; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php

}

elseif($action == "hapusKeluar"){
	$kodeKeluar = mysql_real_escape_string($_POST['pKode']);
	// menghapus data pada tabel belanja detail
	$sql1 = "DELETE FROM tb_permintaan_produksi_detail WHERE no_SPI = '$kodeKeluar'";
	$hasil1 = mysql_query($sql1);

	// ambil kode barang dari tabel jurnal transaksi
	$kdBrg = 0;
	$hasilKodeBarang = mysql_query("SELECT kode_barang FROM tb_jurnal_transaksi WHERE no_SPI = '$kodeKeluar'");
	while($dataKodeBarang = mysql_fetch_array($hasilKodeBarang)){
		// increment kdBrg
		//$kdBrg++;
		
		// ambil kode barang dari tabel jurnal transaksi dari kode belanja
		$hasilKodeBarang = mysql_query("SELECT kode_barang FROM tb_jurnal_transaksi WHERE no_SPI= '$kodeKeluar'");
		$dataKodeBarang = mysql_fetch_array($hasilKodeBarang);

		// menghapus row pada tabel jurnal transaksi berdasarkan kode_belanja dan kode_barang
		mysql_query("DELETE FROM tb_jurnal_transaksi WHERE no_SPI = '$kodeKeluar' AND kode_barang = '$dataKodeBarang[kode_barang]'");

		// hitung jumlah kode_barang setelah dihapus tadi
		$hasilJumlahBarang = mysql_query("SELECT SUM(jumlah_barang) AS jum_barang FROM tb_jurnal_transaksi WHERE kode_barang = '$dataKodeBarang[kode_barang]'");
		$dataJumlahBarang = mysql_fetch_array($hasilJumlahBarang);

		// kemudian lakukan update ke tabel stok barang
		// dan update stok akhir
		mysql_query("UPDATE tb_stok_barang_mentah_gudang SET masuk = '$dataJumlahBarang[jum_barang]' WHERE kode_barang = '$dataKodeBarang[kode_barang]'");
		mysql_query("UPDATE tb_stok_barang_mentah_gudang SET stok_akhir = (stok_awal+masuk-keluar) WHERE kode_barang = '$dataKodeBarang[kode_barang]'") OR die("NGITUNGE ERROR men");

	}

	// ambil jumlah barang dari tabel jurnal transaksi
	// $hasilJumlahBarang = mysql_query("SELECT SUM(jumlah_barang) as jum_barang FROM tb_jurnal_transaksi");

	// lihat stok awal pada tabel 
	// $hasilStokAwal = mysql_query("SELECT stok_awal FROM tb_stok_barang_mentah_gudang");

	// menghapus data pada tabel belanja
	$sql2 = "DELETE FROM tb_permintaan_produksi WHERE no_SPI = '$kodeKeluar'";
	$hasil2 = mysql_query($sql2);

	if($hasil1 AND $hasil2){
		echo "ok";
	}
	else{
		echo "gagal";
	}
}

elseif($action == "editKeluar"){
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#idTanggalKeluarEdit").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#idJumlahKeluarEdit").keyup(function(){
			var harga = $("#idHargaKeluarEdit").val();
			var jmlBarang = $("#idJumlahKeluarEdit").val();
			var hasil = harga * jmlBarang;
			$("#idTotalHargaEdit").val(hasil);
		});
		$("#idHargaBarangEdit").keyup(function(){
			var harga = $("#idHargaBarangEdit").val();
			var jmlBarang = $("#idJumlahBarangEdit").val();
			var hasil = harga * jmlBarang;
			$("#idTotalHargaEdit").val(hasil);
		});
	});
	</script>
	<?php
	$kode = mysql_real_escape_string($_POST['pKode']);
	$sql1 = "SELECT * FROM tb_permintaan_produksi WHERE no_SPI = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
		<table class="table">
			<tr>
				<td width="70%">&nbsp;</td>
				<th>No. SPI</th>
				<td>:</td>
				<td id="kdKeluar"><input id="idKodeKeluarEdit" type="text" class="form-control" autocomplete="off" placeholder="No.SPI" value="<?php echo $data1['no_SPI']; ?>" disabled></td>
			</tr>
			<tr>
				<td width="70%">&nbsp;</td>
				<th>Tanggal Pengeluaran</th>
				<td>:</td>
				<td><input id="idTanggalKeluarEdit" type="text" class="form-control" autocomplete="off" placeholder="Tanggal Pengeluaran" value="<?php echo $data1['tanggal_keluar']; ?>" disabled></td>
			</tr>
			<tr>
				<td width="70%">&nbsp;</td>
				<th>Admin</th>
				<td>:</td>
				<td><?php echo $data1['admin']; ?></td>
			</tr>
		</table>
	<?php
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_SPI = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
		<table class="table">
			<tr>
				<th>No.</th>
				<th>Kode Barang</th>
				<th>Jumlah Satuan</th>
				<th>Isi</th>
				<th>Total Satuan</th>
			</tr>
		<?php
		$a = 0;
		while($data2 = mysql_fetch_array($hasil2)){
			$a++;
			?>
			<tr>
				<td width="65"><input id="idKeluarKe<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['keluar_ke']; ?>" disabled></td>
				<td><input id="idKodeBarangEdit<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['kode_barang']; ?>" disabled></td>
				<td><input id="idHargaBarangEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Jumlah Satuan" value="<?php echo $data2['jumlah_satuan']; ?>" required></td>
				<td><input id="idJumlahBarangEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Isi" value="<?php echo $data2['isi']; ?>" required></td>
				<td><input id="idTotalHargaEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Total Satuan" value="<?php echo $data2['total_satuan']; ?>" disabled></td>
				<td><button id="btnUpdateDetail<?php echo $a; ?>" type="submit" class="btn btn-primary">Update</button></td>
			</tr>
		<script type="text/javascript">
		$(document).ready(function(){
			var ax = "<?php echo $a; ?>";
			$("#idJumlahBarangEdit"+ax).keyup(function(){
				var harga = $("#idHargaBarangEdit"+ax).val();
				var jmlBarang = $("#idJumlahBarangEdit"+ax).val();
				var hasil = harga * jmlBarang;
				$("#idTotalHargaEdit"+ax).val(hasil);
			});
			$("#idHargaBarangEdit"+ax).keyup(function(){
				var harga = $("#idHargaBarangEdit"+ax).val();
				var jmlBarang = $("#idJumlahBarangEdit"+ax).val();
				var hasil = harga * jmlBarang;
				$("#idTotalHargaEdit"+ax).val(hasil);
			});
			$("#btnUpdateDetail"+ax).click(function(){
				var vKodeKeluarEdit = $("#idKodeKeluarEdit").val();
				var vKodeBarangEdit = $("#idKodeBarangEdit"+ax).val();
				var vHargaBarangEdit = $("#idHargaBarangEdit"+ax).val();
				var vJumlahBarangEdit = $("#idJumlahBarangEdit"+ax).val();
				var vKeluarKeEdit = $("#idKeluarKe"+ax).val();
				
				//alert(vBelanjaKeEdit);
			
				$.ajax({
					type: "POST",
					url: "../src/produksi/proses_barang_keluar.php?action=editKeluarOk",
					data:{
						pKeluarKeEdit:vKeluarKeEdit,
						pKodeKeluarEdit:vKodeKeluarEdit,
						pKodeBarangEdit:vKodeBarangEdit,
						pHargaBarangEdit:vHargaBarangEdit,
						pJumlahBarangEdit:vJumlahBarangEdit
					},
					cache: false,
					success: function(hasil){
						alert("ini "+hasil);
					}
				});
				return false;
			});
		});
		</script>
			<?php
		}
		?>
		</table>
	<?php

}

elseif($action == "editKeluarOk"){
	$keluarKeEdit = mysql_real_escape_string($_POST['pKeluarKeEdit']);
	$kodeKeluarEdit = mysql_real_escape_string($_POST['pKodeKeluarEdit']);
	$kodeBarangEdit = mysql_real_escape_string($_POST['pKodeBarangEdit']);
	$hargaBarangEdit = mysql_real_escape_string($_POST['pHargaBarangEdit']);
	$jumlahBarangEdit = mysql_real_escape_string($_POST['pJumlahBarangEdit']);
	$sql = "UPDATE tb_permintaan_produksi_detail SET harga= '$hargaBarangEdit', jumlah = '$jumlahBarangEdit' WHERE no_SPI = '$kodeKeluarEdit' AND kode_barang = '$kodeBarangEdit' AND belanja_ke = '$keluarKeEdit'";
	$hasil = mysql_query($sql);
	if($hasil){
		echo "ok";
	}
	else{
		echo "gagal";
	}
	
	// lihat tb_stok_barang_mentah_gudang
	/*$hasilLihatSBMG = mysql_query("SELECT stok_awal FROM tb_stok_barang_mentah_gudang WHERE kode_barang = '$kodeBarangEdit'");
	$dataLihatSBMG = mysql_fetch_array($hasilLihatSBMG);
	$dataLihatSBMG['stok_awal'];*/

	// lakukan update ke tb_stok_barang_mentah_gudang
	// $sqlUpdateSBMG = "UPDATE ";

	// lakukan update pada tb_jurnal_transaksi
	$hasilUpdateJT = mysql_query("UPDATE tb_jurnal_transaksi 
								  SET jumlah_barang = '$jumlahBarangEdit'
								  WHERE kode_belanja = '$kodeKeluarEdit' AND belanja_ke = '$keluarKeEdit'") or die ("update ke transaksi salah");

	// ambil jumlah barang masuknya dari tabel transaksi
	$hasilJumlahBarang = mysql_query("SELECT SUM(jumlah_barang) AS jum_barang FROM tb_jurnal_transaksi WHERE kode_barang = '$kodeBarangEdit' AND status = 'Barang Keluar'");
	$dataJumlahBarang = mysql_fetch_array($hasilJumlahBarang);
	//echo "jumlah barang ".$dataJumlahBarang['jum_barang'];

	// ambil stok awalnya dari tabel stok barang mentah gudang untuk di jumlahkan dengan jumlah barang yang sudah di edit
	$hasillihatstok = mysql_query("SELECT stok_awal FROM tb_stok_barang_mentah_gudang WHERE kode_barang = '$kodeBarangEdit'");
	$dataLihatStok = mysql_fetch_array($hasillihatstok);

	// hitung jumlah barang dengan stok barang yang sudah di edit
	$jumlahStokAkhir = $dataJumlahBarang['jum_barang']- $dataLihatStok['stok_awal'];

	// lakukan update jumlah barang masuk ke tb_stok_barang_mentah_gudang
	$hasilUpdateSBMG = mysql_query("UPDATE tb_stok_barang_mentah_gudang
									SET masuk = '$dataJumlahBarang[jum_barang]',
									stok_akhir = '$jumlahStokAkhir'
									WHERE kode_barang = '$kodeBarangEdit'") or die ("error pas update stok barang mentah gudang");
}

elseif ($action == "printKeluar") {
	$kode = mysql_real_escape_string($_GET['pKode']);
	$sql1 = "SELECT * FROM tb_permintaan_produksi WHERE no_SPI = '$kode'";
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
			<td id="kdKeluar">:&nbsp;<?php echo $data1['no_SPI']; ?></td>
		</tr>
		<tr>
			<td width="10%">&nbsp;</td>
			<th><hr></th>
			<td><hr></td>
		</tr>
		<tr>
			<td width="50%">&nbsp;</td>
			<th align="left">Tanggal Pengeluaran </th>
			<td>:&nbsp;<?php echo $data1['tanggal_keluar']; ?></td>
</tr>
	</table>
	<br>
	<b> Kepada Yth. </b>
	<br>
	<br>
	<br>
	Bersama Ini kami kirimkan bahan - bahan sebagai berikut :
	<hr>
	<?php
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_SPI = '$kode'";
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
			<td><?php echo $data2['keluar_ke']; ?></td>
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
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi WHERE no_SPI = '$kode'";
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
			<td width="20%"><u><b><?php echo $data2['penerima']; ?></b></u></td>
			<td width="20%"><u><b><?php echo $data2['pengirim']; ?></b></u></td>
			<td width="20%"><u><b>&nbsp;</b></u></td>
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

elseif($action == "selesaiKeluar"){
	//echo "selesai";

	// inisialisasi count belanja
	$keluarKe = 0;

	$tanggal = date('Y-m-d H:i:s');

	// lihat transaksi terakhir
	$cekRow = "SELECT cek FROM tb_permintaan_produksi ORDER BY cek DESC LIMIT 1";
	$hasilCek1 = mysql_query($cekRow) or die("query 1 salah");
	$dataCek = mysql_fetch_array($hasilCek1);
	//echo "row ke ".$dataCek['cek'];

	
	// lihat kode belanja sesuai belanja terakhir
	$cek2 = "SELECT no_SPI, tanggal_keluar FROM tb_permintaan_produksi WHERE cek = '$dataCek[cek]'";
	$hasilCek2 = mysql_query($cek2) or die("query 2 salah");
	$dataKodeKeluarOk = mysql_fetch_array($hasilCek2);
	//echo "Get kode belanja".$dataKodeBelanjaOk['kode_belanja'];

	// lihat list belanja berdasarkan kode belanja terakhir
	$sql = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_SPI = '$dataKodeKeluarOk[no_SPI]'";
	$hasil = mysql_query($sql) or die("queri lhat jumlah stok salah"); 
	while($data = mysql_fetch_array($hasil)){
		//echo $data['kode_belanja']." ".$data['kode_barang']." ".$data['harga_barang']." ".$data['jumlah_barang']."<br>";
		
		// cek kodebarang dan jumlah stok di tabel stok barang mentah
		$sqllihatstok = "SELECT kode_barang, stok_akhir, masuk, keluar FROM tb_stok_barang_mentah_gudang WHERE kode_barang = '$data[kode_barang]'";
		$hasillihatstok = mysql_query($sqllihatstok) or die("query cek stok ");
		$dataStokBarang = mysql_fetch_array($hasillihatstok);

		echo "yg dibeli bkl masuk = ".$data['jumlah'];
		echo "\nyg stok awal = ".$dataStokBarang['stok_awal'];
		echo "\nyg keluar = ".$dataStokBarang['keluar'];
		

		// hitung jumlah barang pada stok akhir dengan jumlah belanja
		$jumlahStokAkhir = $dataStokBarang['stok_akhir']-$data['jumlah']-$dataStokBarang['keluar'];
		$jumlahKeluar = $dataStokBarang['masuk'] - $data['jumlah'];

		//echo $jumlah;
		
		// masukkan hasil transaksinya ke tabel stok barang
		$sqlSelesai = "UPDATE tb_stok_barang_mentah_gudang SET keluar = '$jumlahKeluar', stok_akhir = '$jumlahStokAkhir', last_tanggal_masuk = '$dataKodeKeluarOk[tanggal_keluar]'  WHERE kode_barang = '$dataStokBarang[kode_barang]'";
		$hasilSelesai = mysql_query($sqlSelesai) or die ("query update tb_stok_barang_mentah_gudang salah");

		// masukkan hasil transaksinya ke jurnal barang dengan keterangan barang masuk
		// increment count belanja_ke
		$keluarKe++;
		$sqlTransaksi = "INSERT INTO tb_jurnal_transaksi VALUES('', '$dataStokBarang[kode_barang]', 'Barang Keluar', '$data[jumlah]', '$dataKodeKeluarOk[tanggal_keluar]', '$dataKodeKeluarOk[no_SPI]', '$keluarKe')"; 
		mysql_query($sqlTransaksi) or die ("query entri tb_jurnal_transaksi salah");
		

	}

}

?>