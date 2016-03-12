<!DOCTYPE HTML>
<!-- halaman pengeluaran barang - produksi -->
<?php
include "../src/produksi/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Barang Keluar - Produksi</title>

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
	
				// load listBelanja
				$("#divListKeluar").load("../src/produksi/proses_barang_keluar.php?action=listKeluar");

				// belanja selesai
				$("#btnSelesai").click(function(){
					$("#formDetailKeluar").hide("fade", { direction: "in" }, 1000);
					$("#divKeluar").hide("fade", { direction: "in" }, 1000);
					$("#formKeluar").show("fade", { direction: "out" }, 1000);
					$("#divListKeluar").show("fade", { direction: "out" }, 1000);
					$("#divListKeluar").load("../src/produksi/proses_barang_keluar.php?action=listKeluar");
					$("#idKodeKeluar").val("");
					$("#idTanggalKeluar").val("");
					$("#idPengirim").val("");
					$("#idPenerima").val("");
					
					$.ajax({
						url: "../src/produksi/proses_barang_keluar.php?action=selesaiKeluar",
						cache: false,
						success: function(hasilnya){
							alert("opo hasil = "+hasilnya);
						}
					});
					

				});

				// belanja dibatalkan
				$("#btnCencelKeluar").click(function(){
					var kdKeluar = $("#kdKeluar").html();
					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_barang_keluar.php?action=cancelKeluar",
						data: {pkdKeluar:kdKeluar},
						cache: false,
						success: function(hasilCancelKeluar){
							$("#formDetailKeluar").hide("fade", { direction: "in" }, 1000);
							$("#divKeluar").hide("fade", { direction: "in" }, 1000);
							$("#formKeluar").show("fade", { direction: "out" }, 2000);
							$("#idKodeKeluar").val("");
							$("#idTanggalKeluar").val("");
							$("#idPengirim").val("");
							$("#idPenerima").val("");
						}
					});
				});

				// form detail belanja di submit
				$("#formDetailKeluar").submit(function(){
					$("#btnSelesai").show("fade", { direction: "out" }, 2000);
					var vKodeBarang = $("#idKodeBarang").val();
					var vNamaBarang = $("#idNamaBarang").val();
					var vjumlahSatuan = $("#idJumlahSatuan").val();
					var vjenisSatuan = $("#idJenisSatuan").val();
					var vIsi = $("#idIsi").val();
					var vtotalSatuan = $("#idTotalSatuan").val();
					var vCountKeluar = $("#idCountKeluar").html();

					//alert(vKodeBarang);

					if(vtotalSatuan == "0"){
						alert("Tolong diisi dengan benar");
						return false;
					}
	
					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_barang_keluar.php?action=tambahDetailKeluar",
						data:{
							pKodeBarang:vKodeBarang,
							pNamaBarang:vNamaBarang,
							pJumlahSatuan:vjumlahSatuan,
							pJenisSatuan:vjenisSatuan,
							pIsi:vIsi,
							pTotalSatuan:vtotalSatuan,
							pCountKeluar:vCountKeluar
						},
						cache: false,
						success: function(hasilTambahDetailKeluar){
							$("#divKeluar").show("slide", { direction: "right" }, 1000);
							$("#divHeaderKeluar").load("../src/produksi/proses_barang_keluar.php?action=headerKeluar");
							$("#divDetailKeluar").load("../src/produksi/proses_barang_keluar.php?action=listDetailKeluar");

							// kosongkan form detail belanja
							$("#idKodeBarang").val("");
							$("#idNamaBarang").val("");
							$("#idJumlahSatuan").val("");
							$("#idIsi").val("");
							$("#idTotalSatuan").val("");
							alert(hasilTambahDetailKeluar);
							
						}
					});

					return false;
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

				// auto kode barang
				$("#idKodeBarang").autocomplete({
					source: "../src/produksi/proses_barang_keluar.php?action=autoKodeBarang",
					minLength:1,
					delay:0
				});

				// auto nama barang
				$("#idKodeBarang").blur(function(){
					var krmKodeBarang = $("#idKodeBarang").val();
					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_barang_keluar.php?action=autoNamaBarang",
						data: {pKodeBarang:krmKodeBarang},
						cache: false,
						success: function(hasilAutoNamaBarang){
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
				
				// hide divDetailBelanja
				$("#formDetailKeluar").hide();

				//hide divBelanja
				$("#divKeluar").hide();

				//hide btnSelesai
				$("#btnSelesai").hide();	

				// datepicker
				$("#idTanggalKeluar").datepicker({ dateFormat: 'yy-mm-dd' });

				$("#idProduksiKeluar").click(function(){
					$.ajax({
						url: "../src/produksi/clear_session.php",
						cache: false,
						success: function(hasilLogout){
							alert(hasilLogout);
							window.location.reload();
						}
					});
				});

			    // form belanja diklik
			    $("#formKeluar").submit(function(){
					var vKodeKeluar = $("#idKodeKeluar").val();
					var vTanggalKeluar = $("#idTanggalKeluar").val();
					var vAdmin = "<?php echo $_SESSION['username']; ?>";
					var vPengirim = $("#idPengirim").val();
					var vPenerima = $("#idPenerima").val();
					
					//alert(vKodeKeluar+vTanggalKeluar+vAdmin);

					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_barang_keluar.php?action=tambahKeluar",
						data: {
								pKodeKeluar:vKodeKeluar,
								pTanggalKeluar:vTanggalKeluar,
								pAdmin:vAdmin,
								pPengirim:vPengirim,
								pPenerima:vPenerima
							},
						cache: false,
						success: function(hasilTambahKeluar){
							//alert(hasilTambahBelanja);
						}
					});
					$(this).hide("fade", { direction: "in" }, 1000);
					$("#divListKeluar").hide("fade", { direction: "in" }, 1000);
					$("#formDetailKeluar").show("slide", { direction: "right" }, 1000);
					//$("#divKeluar").show("slide", { direction: "right" }, 1000);
			    	return false;
			    });

			});
