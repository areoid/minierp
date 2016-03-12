<?php
// proses belanja
require_once("../../config/configuration.php");

$action = $_GET['action'];


if($action == "tambahPermintaan"){
	$kode = mysql_real_escape_string($_POST['pKode']);
	$tanggal = mysql_real_escape_string($_POST['pTanggal']);
	$admin = mysql_real_escape_string($_POST['pAdmin']);
	$pengirim = mysql_real_escape_string($_POST['pPengirim']);
	
	$sql = "INSERT INTO tb_permintaan_produksi VALUES('', '$kode', '$tanggal', '$admin', '$pengirim', 'Menunggu')";
	$hasil = mysql_query($sql);
	if($hasil){
		echo "ok";
	}
	else{
		echo "gagal";
	}
}

elseif($action == "tambahDetailPermintaan"){
	// cek row terbaru
	$cek = "SELECT MAX(id_permintaan) AS cek FROM tb_permintaan_produksi";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKodePermintaan = "SELECT no_spi FROM tb_permintaan_produksi WHERE id_permintaan = '$dataCek[cek]'";
	$hasilKodePermintaan = mysql_query($sqlKodePermintaan);
	$dataTemp = mysql_fetch_array($hasilKodePermintaan);
	$kodePermintaan = $dataTemp['no_spi'];

	$kodeBarang = $_POST['pKodeBarang'];
	$namaBarang = $_POST['pNamaBarang'];
	$jumlahSatuan = $_POST['pJumlahSatuan'];
	$jenisSatuan = $_POST['pJenisSatuan'];
	$isi = $_POST['pIsi'];
	$total = $_POST['pTotalSatuan'];
	$satuanKecil = $_POST['pSatuanKecil'];

	// counting detail belanja
	$sqlCount = "SELECT count(*) AS hitung FROM `tb_permintaan_produksi_detail` WHERE no_spi = '$kodePermintaan'";
	$hasilCount = mysql_query($sqlCount);
	$dataCount = mysql_fetch_array($hasilCount);
	$countPermintaan = $dataCount['hitung'];
	$countPermintaan++;
	
	$sql = "INSERT INTO tb_permintaan_produksi_detail VALUES('$kodePermintaan','$kodeBarang', '$namaBarang', '$jumlahSatuan','$jenisSatuan','$isi', '$satuanKecil', '$total','$countPermintaan', 'Menunggu')";
	$hasil = mysql_query($sql) or die("gagal");
	if($hasil){
		echo "Sukses";
	}
	else{
		echo "Gagal";
	}
}

