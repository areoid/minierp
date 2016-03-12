<!DOCTYPE HTML>
<!-- halaman belanja gudang -->
<?php
include "../src/gudang/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Belanja - Gudang</title>

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
				$("#warningLpb").hide();

				// validation LPB
				$("#idKodeBelanja").blur(function(){
					var lpb = $("#idKodeBelanja").val();
					$.ajax({
						url: "../src/gudang/proses_belanja.php?action=cekLpb&lpb="+lpb,
						cache: false,
						success: function(hasilCek){
							if(hasilCek == 0){
								$('button[name="lanjut"]').removeAttr('disabled');
								$("#warningLpb").hide();
							}
							else{
								$("#warningLpb").show();
								$('button[name="lanjut"]').attr('disabled','disabled');
							}
						}
					});
				});

				//auto uppercase
				$('#idKodeBelanja').keyup(function(){
					this.value = this.value.toUpperCase();
				}); 

				// load listSatuan
				$(".classSatuan").load("../src/gudang/proses_belanja.php?action=listSatuan");

				// load listSupplier
				$("#idSupplier").load("../src/gudang/proses_belanja.php?action=listSupplier");

				// load listBelanja
				$("#divListBelanja").load("../src/gudang/proses_belanja.php?action=listBelanja");

				// belanja selesai
				$("#btnSelesai").click(function(){
					$("#formDetailBelanja").hide("fade", { direction: "in" }, 1000);
					$("#divBelanja").hide("fade", { direction: "in" }, 1000);
					$("#formBelanja").show("fade", { direction: "out" }, 1000);
					$("#divListBelanja").show("fade", { direction: "out" }, 1000);
					$("#divListBelanja").load("../src/gudang/proses_belanja.php?action=listBelanja");
					$("#idKodeBelanja").val("");
					$("#idTanggalBelanja").val("");
					$("#formBelanja").slideUp(1000);
					$("#btnTambah").show();
					$("#idSupplier").load("../src/gudang/proses_belanja.php?action=listSupplier");

					$.ajax({
						url: "../src/gudang/proses_belanja.php?action=selesaiBelanja",
						cache: false,
						success: function(hasilnya){
							alert(hasilnya);
						}
					});
				});

				// belanja dibatalkan
				$("#btnCancelBelanja").click(function(){
					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_belanja.php?action=cancelBelanja",
						cache: false,
						success: function(hasilCancelBelanja){
							$("#formDetailBelanja").hide("fade", { direction: "in" }, 1000);
							$("#divBelanja").hide("fade", { direction: "in" }, 1000);
							$('#btnCancelTambahBelanja').trigger('click');
							$("#idKodeBelanja").val("");
							$("#idTanggalBelanja").val("");
							$("#divListBelanja").show("fade", { direction: "out" }, 2000);
							
						}
					});
				});

				// form detail belanja di submit
				$("#formDetailBelanja").submit(function(){
					var vKodeBarang = $("#idKodeBarang").val();
					var vNamaBarang = $("#idNamaBarang").val();
					var vHarga = $("#idHarga").val();
					var vJumlah = $("#idJumlah").val();
					var vSatuanJumlah = $("#idSatuanJumlah").val();
					var vIsi = $("#idIsi").val();
					var vSatuanIsi = $("#idSatuanIsi").val();
					var vTotal = $("#idTotal").val();
					var vKeterangan = $("#idKeterangan").val();
					
					if(vSatuanJumlah == "--"){
						alert("Harap diisi dengan benar !!");
						return false;
					}
					
					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_belanja.php?action=tambahDetailBelanja",
						data:{
							pKodeBarang:vKodeBarang,
							pNamaBarang:vNamaBarang,
							pHarga:vHarga,
							pJumlah:vJumlah,
							pSatuanJumlah:vSatuanJumlah,
							pIsi:vIsi,
							pSatuanIsi:vSatuanIsi,
							pTotal:vTotal,
							pKeterangan:vKeterangan
						},
						cache: false,
						success: function(hasilTambahDetailBelanja){
							$("#divBelanja").show("slide", { direction: "right" }, 1000);
							$("#divHeaderBelanja").load("../src/gudang/proses_belanja.php?action=headerBelanja");
							$("#divDetailBelanja").load("../src/gudang/proses_belanja.php?action=listDetailBelanja");

							// kosongkan form detail belanja
							$("#idKodeBarang").val("");
							$("#idNamaBarang").val("");
							$("#idHarga").val("");
							$("#idJumlah").val("");
							$(".classSatuan").load("../src/gudang/proses_belanja.php?action=listSatuan");
							$("#idIsi").val("");
							$("#idTotal").val("");
							$("#idSatuanIsi").val();
							$("#idKeterangan").val("");
							$("#btnSelesai").show("fade", { direction: "out" }, 2000);

							//alert(hasilTambahDetailBelanja);
						}
					});
					return false;
				});

				// auto hitung total harga
				$("#idJumlah").keyup(function(){
					var jumlah = $("#idJumlah").val();
					var isi = $("#idIsi").val();
					var total = jumlah * isi;
					$("#idTotal").val(total);
				});
				$("#idIsi").keyup(function(){
					var jumlah = $("#idJumlah").val();
					var isi = $("#idIsi").val();
					var total = jumlah * isi;
					$("#idTotal").val(total);
				});

				// auto satuankecil
				$("#idSatuanJumlah").change(function(){
					var satuan = $("#idSatuanJumlah").val();
					$.ajax({
						url: "../src/gudang/proses_belanja.php?action=autoSatKecil&sat="+satuan,
						cache: false,
						success: function(h){
							var hasilArr = h.split('*');
							$("#idIsi").val(hasilArr[0]);
							$("#idSatuanIsi").val(hasilArr[1]);
							var jumlah = $("#idJumlah").val();
							var total = jumlah * hasilArr[0];
							$("#idTotal").val(total);
						}
					});
				});

				// auto kode barang
				$("#idKodeBarang").autocomplete({
					source: "../src/gudang/proses_belanja.php?action=autoKodeBarang",
					minLength:1,
					delay:0
				});

				// auto nama barang
				$("#idKodeBarang").blur(function(){
					var krmKodeBarang = $("#idKodeBarang").val();
					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_belanja.php?action=autoNamaBarang",
						data: {pKodeBarang:krmKodeBarang},
						cache: false,
						success: function(cek){
							var hasilAutoNamaBarang = $.trim(cek);
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

				//btn cancel tambah belanja diklik
				$("#btnCancelTambahBelanja").click(function(){
					$("#idKodeBelanja").val("");
					$("#idTanggalBelanja").val("");
					$("#formBelanja").slideUp(1000);
					$("#btnTambah").show();
					$("#idSupplier").load("../src/gudang/proses_belanja.php?action=listSupplier");
				});

				//btn tambah diklik
				$("#btnTambah").click(function(){
					$("#btnTambah").hide();
					$("#formBelanja").slideDown(1500);
				});

				// hide form belanja
				$("#formBelanja").hide();
				
				// hide divDetailBelanja
				$("#formDetailBelanja").hide();

				//hide divBelanja
				$("#divBelanja").hide();

				//hide btnSelesai
				$("#btnSelesai").hide();	

				// datepicker
				$("#idTanggalBelanja").datepicker({ dateFormat: 'yy-mm-dd' });

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
			    $("#formBelanja").submit(function(){
					var vKodeBelanja = $("#idKodeBelanja").val();
					var vTanggalBelanja = $("#idTanggalBelanja").val();
					var vAdmin = "<?php echo $_SESSION['username']; ?>";
					var vSupplier = $("#idSupplier").val();
					
					if(vSupplier == "--"){
						alert("Harap diisi dengan benar !!");
						return false;
					}
					
					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_belanja.php?action=tambahBelanja",
						data: {
								pKodeBelanja:vKodeBelanja,
								pTanggalBelanja:vTanggalBelanja,
								pSupplier:vSupplier,
								pAdmin:vAdmin
							},
						cache: false,
						success: function(hasilTambahBelanja){
							//alert(hasilTambahBelanja);

						}
					});
					$(this).hide("fade", { direction: "in" }, 1000);
							$("#divListBelanja").hide("fade", { direction: "in" }, 1000);
							$("#formDetailBelanja").show("slide", { direction: "right" }, 1000);
					//$("#divBelanja").show("slide", { direction: "right" }, 1000);*/
			    	return false;
			    });

			});
