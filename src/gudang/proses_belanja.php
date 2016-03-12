<?php
// proses belanja
require_once("../../config/configuration.php");

$action = $_GET['action'];


if($action == "tambahBelanja"){
	$noLpb = $_POST['pKodeBelanja'];
	$tanggal = $_POST['pTanggalBelanja'];
	$supplier = $_POST['pSupplier'];
	$admin = $_POST['pAdmin'];
	
	$sql = "INSERT INTO tb_pembelian VALUES('','$noLpb','$supplier','$admin', '$tanggal')";
	$hasil = mysql_query($sql);
	if($hasil){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
}

elseif($action == "tambahDetailBelanja"){
	// cek lpb terbaru
	$cek = "SELECT MAX(id_pembelian) as cek FROM tb_pembelian";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlKodeBelanja = "SELECT no_lpb FROM tb_pembelian WHERE id_pembelian = '$dataCek[cek]'";
	$hasilKodeBelanja = mysql_query($sqlKodeBelanja);
	$dataTemp = mysql_fetch_array($hasilKodeBelanja);
	$noLpb = $dataTemp['no_lpb'];

	$kodeBarang = $_POST['pKodeBarang'];
	$namaBarang = $_POST['pNamaBarang'];
	$jumlah = $_POST['pJumlah'];
	$satuanJumlah = $_POST['pSatuanJumlah'];
	$isi = $_POST['pIsi'];
	$satuanIsi = $_POST['pSatuanIsi'];
	$total = $_POST['pTotal'];
	$keterangan = $_POST['pKeterangan'];

	// counting detail belanja
	$sqlCount = "SELECT count(*) AS hitung FROM tb_pembelian_detail WHERE no_lpb = '$noLpb'";
	$hasilCount = mysql_query($sqlCount);
	$dataCount = mysql_fetch_array($hasilCount);
	$countBelanja = $dataCount['hitung'];
	$countBelanja++;

	//echo $countBelanja;
	
	$sql = "INSERT INTO tb_pembelian_detail VALUES('$noLpb','$kodeBarang', '$namaBarang',
												 '$jumlah', '$satuanJumlah',
												 '$isi', '$satuanIsi', '$total',
												 '$keterangan', '$countBelanja')";
	$hasil = mysql_query($sql) or die("fuck");
	if($hasil){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
	
}

elseif($action == "headerBelanja"){
	// cek row terbaru
	$cek = "SELECT MAX(id_pembelian) AS cek FROM tb_pembelian";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sql = "SELECT * FROM tb_pembelian WHERE id_pembelian = '$dataCek[cek]'";
	$hasil = mysql_query($sql);
	$data = mysql_fetch_array($hasil);
	?>
	<table class="table" width="100%" border="1">
		<tr>
			<td colspan="2" width="70%">&nbsp;</td>
			<th>No. LPB</th>
			<td>:</td>
			<td id="idNoLpb"><?php echo $data['no_lpb']; ?></td>
		</tr>
		<tr>
			<td width="10%">&nbsp;</td>
			<td>Dari : <?php echo $data['nama_supplier']; ?></td>
			<th>Tanggal</th>
			<td>:</td>
			<td><?php echo $data['tanggal']; ?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<th>Admin</th>
			<td>:</td>
			<td><?php echo $data['admin']; ?></td>
		</tr>
	</table>
	<?php
}

elseif($action == "listDetailBelanja"){
	// cek row terbaru
	$cek = "SELECT MAX(id_pembelian) AS cek FROM tb_pembelian";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlNoLpb = "SELECT no_lpb FROM tb_pembelian WHERE id_pembelian = '$dataCek[cek]'";
	$hasilNoLpb = mysql_query($sqlNoLpb);
	$dataNoLpb = mysql_fetch_array($hasilNoLpb);
	$noLpb = $dataNoLpb['no_lpb'];

	//echo "LPB nya gan ".$noLpb;
	
	$sql = "SELECT * FROM tb_pembelian_detail WHERE no_lpb = '$noLpb'";
	$hasil = mysql_query($sql);
	?>
	<table class="table" width="100%" border="1">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jml</th>
			<th>Isi</th>
			<th>Total</th>
			<th>Keterangan</th>
		</tr>
	<?php
	while($data = mysql_fetch_array($hasil)){
		?>
		<tr>
			<td id="idCountBelanja"><?php echo $data['belanja_ke']; ?></td>
			<td><?php echo $data['kode_barang']; ?></td>
			<td><?php echo $data['nama_barang']; ?></td>
			<td><?php echo $data['jumlah']." ".$data['nama_satuan']; ?></td>
			<td><?php echo $data['isi']." ".$data['nama_satuan_isi']; ?></td>
			<td><?php echo $data['total']." ".$data['nama_satuan_isi']; ?></td>
			<td><?php echo $data['keterangan']; ?></td> 
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
	$tahun = date('Y');
	$sql = "SELECT kode_barang FROM tb_barang WHERE kode_barang LIKE '$kode%' AND tahun_produksi = '$tahun'";
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

elseif($action == "cancelBelanja"){
	// cek row akhir
	$cek = "SELECT MAX(id_pembelian) AS cek FROM tb_pembelian";
	$hasilCek = mysql_query($cek);
	$dataCek = mysql_fetch_array($hasilCek);

	$sqlNoLpb = "SELECT no_lpb FROM tb_pembelian WHERE id_pembelian = '$dataCek[cek]'";
	$hasilNoLpb = mysql_query($sqlNoLpb);
	$dataNoLpb = mysql_fetch_array($hasilNoLpb);
	$noLpb = $dataNoLpb['no_lpb'];

	$sqlDetailBelanja = "DELETE FROM tb_pembelian_detail WHERE no_lpb = '$noLpb'";
	$hasilDelDetailBelanja = mysql_query($sqlDetailBelanja);
	if($hasilDelDetailBelanja){
		echo "Delete detail OK";
	}
	else{
		echo "Delete detail gagal";
	}

	$sqlBelanja = "DELETE FROM tb_pembelian WHERE no_lpb = '$noLpb'";
	$hasilDelBelanja = mysql_query($sqlBelanja);
	if($hasilDelBelanja){
		echo "Delete belanja OK";
	}
	else{
		echo "Delete belanja gagal";
	}
	
}

elseif($action == "listBelanja"){
	$no = 0;
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListBelanja").dataTable({
			"aaSorting": [[ 0, "desc" ]]
		});
	});
	</script>
	<?php
	$sql = "SELECT * FROM tb_pembelian ORDER BY id_pembelian ASC";
	$hasil = mysql_query($sql);
	?>
	<h2 align="center">List Belanja</h2>
	<div class="thumbnail">
			<div class="caption">
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListBelanja">
		<thead>
			<tr>
				<th>No</th>
				<th>No. LPB</th>
				<th>Tanggal Belanja</th>
				<th>Admin</th>
				<th>Option</th>
			</tr>
		</thead>
		<tbody>
	<?php
	while($data = mysql_fetch_array($hasil)){
			$no++;
			?>
			<tr>
				<td><?php echo $no; ?></td>
				<td><?php echo $data['no_lpb']; ?></td>
				<td><?php echo $data['tanggal']; ?></td>
				<td><?php echo $data['admin']; ?></td>
				<td width="175px">
					<button type="button" title="Lihat" class="btn btn-default" onclick="javascript:lihatBelanja('<?php echo $data['no_lpb']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span></button>
					<button type="button" title="Edit" class="btn btn-default" onclick="javascript:editBelanja('<?php echo $data['no_lpb']; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
					<button type="button" title="hapus" class="btn btn-default" onclick="javascript:hapusBelanja('<?php echo $data['no_lpb']; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
					<button type="button" title="print" class="btn btn-default" onclick="javascript:printBelanja('<?php echo $data['no_lpb']; ?>');"><span class="glyphicon glyphicon-print"></span></button>
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

elseif($action == "lihatBelanja"){
	$kode = mysql_real_escape_string($_POST['pKode']);
	$hasil1 = mysql_query("SELECT * FROM tb_pembelian WHERE no_lpb = '$kode'");
	$data1 = mysql_fetch_array($hasil1);
	?>
	<table class="table">
		<tr>
			<td colspan="2" width="70%">&nbsp;</td>
			<th>No. LPB</th>
			<td>:</td>
			<td id="idNoLpb"><?php echo $data1['no_lpb']; ?></td>
		</tr>
		<tr>
			<td width="10%">&nbsp;</td>
			<td>Dari : <?php echo $data1['nama_supplier']; ?></td>
			<th>Tanggal</th>
			<td>:</td>
			<td><?php echo $data1['tanggal']; ?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<th>Admin</th>
			<td>:</td>
			<td><?php echo $data1['admin']; ?></td>
		</tr>
	</table>
	<?php

	$hasil2 = mysql_query("SELECT * FROM tb_pembelian_detail WHERE no_lpb = '$kode'");
	?>
	<table class="table">
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Jml</th>
			<th>Isi</th>
			<th>Total</th>
			<th>Keterangan</th>
		</tr>
	<?php
	while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td id="idCountBelanja"><?php echo $data2['belanja_ke']; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah']." ".$data2['nama_satuan']; ?></td>
			<td><?php echo $data2['isi']." ".$data2['nama_satuan_isi']; ?></td>
			<td><?php echo $data2['total']." ".$data2['nama_satuan_isi']; ?></td>
			<td><?php echo $data2['keterangan']; ?></td> 
		</tr>
		<?php
	}
	?>
	</table>
	<?php

}