elseif($action == "headerPermintaan"){
	// cek row terbaru
	$cek = "SELECT id_permintaan FROM tb_permintaan_produksi ORDER BY id_permintaan DESC LIMIT 1";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sql = "SELECT * FROM tb_permintaan_produksi WHERE id_permintaan = '$dataCek[id_permintaan]'";
	$hasil = mysql_query($sql);
	$data = mysql_fetch_array($hasil);
	?>
	<table class="table" width="100%" border="1">
		<tr>
			<td width="75%">&nbsp;</td>
			<th>No.SPI</th>
			<td>:</td>
			<td id="kdPermintaan"><?php echo $data['no_spi']; ?></td>
		</tr>
		<tr>
			<td width="75%">&nbsp;</td>
			<th>Tanggal</th>
			<td>:</td>
			<td><?php echo $data['tanggal']; ?></td>
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

elseif($action == "listDetailPermintaan"){
	// cek row terbaru
	$cek = "SELECT id_permintaan FROM tb_permintaan_produksi ORDER BY id_permintaan DESC LIMIT 1";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKodeKeluar = "SELECT no_spi FROM tb_permintaan_produksi WHERE id_permintaan = '$dataCek[id_permintaan]'";
	$hasilKodeKeluar = mysql_query($sqlKodeKeluar);
	$dataKodeKeluar = mysql_fetch_array($hasilKodeKeluar);
	$kodeKeluar = $dataKodeKeluar['no_spi'];

	$sql = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$kodeKeluar'";
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
			<td id="idCountKeluar"><?php echo $data['permintaan_ke']; ?></td>
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

elseif($action == "cancelPermintaan"){
	// cek row terbaru
	$cek = "SELECT MAX(id_permintaan) AS cek FROM tb_permintaan_produksi";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKodePermintaan = "SELECT no_spi FROM tb_permintaan_produksi WHERE id_permintaan = '$dataCek[cek]'";
	$hasilKodePermintaan = mysql_query($sqlKodePermintaan);
	$dataTemp = mysql_fetch_array($hasilKodePermintaan);
	$kdPermintaan = $dataTemp['no_spi'];

	$sqlDetailPermintaan = "DELETE FROM tb_permintaan_produksi_detail WHERE no_spi = '$kdPermintaan'";
	$hasilDelDetailPermintaan = mysql_query($sqlDetailPermintaan);
	if($hasilDelDetailPermintaan){
		echo "Delete detail OK";
	}
	else{
		echo "Delete detail gagal";
	}

	$sqlPermintaan = "DELETE FROM tb_permintaan_produksi WHERE no_spi = '$kdPermintaan'";
	$hasilDelPermintaan = mysql_query($sqlPermintaan);
	if($hasilDelPermintaan){
		echo "Delete permintaan OK";
	}
	else{
		echo "Delete permintaan gagal";
	}
}

elseif($action == "listPermintaanMen"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListKeluarMen").dataTable({
			"aaSorting": [[ 0, "desc" ]]
		});
	});
	</script>
	<?php
	$sql = "SELECT * FROM tb_permintaan_produksi WHERE status_permintaan = 'Menunggu' ORDER BY id_permintaan ASC";
	$hasil = mysql_query($sql);
	?>
	<legend>Status : Menunggu</legend>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListKeluarMen">
		<thead>
			<tr>
				<th>Permintaan ke</th>
				<th>No. SPI</th>
				<th>Tanggal</th>
				<th>Admin</th>
				<th>Option</th>
			</tr>
		</thead>
		<tbody>
	<?php
	while($data = mysql_fetch_array($hasil)){
			?>
			<tr>
				<td><?php echo $data['id_permintaan']; ?></td>
				<td><?php echo $data['no_spi']; ?></td>
				<td><?php echo $data['tanggal']; ?></td>
				<td><?php echo $data['admin']; ?></td>
				<td width="175px">
					<button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatPermintaan('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span></button>
					<button type="button" title="Edit" class="btn btn-default" onclick="javascript:editPermintaan('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
					<button type="button" title="hapus" class="btn btn-default" onclick="javascript:hapusPermintaan('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
					<button type="button" title="print" class="btn btn-default" onclick="javascript:printPermintaan('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-print"></span></button>
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

elseif($action == "listPermintaanTer"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListKeluarTer").dataTable({
			"aaSorting": [[ 0, "desc" ]]
		});
	});
	</script>
	<?php
	$sql = "SELECT * FROM tb_permintaan_produksi WHERE status_permintaan = 'Terkonfirmasi' ORDER BY id_permintaan ASC";
	$hasil = mysql_query($sql);
	?>
	<legend>Status : Terkonfirmasi</legend>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListKeluarTer">
		<thead>
			<tr>
				<th>Permintaan ke</th>
				<th>No. SPI</th>
				<th>Tanggal</th>
				<th>Admin</th>
				<th>Option</th>
			</tr>
		</thead>
		<tbody>
	<?php
	while($data = mysql_fetch_array($hasil)){
			?>
			<tr>
				<td><?php echo $data['id_permintaan']; ?></td>
				<td><?php echo $data['no_spi']; ?></td>
				<td><?php echo $data['tanggal']; ?></td>
				<td><?php echo $data['admin']; ?></td>
				<td width="175px">
					<button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatTerkonfirmasi('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span></button>
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

elseif($action == "lihatPermintaan"){
	$kode = mysql_real_escape_string($_POST['pKode']);
	$sql1 = "SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
	<table class="table">
		<tr>
			<td width="70%">&nbsp;</td>
			<th>No. SPI</th>
			<td>:</td>
			<td id="kdKeluar"><?php echo $data1['no_spi']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Pengirim</th>
			<td>:</td>
			<td><?php echo $data1['pengirim']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Tanggal Pengeluaran</th>
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
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table">
		<tr>
			<th>No. </th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Isi</th>
			<th>Total Satuan</th>
			<th>Status</th>
		</tr>
	<?php
	while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td><?php echo $data2['permintaan_ke']; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah_satuan']." ".$data2['jenis_satuan']; ?></td>
			<td><?php echo $data2['isi']; ?></td>
			<td><?php echo $data2['total_satuan']." ".$data2['satuan_kecil']; ?></td>
			<td><?php echo $data2['status_permintaan']; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php

}

elseif($action == "lihatTerkonfirmasi"){
	$kode = mysql_real_escape_string($_POST['pKode']);
	$sql1 = "SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
	<table class="table">
		<tr>
			<td width="70%">&nbsp;</td>
			<th>No. SPI</th>
			<td>:</td>
			<td id="kdKeluar"><?php echo $data1['no_spi']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Pengirim</th>
			<td>:</td>
			<td><?php echo $data1['pengirim']; ?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<th>Tanggal Pengeluaran</th>
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
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$kode' AND status_permintaan = 'Terkonfirmasi'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table">
		<tr>
			<th>No. </th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Isi</th>
			<th>Total Satuan</th>
			<th>Status</th>
		</tr>
	<?php
	while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td><?php echo $data2['permintaan_ke']; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah_satuan']." ".$data2['jenis_satuan']; ?></td>
			<td><?php echo $data2['isi']; ?></td>
			<td><?php echo $data2['total_satuan']." ".$data2['satuan_kecil']; ?></td>
			<td><?php echo $data2['status_permintaan']; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php

}

elseif($action == "hapusPermintaan"){
	$kodePermintaan = $_POST['pKode'];

	$hasilDelPermintaan = mysql_query("DELETE FROM tb_permintaan_produksi WHERE no_spi = '$kodePermintaan'");
	$hasilDelPermintaanDetail = mysql_query("DELETE FROM tb_permintaan_produksi_detail WHERE no_spi = '$kodePermintaan'");

	if($hasilDelPermintaan && $hasilDelPermintaanDetail){
		echo  "Hapus sukses";
	}
	else{
		echo "Hapus gagal";
	}

}

elseif($action == "editPermintaan"){
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
			var vKodePermintaanEdit = $("#idKodePermintaanEdit").val();

			$.ajax({
				type: "POST",
				url: "../src/produksi/proses_permintaan_barang.php?action=upHeader",
				data:{
					pKodePermintaanEdit:vKodePermintaanEdit,
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
	$sql1 = "SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
		<table class="table">
			<tr>
				<td width="70%">&nbsp;</td>
				<th>No. SPI</th>
				<td>:</td>
				<td id="kdKeluar"><input id="idKodePermintaanEdit" type="text" class="form-control" autocomplete="off" placeholder="No.SPI" value="<?php echo $data1['no_spi']; ?>" disabled></td>
			</tr>
			<tr>
				<td width="70%">&nbsp;</td>
				<th>Pengirim</th>
				<td>:</td>
				<td><input id="idPengirimEdit" type="text" class="form-control" autocomplete="off" placeholder="Pengirim" value="<?php echo $data1['pengirim']; ?>" ></td>
			</tr>
			<tr>
				<td width="70%">&nbsp;</td>
				<th>Tanggal Pengeluaran</th>
				<td>:</td>
				<td><input id="idTanggalEdit" type="text" class="form-control" autocomplete="off" placeholder="Tanggal Pengeluaran" value="<?php echo $data1['tanggal']; ?>" ></td>
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
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$kode'";
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
				<td width="65"><input id="idKeluarKeEdit<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['permintaan_ke']; ?>" disabled></td>
				<td><input id="idKodeBarangEdit<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['kode_barang']; ?>" disabled></td>
				<td><input id="idNamaBarangEdit<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['nama_barang']; ?>" disabled></td>
				<td><input id="idJumlahBarangEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Jumlah Satuan" value="<?php echo $data2['jumlah_satuan']; ?>" required></td>
				<td><select id="idJenisSatuanEdit<?php echo $a; ?>" class="form-control satuan"></select></td>
				<td><input id="idIsiEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Isi" value="<?php echo $data2['isi']; ?>" required></td>
				<td><input id="idTotalEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Total Satuan" value="<?php echo $data2['total_satuan']; ?>" disabled></td>
				<td><input id="idSatuanKecil<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['satuan_kecil']; ?>" disabled required></td>
				<td><button id="btnUpdateDetail<?php echo $a; ?>" type="submit" class="btn btn-primary">Update</button></td>
			</tr>
		<script type="text/javascript">
		$(document).ready(function(){
			var ax = "<?php echo $a; ?>";
			var sat = "<?php echo $data2['jenis_satuan']; ?>";

			// id satuan
			$("#idJenisSatuanEdit"+ax).load("../src/produksi/proses_permintaan_barang.php?action=listSatuan", function(){
				$("select#idJenisSatuanEdit"+ax).val(sat);
			});

			$("#idJenisSatuanEdit"+ax).change(function(){
				var satuan = $("#idJenisSatuanEdit"+ax).val();
				var jumlah = $("#idJumlahBarangEdit"+ax).val();

				$.ajax({
					url: "../src/produksi/proses_permintaan_barang.php?action=autoSatuanKecil&sat="+satuan,
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
				var vKodePermintaanEdit = $("#idKodePermintaanEdit").val();
				var vKodeBarangEdit = $("#idKodeBarangEdit"+ax).val();
				var vNamaBarangEdit = $("#idNamaBarangEdit"+ax).val();
				var vJumlahBarangEdit = $("#idJumlahBarangEdit"+ax).val();
				var vJenisSatuanEdit = $("#idJenisSatuanEdit"+ax).val();
				var vIsiEdit = $("#idIsiEdit"+ax).val();
				var vTotalEdit  = $("#idTotalEdit"+ax).val();
				var vSatuanKecil = $("#idSatuanKecil"+ax).val();
				var vKeluarKeEdit = $("#idKeluarKeEdit"+ax).val();
				
				$.ajax({
					type: "POST",
					url: "../src/produksi/proses_permintaan_barang.php?action=upDetail",
					data:{
						pKodePermintaanEdit:vKodePermintaanEdit,
						pKeluarKeEdit:vKeluarKeEdit,
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
	$kode = $_POST['pKodePermintaanEdit'];

	$hasilUpHeader = mysql_query("UPDATE tb_permintaan_produksi SET
								pengirim = '$pengirim',
								tanggal = '$tanggal'
								WHERE no_spi = '$kode'");

	if($hasilUpHeader){
		echo "Update sukses";
	}
	else{
		echo "Update gagal";
	}

}

elseif ($action == "upDetail") {
	$kode = $_POST['pKodePermintaanEdit'];
	$keluarKe = $_POST['pKeluarKeEdit'];
	$kodeBarang = $_POST['pKodeBarangEdit'];
	$namaBarang = $_POST['pNamaBarangEdit'];
	$jumlah = $_POST['pJumlahBarangEdit'];
	$jenisSatuan = $_POST['pJenisSatuanEdit'];
	$isi = $_POST['pIsiEdit'];
	$total = $_POST['pTotalEdit'];
	$satuanKecil = $_POST['pSatuanKecil'];

	$hasilUpDetail = mysql_query("UPDATE tb_permintaan_produksi_detail SET
								jumlah_satuan = '$jumlah',
								jenis_satuan = '$jenisSatuan',
								isi = '$isi',
								total_satuan = '$total',
								satuan_kecil = '$satuanKecil'
								WHERE no_spi = '$kode' AND permintaan_ke = '$keluarKe'");

	if($hasilUpDetail){
		echo "Update sukses";
	}
	else{
		echo "Update gagal";
	}
}

elseif ($action == "printPermintaan") {
	$kode = mysql_real_escape_string($_GET['pKode']);
	$sql1 = "SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode'";
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
			<th align="left">Tanggal Permintaan </th>
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
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$kode'";
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
			<td><?php echo $data2['permintaan_ke']; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah_satuan']; ?></td>
			<td><?php echo $data2['jenis_satuan']; ?></td>
			<td><?php echo $data2['isi']; ?></td>
			<td><?php echo $data2['total_satuan']." ".$data2['satuan_kecil']; ?></td>
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
	
	$sql2 = "SELECT * FROM tb_permintaan_produksi WHERE no_spi = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table" width="100%">
		<tr>
			<td width="20%"><b>Permintaan</b></td>
			<td width="20%"><b></b></td>
			<td width="20%"><b></b></td>
			<td width="20%"><b></b></td>
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
			<td width="20%"><u><b><?php echo $data2['pengirim']; ?></b></u></td>
			<td width="20%"><u><b></b></u></td>
			<td width="20%"><u><b></b></u></td>
			<td width="20%"><u><b></b></u></td>
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
	$cek2 = "SELECT no_spi, tanggal_keluar FROM tb_permintaan_produksi WHERE cek = '$dataCek[cek]'";
	$hasilCek2 = mysql_query($cek2) or die("query 2 salah");
	$dataKodeKeluarOk = mysql_fetch_array($hasilCek2);
	//echo "Get kode belanja".$dataKodeBelanjaOk['kode_belanja'];

	// lihat list belanja berdasarkan kode belanja terakhir
	$sql = "SELECT * FROM tb_permintaan_produksi_detail WHERE no_spi = '$dataKodeKeluarOk[no_spi]'";
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
		$sqlTransaksi = "INSERT INTO tb_jurnal_transaksi VALUES('', '$dataStokBarang[kode_barang]', 'Barang Keluar', '$data[jumlah]', '$dataKodeKeluarOk[tanggal_keluar]', '$dataKodeKeluarOk[no_spi]', '$keluarKe')"; 
		mysql_query($sqlTransaksi) or die ("query entri tb_jurnal_transaksi salah");
		

	}

}

elseif ($action == "listSatuan") {
	$tampil=mysql_query("SELECT * FROM tb_satuan ORDER BY id_satuan");
	echo "<option value='--' selected>- Pilih Jenis Satuan -</option>";

	while($w=mysql_fetch_array($tampil))
	{
	    echo "<option value='$w[nama_satuan]'>$w[nama_satuan]</option>";        
	}
}

elseif ($action == "autoSatuanKecil") {
	$satuan = $_GET['sat'];
	$hasil = mysql_query("SELECT nilai, satuan_kecil FROM tb_satuan WHERE nama_satuan = '$satuan'");
	$data = mysql_fetch_array($hasil);
	echo $data['nilai']."*".$data['satuan_kecil'];
}

elseif ($action == "cekKode") {
	$kode = $_GET['pKode'];
	$hasil = mysql_query("SELECT COUNT(no_spi) AS cek FROM tb_permintaan_produksi WHERE no_spi = '$kode'");
	$data = mysql_fetch_array($hasil);
	echo $data['cek'];
}

?>
