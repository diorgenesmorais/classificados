<?php
class Anuncio {

  /**
   * Obter meus anúncios
   *
   * @param array com todos os anúncios.
   */
  public function getMeusAnuncios(){
    global $pdo;
    $dados = array();
    $sql = $pdo->prepare("select *, (select anuncio_images.url from anuncio_images where anuncio_images.anuncio_id = anuncios.id limit 1) as url from anuncios where usuario_id = :id");
    $sql->bindValue(":id", $_SESSION['cLogin']['id']);

    if($sql->execute() && $sql->rowCount() > 0){
      $dados = $sql->fetchAll();
    }
    return $dados;
  }

  // TODO: fazer documentação
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

  /**
   * Excluir um anúncio
   *
   * @param integer $id primary key do anúncio.
   */
  public function excluirAnuncio($id){
    global $pdo;

    $sql = $pdo->prepare("delete from anuncio_images where anuncio_id = :anuncio_id");
    $sql->bindValue(":anuncio_id", $id);
    if($sql->execute()){
      $sql = $pdo->prepare("delete from anuncios where id = :id");
      $sql->bindValue(":id", $id);
      $sql->execute();
    }
  }

  /**
   * Obtém um anúncio
   *
   * @param integer $id primary key do anúncio
   *
   * @return array com o anúncio e suas fotos
   */
  public function getAnuncio($id){
    global $pdo;
    $dados = array();
    $sql = $pdo->prepare("select * from anuncios where id = :id");
    $sql->bindValue(":id", $id);

    if($sql->execute() && $sql->rowCount() > 0){
      $dados = $sql->fetch();
      $dados['fotos'] = array();

      $sql = $pdo->prepare("select id, url from anuncio_images where anuncio_id = :id");
      $sql->bindValue(":id", $id);

      if($sql->execute() && $sql->rowCount() > 0){
        $dados['fotos'] = $sql->fetchAll();
      }
    }
    return $dados;
  }

  // TODO: fazer documentação
  public function editarAnuncio($titulo, $cat, $valor, $descricao, $estado, $fotos, $id){
    global $pdo;

    $sql = $pdo->prepare("update anuncios set titulo = :titulo, categoria_id = :cat_id, descricao = :descricao, valor = :valor, estado = :estado where id = :id");
    $sql->bindValue(":titulo", $titulo);
    $sql->bindValue(":cat_id", $cat);
    $sql->bindValue(":descricao", $descricao);
    $sql->bindValue(":valor", $valor);
    $sql->bindValue(":estado", $estado);
    $sql->bindValue(":id", intVal($id));

    if($sql->execute()){
      $this->saveImageToFolder($fotos, $id);
    }
  }

  /**
   * Excluir foto
   *
   * @param integer $id primary key da foto
   */
  public function excluirFoto($id){
    global $pdo;
    $anuncio_id = 0;
    $sql = $pdo->prepare("select anuncio_id, url from anuncio_images where id = :id");
    $sql->bindValue(":id", $id);

    if($sql->execute() && $sql->rowCount() > 0){
      $row = $sql->fetch();
      $anuncio_id = $row['anuncio_id'];
      $url = $row['url'];

      // exclui o arquivo na pasta, depois o link no banco
      if(unlink(FILE_LOCATION.$url)){
        $sql = $pdo->prepare("delete from anuncio_images where id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
      }
    }
    return $anuncio_id;
  }

  /**
   * Salvar imagem na pasta
   *
   * @param array $fotos uma array contendo uma ou mais Fotos
   * @param integer $id primary key
   */
  private function saveImageToFolder($fotos, $id){
    if(count($fotos) > 0){
      for ($i=0; $i < count($fotos['tmp_name']); $i++) {
        $tipo = $fotos['type'][$i];
        if(in_array($tipo, array('image/jpeg','image/png'))){
          $filename = md5(time().rand(0,99)).$fotos['name'][$i];
          $imageInFile = FILE_LOCATION.$filename;
          move_uploaded_file($fotos['tmp_name'][$i], $imageInFile);

          list($width_orig, $height_orig) = getimagesize($imageInFile);
          $ratio = $width_orig/$height_orig;

          $width = WIDTH_MAX;
          $height = HEIGHT_MAX;

          // TODO: melhorar, limitando tanto a largura quanto a altura (fazer nas duas medidas)
          if($width/$height > $ratio){
            $width = $height*$ratio;
          } else {
            $height = $width*$ratio;
          }

          $img = imagecreatetruecolor($width, $height);

          if($this->isJpegImage($tipo)) {
            $orig = imagecreatefromjpeg($imageInFile);
          }

          if($this->isPngImage($tipo)) {
            $orig = imagecreatefrompng($imageInFile);
          }

          imagecopyresampled($img, $orig, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

          if($this->isJpegImage($tipo)){
             imagejpeg($img, $imageInFile, 80);
          }

          if($this->isPngImage($tipo)) {
             imagepng($img, $imageInFile, 0);
          }

          $this->savePathDatabase($filename, $id);

        }
      }
    }
  }

  /**
   * @param string 'image/jpeg'
   *
   * @return true se for um tipo JPEG
   */
  private function isJpegImage($tipo){
    return $tipo == 'image/jpeg';
  }

  /**
   * @param string 'image/png'
   *
   * @return true se for um tipo PNG
   */
  private function isPngImage($tipo){
    return $tipo == 'image/png';
  }

  /**
   * Salva numa tabela (banco) o nome do arquivo
   *
   * @param string $filename o nome do arquivo
   * @param integer $id número da primary key
   * @return true se salvou o $filename
   */
  private function savePathDatabase($filename, $id){
    global $pdo;

    $sql = $pdo->prepare("insert into anuncio_images set anuncio_id = :id, url = :url");
    $sql->bindValue(":id", $id);
    $sql->bindValue(":url", $filename);
    return $sql->execute();
  }
}
?>
