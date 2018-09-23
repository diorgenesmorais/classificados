<?php
class Anuncio {

  public function getMeusAnuncios(){
    global $pdo;
    $dados = array();
    $sql = $pdo->prepare("select *, (select anuncio_images.url from anuncio_images where anuncio_images.anuncio_id = anuncios.id limit 1) as url from anuncios where usuario_id = :id");
    $sql->bindValue(":id", $_SESSION['cLogin']['id']);
    $sql->execute();

    if($sql->rowCount() > 0){
      $dados = $sql->fetchAll();
    }
    return $dados;
  }

  public function addAnuncio($titulo, $cat, $valor, $descricao, $estado){
    global $pdo;

    $sql = $pdo->prepare("insert into anuncios set titulo = :titulo, categoria_id = :cat_id, usuario_id = :usuario_id, descricao = :descricao, valor = :valor, estado = :estado");
    $sql->bindValue(":titulo", $titulo);
    $sql->bindValue(":cat_id", $cat);
    $sql->bindValue(":usuario_id", $_SESSION['cLogin']['id']);
    $sql->bindValue(":descricao", $descricao);
    $sql->bindValue(":valor", $valor);
    $sql->bindValue(":estado", $estado);
    $sql->execute();
  }
}
?>