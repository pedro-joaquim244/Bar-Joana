<?php
session_start();


$funcao = $_SESSION['funcao'] ?? null;

if($funcao === "admin"){
 header( "Location: /admin/index.php"); 
 exit;
}

if ($funcao === "cliente"){
 header( "Location: /usuario/index.php");
  exit;
}

header( "Location: /usuario/index.php");
  exit;


?>
