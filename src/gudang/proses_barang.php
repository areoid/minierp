<?php
// proses barang
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "tambah"){
	$kodeBarang = mysql_real_escape_string($_POST['pKodeBarang']);
	$namaBarang = mysql_real_escape_string($_POST['pNamaBarang']);
	$supplier = mysql_real_escape_string($_POST['pSupplier']);
	$satuan = mysql_real_escape_string($_POST['pSatuan']);
	$stokAwal = mysql_real_escape_string($_POST['pStokAwal']);
	$tahunProduksi = $_POST['pTahunProduksi'];
	//$tanggal = date('Y-m-d H:i:s');

	$sql = "INSERT INTO tb_barang VALUES('','$kodeBarang', '$namaBarang','$supplier', '$satuan', '$stokAwal', '$tahunProduksi')";
	$hasil = mysql_query($sql);
	if($hasil){
		echo "Barang sudah disimpan";
	}
	else{
		echo "Barang gagal disimpan";
	}

	// masukkan ke stok barang
	//$sqlStok = "INSERT INTO tb_stok_barang_mentah_gudang VALUES('','$kodeBarang', '$namaBarang', '$stokAwal', '', '', '$stokAwal', '', '', '$tanggal')";
	//mysql_query($sqlStok) or die("input ke tb stok gagal");

}

elseif($action == "listSupplier"){
	$sql = "SELECT nama_supplier FROM tb_supplier";
	$hasil = mysql_query($sql);
		echo '<option value="--">-- Pilih Supplier --</option>';
	while ($data = mysql_fetch_array($hasil)) {
		echo "<option value='$data[nama_supplier]'>".$data['nama_supplier']."</option>";
	}
}

elseif ($action == "listBarang") {
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListBarang").dataTable();
	});
	</script>
	<?php
	$tahunSekarang = date('Y'); 
	$no = 0;
	$hasilBarang = mysql_query("SELECT * FROM tb_barang WHERE tahun_produksi = '$tahunSekarang'");
	?>
<h3 align="center">List Barang Tahun <b id="idBoldTahun"><?php echo $tahunSekarang; ?></b></h3>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListBarang">
	<thead>
		<tr>
			<th>No.</th>
			<th>Kode Barang</th>
			<th>Nama Barang</th>
			<th>Stok Awal</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
		<?php
	while ($dataBarang = mysql_fetch_array($hasilBarang)) {
		$no++;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $dataBarang['kode_barang']; ?></td>
			<td><?php echo $dataBarang['nama_barang']; ?></td>	
			<td><?php echo $dataBarang['stok_awal']." ".$dataBarang['nama_satuan']; ?></td>
			<td>
				<button id="btnView" class="btn btn-default" alt="view" onclick="javascript:lihatBarang('<?php echo $dataBarang['id_barang']; ?>');"><span class="glyphicon glyphicon-zoom-in"></span></button>
				<button id="btnEdit" class="btn btn-default" alt="edit" onclick="javascript:editBarang('<?php echo $dataBarang['id_barang']; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
				<button id="btnDelete" class="btn btn-default" alt="delete" onclick="javascript:deleteBarang('<?php echo $dataBarang['id_barang']; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
			</td>
		</tr>
			<?php
		}
		?>
	</tbody>
</table>
		<?php
}

