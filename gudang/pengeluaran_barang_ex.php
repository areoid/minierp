<!DOCTYPE HTML>
<!-- halaman belanja gudang -->
<?php
include "../src/gudang/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Pengeluaran Eksternal - Gudang</title>

		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.min.css">
		<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/dataTables.bootstrap.js"></script>
		<script type="text/javascript" language="javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				// hide warning
				$("#warning").hide();

				// load listSatuan
				$(".classSatuan").load("../src/gudang/proses_belanja.php?action=listSatuan");

				// load listSupplier
				$("#idSupplier").load("../src/gudang/proses_belanja.php?action=listSupplier");
	
				// load listPengeluaran
				$("#divListPengeluaranEks").load("../src/gudang/proses_barang_keluar.php?action=listPengeluaranEks");

				// cek nomer keluar
				$("#idKodePengeluaranEks").blur(function(){
					var cek = $("#idKodePengeluaranEks").val();
					$.ajax({
						url: "../src/gudang/proses_barang_keluar.php?action=cekKode&pKode="+cek,
						cache: false,
						success: function(h){
							var hasil = $.trim(h);
							if(hasil == "0"){
								$('button[name="lanjut"]').removeAttr('disabled');
								$("#warning").hide();
							}
							else{
								$("#warning").show();
								$('button[name="lanjut"]').attr('disabled','disabled');
							}
						} 
					});
				});

				// pengeluaran selesai
				$("#btnSelesai").click(function(){
					$("#formDetailPengeluaranEks").hide("fade", { direction: "in" }, 1000);
					$("#divPengeluaranEks").hide("fade", { direction: "in" }, 1000);
					$("#divListPengeluaranEks").show("fade", { direction: "out" }, 1000);
					$("#divListPengeluaranEks").load("../src/gudang/proses_barang_keluar.php?action=listPengeluaranEks");
					
					// kosongkan form detail pengeluaran
					$("#idKodeBarang").val("");
					$("#idNamaBarang").val("");
					$("#idJumlah").val("");
					$(".classSatuan").load("../src/gudang/proses_belanja.php?action=listSatuan");
					$("#idTotalHarga").val("");
					// kosongkan form pengeluaran
					$("#idKodePengeluaranEks").val("");
					$("#idTanggalPengeluaranEks").val("");
					$("#idKepada").val("");
					
					$("#btnTambah").show();
					
					$.ajax({
						url: "../src/gudang/proses_barang_keluar.php?action=selesaiPengelaranEks",
						cache: false,
						success: function(hasilnya){
							alert(hasilnya);
						}
					});
					

				});

				// belanja dibatalkan
				$("#btnCancel").click(function(){
					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_barang_keluar.php?action=cancelPengeluaran",
						cache: false,
						success: function(hasilCancelBelanja){
							$("#formDetailPengeluaranEks").hide("fade", { direction: "in" }, 1000);
							$("#divPengeluaranEks").hide("fade", { direction: "in" }, 1000);
							// kosongkan form detail pengeluaran
							$("#idKodeBarang").val("");
							$("#idNamaBarang").val("");
							$("#idJumlah").val("");
							$(".classSatuan").load("../src/gudang/proses_belanja.php?action=listSatuan");
							$("#idTotalHarga").val("");
							// kosongkan form pengeluaran
							$("#idKodePengeluaranEks").val("");
							$("#idTanggalPengeluaranEks").val("");
							$("#idKepada").val("");
							$("#btnTambah").show();
							$("#divListPengeluaranEks").show("fade", { direction: "out" }, 2000);
							$("#divListPengeluaranEks").load("../src/gudang/proses_barang_keluar.php?action=listPengeluaranEks");

						}
					});
				});

				// auto hitung
				$("#idSatuanJumlah").change(function(){
					var sat = $("#idSatuanJumlah").val();
					var jum = $("#idJumlah").val();
					$.ajax({
						url: "../src/gudang/proses_barang_keluar.php?action=autoHitung&sat="+sat,
						cache: false,
						success: function(h){
							var hasilArr = h.split('*');
							$("#idSatuanKecil").val(hasilArr[1]);
							var jumlah = $("#idJumlah").val();
							var total = jum * hasilArr[0];
							$("#idTotal").val(total);
						}
					});
				});

				$("#idJumlah").keyup(function(){
					var sat = $("#idSatuanJumlah").val();
					var jum = $("#idJumlah").val();
					$.ajax({
						url: "../src/gudang/proses_barang_keluar.php?action=autoHitung&sat="+sat,
						cache: false,
						success: function(h){
							var hasilArr = h.split('*');
							var total = jum * hasilArr[0];
							$("#idTotal").val(total);
						}
					});
				});


				// form detail belanja di submit
				$("#formDetailPengeluaranEks").submit(function(){
					var vKodeBarang = $("#idKodeBarang").val();
					var vNamaBarang = $("#idNamaBarang").val();
					var vJumlah = $("#idJumlah").val();
					var vSatuanJumlah = $("#idSatuanJumlah").val();
					var vTotal = $("#idTotal").val();
					var vSatuanKecil = $("#idSatuanKecil").val();

					if(vSatuanJumlah == "--"){
						alert("Harap diisi dengan benar !!");
						return false;
					}

					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_barang_keluar.php?action=tambahDetailPengeluaranEks",
						data:{
							pKodeBarang:vKodeBarang,
							pNamaBarang:vNamaBarang,
							pJumlah:vJumlah,
							pSatuan:vSatuanJumlah,
							pTotal:vTotal,
							pSatuanKecil:vSatuanKecil
						},
						cache: false,
						success: function(hasilTambahDetailPengeluaranEks){
							$("#divHeaderPengeluaranEks").load("../src/gudang/proses_barang_keluar.php?action=headerPengeluaranEks");
							$("#divDetailPengeluaranEks").load("../src/gudang/proses_barang_keluar.php?action=listDetailPengeluaranEks");

							// kosongkan form detail belanja
							$("#idKodeBarang").val("");
							$("#idNamaBarang").val("");
							$("#idJumlah").val("");
							$(".classSatuan").load("../src/gudang/proses_belanja.php?action=listSatuan");
							$("#idTotalHarga").val("");
							
						}
					});
					$("#btnSelesai").show("fade", { direction: "out" }, 2000);
					return false;
				});
				
				//btn cancel tambah belanja diklik
				$("#btnCancelTambahPengiriman").click(function(){
					$("#warning").hide();
					$("#idKodePengeluaranEks").val("");
					$("#idTanggalPengeluaranEks").val("");
					$("#formPengeluaranEks").slideUp(1000);
					$("#idKepada").val("");
					$("#idAlamat").val("");
					$("#btnTambah").show();
				});

				//btn tambah diklik
				$("#btnTambah").click(function(){
					$("#btnTambah").hide();
					$("#formPengeluaranEks").slideDown(1500);
				});

				// hide form belanja
				$("#formPengeluaranEks").hide();

				// auto hitung total harga
				$("#idJumlahBarang").keyup(function(){
					var harga = $("#idHargaBarang").val();
					var jmlBarang = $("#idJumlahBarang").val();
					var hasil = harga * jmlBarang;
					$("#idTotalHarga").val(hasil);
				});
				$("#idHargaBarang").keyup(function(){
					var harga = $("#idHargaBarang").val();
					var jmlBarang = $("#idJumlahBarang").val();
					var hasil = harga * jmlBarang;
					$("#idTotalHarga").val(hasil);
				});

				// auto kode barang
				$("#idKodeBarang").autocomplete({
					source: "../src/gudang/proses_barang_keluar.php?action=autoKodeBarang",
					minLength:1,
					delay:0
				});

				// auto nama barang
				$("#idKodeBarang").blur(function(){
					var krmKodeBarang = $("#idKodeBarang").val();
					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_barang_keluar.php?action=autoNamaBarang",
						data: {pKodeBarang:krmKodeBarang},
						cache: false,
						success: function(h){
							var hasilAutoNamaBarang = $.trim(h);
							if(hasilAutoNamaBarang == "gagal"){
								alert("Kode Barang Salah !!");
								$('button[name="simpan"]').attr('disabled','disabled');
								$("#idNamaBarang").val("Kode Barang Salah !!");
							}
							else{
								$("#idNamaBarang").val(hasilAutoNamaBarang);
								$('button[name="simpan"]').removeAttr('disabled');
							}
						}
					});
				});
				
				// hide divDetailPengeluaran
				$("#formDetailPengeluaranEks").hide();

				//hide btnSelesai
				$("#btnSelesai").hide();	

				// datepicker
				$("#idTanggalPengeluaranEks").datepicker({ dateFormat: 'yy-mm-dd' });

				$("#idGudangKeluar").click(function(){
					$.ajax({
						url: "../src/gudang/clear_session.php",
						cache: false,
						success: function(hasilLogout){
							alert(hasilLogout);
							window.location.reload();
						}
					});
				});

			    // form belanja diklik
			    $("#formPengeluaranEks").submit(function(){
					var vKodePengeluaranEks = $("#idKodePengeluaranEks").val();
					var vTanggalPengeluaranEks = $("#idTanggalPengeluaranEks").val();
					var vKepada = $("#idKepada").val();
					var vAlamat = $("#idAlamat").val();
					var vAdmin = "<?php echo $_SESSION['username']; ?>";
					
					//alert(vKodeBelanja+vTanggalBelanja+vAdmin);

					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_barang_keluar.php?action=tambahPengeluaranEks",
						data: {
								pKodePengeluaranEks:vKodePengeluaranEks,
								pTanggalPengeluaranEks:vTanggalPengeluaranEks,
								pKepada:vKepada,
								pAlamat:vAlamat,
								pAdmin:vAdmin
							},
						cache: false,
						success: function(hasilTambahPengeluaranEks){
							alert(hasilTambahBelanja);
						}
					});
					$(this).hide("fade", { direction: "in" }, 1000);
					$("#divListPengeluaranEks").hide("fade", { direction: "in" }, 1000);
					$("#formDetailPengeluaranEks").show("slide", { direction: "right" }, 1000);
					//$("#divBelanja").show("slide", { direction: "right" }, 1000);
			    	return false;
			    });

			});
