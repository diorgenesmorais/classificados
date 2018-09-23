<?php
class Anuncio {
  public function getMeusAnuncios(){
    global $pdo;
    $dados = array();
    $sql = $pdo->prepare("select *, (select anuncio_images.url from anuncio_images where anuncio_images.anuncio_id = anuncios.id) as url from anuncios where usuario_id = :id");
    $sql->bindValue(":id", $_SESSION['cLogin']['id']);
    $sql->execute();

    if($sql->rowCount() > 0){
      $dados = $sql->fetchAll();
    }
    return $dados;
  }
}
?>
