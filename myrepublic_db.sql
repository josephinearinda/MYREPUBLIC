<?php
session_start();
session_destroy();
header("Location: pilih_role.php");
?>