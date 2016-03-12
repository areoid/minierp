<?php
// proses Stok Barang Mentah Gudang
require_once("../../config/configuration.php");

$action = $_GET['action'];

if($action == "listStokBMG"){
?>
<script type="text/javascript">
$(document).ready(function(){
	$("#tabelListStokBMG").dataTable();
});
</script>
<?php
	$tahunSekarang = date('Y');
	$no = 0;
	$hasilBRG = mysql_query("SELECT * FROM tb_barang WHERE tahun_produksi = '$tahunSekarang'");
	?>
<h3 align="center">Stok Barang Mentah (Gudang)</h3>
<div class="thumbnail">
	<div class="caption">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabelListStokBMG">
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

		$kodeBarang = $dataBRG['kode_barang'];
		$stokAwal = $dataBRG['stok_awal'];
		$namaBarang = $dataBRG['nama_barang'];

		// hitung Jumlah Barang Masuknya
		$hasilJBM = mysql_query("SELECT SUM(jumlah) AS s_masuk FROM tb_jurnal_transaksi WHERE kode_barang = '$kodeBarang' AND status = 'Barang Masuk' AND YEAR(tanggal) = '$tahunSekarang'");
		$dataJBM = mysql_fetch_array($hasilJBM);

		// kondisikan bila jumlah masuk kosong
		if($dataJBM['s_masuk'] == NULL){
			$stokMasuk = 0;
		}
		else{
			$stokMasuk = $dataJBM['s_masuk'];
		}

		// hitung Jumlah Barang Keluarnya
		$hasilJBK = mysql_query("SELECT SUM(jumlah) AS s_keluar FROM tb_jurnal_transaksi WHERE kode_barang = '$kodeBarang' AND status = 'Barang Keluar' AND YEAR(tanggal) = '$tahunSekarang'");
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