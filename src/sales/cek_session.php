<?php
session_start();
// cek session halaman sales

if(empty($_SESSION['level'])){
	?>
	<script> window.location.href="../index.php"; </script>
	<?php
}
elseif($_SESSION['level'] == "gudang"){
	?>
	<script> window.location.href = "../gudang/"; </script>
	<?php
}
elseif($_SESSION['level'] == "produksi"){
	?>
	<script> window.location.href = "../produksi/"; </script>
	<?php
}

?>