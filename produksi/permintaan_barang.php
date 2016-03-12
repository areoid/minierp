<!DOCTYPE HTML>
<!-- halaman pengeluaran barang - produksi -->
<?php
include "../src/produksi/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Permintaan Barang - Produksi</title>

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
				// hide form permintaan
				$("#formPermintaan").hide();

				// load listSatuan
				$("#idJenisSatuan").load("../src/produksi/proses_permintaan_barang.php?action=listSatuan");

				// load listPermintaan
				$("#divListPermintaanMen").load("../src/produksi/proses_permintaan_barang.php?action=listPermintaanMen");
				$("#divListPermintaanTer").load("../src/produksi/proses_permintaan_barang.php?action=listPermintaanTer");

				// auto
				$("#idJenisSatuan").change(function(){
					var sat = $("#idJenisSatuan").val();
					$.ajax({
						url: "../src/produksi/proses_permintaan_barang.php?action=autoSatuanKecil&sat="+sat,
						cache: false,
						success: function(h){
							var hasilArr = h.split("*");
							$("#idIsi").val(hasilArr[0]);
							$("#idSatuanKecil").val(hasilArr[1]);
							var jumlahSatuan = $("#idJumlahSatuan").val();
							var Isi = $("#idIsi").val();
							var hasil = jumlahSatuan * Isi;
							$("#idTotalSatuan").val(hasil);
						}
					});
				});

				// auto hitung total satuan
				$("#idIsi").keyup(function(){
					var jumlahSatuan = $("#idJumlahSatuan").val();
					var Isi = $("#idIsi").val();
					var hasil = jumlahSatuan * Isi;
					$("#idTotalSatuan").val(hasil);
				});
				$("#idJumlahSatuan").keyup(function(){
					var jumlahSatuan = $("#idJumlahSatuan").val();
					var Isi = $("#idIsi").val();
					var hasil = jumlahSatuan * Isi;
					$("#idTotalSatuan").val(hasil);
				});

				//btn cancel tambah permintaan diklik
				$("#btnCancelTambahPermintaan").click(function(){
					$("#warning").hide();
					$("#idNoSpi").val("");
					$("#idTanggal").val("");
					$("#idPengirim").val("");
					$("#formPermintaan").slideUp(1000);
					$("#btnTambahPermintaan").show();
				});

				// button tambah klik
				$("#btnTambahPermintaan").click(function(){
					$("#btnTambahPermintaan").hide();
					$("#formPermintaan").slideDown(1500);
				});

				// belanja selesai
				$("#btnSelesai").click(function(){
					$("#formDetailPermintaan").hide("fade", { direction: "in" }, 1000);
					$("#divPermintaan").hide("fade", { direction: "in" }, 1000);
					$("#btnTambahPermintaan").show("fade", { direction: "out" }, 1000);
					$("#divListPermintaanMen").show("fade", { direction: "out" }, 1000);
					$("#divListPermintaanTer").show("fade", { direction: "out" }, 1000);
					$("#divListPermintaanMen").load("../src/produksi/proses_permintaan_barang.php?action=listPermintaanMen");
					$("#divListPermintaanTer").load("../src/produksi/proses_permintaan_barang.php?action=listPermintaanTer");
					$("#idNoSpi").val("");
					$("#idTanggal").val("");
					$("#idPengirim").val("");
					
					$.ajax({
						url: "../src/produksi/proses_permintaan_barang.php?action=selesaiPermintaan",
						cache: false,
						success: function(hasilnya){
							//alert("opo hasil = "+hasilnya);
						}
					});
					

				});

				// belanja dibatalkan
				$("#btnCancelPermintaan").click(function(){
					var kdPermintaan = $("#kdPermintaan").html();
					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_permintaan_barang.php?action=cancelPermintaan",
						data: {pKdPermintaan:kdPermintaan},
						cache: false,
						success: function(hasilCancelPermintaan){
							$("#formDetailPermintaan").hide("fade", { direction: "in" }, 1000);
							$("#divPermintaan").hide("fade", { direction: "in" }, 1000);
							$("#btnTambahPermintaan").show("fade", { direction: "out" }, 1000);
							$("#divListPermintaanMen").show("fade", { direction: "out" }, 1000);
							$("#divListPermintaanTer").show("fade", { direction: "out" }, 1000);
							$("#divListPermintaanMen").load("../src/produksi/proses_permintaan_barang.php?action=listPermintaanMen");
							$("#divListPermintaanTer").load("../src/produksi/proses_permintaan_barang.php?action=listPermintaanTer");
							$("#idNoSpi").val("");
							$("#idTanggal").val("");
							$("#idPengirim").val("");
						}
					});
				});

				// form detail belanja di submit
				$("#formDetailPermintaan").submit(function(){
					var vKodeBarang = $("#idKodeBarang").val();
					var vNamaBarang = $("#idNamaBarang").val();
					var vjumlahSatuan = $("#idJumlahSatuan").val();
					var vjenisSatuan = $("#idJenisSatuan").val();
					var vIsi = $("#idIsi").val();
					var vtotalSatuan = $("#idTotalSatuan").val();
					var vSatuanKecil = $("#idSatuanKecil").val();

					//alert(vKodeBarang);

					if(vtotalSatuan == "0" || vjenisSatuan == "--"){
						alert("Tolong diisi dengan benar");
						return false;
					}
	
					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_permintaan_barang.php?action=tambahDetailPermintaan",
						data:{
							pKodeBarang:vKodeBarang,
							pNamaBarang:vNamaBarang,
							pJumlahSatuan:vjumlahSatuan,
							pJenisSatuan:vjenisSatuan,
							pIsi:vIsi,
							pTotalSatuan:vtotalSatuan,
							pSatuanKecil:vSatuanKecil
						},
						cache: false,
						success: function(hasilTambahDetailPermintaan){
							$("#divPermintaan").show("slide", { direction: "right" }, 1000);
							$("#divHeaderPermintaan").load("../src/produksi/proses_permintaan_barang.php?action=headerPermintaan");
							$("#divDetailPermintaan").load("../src/produksi/proses_permintaan_barang.php?action=listDetailPermintaan");

							// kosongkan form detail belanja
							$("#idKodeBarang").val("");
							$("#idNamaBarang").val("");
							$("#idJumlahSatuan").val("");
							$("#idIsi").val("");
							$("#idTotalSatuan").val("");
							$("#idJenisSatuan").load("../src/produksi/proses_permintaan_barang.php?action=listSatuan");
							alert(hasilTambahDetailPermintaan);
							$("#btnSelesai").show("fade", { direction: "out" }, 2000);
							
						}
					});

					return false;
				});

				// auto kode barang
				$("#idKodeBarang").autocomplete({
					source: "../src/produksi/proses_permintaan_barang.php?action=autoKodeBarang",
					minLength:1,
					delay:0
				});

				// auto nama barang
				$("#idKodeBarang").blur(function(){
					var krmKodeBarang = $("#idKodeBarang").val();
					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_permintaan_barang.php?action=autoNamaBarang",
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

				$("#warning").hide();
				$("#idNoSpi").blur(function(){
					var cek = $("#idNoSpi").val();
					$.ajax({
						url: "../src/produksi/proses_permintaan_barang.php?action=cekKode&pKode="+cek,
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
				
				// hide divDetailBelanja
				$("#formDetailPermintaan").hide();

				//hide divBelanja
				$("#divPermintaan").hide();

				//hide btnSelesai
				$("#btnSelesai").hide();	

				// datepicker
				$("#idTanggal").datepicker({ dateFormat: 'yy-mm-dd' });

				$("#idProduksiPermintaan").click(function(){
					$.ajax({
						url: "../src/produksi/clear_session.php",
						cache: false,
						success: function(hasilLogout){
							alert(hasilLogout);
							window.location.reload();
						}
					});
				});

			    // form permintaan diklik
			    $("#formPermintaan").submit(function(){
					var vKode = $("#idNoSpi").val();
					var vTanggal = $("#idTanggal").val();
					var vAdmin = "<?php echo $_SESSION['username']; ?>";
					var vPengirim = $("#idPengirim").val();
					
					//alert(vKodePermintaan+vTanggalPermintaan+vAdmin);

					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_permintaan_barang.php?action=tambahPermintaan",
						data: {
								pKode:vKode,
								pTanggal:vTanggal,
								pAdmin:vAdmin,
								pPengirim:vPengirim
							},
						cache: false,
						success: function(hasilTambahPermintaan){
							//alert(hasilTambahBelanja);
						}
					});
					$(this).hide("fade", { direction: "in" }, 1000);
					$("#divListPermintaanMen").hide("fade", { direction: "in" }, 1000);
					$("#divListPermintaanTer").hide("fade", { direction: "in" }, 1000);
					$("#formDetailPermintaan").show("slide", { direction: "right" }, 1000);
					//$("#divPermintaan").show("slide", { direction: "right" }, 1000);
			    	return false;
			    });

			});
function angka(e) {
	if (!/^[0-9]+$/.test(e.value)) {
		e.value = e.value.substring(0,e.value.length-1);
   }
}

function lihatTerkonfirmasi(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_permintaan_barang.php?action=lihatTerkonfirmasi",
		data: {pKode:kode},
		cache: false,
		success: function(hasilLihatTerkonfirmasi){
			$("#dialogLihatBerita").html(hasilLihatTerkonfirmasi);
			$("#dialogLihatBerita").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
}

function lihatPermintaan(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_permintaan_barang.php?action=lihatPermintaan",
		data: {pKode:kode},
		cache: false,
		success: function(hasilLihatPermintaan){
			$("#dialogLihatBerita").html(hasilLihatPermintaan);
			$("#dialogLihatBerita").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
}

function editPermintaan(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_permintaan_barang.php?action=editPermintaan",
		data: {pKode:kode},
		cache: false,
		success: function(hasilEditPermintaan){
			$("#dialogEditBerita").html(hasilEditPermintaan);
			$("#dialogEditBerita").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
}

function hapusPermintaan(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_permintaan_barang.php?action=hapusPermintaan",
		data: {pKode:kode},
		cache: false,
		success: function(hasilHapusPermintaan){
			alert(hasilHapusPermintaan);
			$("#divListPermintaanMen").load("../src/produksi/proses_permintaan_barang.php?action=listPermintaanMen");
			$("#divListPermintaanTer").load("../src/produksi/proses_permintaan_barang.php?action=listPermintaanTer");
		}
	});
}

function printPermintaan(kode){
	var url = "../src/produksi/proses_permintaan_barang.php?action=printPermintaan&pKode="+kode;
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
      <a class="navbar-brand" href="#">Area Produksi</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="#"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;Master <b class="caret"></b></a>
          <ul class="dropdown-menu">
          	<li><a href="barang_jadi.php">Barang Jadi</a></li>
          	<li class="divider"></li>
          	<li><a href="stok_barang_mentah_gudang.php">Stok Barang Mentah</a></li>
            <li><a href="stok_barang_jadi.php">Stok Barang Jadi</a></li>
          </ul>
        </li>
        <li class="dropdown active">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Transaksi <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Permintaan Barang Mentah</a></li>
            <li><a href="pengiriman_barang.php">Pengiriman Barang Jadi</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span> Laporan <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="lap_barang_jadi.php">Laporan Barang Jadi</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav pull-right">
		<li><a id="idProduksiPermintaan" href="#"><span class="glyphicon glyphicon-off"></span> Keluar</a></li>
	  </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<!-- /navbar -->

<!-- kotak tengah -->
<div class="thumbnail">
	<div class="caption">
		<button id="btnTambahPermintaan" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Tambah Permintaan
		</button>
		<form id="formPermintaan">
			<h2 align="center">Form Permintaan Barang</h2>
			<div class="row">
				<div class="col-md-4">
				</div><!-- ruang kosong kiri -->

				<div class="col-md-4">
					<div class="thumbnail">
						<div class="caption">
					<label class="control-label" for="kodePermintaan">No. SPI</label>
					<input id="idNoSpi" type="text" class="form-control" autocomplete="off" placeholder="No. SPI" required>
					<span id="warning" class="label label-danger pull-right"><span class="glyphicon glyphicon-warning-sign"></span> No. SPI sudah ada</span>
					<br>
					<label class="control-label" for="tanggalPermintaan">Tanggal Permintaan</label>
	             	<input id="idTanggal" type="text" class="form-control" autocomplete="off" placeholder="Tanggal Permintaan" required>
	             	<br>
					<label class="control-label" for="pengirim">Pengirim</label>
	             	<input id="idPengirim" type="text" class="form-control" autocomplete="off" placeholder="Nama Pengirim" required>
					<br>
	             	<div class="pull-right">
		           		<button id="btnCancelTambahPermintaan" type="button" class="btn btn-default">
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
		<div id="divListPermintaanTer">

		</div>
		<br>
		<div id="divListPermintaanMen">

		</div>
		<form id="formDetailPermintaan">
			<h2 align="center">Item Permintaan Barang</h2>
			<div class="thumbnail">
				<div class="caption">
			<table width="100%">
				<tr>
					<th>Kode Barang</th>
					<th>Nama Barang</th>
					<th>Jumlah</th>
					<th>Jenis Satuan</th>
					<th>Isi</th>
					<th>Total </th>
					<th>&nbsp;</th>
				</tr>
				<tr>
					<td width="10%"><input id="idKodeBarang" type="text" name="kodeBarang" class="form-control kodeBarang" placeholder="Kode Barang" required></td>
					<td><input id="idNamaBarang" type="text" class="form-control namaBarang" placeholder="Nama Barang" disabled></td>
					<td width="15%"><input id="idJumlahSatuan" type="text" name="jumlahSatuan" onkeyup="angka(this);" class="form-control jumlahSatuan" required></td>
					<td><select id ='idJenisSatuan' name='jenisSatuan' class='form-control'></td>
					<td width="10%"><input id="idIsi" type="text" name="Isi" onkeyup="angka(this);" class="form-control Isi" required disabled></td>
					<td width="15%"><input id="idTotalSatuan" type="text" name="totalSatuan" class="form-control totalSatuan" disabled required></td>
					<td width="10%"><input id="idSatuanKecil" type="text" name="satuanKecil" class="form-control" disabled required></td>
				</tr>
			</table>
			<br>
			<div class="pull-right">
				<button type="submit" name="simpan" class="btn btn-primary">Simpan & Tambah Belanja</button>
				<button id="btnCancelPermintaan" type="button" class="btn btn-primary">Cancel</button>
				<button id="btnSelesai" type="button" class="btn btn-primary">Selesai</button>
			</div>
			<br>
			<br>
				</div>
			</div>
		</form>
		<br>
		<div id="divPermintaan">
			<div id="divHeaderPermintaan">

			</div>
			<div id="divDetailPermintaan">

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
<div id="dialogLihatBerita">
	
</div>
<div id="dialogEditBerita">
	
</div>
	</body>
</html>