elseif($action == "hapusBelanja"){
	$noLpb = $_POST['pKode'];

	// hapus di tb_pembelian tb_pembelian_detail tb_jurnal_transaksi
	$hasilHapusDetail = mysql_query("DELETE FROM tb_pembelian_detail WHERE no_lpb = '$noLpb'");
	$hasilHapusHeader = mysql_query("DELETE FROM tb_pembelian WHERE no_lpb = '$noLpb'");
	$hasilHapusJurnal = mysql_query("DELETE FROM tb_jurnal_transaksi WHERE kode = '$noLpb'");


	if($hasilHapusHeader AND $hasilHapusDetail AND $hasilHapusJurnal){
		echo "Hapus sukses";
	}
	else{
		echo "Hapus gagal";
	}
}

elseif($action == "editBelanja"){
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#idTanggal").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#idJumlahBarangEdit").keyup(function(){
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
		});

		// button update yang bagian atas di klik
			$("#btnUpdateHeader").click(function(){
				var vNoLpbEdit = $("#idNoLpbEdit").html();
				var vTanggalEdit = $("#idTanggal").val();
				var vSupplierEdit = $("#idSupplierEdit").val();

				/*
				alert(vNoLpbEdit);
				alert(vTanggalEdit);
				alert(vSupplierEdit);
				*/

				$.ajax({
					type: "POST",
					url: "../src/gudang/proses_belanja.php?action=upHeader",
					data: {
						pNoLpbEdit:vNoLpbEdit,
						pTanggalEdit:vTanggalEdit,
						pSupplierEdit:vSupplierEdit
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
	$sql1 = "SELECT * FROM tb_pembelian WHERE no_lpb = '$kode'";
	$hasil1 = mysql_query($sql1);
	$data1 = mysql_fetch_array($hasil1);
	?>
		<table class="table">
			<tr>
				<td colspan="2" width="70%">&nbsp;</td>
				<th>No. LPB</th>
				<td>:</td>
				<td id="idNoLpbEdit"><?php echo $data1['no_lpb']; ?></td>
			</tr>
			<tr>
				<td width="10%">&nbsp;</td>
				<td>
					<div class="row">
						<div class="col-xs-1">
							Dari:
						</div>
						<div class="col-xs-4">
							<select id="idSupplierEdit" class="form-control sup" name="supplier">
							</select>
						</div>
					</div>
				</td>
				<th>Tanggal</th>
				<td>:</td>
				<td>
					<input id="idTanggal" type="text" class="form-control" value="<?php echo $data1['tanggal']; ?>">
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<th>Admin</th>
				<td>:</td>
				<td><?php echo $data1['admin']; ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<th>&nbsp;</th>
				<td>&nbsp;</td>
				<td><button id="btnUpdateHeader" type="button" class="btn btn-primary pull-right">Update</button></td>
			</tr>
		</table>
	<?php
	
	$sql2 = "SELECT * FROM tb_pembelian_detail WHERE no_lpb = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
		<table class="table">
			<tr>
				<th>No.</th>
				<th>Kode Barang</th>
				<th>Nama Barang</th>
				<th>Jumlah</th>
				<th>Sat.</th>
				<th>Isi</th>
				<th>Total</th>
				<th>&nbsp;</th>
				<th>Keterangan</th>
				<th>Option</th>
			</tr>
		<?php
		$a = 0;
		while($data2 = mysql_fetch_array($hasil2)){
			$a++;
			?>
			<tr>
				<td width="65">
					<input id="idBelanjaKe<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['belanja_ke']; ?>" disabled>
				</td>
				<td>
					<input id="idKodeBarangEdit<?php echo $a; ?>" type="text" class="form-control" value="<?php echo $data2['kode_barang']; ?>" disabled>
				</td>
				<td>
					<input id="idNamaBarangEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="harga Barang" value="<?php echo $data2['nama_barang']; ?>" disabled>
				</td>
				<td>
					<input id="idJumlahEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Jumlah" value="<?php echo $data2['jumlah']; ?>" required>
				</td>
				<td>
					<select id="idSatuanEdit<?php echo $a; ?>" class="form-control"  required>
					</select>
				</td>
				<td>
					<input id="idIsiEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Total harga" value="<?php echo $data2['isi']; ?>" required>
				</td>
				<td>
					<input id="idTotalEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Total harga" value="<?php echo $data2['jumlah'] * $data2['isi']; ?>" required>
				</td>
				<td>
					<input id="idSatuanIsiEdit<?php echo $a; ?>" class="form-control" autocomplete="off" placeholder="Satuan Kecil" value="<?php echo $data2['nama_satuan_isi']; ?>" disabled>
				</td>
				<td>
					<input id="idKeteranganEdit<?php echo $a; ?>" type="text" class="form-control" autocomplete="off" placeholder="Keterangan" value="<?php echo $data2['keterangan']; ?>" >
				</td>
				<td>
					<button id="btnUpdateDetail<?php echo $a; ?>" type="submit" class="btn btn-primary">Update</button>
				</td>
			</tr>
		<script type="text/javascript">
		$(document).ready(function(){
			var sup = "<?php echo $data1['nama_supplier']; ?>";
			var ax = "<?php echo $a; ?>";
			var sat1 = "<?php echo $data2['nama_satuan']; ?>";

			$("#idJumlahEdit"+ax).keyup(function(){
				var jumlah = $("#idJumlahEdit"+ax).val();
				var isi = $("#idIsiEdit"+ax).val();
				var hasil = jumlah * isi;
				$("#idTotalEdit"+ax).val(hasil);
			});
			$("#idIsiEdit"+ax).keyup(function(){
				var jumlah = $("#idJumlahEdit"+ax).val();
				var isi = $("#idIsiEdit"+ax).val();
				var hasil = jumlah * isi;
				$("#idTotalEdit"+ax).val(hasil);
			});

			// load supplier
			$("#idSupplierEdit").load("../src/gudang/proses_belanja.php?action=listSupplier", function(){
				$("select#idSupplierEdit").val(sup);
			});

			// load satuan 1
			$("#idSatuanEdit"+ax).load("../src/gudang/proses_belanja.php?action=listSatuan", function(){
				$("select#idSatuanEdit"+ax).val(sat1);
			});

			//autosatuankecil
			$("#idSatuanEdit"+ax).change(function(){
				var satuan = $("#idSatuanEdit"+ax).val();
				$.ajax({
					url: "../src/gudang/proses_belanja.php?action=autoSatKecil&sat="+satuan,
					cache: false,
					success: function(h){
						var hasilArr = h.split('*');
						$("#idIsiEdit"+ax).val(hasilArr[0]);
						$("#idSatuanIsiEdit"+ax).val(hasilArr[1]);
						var jumlah = $("#idJumlahEdit"+ax).val();
						var total = jumlah * hasilArr[0];
						$("#idTotalEdit"+ax).val(total);
					}
				});
			});

			// button update yang bagian bawah di klik
			$("#btnUpdateDetail"+ax).click(function(){
				var vBelanjaKe = $("#idBelanjaKe"+ax).val();
				var vJumlahEdit = $("#idJumlahEdit"+ax).val();
				var vSatuan1 = $("#idSatuanEdit"+ax).val();
				var vIsi = $("#idIsiEdit"+ax).val();
				var vSatuan2 = $("#idSatuanIsiEdit"+ax).val();
				var vTotal = $("#idTotalEdit"+ax).val();
				var vKeterangan = $("#idKeteranganEdit"+ax).val();
				var vNoLpbEdit = $("#idNoLpbEdit").html();
				
				$.ajax({ 
					type: "POST",
					url: "../src/gudang/proses_belanja.php?action=upDetail",
					data:{
						pNoLpbEdit:vNoLpbEdit,
						pBelanjaKeEdit:vBelanjaKe,
						pJumlahEdit:vJumlahEdit,
						pSatuan1Edit:vSatuan1,
						pIsiEdit:vIsi,
						pSatuan2Edit:vSatuan2,
						pTotalEdit:vTotal,
						pKeteranganEdit:vKeterangan
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

elseif($action == "upHeader"){
	$noLpbEdit = $_POST['pNoLpbEdit'];
	$tanggalEdit = $_POST['pTanggalEdit'];
	$supplierEdit = $_POST['pSupplierEdit'];

	// lakukan update ke tb_pembelian
	$hasilUpHeader1 = mysql_query("UPDATE tb_pembelian SET
								tanggal = '$tanggalEdit',
								nama_supplier = '$supplierEdit'
								WHERE no_lpb = '$noLpbEdit'");

	// lakukan update ke ke tb_jurnal_transaksi
	$hasilUpHeader2 = mysql_query("UPDATE tb_jurnal_transaksi SET
								tanggal = '$tanggalEdit',
								nama_supplier = '$supplierEdit'
								WHERE kode = '$noLpbEdit'");

	if($hasilUpHeader1 AND $hasilUpHeader2){
		echo "Update Sukses";
	}
	else{
		echo "Update Gagal";
	}

}

elseif ($action == "upDetail") {
	$noLpb = $_POST['pNoLpbEdit'];
	$belanjaKe = $_POST['pBelanjaKeEdit'];
	$jumlah = $_POST['pJumlahEdit'];
	$satuan1 = $_POST['pSatuan1Edit'];
	$isi = $_POST['pIsiEdit'];
	$satuan2 = $_POST['pSatuan2Edit'];
	$total = $_POST['pTotalEdit'];
	$keterangan = $_POST['pKeteranganEdit'];

	$hasilUpDetail = mysql_query("UPDATE tb_pembelian_detail SET
								jumlah = '$jumlah',
								nama_satuan = '$satuan1',
								isi = '$isi',
								nama_satuan_isi = '$satuan2',
								total = '$total',
								keterangan = '$keterangan'
								WHERE no_lpb = '$noLpb' AND belanja_ke = '$belanjaKe'");

	$upJurnal = mysql_query("UPDATE tb_jurnal_transaksi SET
								jumlah = '$jumlah',
								nama_satuan = '$satuan1',
								isi = '$isi',
								nama_satuan_isi = '$satuan2',
								total = '$total',
								keterangan = '$keterangan'
								WHERE kode = '$noLpb' AND barang_ke = '$belanjaKe'");

	if($hasilUpDetail AND $upJurnal){
		echo "Update sukses";
	}
	else{
		echo "Update gagal";
	}

}

elseif ($action == "printBelanja") {
	$kode = mysql_real_escape_string($_GET['pKode']);
	$sql1 = "SELECT * FROM tb_pembelian WHERE no_lpb = '$kode'";
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
	<br>
	<table class="table" width="100%">
		<tr>
			<td colspan="2" width="70%">&nbsp;</td>
			<th align="left">No. LPB</th>
			<td>:</td>
			<td id="idNoLpb"><?php echo $data1['no_lpb']; ?></td>
		</tr>
		<tr>
			<td width="10%">&nbsp;</td>
			<td>Dari : <?php echo $data1['nama_supplier']; ?></td>
			<th align="left">Tanggal</th>
			<td>:</td>
			<td><?php echo $data1['tanggal']; ?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<th align="left">Admin</th>
			<td>:</td>
			<td><?php echo $data1['admin']; ?></td>
		</tr>
	</table>
	<br>
	<?php
	
	$sql2 = "SELECT * FROM tb_pembelian_detail WHERE no_lpb = '$kode'";
	$hasil2 = mysql_query($sql2);
	?>
	<hr>
	<table class="table" width="100%">
		<tr>
			<th align="left">No.</th>
			<th align="left">Kode Barang</th>
			<th align="left">Nama Barang</th>
			<th align="left">Jumlah</th>
			<th align="left">Isi</th>
			<th align="left">Total</th>
			<th align="left">Keterangan</th>
		</tr>
		<tr>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
		</tr>
	<?php
	while($data2 = mysql_fetch_array($hasil2)){
		?>
		<tr>
			<td id="idCountBelanja"><?php echo $data2['belanja_ke']; ?></td>
			<td><?php echo $data2['kode_barang']; ?></td>
			<td><?php echo $data2['nama_barang']; ?></td>
			<td><?php echo $data2['jumlah']." ".$data2['nama_satuan']; ?></td>
			<td><?php echo $data2['isi']." ".$data2['nama_satuan_isi']; ?></td>
			<td><?php echo $data2['total']." ".$data2['nama_satuan_isi']; ?></td>
			<td><?php echo $data2['keterangan']; ?></td> 
		</tr>
		<tr>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
			<th><hr></th>
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

elseif($action == "selesaiBelanja"){
	// ambil No LPB terakir
	$hasilCek = mysql_query("SELECT MAX(id_pembelian) AS cek FROM tb_pembelian");
	$dataCek = mysql_fetch_array($hasilCek);

	$hasilBelanja = mysql_query("SELECT no_lpb, nama_supplier, tanggal FROM tb_pembelian WHERE id_pembelian = '$dataCek[cek]'");
	$dataBelanja = mysql_fetch_array($hasilBelanja);
	
	$hasilRecord = mysql_query("SELECT * FROM tb_pembelian_detail WHERE no_lpb = '$dataBelanja[no_lpb]'");
	while($dataRecord = mysql_fetch_array($hasilRecord)){
	$hasilSelesai = mysql_query("INSERT INTO tb_jurnal_transaksi VALUES('', '$dataBelanja[no_lpb]', '$dataBelanja[nama_supplier]', 
					'Barang Masuk', '$dataBelanja[tanggal]', '$dataRecord[kode_barang]', '$dataRecord[nama_barang]', 
					'$dataRecord[jumlah]', '$dataRecord[nama_satuan]', '$dataRecord[isi]',
					'$dataRecord[nama_satuan_isi]', '$dataRecord[total]', '$dataRecord[keterangan]', '$dataRecord[belanja_ke]')");
	}

	// masukkan ke jurnal
	
	if($hasilSelesai){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
}

elseif($action == "listSupplier"){
	$sql = "SELECT nama_supplier FROM tb_supplier";
	$hasil = mysql_query($sql);
		echo '<option value="--" selected>-- Pilih Supplier --</option>';
	while ($data = mysql_fetch_array($hasil)) {
		echo "<option value='$data[nama_supplier]'>".$data['nama_supplier']."</option>";
	}
}

elseif($action == "listSatuan"){
	$sql = "SELECT nama_satuan FROM tb_satuan";
	$hasil = mysql_query($sql);
		echo '<option value="--" selected>-- Pilih --</option>';
	while ($data = mysql_fetch_array($hasil)) {
		echo "<option value='$data[nama_satuan]'>".$data['nama_satuan']."</option>";
	}
}

elseif($action == "testJurnal"){
	?>
	<table>
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>S. Awal</th>
			<th>Masuk</th>
			<th>Keluar</th>
			<th>S. Akhir</th>
		</tr>
	<?php
	$no = 0;
	// ambil kode barang
	$hasilBarang = mysql_query("SELECT * FROM tb_barang WHERE tahun_produksi = '2014'");
	while ($dataBarang = mysql_fetch_array($hasilBarang)) {
		$no++;
		$kodeBarang = $dataBarang['kode_barang'];
		$namaBarang = $dataBarang['nama_barang'];
		$stokAwal = $dataBarang['stok_awal'];
		//$hasilJumla = mysql_query("SELECT SUM(")
		// ambil kode barang berdasarnya lpb
		$hasilJumlahBarang = mysql_query("SELECT SUM(jumlah) AS masuk FROM tb_pembelian_detail WHERE kode_barang = '$kodeBarang'");
		$dataJumlahBarang = mysql_fetch_array($hasilJumlahBarang);
		$masuk = $dataJumlahBarang['masuk'];
		$keluar = 0;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $kodeBarang; ?></td>
			<td><?php echo $namaBarang; ?></td>
			<td><?php echo $stokAwal; ?></td>
			<td><?php echo $masuk; ?></td>
			<td><?php echo $keluar; ?></td>
			<td><?php echo $stokAwal+$masuk-$keluar; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}

elseif ($action == "cekLpb") {
	$lpb = $_GET['lpb'];
	$hasil = mysql_query("SELECT COUNT(no_lpb) AS cek FROM tb_pembelian WHERE no_lpb = '$lpb'");
	$data = mysql_fetch_array($hasil);
	echo $data['cek'];
}

elseif ($action == "autoSatKecil") {
	$satuan = $_GET['sat'];
	$hasil = mysql_query("SELECT nilai, satuan_kecil FROM tb_satuan WHERE nama_satuan = '$satuan'");
	$data = mysql_fetch_array($hasil);
	echo $data['nilai']."*".$data['satuan_kecil'];
}

?>