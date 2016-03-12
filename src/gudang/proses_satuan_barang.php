<?php
require_once("../../config/configuration.php");
$action = $_GET['action'];

if ($action == "tambahSatuan") {
	$namaSatuan = mysql_real_escape_string($_POST['pNamaSatuan']);
	$keteranganSatuan = mysql_real_escape_string($_POST['pKeteranganSatuan']);
	$nilaiSatuan = $_POST['pNilaiSatuan'];
	$satuanKecil = $_POST['pSatuanKecil'];

	$sql = "INSERT INTO tb_satuan VALUES('','$namaSatuan', '$nilaiSatuan', '$satuanKecil','$keteranganSatuan')";
	$hasil = mysql_query($sql);
	if($hasil){
		echo "OK";
	}
	else{
		echo "Gagal";
	}

}

elseif($action == "listSatuan"){
	?>
	<script type="text/javascript">
	// buat tabel
	  $("#tabelListSatuan").dataTable();
	  // end tabel
	</script>
	<?php
	$sql = "SELECT * FROM tb_satuan";
	$hasil = mysql_query($sql);
	$no = 0;
	?>
	<h2 align="center">List Satuan</h2>
	<div class="thumbnail">
		<div class="caption">
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListSatuan">
		<thead>
			<th>No</th>
			<th>Nama Satuan</th>
			<th>Nilai</th>
			<th>Satuan Kecil</th>
			<th>Keterangan</th>
			<th>Option</th>
		</thead>
		<tbody>
	<?php
	while($data = mysql_fetch_array($hasil)){
		$no++;
		?>
			<tr>
				<td><?php echo $no; ?></td>
				<td><?php echo $data['nama_satuan']; ?></td>
				<td><?php echo $data['nilai']; ?></td>
				<td><?php echo $data['satuan_kecil']; ?></td>
				<td><?php echo $data['keterangan']; ?></td>
				<td width="100px">
					<button type="button" title="Edit" class="btn btn-default" onclick="javascript:editSatuan('<?php echo $data['id_satuan']; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
					<button type="button" title="hapus" class="btn btn-default" onclick="javascript:hapusSatuan('<?php echo $data['id_satuan']; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
				</td> 
			</tr>
		<?php
	}
	?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
		</div>
	</div>
	<?php
}

elseif($action == "editSatuan"){
?>
<script type="text/javascript">
$(document).ready(function(){
	// form edit di submit
	  $("#formEditSatuan").submit(function(){
	    var vIdEdit = $("#idSatuanEdit").val();
	    var vNamaSatuanEdit = $("#idNamaSatuanEdit").val();
	    var vNilaiEdit = $("#idNilaiEdit").val();
	    var vSatuanKecilEdit = $("#idSatuanKecilEdit").val();
	    var vKeteranganSatuanEdit = $("#idKeteranganSatuanEdit").val();
	    
	    $.ajax({
	    	type: "POST",
	    	url: "../src/gudang/proses_satuan_barang.php?action=editSatuanOk",
	    	data:{
	    		pIdEdit:vIdEdit,
	    		pNamaSatuanEdit:vNamaSatuanEdit,
	    		pNilaiEdit:vNilaiEdit,
	    		pSatuanKecilEdit:vSatuanKecilEdit,
	    		pKeteranganSatuanEdit:vKeteranganSatuanEdit
	    	},
	    	cache: false,
	    	success: function(hasilEditOk){
	    		alert(hasilEditOk);
	    		$("#divTabelListSatuan").load("../src/gudang/proses_satuan_barang.php?action=listSatuan");
	    		$("#dialogEditSatuan").dialog("close");
	    	}
	    });

	    return false;
	  });
	  // end form edit

	  $("#btnCancel").click(function(){
	  	$("#dialogEditSatuan").dialog("close");
	  });
});

</script>
<?php
	$id = mysql_real_escape_string($_POST['pId']);
	$sql = "SELECT * FROM tb_satuan WHERE id_satuan = '$id'";
	$hasil = mysql_query($sql) or die("error");
	?>
<form id="formEditSatuan">
	<table class="table">
	<?php
	while ($data = mysql_fetch_array($hasil)) {
		?>
		<tr>
			<th>Nama Satuan</th>
			<td><input id="idNamaSatuanEdit" type="text" class="form-control" value="<?php echo $data['nama_satuan']; ?>"></td>
		</tr>
		<tr>
			<th>Nilai</th>
			<td><input id="idNilaiEdit" type="text" class="form-control" value="<?php echo $data['nilai']; ?>"></td>
		</tr>
		<tr>
			<th>Satuan Kecil</th>
			<td><input id="idSatuanKecilEdit" type="text" class="form-control" value="<?php echo $data['satuan_kecil']; ?>"></td>
		</tr>
		<tr>
			<th>Keterangan</th>
			<td><input id="idKeteranganSatuanEdit" type="text" class="form-control" value="<?php echo $data['keterangan']; ?>"></td>
		</tr>
		<tr>
			<td><input id="idSatuanEdit" type="hidden" type="text" class="form-control" value="<?php echo $data['id_satuan']; ?>"></td>
			<td>
				<div class="pull-right">
					<button id="btnCancel" type="button" class="btn btn-default">Cancel</button>
					<button id=""type="submit" class="btn btn-primary">Update</button>
				</div>
			</td>
		</tr>
		<?php
	}
	?>
	</table>
</form>
	<?php
}

elseif($action == "editSatuanOk") {
	$idOk = $_POST['pIdEdit'];
	$namaSatuanOk = $_POST['pNamaSatuanEdit'];
	$nilaiSatuanOk = $_POST['pNilaiEdit'];
	$satuanKecilOk = $_POST['pSatuanKecilEdit'];
	$keteranganSatuanOk = $_POST['pKeteranganSatuanEdit'];

	$sql = "UPDATE tb_satuan SET nama_satuan = '$namaSatuanOk', 
								nilai = '$nilaiSatuanOk', 
								satuan_kecil = '$satuanKecilOk', 
								keterangan = '$keteranganSatuanOk' 
			WHERE id_satuan = '$idOk'";
	$hasil = mysql_query($sql);

	if($hasil){
		echo "Update sukses";
	}	
	else{
		echo "Update gagal";
	}

}

elseif($action == "hapusSatuan"){
	$idHapus = mysql_real_escape_string($_GET['pId']);
	$sql = "DELETE FROM tb_satuan WHERE id_satuan = '$idHapus'";
	$hasil = mysql_query($sql);
	if($hasil){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
}

// how to win friends and influence peopless

?>