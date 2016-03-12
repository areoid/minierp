<?php
session_start();
// cek session halaman login

if(empty($_SESSION)){
	return true;
}
elseif($_SESSION['level'] == "gudang"){
	?>
	<script> window.location.href = "gudang/"; </script>
	<?php
}
elseif($_SESSION['level'] == "produksi"){
	?>
	<script> window.location.href = "produksi/"; </script>
	<?php
}
elseif($_SESSION['level'] == "sales"){
	?>
	<script> window.location.href = "sales/"; </script>
	<?php
}

?>