function angka(e) {
	if (!/^[0-9]+$/.test(e.value)) {
		e.value = e.value.substring(0,e.value.length-1);
   }
}

function lihatKeluar(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_barang_keluar.php?action=lihatKeluar",
		data: {pKode:kode},
		cache: false,
		success: function(hasilLihatKeluar){
			$("#dialogLihatBerita").html(hasilLihatKeluar);
			$("#dialogLihatBerita").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
	
}

function editKeluar(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_barang_keluar.php?action=editKeluar",
		data: {pKode:kode},
		cache: false,
		success: function(hasilEditKeluar){
			$("#dialogEditBerita").html(hasilEditKeluar);
			$("#dialogEditBerita").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
}

function hapusKeluar(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_barang_keluar.php?action=hapusKeluar",
		data: {pKode:kode},
		cache: false,
		success: function(hasilHapusKeluar){
			alert("Hapus : "+hasilHapusKeluar);
			$("#divListKeluar").load("../src/produksi/proses_barang_keluar.php?action=listKeluar");
		}
	});
}

function printKeluar(kode){
	var url = "../src/produksi/proses_barang_keluar.php?action=printKeluar&pKode="+kode;
  	var win=window.open(url, '_blank');
  	win.focus();
  	//win.close();
}
		</script>
	</head>
	<body>
		<div class="container">

<!-- navbar -->
<nav class="navbar navbar-default" role="navigation">
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
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;Master <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="barang.php">Barang</a></li>
            <li><a href="supplier.php">Supplier</a></li>
            <li class="divider"></li>
            <li><a href="stok_barang_mentah_gudang.php">Stok Barang Mentah (Gudang)</a></li>
            <li><a href="stok_barang_mentah_produksi.php">Stok Barang Mentah (Produksi)</a></li>
            <li><a href="stok_barang_jadi.php">Stok Barang Jadi</a></li>
          </ul>
        </li>
        <li class="active" class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Transaksi <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Permintaan Barang Mentah</a></li>
			<li> <a href="#">Pengiriman Barang Jadi</a></li>
		   <!-- <li><a href="konfirmasi_barang_mentah.php">Konfirmasi Barang Mentah</a></li>
            <li><a href="konfirmasi_barang_jadi.php">Konfirmasi Barang Jadi</a></li>-->
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span> Laporan <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="lap_jurnal.php">Laporan Barang</a></li>
            <li><a href="lap_barang_mentah_keluar">Laporan Barang Mentah Keluar</a></li>
            <li><a href="lap_barang_jadi.php">Laporan Barang Jadi</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav pull-right">
		<li><a id="idProduksiKeluar" href="#"><span class="glyphicon glyphicon-off"></span> Keluar</a></li>
	  </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<!-- /navbar -->

