<?php
// proses pengiriman barang jadi
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "tambahPengiriman"){
	$kode = $_POST['pKode'];
	$tanggal = $_POST['pTanggal'];
	$admin = $_POST['pAdmin'];
	$pengirim = $_POST['pPengirim'];

	mysql_query("INSERT INTO tb_pengiriman_produksi VALUES('', '$kode', '$tanggal', '$pengirim', '$admin', 'Menunggu')");

}

elseif($action == "autoKodeBarang"){
	$kode = $_GET['term'];
	$sql = "SELECT kode_barang_jadi FROM tb_barang_jadi WHERE kode_barang_jadi LIKE '$kode%'";
	$hasil = mysql_query($sql);
	while ($data = mysql_fetch_array($hasil)) {
		$hasilAutoKodeBarang[] = array('label' => $data['kode_barang_jadi'] , );
	}
	echo json_encode($hasilAutoKodeBarang);
}

elseif ($action == "listSatuan") {
	$tampil=mysql_query("SELECT * FROM tb_satuan ORDER BY id_satuan");
	echo "<option value='belum milih' selected>- Pilih Jenis Satuan -</option>";

	while($w=mysql_fetch_array($tampil))
	{
	    echo "<option value='$w[nama_satuan]'>$w[nama_satuan]</option>";        
	}
}

elseif($action == "autoNamaBarang") {
	$kodeBarang = $_POST['pKodeBarang'];
	$sql = "SELECT count(nama_barang_jadi) AS cek, nama_barang_jadi FROM tb_barang_jadi WHERE kode_barang_jadi='$kodeBarang'";
	$hasil = mysql_query($sql);
	$data = mysql_fetch_array($hasil);
	if($data['cek'] == "0"){
		echo "gagal";
	}
	else{
		echo $data['nama_barang_jadi'];
	}
}

elseif($action == "tambahDetailPengiriman"){
	// cek row terbaru
	$cek = "SELECT MAX(id_pengiriman) AS cek FROM tb_pengiriman_produksi";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKodePengiriman = "SELECT no_kirim FROM tb_pengiriman_produksi WHERE id_pengiriman = '$dataCek[cek]'";
	$hasilKodePengiriman = mysql_query($sqlKodePengiriman);
	$dataTemp = mysql_fetch_array($hasilKodePengiriman);
	$kodePengiriman = $dataTemp['no_kirim'];
	
	$kodeBarang = $_POST['pKodeBarang'];
	$namaBarang = $_POST['pNamaBarang'];
	$jumlahSatuan = $_POST['pJumlahSatuan'];
	$jenisSatuan = $_POST['pJenisSatuan'];
	$isi = $_POST['pIsi'];
	$total = $_POST['pTotalSatuan'];
	$jenisSatuanKecil = $_POST['pJenisSatuanKecil'];

	// counting detail belanja
	$sqlCount = "SELECT count(*) AS hitung FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kodePengiriman'";
	$hasilCount = mysql_query($sqlCount);
	$dataCount = mysql_fetch_array($hasilCount);
	$countKirim = $dataCount['hitung'];
	
	$countKirim++;

	$sql = "INSERT INTO tb_pengiriman_produksi_detail VALUES('$kodePengiriman','$kodeBarang', '$namaBarang', '$jumlahSatuan','$jenisSatuan','$isi', '$total', '$jenisSatuanKecil', '$countKirim', 'Menunggu')";
	$hasil = mysql_query($sql) or die("gagal");
	if($hasil){
		echo "Sukses";
	}
	else{
		echo "Gagal";
	}
	
}

