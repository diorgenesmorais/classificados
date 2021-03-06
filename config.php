<?php
session_start();

// see README about constants
require_once 'vendor/access.php';

global $pdo;

try {
  $pdo = new PDO($dsn, $dbuser, $dbpass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec("SET NAMES 'utf8'");
} catch(PDOException $e){
  echo "Falhou: ".$e->getMessage();
}
?>