<!-- kotak tengah -->
<div class="thumbnail">
	<div class="caption">
		<!--<button id="idtest" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Tambah Belanja
		</button>-->
		<h2>Form Permintaan Barang</h2>
		<form id="formKeluar">
			<div class="row">
				<div class="col-md-4">
				</div><!-- ruang kosong kiri -->

			<div class="col-md-4">
					<label class="control-label" for="kodekeluar">No. SPI</label>
					<input id="idKodeKeluar" type="text" class="form-control" autocomplete="off" placeholder="No. SPI" required>
					<br>
					<label class="control-label" for="tanggalkeluar">Tanggal Permintaan</label>
	             	<input id="idTanggalKeluar" type="text" class="form-control" autocomplete="off" placeholder="Tanggal Pengeluaran" required>
	             	<br>
					<label class="control-label" for="pengirim">Pengirim</label>
	             	<input id="idPengirim" type="text" class="form-control" autocomplete="off" placeholder="Nama Pengirim" required>
					<br>
					<label class="control-label" for="penerima">Penerima</label>
	             	<input id="idPenerima" type="text" class="form-control" autocomplete="off" placeholder="Nama Penerima" required>
					<br>
	             	<button class="btn btn-primary pull-right">LANJUT <span class="glyphicon glyphicon-chevron-right"></span></button>
				</div>

				<div class="col-md-4">
				</div><!-- ruang kosong kanan -->
			</div>
		</form>
		<br>
		<br>
	<div id="divListKeluar">

		</div>
		<form id="formDetailKeluar">
			<table width="100%">
				<tr>
					<th>Kode Barang</th>
					<th>Nama Barang</th>
					<th>Jumlah Satuan</th>
					<th>Jenis Satuan</th>
					<th>Isi</th>
					<th>Total Satuan</th>
				</tr>
				<tr>
					<td width="10%"><input id="idKodeBarang" type="text" name="kodeBarang" class="form-control kodeBarang" placeholder="Kode Barang" required></td>
					<td><input id="idNamaBarang" type="text" class="form-control namaBarang" placeholder="Nama Barang" disabled></td>
					<td width="15%"><input id="idJumlahSatuan" type="text" name="jumlahSatuan" onkeyup="angka(this);" class="form-control jumlahSatuan" required></td>
						<td><?php include "../config/configuration.php";
echo "<select id ='idJenisSatuan' name='jenisSatuan' class='form-control jenisSatuan'>";
$tampil=mysql_query("SELECT * FROM tb_satuan ORDER BY id_satuan");
echo "<option value='belum milih' selected>- Pilih Jenis Satuan -</option>";

while($w=mysql_fetch_array($tampil))
{
    echo "<option value=$w[nama_satuan] selected>$w[nama_satuan]</option>";        
}
 echo "</select>";
?></td>
					<td width="10%"><input id="idIsi" type="text" name="Isi" onkeyup="angka(this);" class="form-control Isi" required></td>
					<td width="15%"><input id="idTotalSatuan" type="text" name="totalSatuan" class="form-control totalSatuan" disabled required></td>
				</tr>
			<table>
			<br>
			<div class="pull-right">
				<button type="submit" name="simpan" class="btn btn-primary">Simpan & Tambah Belanja</button>
				<button id="btnCencelKeluar" type="button" class="btn btn-primary">Cancel</button>
				<button id="btnSelesai" type="button" class="btn btn-primary">Selesai</button>
			</div>
			<br>
			<br>
		</form>
		<br>
		<div id="divKeluar">
			<div id="divHeaderKeluar">

			</div>
			<div id="divDetailKeluar">

			</div>
		</div>
	</div>
</div>
<!-- /kotak tengah -->
			
		</div>
<div id="dialogLihatBerita">
	
</div>
<div id="dialogEditBerita">
	
</div>
	</body>
</html>