elseif($action == "headerPengiriman"){
	// cek row terbaru
	$cek = "SELECT MAX(id_pengiriman) AS cek FROM tb_pengiriman_produksi";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sql = "SELECT * FROM tb_pengiriman_produksi WHERE id_pengiriman = '$dataCek[cek]'";
	$hasil = mysql_query($sql);
	$data = mysql_fetch_array($hasil);
	?>
	<table class="table" width="100%" border="1">
		<tr>
			<td width="75%">&nbsp;</td>
			<th>No. Kirim</th>
			<td>:</td>
			<td id="kdPengiriman"><?php echo $data['no_kirim']; ?></td>
		</tr>
		<tr>
			<td width="75%">&nbsp;</td>
			<th>Tanggal</th>
			<td>:</td>
			<td><?php echo $data['tanggal']; ?></td>
		</tr>
		<tr>
			<td width="75%">&nbsp;</td>
			<th>Pengirim</th>
			<td>:</td>
			<td><?php echo $data['pengirim']; ?></td>
		</tr>
	</table>
	<?php
}

elseif($action == "listDetailPengiriman"){
	// cek row terbaru
	$cek = "SELECT MAX(id_pengiriman) AS cek FROM tb_pengiriman_produksi";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKode = "SELECT no_kirim FROM tb_pengiriman_produksi WHERE id_pengiriman = '$dataCek[cek]'";
	$hasilKode = mysql_query($sqlKode);
	$dataKode = mysql_fetch_array($hasilKode);
	$kodeKirim = $dataKode['no_kirim'];

	$sql = "SELECT * FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kodeKirim'";
	$hasil = mysql_query($sql);
	?>
	<table class="table" width="100%" border="1">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Jenis Satuan</th>
			<th>Isi</th>
			<th>Total</th>
		</tr>
	<?php
	while($data = mysql_fetch_array($hasil)){
		?>
		<tr>
			<td id="idCountKeluar"><?php echo $data['pengiriman_ke']; ?></td>
			<td><?php echo $data['kode_barang_jadi']; ?></td>
			<td><?php echo $data['nama_barang_jadi']; ?></td>
			<td><?php echo $data['jumlah']; ?></td>
			<td><?php echo $data['nama_satuan']; ?></td>
			<td><?php echo $data['isi']; ?></td>
			<td><?php echo $data['total']." ".$data['satuan_kecil']; ?></td>
			
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}

elseif($action == "listPengirimanTer"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelPengirimanTer").dataTable();
	});
	</script>
	<?php
	$sql = "SELECT * FROM tb_pengiriman_produksi WHERE status_pengiriman = 'Terkonfirmasi'";
	$hasil = mysql_query($sql);
	?>
	<legend>Status : <b>Terkonfirmasi</b></legend>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelPengirimanTer">
		<thead>
			<tr>
				<th>Permintaan ke</th>
				<th>No. Kirim</th>
				<th>Tanggal</th>
				<th>Pengirim</th>
				<th>Option</th>
			</tr>
		</thead>
		<tbody>
	<?php
	$no = 0;
	while($data = mysql_fetch_array($hasil)){
			$no++;
			?>
			<tr>
				<td><?php echo $no; ?></td>
				<td><?php echo $data['no_kirim']; ?></td>
				<td><?php echo $data['tanggal']; ?></td>
				<td><?php echo $data['pengirim']; ?></td>
				<td width="175px">
					<button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatTerkonfirmasi('<?php echo $data['no_kirim']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span></button>
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

elseif($action == "listPengirimanMen"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelPengirimanMen").dataTable();
	});
	</script>
	<?php
	$sql = "SELECT * FROM tb_pengiriman_produksi WHERE status_pengiriman = 'Menunggu'";
	$hasil = mysql_query($sql);
	?>
	<legend>Status : <b>Menunggu</b></legend>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelPengirimanMen">
		<thead>
			<tr>
				<th>Permintaan ke</th>
				<th>No. Kirim</th>
				<th>Tanggal</th>
				<th>Pengirim</th>
				<th>Option</th>
			</tr>
		</thead>
		<tbody>
	<?php
	$no = 0;
	while($data = mysql_fetch_array($hasil)){
			$no++;
			?>
			<tr>
				<td><?php echo $no; ?></td>
				<td><?php echo $data['no_kirim']; ?></td>
				<td><?php echo $data['tanggal']; ?></td>
				<td><?php echo $data['pengirim']; ?></td>
				<td width="175px">
					<button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatPengiriman('<?php echo $data['no_kirim']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span></button>
					<button type="button" title="Edit" class="btn btn-default" onclick="javascript:editPengiriman('<?php echo $data['no_kirim']; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
					<button type="button" title="hapus" class="btn btn-default" onclick="javascript:hapusPengiriman('<?php echo $data['no_kirim']; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
					<button type="button" title="print" class="btn btn-default" onclick="javascript:printPengiriman('<?php echo $data['no_kirim']; ?>');"><span class="glyphicon glyphicon-print"></span></button>
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

elseif($action == "cancelPengiriman"){
	// cek row terbaru
	$cek = "SELECT MAX(id_pengiriman) AS cek FROM tb_pengiriman_produksi";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKodePengiriman = "SELECT no_kirim FROM tb_pengiriman_produksi WHERE id_pengiriman = '$dataCek[cek]'";
	$hasilKodePengiriman = mysql_query($sqlKodePengiriman);
	$dataTemp = mysql_fetch_array($hasilKodePengiriman);
	$kdPengiriman = $dataTemp['no_kirim'];

	$hasil1 = mysql_query("DELETE FROM tb_pengiriman_produksi WHERE no_kirim = '$kdPengiriman'");
	$hasil2 = mysql_query("DELETE FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kdPengiriman'");
	if($hasil1 AND $hasil2){
		echo "Pengiriman dibatalkan";
	}
	else {
		echo "error !!";
	}
}

elseif ($action == "selesaiPengiriman") {
	echo "belum memasukkan ke jurnal";
}

elseif ($action == "hapusPengiriman") {
	$kode = $_POST['pKode'];

	$hasilDelPengiriman = mysql_query("DELETE FROM tb_pengiriman_produksi WHERE no_kirim = '$kode'");
	$hasilDelPengirimanDetail = mysql_query("DELETE FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kode'");

	if($hasilDelPengiriman && $hasilDelPengirimanDetail){
		echo  "Hapus sukses";
	}
	else{
		echo "Hapus gagal";
	}
}

elseif ($action == "lihatPengiriman") {
	$kode = mysql_real_escape_string($_POST['pKode']);
	$sql1 = "SELECT * FROM tb_pengiriman_produksi WHERE no_kirim = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
	<table class="table">
		<tr>
			<td width="70%">&nbsp;</td>
			<th>No. Kirim</th>
			<td>:</td>
			<td id="kdKeluar"><?php echo $data1['no_kirim']; ?></td>
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
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Admin</th>
			<td>:</td>
			<td><?php echo $data1['admin']; ?></td>
		</tr>
	</table>
	<?php
	
	$sql2 = "SELECT * FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table">
		<tr>
			<th>No. </th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Isi</th>
			<th>Total</th>
			<th>Status</th>
		</tr>
	<?php
	while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td><?php echo $data2['pengiriman_ke']; ?></td>
			<td><?php echo $data2['kode_barang_jadi']; ?></td>
			<td><?php echo $data2['nama_barang_jadi']; ?></td>
			<td><?php echo $data2['jumlah']." ".$data2['nama_satuan']; ?></td>
			<td><?php echo $data2['isi']; ?></td>
			<td><?php echo $data2['total']." ".$data2['satuan_kecil']; ?></td>
			<td><?php echo $data2['status_pengiriman']; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}

elseif ($action == "lihatTerkonfirmasi") {
	$kode = mysql_real_escape_string($_POST['pKode']);
	$sql1 = "SELECT * FROM tb_pengiriman_produksi WHERE no_kirim = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
	<table class="table">
		<tr>
			<td width="70%">&nbsp;</td>
			<th>No. Kirim</th>
			<td>:</td>
			<td id="kdKeluar"><?php echo $data1['no_kirim']; ?></td>
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
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Admin</th>
			<td>:</td>
			<td><?php echo $data1['admin']; ?></td>
		</tr>
	</table>
	<?php
	
	$sql2 = "SELECT * FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kode' AND status_pengiriman = 'Terkonfirmasi'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table">
		<tr>
			<th>No. </th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Isi</th>
			<th>Total</th>
			<th>Status</th>
		</tr>
	<?php
	while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td><?php echo $data2['pengiriman_ke']; ?></td>
			<td><?php echo $data2['kode_barang_jadi']; ?></td>
			<td><?php echo $data2['nama_barang_jadi']; ?></td>
			<td><?php echo $data2['jumlah']." ".$data2['nama_satuan']; ?></td>
			<td><?php echo $data2['isi']; ?></td>
			<td><?php echo $data2['total']." ".$data2['satuan_kecil']; ?></td>
			<td><?php echo $data2['status_pengiriman']; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}

elseif($action == "editPengiriman"){
	?>
	<script type="text/javascript">
	$(document).ready(function(){

		// datepicker
		$("#idTanggalEdit").datepicker({ dateFormat: 'yy-mm-dd' });
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

		//btn Update Klik
		$("#btnUpHeader").click(function(){
			var vPengirimEdit = $("#idPengirimEdit").val();
			var vTanggalEdit = $("#idTanggalEdit").val();
			var vKodePengirimanEdit = $("#idKodePengirimanEdit").val();

			$.ajax({
				type: "POST",
				url: "../src/produksi/proses_pengiriman_barang.php?action=upHeader",
				data:{
					pKodePengirimanEdit:vKodePengirimanEdit,
					pPengirimEdit:vPengirimEdit,
					pTanggalEdit:vTanggalEdit
				},
				cache: false,
				success: function(hasilUpHeader){
					alert(hasilUpHeader);
				}
			});

		});
	});
	</script>
	<?php
	$kode = mysql_real_escape_string($_POST['pKode']);
	$sql1 = "SELECT * FROM tb_pengiriman_produksi WHERE no_kirim = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
		<table class="table">
			<tr>
				<td width="70%">&nbsp;</td>
				<th>No. Kirim</th>
				<td>:</td>
				<td id="kdKeluar"><input id="idKodePengirimanEdit" type="text" class="form-control" autocomplete="off" placeholder="No. Kirim" value="<?php echo $data1['no_kirim']; ?>" disabled></td>
			</tr>
			<tr>
				<td width="70%">&nbsp;</td>
				<th>Pengirim</th>
				<td>:</td>
				<td><input id="idPengirimEdit" type="text" class="form-control" autocomplete="off" placeholder="Pengirim" value="<?php echo $data1['pengirim']; ?>" ></td>
			</tr>
			<tr>
				<td width="70%">&nbsp;</td>
				<th>Tanggal</th>
				<td>:</td>
				<td><input id="idTanggalEdit" type="text" class="form-control" autocomplete="off" placeholder="Tanggal" value="<?php echo $data1['tanggal']; ?>" ></td>
			</tr>
			<tr>
				<td width="70%">&nbsp;</td>
				<th>Admin</th>
				<td>:</td>
				<td><?php echo $data1['admin']; ?></td>
			</tr>
			<tr>
				<td width="70%">&nbsp;</td>
				<th>&nbsp;</th>
				<td>&nbsp;</td>
				<td><button id="btnUpHeader" type="button" class="btn btn-primary pull-right">Update</button></td>
			</tr>
		</table>
	<?php
	
	$sql2 = "SELECT * FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
		<table class="table">
			<tr>
				<th>No.</th>
				<th>Kode Barang</th>
				<th>Nama Barang</th>
				<th>Jumlah</th>
				<th>Jenis Satuan</th>
				<th>Isi</th>
				<th>Total</th>
				<th>&nbsp;</th>
				<th>Option</th>
			</tr>
		<?php
		$a = 0;
		while($data2 = mysql_fetch_array($hasil2)){
			$a++;
			?>
			<tr>
				<td width="65"><input id="idKirimKeEdit<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['pengiriman_ke']; ?>" disabled></td>
				<td><input id="idKodeBarangEdit<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['kode_barang_jadi']; ?>" disabled></td>
				<td><input id="idNamaBarangEdit<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['nama_barang_jadi']; ?>" disabled></td>
				<td><input id="idJumlahBarangEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Jumlah Satuan" value="<?php echo $data2['jumlah']; ?>" required></td>
				<td><select id="idJenisSatuanEdit<?php echo $a; ?>" class="form-control satuan"></select></td>
				<td><input id="idIsiEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Isi" value="<?php echo $data2['isi']; ?>" required></td>
				<td><input id="idTotalEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Total" value="<?php echo $data2['total']; ?>" disabled></td>
				<td><input id="idSatuanKecil<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['satuan_kecil']; ?>" disabled required></td>
				<td><button id="btnUpdateDetail<?php echo $a; ?>" type="submit" class="btn btn-primary">Update</button></td>
			</tr>

		<script type="text/javascript">
		$(document).ready(function(){
			var ax = "<?php echo $a; ?>";
			var sat = "<?php echo $data2['nama_satuan']; ?>";

			// id satuan
			$("#idJenisSatuanEdit"+ax).load("../src/produksi/proses_pengiriman_barang.php?action=listSatuan", function(){
				$("select#idJenisSatuanEdit"+ax).val(sat);
			});

			$("#idJenisSatuanEdit"+ax).change(function(){
				var satuan = $("#idJenisSatuanEdit"+ax).val();
				var jumlah = $("#idJumlahBarangEdit"+ax).val();

				$.ajax({
					url: "../src/produksi/proses_pengiriman_barang.php?action=autoSatuanKecil&sat="+satuan,
					cache: false,
					success: function(h){
						var hasilArr = h.split("*");
						$("#idIsiEdit"+ax).val(hasilArr[0]);
						$("#idSatuanKecil"+ax).val(hasilArr[1]);
						var hasil = jumlah * hasilArr[0];
						$("#idTotalEdit"+ax).val(hasil);
					}
				});
			});

			$("#idJumlahBarangEdit"+ax).keyup(function(){
				var isi = $("#idIsiEdit"+ax).val();
				var jmlBarang = $("#idJumlahBarangEdit"+ax).val();
				var total = isi * jmlBarang;
				$("#idTotalEdit"+ax).val(total);
			});

			$("#idIsiEdit"+ax).keyup(function(){
				var isi = $("#idIsiEdit"+ax).val();
				var jmlBarang = $("#idJumlahBarangEdit"+ax).val();
				var isi = isi * jmlBarang;
				$("#idTotalEdit"+ax).val(isi);
			});

			// update detail di klik
			$("#btnUpdateDetail"+ax).click(function(){
				var vKodePengirimanEdit = $("#idKodePengirimanEdit").val();
				var vKodeBarangEdit = $("#idKodeBarangEdit"+ax).val();
				var vNamaBarangEdit = $("#idNamaBarangEdit"+ax).val();
				var vJumlahBarangEdit = $("#idJumlahBarangEdit"+ax).val();
				var vJenisSatuanEdit = $("#idJenisSatuanEdit"+ax).val();
				var vIsiEdit = $("#idIsiEdit"+ax).val();
				var vTotalEdit  = $("#idTotalEdit"+ax).val();
				var vSatuanKecil = $("#idSatuanKecil"+ax).val();
				var vKirimKeEdit = $("#idKirimKeEdit"+ax).val();
				
				$.ajax({
					type: "POST",
					url: "../src/produksi/proses_pengiriman_barang.php?action=upDetail",
					data:{
						pKodePengirimanEdit:vKodePengirimanEdit,
						pKirimKeEdit:vKirimKeEdit,
						pKodeBarangEdit:vKodeBarangEdit,
						pNamaBarangEdit:vNamaBarangEdit,
						pJumlahBarangEdit:vJumlahBarangEdit,
						pJenisSatuanEdit:vJenisSatuanEdit,
						pIsiEdit:vIsiEdit,
						pSatuanKecil:vSatuanKecil,
						pTotalEdit:vTotalEdit
					},
					cache: false,
					success: function(hasilUpDetail){
						alert(hasilUpDetail);
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

elseif($action == "upHeader"){
	$pengirim = $_POST['pPengirimEdit'];
	$tanggal = $_POST['pTanggalEdit'];
	$kode = $_POST['pKodePengirimanEdit'];

	$hasilUpHeader = mysql_query("UPDATE tb_pengiriman_produksi SET
								pengirim = '$pengirim',
								tanggal = '$tanggal'
								WHERE no_kirim = '$kode'");

	if($hasilUpHeader){
		echo "Update sukses";
	}
	else{
		echo "Update gagal";
	}

}

elseif ($action == "upDetail") {
	$kode = $_POST['pKodePengirimanEdit'];
	$kirimKe = $_POST['pKirimKeEdit'];
	$kodeBarang = $_POST['pKodeBarangEdit'];
	$namaBarang = $_POST['pNamaBarangEdit'];
	$jumlah = $_POST['pJumlahBarangEdit'];
	$jenisSatuan = $_POST['pJenisSatuanEdit'];
	$isi = $_POST['pIsiEdit'];
	$total = $_POST['pTotalEdit'];
	$satuanKecil = $_POST['pSatuanKecil'];

	$hasilUpDetail = mysql_query("UPDATE tb_pengiriman_produksi_detail SET
								jumlah = '$jumlah',
								nama_satuan = '$jenisSatuan',
								isi = '$isi',
								total = '$total',
								satuan_kecil = '$satuanKecil'
								WHERE no_kirim = '$kode' AND pengiriman_ke = '$kirimKe'");

	if($hasilUpDetail){
		echo "Update sukses";
	}
	else{
		echo "Update gagal";
	}
}

elseif ($action == "autoSatuanKecil") {
	$satuan = $_GET['sat'];
	$hasil = mysql_query("SELECT nilai, satuan_kecil FROM tb_satuan WHERE nama_satuan = '$satuan'");
	$data = mysql_fetch_array($hasil);
	echo $data['nilai']."*".$data['satuan_kecil'];
}

elseif ($action == "printPengiriman") {
	$kode = mysql_real_escape_string($_GET['pKode']);
	$sql1 = "SELECT * FROM tb_pengiriman_produksi WHERE no_kirim = '$kode'";
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
			<td>SURAT PENGIRIMAN BARANG</td>
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
	
	<br>
	<br>
	<br>
	Bersama Ini kami kirimkan bahan - bahan sebagai berikut :
	<hr>
	<?php
	
	$sql2 = "SELECT * FROM tb_pengiriman_produksi_detail WHERE no_kirim = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table" width="100%">
		<tr>
			<td><b>No</b></td>
			<td><b>Kode Barang</b></td>
			<td><b>Nama Barang</b></td>
			<td><b>Jumlah</b></td>
			<td><b>Satuan</b></td>
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
			<td><?php echo $data2['isi']; ?></td>
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
	
	//$sql2 = "SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode'";
	//$hasil2 = mysql_query($sql2);
	?>
	<table class="table" width="100%">
		<tr>
			<td width="20%"><b>Pengirim</b></td>
			<td width="20%"><b></b></td>
			<td width="20%"><b></b></td>
			<td width="20%"><b></b></td>
		</tr>
	<?php
	//while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td width="20%"><br><br><br><br><br><br></td>
			<td width="20%"><br><br><br><br><br><br></td>
			<td width="20%"><br><br><br><br><br><br></td>
			<td width="20%"><br><br><br><br><br><br></td>
		</tr>	
		<tr>
			<td width="20%"><u><b><?php echo $data1['pengirim']; ?></b></u></td>
			<td width="20%"><u><b></b></u></td>
			<td width="20%"><u><b></b></u></td>
			<td width="20%"><u><b></b></u></td>
		</tr>	
		<?php
	//}
	?>
	</table>
	<script type="text/javascript">
	window.print();
	</script>
	<?php
}

elseif ($action == "cekNomer") {
	$nomer = $_GET['nomer'];
	$hasil = mysql_query("SELECT COUNT(no_kirim) AS cek FROM tb_pengiriman_produksi WHERE no_kirim = '$nomer'");
	$data = mysql_fetch_array($hasil);
	echo $data['cek'];
}


?>