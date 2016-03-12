<?php
// halaman untuk proses login
require_once("../../config/configuration.php");

$username = mysql_real_escape_string($_POST['postUsername']);
$password_temp = mysql_real_escape_string($_POST['postPassword']);
$password = md5($password_temp);

//echo "username ".$username;
//echo "\npassword ".$password;

$sqllogin = "SELECT COUNT(*) AS cek, level FROM tb_user WHERE username = '$username' AND password = '$password'";
$hasil = mysql_query($sqllogin) or die ("query error !!");
$baris = mysql_fetch_array($hasil) or die("get data error !!");

if($baris['cek'] == "0") {
	echo "gagal";
}

elseif($baris['cek'] != "0"){
	session_start();
	$_SESSION['username'] = $username;
	$_SESSION['level'] = $baris['level'];
	echo $baris['level'];
}

?>