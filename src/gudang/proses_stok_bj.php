<?php
// proses stok barang jadi
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "listStokBJ"){
?>
<script type="text/javascript">
$(document).ready(function(){
	$("#tabelListStokBJ").dataTable();
});
</script>
<?php
	$tahunSekarang = date('Y');
	$no = 0;
	//mysql_query("SELECT * FROM tb_barang_jadi'") or die("error cur ");
	$hasilBRG = mysql_query("SELECT * FROM tb_barang_jadi") ;
	?>
<h3 align="center">Stok Barang Jadi</h3>
<div class="thumbnail">
	<div class="caption">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListStokBJ">
	<thead>
		<tr>
			<th>NO</th>
			<th>KODE BARANG</th>
			<th>NAMA BARANG</th>
			<th>S. AWAL</th>
			<th>MASUK</th>
			<th>KELUAR</th>
			<th>S. AKHIR</th>
		</tr>
	</thead>
	<tbody>
	<?php
	while($dataBRG = mysql_fetch_array($hasilBRG)){
		$no++;

		$kodeBarang = $dataBRG['kode_barang_jadi'];
		$stokAwal = $dataBRG['stok_awal'];
		$namaBarang = $dataBRG['nama_barang_jadi'];

		// hitung Jumlah Barang Masuknya
		$hasilJBM = mysql_query("SELECT SUM(jumlah) AS s_masuk FROM tb_jurnal_barang_jadi WHERE kode_barang = '$kodeBarang' AND status = 'Barang Masuk' AND YEAR(tanggal) = '$tahunSekarang'");
		$dataJBM = mysql_fetch_array($hasilJBM);

		// kondisikan bila jumlah masuk kosong
		if($dataJBM['s_masuk'] == NULL){
			$stokMasuk = 0;
		}
		else{
			$stokMasuk = $dataJBM['s_masuk'];
		}

		// hitung Jumlah Barang Keluarnya
		$hasilJBK = mysql_query("SELECT SUM(jumlah) AS s_keluar FROM tb_jurnal_barang_jadi WHERE kode_barang = '$kodeBarang' AND status = 'Barang Keluar' AND YEAR(tanggal) = '$tahunSekarang'");
		$dataJBK = mysql_fetch_array($hasilJBK);

		// kondisikan bila jumlah keluar kosong
		if($dataJBK['s_keluar'] == NULL){
			$stokKeluar = 0;
		}
		else{
			$stokKeluar = $dataJBK['s_keluar'];
		}

		// hitung semua :D
		$stokAkhir = $stokAwal + $stokMasuk - $stokKeluar;

		?>

		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $kodeBarang; ?></td>
			<td><?php echo $namaBarang; ?></td>
			<td><?php echo $stokAwal; ?></td>
			<td><?php echo $stokMasuk; ?></td>
			<td><?php echo $stokKeluar; ?></td>
			<td><?php echo $stokAkhir; ?></td>
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

?>