function angka(e) {
	if (!/^[0-9]+$/.test(e.value)) {
		e.value = e.value.substring(0,e.value.length-1);
   }
}

function lihatBelanja(kode){
	$.ajax({
		type: "POST",
		url: "../src/gudang/proses_belanja.php?action=lihatBelanja",
		data: {pKode:kode},
		cache: false,
		success: function(hasilLihatBelanja){
			$("#dialogLihatBerita").html(hasilLihatBelanja);
			$("#dialogLihatBerita").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
	
}

function editBelanja(kode){
	//alert(kode);
	
	$.ajax({
		type: "POST",
		url: "../src/gudang/proses_belanja.php?action=editBelanja",
		data: {pKode:kode},
		cache: false,
		success: function(hasilEditBelanja){
			$("#dialogEditBerita").html(hasilEditBelanja);
			$("#dialogEditBerita").dialog({
				position:['middle',20],
				resizeable:true,
				width:1200,
				modal: true
			});
		}
	});

}

function hapusBelanja(kode){
	//alert(kode);
	
	$.ajax({
		type: "POST",
		url: "../src/gudang/proses_belanja.php?action=hapusBelanja",
		data: {pKode:kode},
		cache: false,
		success: function(hasilHapusBelanja){
			alert(hasilHapusBelanja);
			$("#divListBelanja").load("../src/gudang/proses_belanja.php?action=listBelanja");
		}
	});
}

function printBelanja(kode){
	var url = "../src/gudang/proses_belanja.php?action=printBelanja&pKode="+kode;
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
            <li><a href="#">Belanja</a></li>
            <li><a href="pengeluaran_barang_ex.php">Permintaan Eksternal</a></li>
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
			<span class="glyphicon glyphicon-plus"></span> Tambah Belanja
		</button>
		<br>
		<form id="formBelanja">
			<h2 align="center" id="idJudul">Form Belanja</h2>
			<div class="row">
				<div class="col-md-4">
				</div><!-- ruang kosong kiri -->
				<div class="col-md-4">
					<div class="thumbnail">
						<div class="caption">
						<label class="control-label" for="kodebelanja">No. LPB</label>
						<input id="idKodeBelanja" type="text" class="form-control" autocomplete="off" placeholder="No. LPB" required>
						<span id="warningLpb" class="label label-danger pull-right"><span class="glyphicon glyphicon-warning-sign"></span> No. LPB sudah ada</span>
						<br>
						<label class="control-label" for="tanggalbelanja">Tanggal</label>
		             	<input id="idTanggalBelanja" type="text" class="form-control" autocomplete="off" placeholder="Tanggal Belanja" required>
		             	<br>
		             	<label class="control-label" for="supplier">Supplier</label>
		             	<select id="idSupplier" class="form-control">
						</select>
		             	<br>
		             	<div class="pull-right">
		              		<button id="btnCancelTambahBelanja" type="button" class="btn btn-default">
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
		<div id="divListBelanja">

		</div>
		<form id="formDetailBelanja">
			<h2 align="center">Item Belanja</h2>
			<div class="thumbnail">
				<div class="caption">
			<table width="100%">
				<tr>
					<th>Kode Barang</th>
					<th>Nama Barang</th>
					<th>Jumlah</th>
					<th>Satuan</th>
					<th>Isi</th>
					<th>Total</th>
					<th>&nbsp;</th>
					<th>Keterangan</th>
				</tr>
				<tr>
					<td width="10%"><input id="idKodeBarang" type="text" name="kodeBarang" class="form-control" placeholder="Kode Barang" required></td>
					<td><input id="idNamaBarang" type="text" class="form-control" placeholder="Nama Barang" disabled></td>
					<td width="10%"><input id="idJumlah" type="text" name="jumlahBarang" onkeyup="angka(this);" class="form-control" autocomplete="off" required></td>
					<td width="10%"><select id="idSatuanJumlah" class="form-control classSatuan"></select></td>
					<td width="10%"><input id="idIsi" type="text" name="isi" onkeyup="angka(this);" class="form-control" autocomplete="off" required></td>
					<td width="10%"><input id="idTotal" type="text" name="totalHarga" class="form-control" disabled required></td>
					<td width="10%"><input id="idSatuanIsi" type="text" name="satuankecil" class="form-control" placeholder="Satuan Kecil" disabled></td>
					<td><input id="idKeterangan" type="text" name="keterangan" class="form-control" autocomplete="off" required><td>
				</tr>
			</table>
			<br>
			<div class="pull-right">
				<button type="submit" name="simpan" class="btn btn-primary">Simpan & Tambah Item</button>
				<button id="btnCancelBelanja" type="button" class="btn btn-primary">Cancel</button>
				<button id="btnSelesai" type="button" class="btn btn-primary">Selesai</button>
			</div>
			<br>
			<br>
				</div>
			</div>
		</form>
		<br>
		<div id="divBelanja">
			<div id="divHeaderBelanja">

			</div>
			<div id="divDetailBelanja">

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

		</div><!-- /container -->


<div id="dialogLihatBerita">
	
</div>
<div id="dialogEditBerita">
	
</div>
	</body>
</html>