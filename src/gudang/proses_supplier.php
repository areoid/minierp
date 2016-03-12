<?php
// proses supplier
error_reporting('0');
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "kodeSupplier"){

	// membaca kode barang terbesar
	$sql = "SELECT max(kode_supplier) as maxKode FROM tb_supplier";
	$hasil = mysql_query($sql);
	$data  = mysql_fetch_array($hasil);
	$kodeSupplier = $data['maxKode'];
	
	// pisahkan code
	$noUrut = (int) substr($kodeSupplier, 2, 3);
	
	// increment $noUrut
	$noUrut++;

	//gabung menjadi kode baru
	$char = "S";
	$newID = $char . sprintf("%03s", $noUrut);

	echo $newID;

}

elseif ($action == "tambahSupplier") {
	$kodeSupplier = mysql_real_escape_string($_POST['pKodeSupplier']);
	$namaSupplier = mysql_real_escape_string($_POST['pNamaSupplier']);
	$alamatSupplier = mysql_real_escape_string($_POST['pAlamatSupplier']);

	$sql = "INSERT INTO tb_supplier VALUES('','$kodeSupplier','$namaSupplier','$alamatSupplier')";
	$hasil = mysql_query($sql);
	if($hasil){
		echo  "Supplier berhasil ditambahkan";
	}
	else{
		echo "Supplier gagal ditambahkan";
	}
}

elseif($action == "listSupplier"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tabelListSupplier").dataTable();
		$("#btnCancelEditSupplier").click(function(){
          alert("cancel di klik");
        });
	});
	</script>
	<?php
	$sql = "SELECT * FROM tb_supplier";
	$hasil = mysql_query($sql);
	?>
	<h3 align="center">List Supplier</h3>
	<div class="thumbnail">
		<div class="caption">
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListSupplier">
		<thead>
			<tr>
				<th>Kode Supplier</th>
				<th>Nama Supplier</th>
				<th>Alamat</th>
				<th>Option</th>
			</tr>
		</thead>
		<tbody>
	<?php
	while($data = mysql_fetch_array($hasil)){
		?>
		<tr>
			<td><?php echo $data['kode_supplier']; ?></td>
			<td><?php echo $data['nama_supplier']; ?></td>
			<td><?php echo $data['alamat']; ?></td>
			<td width="85px">
				<button type="button" title="Edit" class="btn btn-default" onclick="javascript:editSupplier('<?php echo $data['kode_supplier']; ?>');"><span class="glyphicon glyphicon-pencil"></span></button>
				<button type="button" title="Edit" class="btn btn-default" onclick="javascript:hapusSupplier('<?php echo $data['kode_supplier']; ?>');"><span class="glyphicon glyphicon-remove"></span></button>
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

elseif($action == "editSupplierOk"){
	$kodeSupplierEdit = mysql_real_escape_string($_POST['pKodeSupplierEdit']);
	$namaSupplierEdit = mysql_real_escape_string($_POST['pNamaSupplierEdit']);
	$alamatSupplierEdit = mysql_real_escape_string($_POST['pAlamatSupplierEdit']);
	$sql = "UPDATE tb_supplier SET nama_supplier = '$namaSupplierEdit', alamat = '$alamatSupplierEdit' WHERE kode_supplier = '$kodeSupplierEdit'";
	$hasil = mysql_query($sql);
	if($hasil){
		echo "sukses";
	}
	else{
		echo "gagal";
	}
}

elseif($action == "editSupplier"){
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		//button cancel di klik
		$("#btnCancelEditSupplier").click(function(){
          $("#dialogEditSupplier").dialog("close");
        });

        $("#formEditSupplier").submit(function(){
        	var vKodeSupplierEdit = $("#idKodeSupplierEdit").val();
        	var vNamaSupplierEdit = $("#idNamaSupplierEdit").val();
        	var vAlamatSupplierEdit = $("#idAlamatSupplierEdit").val();
        	
        	$.ajax({
        		type: "POST",
        		url: "../src/gudang/proses_supplier.php?action=editSupplierOk",
        		data: {
        			pKodeSupplierEdit:vKodeSupplierEdit,
        			pNamaSupplierEdit:vNamaSupplierEdit,
        			pAlamatSupplierEdit:vAlamatSupplierEdit
        		},
        		cache: false,
        		success: function(hasilEditSupplier){
        			alert("Edit "+hasilEditSupplier);
        			$("#dialogEditSupplier").dialog("close");
        			$("#divTabelListSupplier").load("../src/gudang/proses_supplier.php?action=listSupplier");
        		}
        	});

        	return false;
        });
	});
	</script>
	<?php
	$kode = mysql_real_escape_string($_POST['pKode']);
	$sql = "SELECT * FROM tb_supplier WHERE kode_supplier = '$kode'";
	$hasil = mysql_query($sql);
	?>
	<form id="formEditSupplier">
		<table class="table">
			
		<?php
		while ($data = mysql_fetch_array($hasil)) {
			?>
			<tr>
				<th>Kode Supplier</th>
				<td><input id="idKodeSupplierEdit" type"text" value="<?php echo $data['kode_supplier']; ?>" class="form-control" disabled></td>
			</tr>
			<tr>
				<th>Nama Supplier</th>
				<td><input id="idNamaSupplierEdit" type"text" value="<?php echo $data['nama_supplier']; ?>" class="form-control"></td>
			</tr>
			<tr>
				<th>Alamat</th>
				<td><textarea id="idAlamatSupplierEdit" class="form-control"><?php echo $data['alamat']; ?></textarea></td>
			</tr>
			<?php
		}
		?>
		</table>
		<div class="pull-right">
			<button type="submit" class="btn btn-primary">Update</button>
			<button id="btnCancelEditSupplier" type="button" class="btn btn-default">Cancel</button>
		</div>
	</form>
	<?php
}

elseif ($action == "hapusSupplier") {
	$kode = $_POST['pKode'];
	$hasilHapus = mysql_query("DELETE FROM tb_supplier WHERE kode_supplier = '$kode'");
	if($hasilHapus){
		echo "Hapus berhasil";
	}
	else{
		echo "Hapus gagal";
	}
}

?>