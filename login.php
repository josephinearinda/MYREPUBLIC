<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!="cs"){
    header("Location: pilih_role.php");
    exit;
}
header('Location: home_cs.php');
exit;
?>