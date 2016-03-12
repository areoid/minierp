<?php
session_start();
// cek session halaman gudang

if(empty($_SESSION['level'])){
	?>
	<script> window.location.href="../index.php"; </script>
	<?php
}
elseif($_SESSION['level'] == "produksi"){
	?>
	<script> window.location.href = "../produksi/"; </script>
	<?php
}
elseif($_SESSION['level'] == "sales"){
	?>
	<script> window.location.href = "../sales/"; </script>
	<?php
}

?>