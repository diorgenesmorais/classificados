<?php
require_once 'config.php';
if(empty($_SESSION['cLogin'])){
  header("Location: login.php");
  exit;
}

require_once 'classes/anuncio.class.php';
$a = new Anuncio();

if(isset($_GET['id']) && !empty($_GET['id'])){
  $a->excluirAnuncio($_GET['id']);
}

header("Location: meus-anuncios.php");
?>
