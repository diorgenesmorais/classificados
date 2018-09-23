<?php
class Categoria {
  public function getLista(){
    $lista = array();
    global $pdo;

    $sql = $pdo->query("select * from categorias");
    if($sql->rowCount() > 0){
      $lista = $sql->fetchAll();
    }
    return $lista;
  }
}
?>
