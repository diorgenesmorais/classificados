<?php
session_start();

require_once 'vendor/access.php';

try {
  $pdo = new PDO($dsn, $dbuser, $dbpass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec("SET NAMES 'utf8'");
} catch(PDOException $e){
  echo "Falhou: ".$e->getMessage();
}
?>
