<?php
// proses barang keluar
require_once("../../config/configuration.php");

$action = $_GET['action'];


if($action == "tambahPengeluaranEks"){
	$kodePengeluaranEks = mysql_real_escape_string($_POST['pKodePengeluaranEks']);
	$tanggalPengeluaranEks = mysql_real_escape_string($_POST['pTanggalPengeluaranEks']);
	$kepada = mysql_real_escape_string($_POST['pKepada']);
	$alamat = mysql_real_escape_string($_POST['pAlamat']);
	$admin = mysql_real_escape_string($_POST['pAdmin']);
	
	$sql = "INSERT INTO tb_pengeluaran VALUES('','$kodePengeluaranEks', '$kepada', '$alamat', '$admin', '$tanggalPengeluaranEks')";
	$hasil = mysql_query($sql);
	if($hasil){
		echo "ok";
	}
	else{
		echo "Kode Belanja Salah";
	}
}


elseif($action == "tambahDetailPengeluaranEks"){
	// cek row terbaru
	$cek = "SELECT MAX(id_pengeluaran) AS cek FROM tb_pengeluaran";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKodeSPI = "SELECT no_spi FROM tb_pengeluaran WHERE id_pengeluaran = '$dataCek[cek]'";
	$hasilKodeSPI = mysql_query($sqlKodeSPI);
	$dataTemp = mysql_fetch_array($hasilKodeSPI);
	$noSPI = $dataTemp['no_spi'];

	$kodeBarang = $_POST['pKodeBarang'];
	$namaBarang = $_POST['pNamaBarang'];
	$jumlah = $_POST['pJumlah'];
	$satuan = $_POST['pSatuan'];
	$total = $_POST['pTotal'];
	$satuanKecil = $_POST['pSatuanKecil'];

	// counting detail belanja
	$sqlCount = "SELECT count(*) AS hitung FROM tb_pengeluaran_detail WHERE no_spi = '$noSPI'";
	$hasilCount = mysql_query($sqlCount);
	$dataCount = mysql_fetch_array($hasilCount);
	$countPengeluaran = $dataCount['hitung'];
	$countPengeluaran++;
	
	$sql = "INSERT INTO tb_pengeluaran_detail VALUES('$noSPI', '$kodeBarang', '$namaBarang', '$jumlah', '$satuan', '$satuanKecil', '$total', '$countPengeluaran')";
	$hasil = mysql_query($sql) or die("fuck");
	if($hasil){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
	
}

elseif($action == "headerPengeluaranEks"){
	// cek row terbaru
	$cek = "SELECT MAX(id_pengeluaran) AS cek FROM tb_pengeluaran";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sql = "SELECT * FROM tb_pengeluaran WHERE id_pengeluaran = '$dataCek[cek]'";
	$hasil = mysql_query($sql);
	$data = mysql_fetch_array($hasil);
	?>
<div class="clearfix">
	<table class="clearfix" width="100%">
	  	<tr>
	    	<td rowspan="2"><h1>PT Ongkowidjojo</h1></td>
	    	<td>&nbsp;</td>
	    	<td>Jl. Kol. Sugiono 80 Malang 65134</td>
	    	<td>&nbsp;</td>
	  	</tr>
	  	<tr>
	    	<td>&nbsp;</td>
	    	<td>Telp: (0341) 362211</td>
	    	<td>Fax: (0341) 328139</td>
	  	</tr>
	</table>
	<h1 align="center">SURAT JALAN</h1>
	<table class="clearfix" width="25%" align="right" border="0">
		<tr>
			<td width="10%">No. </td>
			<td id="kodePengeluaranEksternal"><?php echo $data['no_spi']; ?></td>
		</tr>
		<tr>
			<td></td>
			<td><hr /></td>
		</tr>
		<tr>
			<td>Tanggal </td>
			<td><?php echo $data['tanggal']; ?></td>
		</tr>
	</table>
	<table class="clearfix" width="100%" border="1">
		<tr>
			<td width="75%">Kepada Yth. <b><?php echo $data['kepada']; ?><b></td>
			<td>Kendaraan:</td>
			<td width="18%">&nbsp;</td>
		</tr>
		<tr>
			<td width="75%">Bersama ini kami kirimkan bahan-bahan sebagai berikut: </td>
			<td>Pengemudi:</td>
			<td width="18%">&nbsp;</td>
			
		</tr>
	</table>
</div>
	<?php
}

elseif($action == "listDetailPengeluaranEks"){
	// cek row terbaru
	$cek = "SELECT MAX(id_pengeluaran) AS cek FROM tb_pengeluaran";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlNoSpi = "SELECT no_spi FROM tb_pengeluaran WHERE id_pengeluaran = '$dataCek[cek]'";
	$hasilNoSpi = mysql_query($sqlNoSpi);
	$dataNoSpi = mysql_fetch_array($hasilNoSpi);
	$noSPI = $dataNoSpi['no_spi'];

	$sql = "SELECT * FROM tb_pengeluaran_detail WHERE no_spi = '$noSPI'";
	$hasil = mysql_query($sql);
	?>
	<table class="clearfix" width="100%" border="1">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Harga Barang</th>
			<th>Jumlah</th>
			<th>Sat.</th>
			<th>Total</th>
		</tr>
	<?php
	while($data = mysql_fetch_array($hasil)){
		?>
		<tr>
			<td id="idCountBelanja"><?php echo $data['pengeluaran_ke']; ?></td>
			<td><?php echo $data['kode_barang']; ?></td>
			<td><?php echo $data['nama_barang']; ?></td>
			<td><?php echo $data['jumlah']; ?></td>
			<td><?php echo $data['nama_satuan']; ?></td>
			<td><?php echo $data['total']; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}

elseif ($action == "autoHitung") {
	$sat = $_GET['sat'];
	$hasilSat = mysql_query("SELECT nilai, satuan_kecil FROM tb_satuan WHERE nama_satuan = '$sat'");
	$dataNilai = mysql_fetch_array($hasilSat);
	echo $dataNilai['nilai']."*".$dataNilai['satuan_kecil'];
}

elseif($action == "autoKodeBarang"){
	$kode = $_GET['term'];
	$tahunSekarang = date('Y');
	$sql = "SELECT kode_barang FROM tb_barang WHERE kode_barang LIKE '$kode%' AND tahun_produksi = '$tahunSekarang'";
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

elseif($action == "cancelPengeluaran"){
	// row terbaru
	$hasilCek = mysql_query("SELECT MAX(id_pengeluaran) AS cek FROM tb_pengeluaran");
	$dataCek = mysql_fetch_array($hasilCek);

	$hasilPengeluaran = mysql_query("SELECT no_spi FROM tb_pengeluaran WHERE id_pengeluaran = '$dataCek[cek]'");
	$dataPengeluaran = mysql_fetch_array($hasilPengeluaran);

	$sqlDetailPengeluaran = "DELETE FROM tb_pengeluaran_detail WHERE no_spi = '$dataPengeluaran[no_spi]'";
	$hasilDelDetailPengeluaran = mysql_query($sqlDetailPengeluaran);
	if($hasilDelDetailPengeluaran){
		echo "Delete detail OK";
	}
	else{
		echo "Delete detail gagal";
	}

	$sqlPengeluran = "DELETE FROM tb_pengeluaran WHERE no_spi = '$dataPengeluaran[no_spi]'";
	$hasilDelPengeluaran = mysql_query($sqlPengeluran);
	if($hasilDelPengeluaran){
		echo "Delete belanja OK";
	}
	else{
		echo "Delete belanja gagal";
	}
	
}

elseif($action == "listPengeluaranEks"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListPengeluaranEks").dataTable({
			"aaSorting": [[ 0, "desc" ]]
		});
	});
	</script>
	<?php
	$sql = "SELECT * FROM tb_pengeluaran";
	$hasil = mysql_query($sql);
	?>
	<h3 align="center">List Pengeluaran</h3>
