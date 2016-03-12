<?php
session_start();
// cek session halaman produksi

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
elseif($_SESSION['level'] == "sales"){
	?>
	<script> window.location.href = "../sales/"; </script>
	<?php
}

?>