elseif ($action == "editBarang") {
	?>
	<script type="text/javascript">

		// cancel diklik
		$("#btnCancelEditBarang").click(function(){
			$("#dialogEditBarang").dialog("close");
		});

		// form edit di submit
		$("#formEditBarang").submit(function(){
			var vKodeBarangEdit = $("#idKodeBarangEdit").val();
			var vNamaBarangEdit = $("#idNamaBarangEdit").val();
			var vSupplierEdit = $("#idSupplierEdit").val();
			var vSatuanEdit = $("#idSatuanEdit").val();
			var vStokAwalEdit = $("#idStokAwalEdit").val();
			var vTahunProduksiEdit = $("#idTahunProduksiEdit").val();
			
			/*
			alert(vKodeBarangEdit);
			alert(vNamaBarangEdit);
			alert(vSupplierEdit);
			alert(vSatuanEdit);
			alert(vStokAwalEdit);
			alert(vTahunProduksiEdit);
			*/

			if(vSupplierEdit == "--"){
				alert("Supplier belum diisi");
				return false;
			}
			

			$.ajax({
				type: "POST",
				url: "../src/gudang/proses_barang.php?action=editBarangOk",
				data:{
					pKodeBarangEdit:vKodeBarangEdit,
					pNamaBarangEdit:vNamaBarangEdit,
					pSupplierEdit:vSupplierEdit,
					pSatuanEdit:vSatuanEdit,
					pStokAwalEdit:vStokAwalEdit,
					pTahunProduksiEdit:vTahunProduksiEdit
				},
				cache: false,
				success: function(hasilEditBarangOk){
					alert(hasilEditBarangOk);
					$("#dialogEditBarang").dialog("close");
					$("#divListBarang").load("../src/gudang/proses_barang.php?action=listBarang");
					//$("#divTabelListBarang").load("../src/gudang/proses_barang.php?action=listBarang");
				}
			});

			return false;
		});

	</script> 
	<?php
	$kode = $_POST['pKode'];
	$sql = "SELECT * FROM tb_barang WHERE id_barang = '$kode'";
	$hasil = mysql_query($sql);
	?>
	<form id="formEditBarang">
		<table class="table">
		<?php
		while ($data = mysql_fetch_array($hasil)) {
			?>
			<tr>
				<td>Kode Barang</td>
				<td><input id="idKodeBarangEdit" autocomplete="off" type="text" value="<?php echo $data['kode_barang']; ?>" class="form-control" disabled></td>
			</tr>
			<tr>
				<td>Nama Barang</td>
				<td><input id="idNamaBarangEdit" autocomplete="off" type="text" value="<?php echo $data['nama_barang']; ?>" class="form-control"></td>
			</tr>
			<tr>
				<td>Supplier</td>
				<td>
					<select id="idSupplierEdit" class="form-control sup" name="supplier">
					</select>
				</td>
			</tr>
			<tr>
				<td>Satuan</td>
				<td>
					<select id="idSatuanEdit" class="form-control">
					</select> 
				</td>
			</tr>
			<tr>
				<td>Stok Awal</td>
				<td><input id="idStokAwalEdit" autocomplete="off" type="text" value="<?php echo $data['stok_awal']; ?>" class="form-control"></td>
			</tr>
			<tr>
				<td>Tahun Produksi</td>
				<td>
					<select id="idTahunProduksiEdit" class="form-control">
					</select> 
				</td>
			</tr>
		<script type="text/javascript">
			var sup = "<?php echo $data['nama_supplier']; ?>";
			var sat = "<?php echo $data['nama_satuan']; ?>";
			var tah = "<?php echo $data['tahun_produksi']; ?>";

			// load supplier
			$("#idSupplierEdit").load("../src/gudang/proses_barang.php?action=listSupplier", function(){
				$("select#idSupplierEdit").val(sup);
			});

			// load satuan
			$("#idSatuanEdit").load("../src/gudang/proses_barang.php?action=listSatuan", function(){
				$("select#idSatuanEdit").val(sat);
			});

			// load tahun produksi
			$("#idTahunProduksiEdit").load("../src/gudang/proses_barang.php?action=listTahunProduksi", function(){
				$("select#idTahunProduksiEdit").val(tah);
			});
		</script>
			<?php		
		}
		?>
		</table>
		<div class="pull-right">
			<button type="submit" class="btn btn-primary">Update</button>
			<button id="btnCancelEditBarang" type="button" class="btn btn-default">Cancel</button>
		</div>
	</form>
	<?php
}

elseif ($action == "editBarangOk") {

	$kodeBarangEdit = $_POST['pKodeBarangEdit'];
	$namaBarangEdit = $_POST['pNamaBarangEdit'];
	$supplierEdit = $_POST['pSupplierEdit'];
	$satuanEdit = $_POST['pSatuanEdit'];
	$stokAwalEdit = $_POST['pStokAwalEdit'];
	$tahunProduksiEdit = $_POST['pTahunProduksiEdit'];

	// update pada tb_barang
	$sqlEditBarangOk = "UPDATE tb_barang 
						SET 
						nama_barang = '$namaBarangEdit',
						nama_supplier = '$supplierEdit',
						nama_satuan = '$satuanEdit',
						stok_awal = '$stokAwalEdit',
						tahun_produksi = '$tahunProduksiEdit'
						WHERE kode_barang = '$kodeBarangEdit'";

	$hasilEditBarangOk = mysql_query($sqlEditBarangOk);
	if($hasilEditBarangOk){
		echo "sukses";
	}
	else{
		echo "gagal";
	}

}

