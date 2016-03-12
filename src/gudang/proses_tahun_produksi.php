<?php
// proses tahun produksi
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "tambah"){
	$tahunProduksi = $_POST['pTahunProduksi'];
	$keterangan = $_POST['pKeterangan'];
	$hasilTambah = mysql_query("INSERT INTO tb_tahun_produksi VALUES('','$tahunProduksi','$keterangan')");

	if($hasilTambah){
		echo "Sukses";
	}
	else{
		echo "Gagal";
	}
}

elseif($action == "listTahunProduksi"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListTahun").dataTable();
	});
	</script>
	<?php
	$hasilList = mysql_query("SELECT * FROM tb_tahun_produksi");
	?>
<h3 align="center">List Tahun</h3>
<div class="thumbnail">
	<div class="caption">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListTahun">
	<thead>
		<th>No.</th>
		<th>Tahun Produksi</th>
		<th>Keterangan</th>
		<th>Option</th>
	</thead>
	<tbody>
	<?php
	$no = 0;
	while ($data = mysql_fetch_array($hasilList)) {
		$no++;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $data['tahun_produksi']; ?></td>
			<td><?php echo $data['keterangan']; ?></td>
			<td width="85px">
				<button type="button" title="Edit" class="btn btn-default" onclick="javascript:editTahunProduksi(<?php echo $data['id_tahun_produksi']; ?>);"><span class="glyphicon glyphicon-pencil"></span></button>
				<button type="button" title="Edit" class="btn btn-default" onclick="javascript:hapusTahunProduksi(<?php echo $data['id_tahun_produksi']; ?>);"><span class="glyphicon glyphicon-remove"></span></button>
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

elseif ($action == "editTahunProduksi") {
	?>
	<script>
	$(document).ready(function(){
		// Update diklik
		$("#btnEdit").click(function(){
			var vTahunProduksiEdit = $("#idTahunProduksiEdit").val();
			var vKeteranganEdit = $("#idKeteranganEdit").val();

			//alert(vTahunProduksiEdit+" "+vKeteranganEdit);

			$.ajax({
				type: "POST",
				url: "../src/gudang/proses_tahun_produksi.php?action=editTahunProduksiOk",
				data: {
					pTahunProduksiEdit:vTahunProduksiEdit,
					pKeteranganEdit:vKeteranganEdit
				},
				cache: false,
				success: function(hasilEditOk){
					alert(hasilEditOk);
					$("#dialogEditTahunProduksi").dialog("close");
					$("#divTabelTahunProduksi").load("../src/gudang/proses_tahun_produksi.php?action=listTahunProduksi");
				}
			});
		});

		// cancel
		$("#btnCancel").click(function(){
			$("#dialogEditTahunProduksi").dialog("close");
		});
	});
	</script>
	<?php
	$id = $_POST['pId'];
	$hasilEdit = mysql_query("SELECT * FROM tb_tahun_produksi WHERE id_tahun_produksi = '$id'");
	$dataTahun = mysql_fetch_array($hasilEdit);
	?>
	<table class="table">
		<thead>
			<th>Tahun Produksi</th>
			<th>Keterangan</th>
			<th width="200px">Option</th>
		</thead>
		<tbody>
			<td><input id="idTahunProduksiEdit" type="text" class="form-control" value="<?php echo $dataTahun['tahun_produksi']; ?>"></td>
			<td><textarea id="idKeteranganEdit" class="form-control"><?php echo $dataTahun['keterangan']; ?></textarea></td>
			<td>
				<button id="btnEdit" class="btn btn-primary">Update</button>
				<button id="btnCancel" class="btn btn-default">Cancel</button>
			</td>
		</tbody>
	</table>
	<?php
}

elseif ($action == "editTahunProduksiOk") {
	$tahunProduksiEdit = $_POST['pTahunProduksiEdit'];
	$keteranganEdit = $_POST['pKeteranganEdit'];
	$hasilEditOk = mysql_query("UPDATE tb_tahun_produksi SET keterangan = '$keteranganEdit' WHERE tahun_produksi = '$tahunProduksiEdit'");
	if($hasilEditOk){
		echo "Sukses";
	}
	else{
		echo "Gagal";
	}
}

elseif ($action == "hapusTahunProduksi") {
	$id = $_POST['pId'];
	$hasilHapus = mysql_query("DELETE FROM `tb_tahun_produksi` WHERE id_tahun_produksi = '$id'");
	if(hasilHapus){
		echo "Sukses";
	}
	else{
		echo "Gagal";
	}
}

elseif ($action == "cekTahun") {
	$th = $_GET['cek'];
	$hasil = mysql_query("SELECT COUNT(tahun_produksi) AS cek FROM tb_tahun_produksi WHERE tahun_produksi = '$th'");
	$data = mysql_fetch_array($hasil);
	echo $data['cek'];
}

?>