<div class="thumbnail">
	<div class="caption">
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListPengeluaranEks">
		<thead>
			<tr>
				<th>Pengeluaran ke</th>
				<th>No. Surat</th>
				<th>Tanggal</th>
				<th>Kepada</th>
				<th>Admin</th>
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
				<td width="15%"><?php echo $no; ?></td>
				<td><?php echo $data['no_spi']; ?></td>
				<td><?php echo $data['tanggal']; ?></td>
				<td><?php echo $data['kepada']; ?></td>
				<td><?php echo $data['admin']; ?></td>
				<td width="175px">
					<button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatPengeluaranEks('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span></button>
					<button type="button" title="Edit" class="btn btn-default" onclick="javascript:editPengeluaranEks('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
					<button type="button" title="hapus" class="btn btn-default" onclick="javascript:hapusPengeluaranEks('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
					<button type="button" title="print" class="btn btn-default" onclick="javascript:printPengeluaranEks('<?php echo $data['no_spi']; ?>');"><span class="glyphicon glyphicon-print"></span></button>
				</td>
			</tr>
			<?php
	}
	?>
		</tbody>
		<thead>
		</thead>
	</table>
	</div>
</div>
	<?php
}

elseif($action == "lihatPengeluaranEks"){
	$kode = mysql_real_escape_string($_POST['pKode']);
	$sql1 = "SELECT * FROM tb_pengeluaran WHERE no_spi = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
	<table class="table">
		<tr>
			<td width="50%">&nbsp;</td>
			<th>No. Pengeluaran</th>
			<td>:</td>
			<td><?php echo $data1['no_spi']; ?></td>
		</tr>
		<tr>
			<td width="50%">&nbsp;</td>
			<th>Tanggal Pengeluaran</th>
			<td>:</td>
			<td><?php echo $data1['tanggal']; ?></td>
		</tr>
		<tr>
			<td width="50%">&nbsp;</td>
			<th>Kepada</th>
			<td>:</td>
			<td><?php echo $data1['kepada']; ?></td>
		</tr>
		<tr>
			<td width="50%">&nbsp;</td>
			<th>Alamat</th>
			<td>:</td>
			<td><?php echo $data1['alamat']; ?></td>
		</tr>
		<tr>
			<td width="50%">&nbsp;</td>
			<th>Admin</th>
			<td>:</td>
			<td><?php echo $data1['admin']; ?></td>
		</tr>
	</table>
	<?php
	
	$sql2 = "SELECT * FROM tb_pengeluaran_detail WHERE no_spi = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
	<table class="table">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah Barang</th>
			<th>Total</th>
		</tr>
	<?php
	$no = 0;
	while($data2 = mysql_fetch_array($hasil2)){
		$no++;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah']." ".$data2['nama_satuan']; ?></td>
			<td><?php echo $data2['total']." ".$data2['satuan_kecil']; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php

}

elseif($action == "hapusPengeluaranEks"){
	$kode = mysql_real_escape_string($_POST['pKode']);

	$hasilHapus1 = mysql_query("DELETE FROM tb_pengeluaran_detail WHERE no_spi = '$kode'");
	$hasilHapus2 = mysql_query("DELETE FROM tb_pengeluaran WHERE no_spi = '$kode'");
	$hasilHapus3 = mysql_query("DELETE FROM tb_jurnal_transaksi WHERE kode = '$kode'");

	if($hasilHapus3 AND $hasilHapus2 AND $hasilHapus3){
		echo "Hapus sukses";
	}
	else{
		echo "Hapus gagal";
	}
}

elseif($action == "editPengeluaranEks"){
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#idTanggalPengeluaranEdit").datepicker({ dateFormat: 'yy-mm-dd' });
		
		/*$("#idJumlahBarangEdit").keyup(function(){
			var harga = $("#idHargaBarangEdit").val();
			var jmlBarang = $("#idJumlahBarangEdit").val();
			var hasil = harga * jmlBarang;
			$("#idTotalHargaEdit").val(hasil);
		});
		$("#idHargaBarangEdit").keyup(function(){
			var harga = $("#idHargaBarangEdit").val();
			var jmlBarang = $("#idJumlahBarangEdit").val();
			var hasil = harga * jmlBarang;
			$("#idTotalHargaEdit").val(hasil);
		});*/

		// update header 
		$("#btnUpHeader").click(function(){
			var vKodePengeluaranEdit = $("#idKodePengeluaranEdit").val();
			var vKepadaEdit = $("#idKepadaEdit").val();
			var vAlamatEdit = $("#idAlamatEdit").val();
			var vTanggalEdit = $("#idTanggalPengeluaranEdit").val();
			$.ajax({
				type: "POST",
				url: "../src/gudang/proses_barang_keluar.php?action=upHeader",
				data: {
					pKodePengeluaranEdit:vKodePengeluaranEdit,
					pKepadaEdit:vKepadaEdit,
					pAlamatEdit:vAlamatEdit,
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
	$sql1 = "SELECT * FROM tb_pengeluaran WHERE no_spi = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
		<table class="table">
			<tr>
				<td width="50%">&nbsp;</td>
				<th>No. Pengeluaran</th>
				<td>:</td>
				<td id="kdPengeluaran"><input id="idKodePengeluaranEdit" type="text" class="form-control" autocomplete="off" placeholder="No. SPI" value="<?php echo $data1['no_spi']; ?>" disabled></td>
			</tr>
			<tr>
				<td width="50%">&nbsp;</td>
				<th>Kepada</th>
				<td>:</td>
				<td><input id="idKepadaEdit" type="text" class="form-control" autocomplete="off" placeholder="Kepada" value="<?php echo $data1['kepada']; ?>"></td>
			</tr>
			<tr>
				<td width="50%">&nbsp;</td>
				<th>Alamat</th>
				<td>:</td>
				<td><textarea id="idAlamatEdit" class="form-control" autocomplete="off" placeholder="Alamat"><?php echo $data1['alamat']; ?></textarea></td>
			</tr>
			<tr>
				<td width="50%">&nbsp;</td>
				<th>Tanggal Pengeluaran</th>
				<td>:</td>
				<td><input id="idTanggalPengeluaranEdit" type="text" class="form-control" autocomplete="off" placeholder="Tanggal Pengeluaran" value="<?php echo $data1['tanggal']; ?>"></td>
			</tr>
			<tr>
				<td width="50%">&nbsp;</td>
				<th>Admin</th>
				<td>:</td>
				<td><?php echo $data1['admin']; ?></td>
			</tr>
			<tr>
				<td width="50%">&nbsp;</td>
				<th>&nbsp;</th>
				<td>&nbsp;</td>
				<td><button id="btnUpHeader" class="pull-right btn btn-primary">Update</button></td>
			</tr>
		</table>
	<?php
	
	$sql2 = "SELECT * FROM tb_pengeluaran_detail WHERE no_spi = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
		<table class="table">
			<tr>
				<th>No.</th>
				<th>Kode Barang</th>
				<th>Nama Barang</th>
				<th>Jumlah</th>
				<th>Satuan</th>
				<th>Total</th>
			</tr>
		<?php
		$a = 0;
		while($data2 = mysql_fetch_array($hasil2)){
			$a++;
			?>
			<tr>
				<td width="65"><input id="idPengeluaranKe<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['pengeluaran_ke']; ?>" disabled></td>
				<td><input id="idKodeBarangEdit<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['kode_barang']; ?>" disabled></td>
				<td><input id="idNamaBarangEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Nama Barang Barang" value="<?php echo $data2['nama_barang']; ?>" disabled></td>
				<td><input id="idJumlahEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Jumlah Barang" value="<?php echo $data2['jumlah']; ?>" required></td>
				<td><select id="idSatuan<?php echo $a; ?>" class="form-control classSatuan"></select></td>
				<td><input id="idTotalEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Total harga" value="<?php echo $data2['total']; ?>" disabled></td>
				<td><input id="idSatuanKecil<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Satuan Kecil" value="<?php echo $data2['satuan_kecil']; ?>" disabled></td>
				<td><button id="btnUpdateDetail<?php echo $a; ?>" type="submit" class="btn btn-primary">Update</button></td>
			</tr>
		<script type="text/javascript">
		$(document).ready(function(){
			var ax = "<?php echo $a; ?>";
			var sat1 = "<?php echo $data2['nama_satuan']; ?>";

			/*$("#idJumlahBarangEdit"+ax).keyup(function(){
				var harga = $("#idHargaBarangEdit"+ax).val();
				var jmlBarang = $("#idJumlahEdit"+ax).val();
				var hasil = harga * jmlBarang;
				$("#idTotalHargaEdit"+ax).val(hasil);
			});
			$("#idHargaBarangEdit"+ax).keyup(function(){
				var harga = $("#idHargaBarangEdit"+ax).val();
				var jmlBarang = $("#idJumlahBarangEdit"+ax).val();
				var hasil = harga * jmlBarang;
				$("#idTotalHargaEdit"+ax).val(hasil);
			});*/

			// load satuan
			$("#idSatuan"+ax).load("../src/gudang/proses_belanja.php?action=listSatuan", function(){
				$("select#idSatuan"+ax).val(sat1);
			});

			//auto hitung
			// auto hitung
				$("#idSatuan"+ax).change(function(){
					var sat = $("#idSatuan"+ax).val();
					var jum = $("#idJumlahEdit"+ax).val();
					$.ajax({
						url: "../src/gudang/proses_barang_keluar.php?action=autoHitung&sat="+sat,
						cache: false,
						success: function(h){
							var hasilArr = h.split('*');
							$("#idSatuanKecil"+ax).val(hasilArr[1]);
							var jumlah = $("#idJumlahEdit"+ax).val();
							var total = jum * hasilArr[0];
							$("#idTotalEdit"+ax).val(total);
						}
					});
				});
				
				$("#idJumlahEdit"+ax).keyup(function(){
					var sat = $("#idSatuan"+ax).val();
					var jum = $("#idJumlahEdit"+ax).val();
					$.ajax({
						url: "../src/gudang/proses_barang_keluar.php?action=autoHitung&sat="+sat,
						cache: false,
						success: function(h){
							var hasilArr = h.split('*');
							var total = jum * hasilArr[0];
							$("#idTotalEdit"+ax).val(total);
						}
					});
				});

			$("#btnUpdateDetail"+ax).click(function(){
				var vKodePengeluaranEdit = $("#idKodePengeluaranEdit").val();
				var vKodeBarangEdit = $("#idKodeBarangEdit"+ax).val();
				var vNamaBarangEdit = $("#idNamaBarangEdit"+ax).val();
				var vJumlahEdit = $("#idJumlahEdit"+ax).val();
				var vPengeluaranKe = $("#idPengeluaranKe"+ax).val();
				var vTotalEdit = $("#idTotalEdit"+ax).val();
				var vSatuan = $("#idSatuan"+ax).val();

				$.ajax({
					type: "POST",
					url: "../src/gudang/proses_barang_keluar.php?action=upDetail",
					data:{
						pKode:vKodePengeluaranEdit,
						pKodeBarangEdit:vKodeBarangEdit,
						pNamaBarangEdit:vNamaBarangEdit,
						pJumlahEdit:vJumlahEdit,
						pPengeluaranKeEdit:vPengeluaranKe,
						pTotalEdit:vTotalEdit,
						pSatuanEdit:vSatuan
					},
					cache: false,
					success: function(hasil){
						alert(hasil);
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

elseif ($action == "printPengeluaranEks") {
	$kode = $_GET['pKode'];
	$sql1 = "SELECT * FROM tb_pengeluaran WHERE no_spi = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
	<style>
	table.x{
	border-collapse:collapse;
	}
	table.x, td.x, th.x{
	border:0px solid black;
	padding: 5px;
	}	

	table{
	border-collapse:collapse;
	}
	table, td, th{
	border:1px solid black;
	padding: 5px;
	}
	#kotak{
		margin: auto;
		padding: 5px;
		border:1px solid black;
	}
	</style>
	<table class = "table x" width="100%">
		<tr class="x">
			<td class="x"><b>PT ONGKOWIJOYO</b></td>
			<td class="x">&nbsp;</td>
			<td class="x" align="right">Jl.Gadang Selatan No.22 Malang, Jawa Timur 653149</td>
		</tr>
		<tr class="x">
			<td class="x"><b>SURAT JALAN</b></td>
			<td class="x">&nbsp;</td>
			<td class="x"align="right">Telp:(0341)808181, Fax:(0341)808585 </td>
		</tr>
		<tr class="x">
			<td class="x">&nbsp;</td>
			<td class="x">&nbsp;</td>
		</tr>
	</table>
	<table class="table" width="100%">
		<tr>
			<td width="60%" rowspan="3"> 
				Kepada Yth. <div id="kotak">
								<b><?php echo $data1['kepada']; ?></b>
								<br>
								<?php echo $data1['alamat']; ?>
							</div>
			</td>
			<!--<th align="left">No.</th>-->
			<th align="left" width="10%">No. </th>
			<td><?php echo $data1['no_spi']; ?></td>
		</tr>
		<tr>
			<th align="left">Tanggal</th>
			<!--<th align="left">Tanggal Pengeluaran </th>-->
			<td><?php echo $data1['tanggal']; ?></td>
		</tr>
		<tr>
			<th align="left">Admin</th>
			<!--<th align="left">Admin : </th>-->
			<td><?php echo $data1['admin']; ?></td>
		</tr>
	</table>
	<br>
	<?php
	
	$sql2 = "SELECT * FROM tb_pengeluaran_detail WHERE no_spi = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
	<table style="border-style:hidden;">
		<tr>
			<td rowspan="2">Bersama ini kamu kirimkan bahan - bahan sebagai berikut:</td>
			<!--<td>Kendaraan</td>-->
		</tr>
	</table>
	<table class="table" width="100%">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah</th>
			<th>Total Harga</th>
		</tr>
	<?php
	$no = 0;
	while($data2 = mysql_fetch_array($hasil2)){
		$no++;
		?>
		<tr>
			<td width="5px"><?php echo $no; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah']." ".$data2['nama_satuan']; ?></td>
			<td><?php echo $data2['total']." ".$data2['satuan_kecil']; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<br>
	<table width="100%">
		<tr>
			<td width="30%" align="center">Pengirim</td>
			<td width="30%" align="center">Penerima</td>
			<td width="40%" align="center">Dibukukan oleh</td>
		</tr>
		<tr>
			<td height="100"></td>
			<td height="100"></td>
			<td height="100"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<script type="text/javascript">
	window.print();
	</script>
	<?php
}

elseif($action == "selesaiPengelaranEks"){
	// ambil kode pengeluaran terakir
	$hasilCek = mysql_query("SELECT MAX(id_pengeluaran) AS cek FROM tb_pengeluaran");
	$dataCek = mysql_fetch_array($hasilCek);

	$hasilPengeluaran = mysql_query("SELECT * FROM tb_pengeluaran WHERE id_pengeluaran = '$dataCek[cek]'");
	$dataPengeluaran = mysql_fetch_array($hasilPengeluaran);
	
	$hasilRecord = mysql_query("SELECT * FROM tb_pengeluaran_detail WHERE no_spi = '$dataPengeluaran[no_spi]'");
	while($dataRecord = mysql_fetch_array($hasilRecord)){
	$hasilSelesai = mysql_query("INSERT INTO tb_jurnal_transaksi VALUES('', '$dataPengeluaran[no_spi]', '$dataPengeluaran[kepada]', 
					'Barang Keluar', '$dataPengeluaran[tanggal]', '$dataRecord[kode_barang]', '$dataRecord[nama_barang]', 
					'$dataRecord[jumlah]', '$dataRecord[nama_satuan]', '',
					'', '$dataRecord[total]', 'Pengeluaran Eksternal', '$dataRecord[pengeluaran_ke]')");
	}

	// masukkan ke jurnal
	
	if($hasilSelesai){
		echo "sukses";
	}
	else{
		echo "gagal";
	}

}

elseif ($action == "upHeader") {
	$kode = $_POST['pKodePengeluaranEdit'];
	$kepada = $_POST['pKepadaEdit'];
	$alamat = $_POST['pAlamatEdit'];
	$tanggal = $_POST['pTanggalEdit'];

	/*
	echo $kode;
	echo "\n".$kepada;
	echo "\n$tanggal"; 
	*/

	$hasilUpHeader1 = mysql_query("UPDATE tb_pengeluaran SET
								kepada = '$kepada',
								alamat = '$alamat',
								tanggal = '$tanggal'
								WHERE no_spi = '$kode'");

	$hasilUpHeader2 = mysql_query("UPDATE tb_jurnal_transaksi SET
								nama_supplier = '$kepada',
								tanggal = '$tanggal'
								WHERE kode = '$kode'");	

	if($hasilUpHeader1 && $hasilUpHeader2){
		echo "Update sukses";
	}
	else{
		echo "Update gagal";
	}
}

elseif($action == "upDetail"){
	$kode = $_POST['pKode'];
	$kodeBarang = $_POST['pKodeBarangEdit'];
	$namaBarang = $_POST['pNamaBarangEdit'];
	$jumlah = $_POST['pJumlahEdit'];
	$satuan = $_POST['pSatuanEdit'];
	$total = $_POST['pTotalEdit'];
	$pengeluaranKe = $_POST['pPengeluaranKeEdit'];

	$hasilUpDetail1 = mysql_query("UPDATE tb_pengeluaran_detail SET
								jumlah = '$jumlah',
								nama_satuan = '$satuan',
								total = '$total'
								WHERE no_spi = '$kode' AND pengeluaran_ke = '$pengeluaranKe' AND kode_barang = '$kodeBarang'");

	$hasilUpDetail2 = mysql_query("UPDATE tb_jurnal_transaksi SET
								jumlah = '$jumlah',
								nama_satuan = '$satuan',
								total = '$total'
								WHERE kode = '$kode' AND barang_ke = '$pengeluaranKe' AND kode_barang = '$kodeBarang'");	

	if($hasilUpDetail1 AND $hasilUpDetail2){
		echo "Update sukses";
	}
	else{
		echo "Update gagal";
	}
}

elseif ($action == "cekKode") {
	$kode = $_GET['pKode'];
	$hasil = mysql_query("SELECT COUNT(no_spi) AS cek FROM tb_pengeluaran WHERE no_spi = '$kode'");
	$data = mysql_fetch_array($hasil);
	echo $data['cek'];
}


?>