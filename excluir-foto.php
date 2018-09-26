<?php
require_once 'config.php';
if(empty($_SESSION['cLogin'])){
  header("Location: login.php");
  exit;
}

require_once 'classes/anuncio.class.php';
$a = new Anuncio();

if(isset($_GET['id']) && !empty($_GET['id'])){
  $anuncio_id = $a->excluirFoto($_GET['id']);
}

if(isset($anuncio_id)){
  header("Location: editar-anuncio.php?id=".$anuncio_id);
} else {
  header("Location: meus-anuncios.php");
}
?>