elseif($action == "listSatuan"){
	$sql = "SELECT nama_satuan FROM tb_satuan";
	$hasil = mysql_query($sql);
	?>
	<option value="--" selected> -- Pilih Satuan -- </option>
	<?php
	while ($data = mysql_fetch_array($hasil)) {
		echo "<option value=".$data['nama_satuan'].">".$data['nama_satuan']."</option>";
	}
}

elseif ($action == "listTahunProduksi") {
	$sql = "SELECT tahun_produksi FROM tb_tahun_produksi";
	$hasil = mysql_query($sql);
	?>
	<option value="--" selected> -- Pilih Tahun -- </option>
	<?php
	while ($data = mysql_fetch_array($hasil)) {
		echo "<option value=".$data['tahun_produksi'].">".$data['tahun_produksi']."</option>";
	}
}

elseif($action == "filterTahunBarang"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListBarang").dataTable({
			
		});
	});
	</script>
	<?php
	$tahun = $_GET['pTahun'];
	$hasilBarang = mysql_query("SELECT * FROM tb_barang WHERE tahun_produksi = '$tahun'");
	?>
	<h3 align="center">List Barang Tahun <b id="idBoldTahun"><?php echo $tahun; ?></b></h3>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListBarang">
		<thead>
			<tr>
				<th>No.</th>
				<th>Kode Barang</th>
				<th>Nama Barang</th>
				<th>Stok Awal</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 0;
		while ($dataBarang = mysql_fetch_array($hasilBarang)) {
			$no++;
			?>
			<tr>
				<td><?php echo $no; ?></td>
				<td><?php echo $dataBarang['kode_barang']; ?></td>
				<td><?php echo $dataBarang['nama_barang']; ?></td>	
				<td><?php echo $dataBarang['stok_awal']." ".$dataBarang['nama_satuan']; ?></td>
				<td>
					<button id="btnView" class="btn btn-default" alt="view" onclick="javascript:lihatBarang('<?php echo $dataBarang[id_barang]; ?>');"><span class="glyphicon glyphicon-zoom-in"></span></button>
					<button id="btnEdit" class="btn btn-default" alt="edit" onclick="javascript:editBarang('<?php echo $dataBarang[id_barang]; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
					<button id="btnDelete" class="btn btn-default" alt="delete" onclick="javascript:deleteBarang('<?php echo $dataBarang[id_barang]; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
}

elseif ($action == "lihatBarang") {
	$id = $_POST['pKode'];
	$hasilLihat = mysql_query("SELECT * FROM tb_barang WHERE id_barang = '$id'");
	?>
<table class="table">
	<?php
	while($data = mysql_fetch_array($hasilLihat)){
	?>
	<tr>
		<th>Kode Barang<th>
		<td><?php echo $data['kode_barang']; ?></td>
	</tr>
	<tr>
		<th>Nama Barang<th>
		<td><?php echo $data['nama_barang']; ?></td>
	</tr>
	<tr>
		<th>Stok Awal<th>
		<td><?php echo $data['stok_awal']." ".$data['nama_satuan']; ?></td>
	</tr>
	<tr>
		<th>Supplier<th>
		<td><?php echo $data['nama_supplier']; ?></td>
	</tr>
	<tr>
		<th>Tahun Produksi<th>
		<td><?php echo $data['tahun_produksi']; ?></td>
	</tr>
	<?php
	}
	?>
</table>
	<?php
}

elseif ($action == "deleteBarang") {
	$id = $_POST['pKode'];
	$hasilDetele = mysql_query("DELETE FROM tb_barang WHERE id_barang = '$id'");
	if($hasilDetele){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
}

elseif ($action == "cekKode") {
	$kode = $_GET['pKode'];
	$tahun = date('Y');
	$hasil = mysql_query("SELECT COUNT(kode_barang) AS cek FROM tb_barang WHERE kode_barang = '$kode' AND tahun_produksi = '$tahun'");
	$data = mysql_fetch_array($hasil);
	echo $data['cek'];
}

?>