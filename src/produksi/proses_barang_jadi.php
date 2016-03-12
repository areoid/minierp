<?php
// proses barang jadi
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "tambah"){
	$kodeBarang = $_POST['pKodeBarang'];
	$namaBarang = $_POST['pNamaBarang'];
	$satuan = $_POST['pSatuan'];
	$stokAwal = $_POST['pStokAwal'];

	$hasilTambah = mysql_query("INSERT INTO tb_barang_jadi VALUES
							('', '$kodeBarang', '$namaBarang', '$satuan', '$stokAwal')");
	if($hasilTambah){
		echo "Sukses";
	}
	else{
		echo "Gagal";
	}
}

elseif ($action == "listBarang") {
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListBarangx").dataTable();
	});
	</script>
	<?php
	$no = 0;
	$hasilBarang = mysql_query("SELECT * FROM tb_barang_jadi");
	?>
<h3 align="center">List Barang (Jadi)</h3>
<div class="thumbnail">
	<div class="caption">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListBarangx">
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
			<td><?php echo $dataBarang['kode_barang_jadi']; ?></td>
			<td><?php echo $dataBarang['nama_barang_jadi']; ?></td>	
			<td><?php echo $dataBarang['stok_awal']." ".$dataBarang['nama_satuan']; ?></td>
			<td>
				<button id="btnEdit" class="btn btn-default" alt="edit" onclick="javascript:editBarang('<?php echo $dataBarang['id_barang_jadi']; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
				<button id="btnDelete" class="btn btn-default" alt="delete" onclick="javascript:deleteBarang('<?php echo $dataBarang['id_barang_jadi']; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
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
			var vSatuanEdit = $("#idSatuanEdit").val();
			var vStokAwalEdit = $("#idStokAwalEdit").val();
			
			$.ajax({
				type: "POST",
				url: "../src/produksi/proses_barang_jadi.php?action=editBarangOk",
				data:{
					pKodeBarangEdit:vKodeBarangEdit,
					pNamaBarangEdit:vNamaBarangEdit,
					pSatuanEdit:vSatuanEdit,
					pStokAwalEdit:vStokAwalEdit
				},
				cache: false,
				success: function(hasilEditBarangOk){
					alert(hasilEditBarangOk);
					$("#divListBarang").load("../src/produksi/proses_barang_jadi.php?action=listBarang");
					$("#dialogEditBarang").dialog("close");
				}
			});
			
			return false;
		});

	</script> 
	<?php
	$kode = $_POST['pKode'];
	$sql = "SELECT * FROM tb_barang_jadi WHERE id_barang_jadi = '$kode'";
	$hasil = mysql_query($sql);
	?>
	<form id="formEditBarang">
		<table class="table">
		<?php
		while ($data = mysql_fetch_array($hasil)) {
			?>
			<tr>
				<td>Kode Barang</td>
				<td><input id="idKodeBarangEdit" type="text" value="<?php echo $data['kode_barang_jadi']; ?>" class="form-control" disabled></td>
			</tr>
			<tr>
				<td>Nama Barang</td>
				<td><input id="idNamaBarangEdit" type="text" value="<?php echo $data['nama_barang_jadi']; ?>" class="form-control"></td>
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
				<td><input id="idStokAwalEdit" type="text" value="<?php echo $data['stok_awal']; ?>" class="form-control"></td>
			</tr>
		<script type="text/javascript">
			var sat = "<?php echo $data['nama_satuan']; ?>";

			// load satuan
			$("#idSatuanEdit").load("../src/gudang/proses_barang.php?action=listSatuan", function(){
				$("select#idSatuanEdit").val(sat);
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
	$satuanEdit = $_POST['pSatuanEdit'];
	$stokAwalEdit = $_POST['pStokAwalEdit'];

	// update pada tb_barang
	$sqlEditBarangOk = "UPDATE tb_barang_jadi
						SET 
						nama_barang_jadi = '$namaBarangEdit',
						nama_satuan = '$satuanEdit',
						stok_awal = '$stokAwalEdit'
						WHERE kode_barang_jadi = '$kodeBarangEdit'";

	$hasilEditBarangOk = mysql_query($sqlEditBarangOk);
	if($hasilEditBarangOk){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
}

elseif ($action == "deleteBarang") {
	$kode = $_POST['pKode'];
	$hasil = mysql_query("DELETE FROM tb_barang_jadi WHERE id_barang_jadi = '$kode'");
	if($hasil){
		echo "Hapus sukses";
	}
	else{
		echo "Hapus gagal";
	}
}

elseif ($action == "cekKode") {
	$kode = $_GET['pKode'];
	$hasil = mysql_query("SELECT COUNT(kode_barang_jadi) AS cek FROM tb_barang_jadi WHERE kode_barang_jadi = '$kode'");
	$data = mysql_fetch_array($hasil);
	echo $data['cek'];
}

?>