function angka(e) {
	if (!/^[0-9]+$/.test(e.value)) {
		e.value = e.value.substring(0,e.value.length-1);
   }
}

function lihatPengeluaranEks(kode){
	$.ajax({
		type: "POST",
		url: "../src/gudang/proses_barang_keluar.php?action=lihatPengeluaranEks",
		data: {pKode:kode},
		cache: false,
		success: function(hasilLihatPengeluaran){
			$("#dialogLihatPengeluaranEks").html(hasilLihatPengeluaran);
			$("#dialogLihatPengeluaranEks").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
	
}

function editPengeluaranEks(kode){
	$.ajax({
		type: "POST",
		url: "../src/gudang/proses_barang_keluar.php?action=editPengeluaranEks",
		data: {pKode:kode},
		cache: false,
		success: function(hasilEditBelanja){
			$("#dialogEditPengeluaranEks").html(hasilEditBelanja);
			$("#dialogEditPengeluaranEks").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
}

function hapusPengeluaranEks(kode){
	$.ajax({
		type: "POST",
		url: "../src/gudang/proses_barang_keluar.php?action=hapusPengeluaranEks",
		data: {pKode:kode},
		cache: false,
		success: function(hasilHapusPengeluaranEks){
			alert(hasilHapusPengeluaranEks);
			$("#divListPengeluaranEks").load("../src/gudang/proses_barang_keluar.php?action=listPengeluaranEks");
		}
	});
}

function printPengeluaranEks(kode){
	var url = "../src/gudang/proses_barang_keluar.php?action=printPengeluaranEks&pKode="+kode;
  	var win=window.open(url, '_blank');
  	win.focus();
  	//win.close();
}
		</script>
	</head>
	<body>
		<div class="container">

<!-- navbar -->
<nav class="navbar navbar-inverse" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Area Gudang</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;Master <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="barang.php">Barang (Mentah)</a></li>
            <li><a href="supplier.php">Supplier</a></li>
            <li><a href="tahun_produksi.php">Tahun Produksi</a></li>
            <li><a href="satuan_barang.php">Satuan Barang</a></li>
            <li class="divider"></li>
            <li><a href="stok_barang_mentah_gudang.php">Stok Barang Mentah</a></li>
            <li><a href="stok_barang_jadi.php">Stok Barang Jadi</a></li>
          </ul>
        </li>
        <li class="active" class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Transaksi <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="belanja.php">Belanja</a></li>
			<li> <a href="#">PengeluaranEks</a></li>
		    <li><a href="konfirmasi_barang_mentah.php">Konfirmasi Barang Mentah</a></li>
            <li><a href="konfirmasi_barang_jadi.php">Konfirmasi Barang Jadi</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span> Laporan <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="lap_jurnal.php">Laporan Barang Mentah</a></li>
            <li><a href="lap_barang_jadi.php">Laporan Barang Jadi</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav pull-right">
		<li><a id="idGudangKeluar" href="#"><span class="glyphicon glyphicon-off"></span> Keluar</a></li>
	  </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<!-- /navbar -->

<!-- kotak tengah -->
<div class="thumbnail">
	<div class="caption">
		<button id="btnTambah" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Tambah Pengeluaran
		</button>
		<br>
		<form id="formPengeluaranEks">
			<h3 align="center">Form Pengeluaran Eksternal</h3>
			<div class="row">
				<div class="col-md-4">
				</div><!-- ruang kosong kiri -->
				<div class="col-md-4">
				<div class="thumbnail">
					<div class="caption">
					<label class="control-label" for="kodePengeluaranEksBarang">No. Pengeluaran</label>
					<input id="idKodePengeluaranEks" type="text" class="form-control" autocomplete="off" placeholder="No. Pengeluaran" required>
					<span id="warning" class="label label-danger pull-right"><span class="glyphicon glyphicon-warning-sign"></span> No. Pengeluaran sudah ada</span>
					<br>
					<label class="control-label" for="tanggalPengeluaranEksBarang">Tanggal Pengeluaran</label>
	             	<input id="idTanggalPengeluaranEks" type="text" class="form-control" autocomplete="off" placeholder="Tanggal Pengeluaran" required>
	             	<br>
					<label class="control-label" for="kepada">Kepada</label>
	             	<input id="idKepada" type="text" class="form-control" autocomplete="off" placeholder="Kepada" required>
	             	<br>
	             	<label class="control-label" for="alamat">Alamat</label>
	             	<textarea id="idAlamat" class="form-control" placeholder="Alamat" required></textarea>
	             	<br>
	             	<div class="pull-right">
		          		<button id="btnCancelTambahPengiriman" type="button" class="btn btn-default">
							<span class="glyphicon glyphicon-remove"></span> Cancel
						</button>
		              	<button type="submit" name="lanjut" class="btn btn-primary">
		              		LANJUT <span class="glyphicon glyphicon-chevron-right"></span>
		              	</button>
	              	</div>
	              	<br>
	              	<br>
	              	</div>
				</div>
				</div>
				<div class="col-md-4">
				</div><!-- ruang kosong kanan -->
			</div>
		</form>
		<br>
		<br>
		<div id="divListPengeluaranEks">
		
		</div>
		<form id="formDetailPengeluaranEks">
			<h2 align="center">Item Pengeluaran</h2>
			<div class="thumbnail">
				<div class="caption">
			<table width="100%">
				<table width="100%">
				<tr>
					<th>Kode Barang</th>
					<th>Nama Barang</th>
					<th>Jumlah</th>
					<th>Satuan</th>
					<th>Total</th>
				</tr>
				<tr>
					<td width="15%"><input id="idKodeBarang" type="text" name="kodeBarang" class="form-control" placeholder="Kode Barang" required></td>
					<td><input id="idNamaBarang" type="text" class="form-control" placeholder="Nama Barang" disabled></td>
					<td width="10%"><input id="idJumlah" type="text" name="jumlahBarang" onkeyup="angka(this);" class="form-control" autocomplete="off" required></td>
					<td width="10%"><select id="idSatuanJumlah" class="form-control classSatuan"></select></td>
					<td width="10%"><input id="idTotal" type="text" name="total" class="form-control" disabled required></td>
					<td width="10%"><input id="idSatuanKecil" type="text" name="satuankecil" class="form-control" disabled required></td>
				</tr>
			</table>
			<br>
			<div class="pull-right">
				<button type="submit" name="simpan" class="btn btn-primary">Simpan & Tambah Item Barang</button>
				<button id="btnCancel" type="button" class="btn btn-primary">Cancel</button>
				<button id="btnSelesai" type="button" class="btn btn-primary">Selesai</button>
			</div>
			<br>
			<br>
				</div>
			</div>
		</form>
		<br>
		<div id="divPengeluaranEks">
			<div id="divHeaderPengeluaranEks">

			</div>
			<div id="divDetailPengeluaranEks">

			</div>
		</div>
	</div>
</div>
<!-- /kotak tengah -->

<nav class="navbar navbar-inverse" role="navigation">
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse .footer" id="bs-example-navbar-collapse-1">

    </div><!-- /.navbar-collapse -->
    <br>
    <div align="center">
    	<!--<div class="thumbnail" style="background-color: transparent">
			<div class="caption" style="background-color: transparent">-->
    			<img width="20%" src="../images/logo-naratel.png" />
    			<h6><span style="color: #fff"> Copyright © 2014 | PT.Naraya Telematika </span></h6>
    		
    </div>
</nav>
			
		</div>
<div id="dialogLihatPengeluaranEks">
	
</div>
<div id="dialogEditPengeluaranEks">
	
</div>
	</body>
</html>