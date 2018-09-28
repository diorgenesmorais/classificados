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
    $sql = $pdo->prepare("select * from anuncios_all where id = :id");
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

  public function getTotalAnuncios(){
    global $pdo;

    $sql = $pdo->query("select count(*) as total from anuncios");
    $row = $sql->fetch();

    return $row['total'];
  }

  /**
   * Obter últimos anúncios
   *
   * @param integer $page obter a partir de qual página
   * @param integer $perPage limita a quantidade de páginas.
   * @return array com os últimos anúncios.
   */
  public function getUltimosAnuncios($page, $perPage){
    global $pdo;
    $dados = array();
    $offset = ($page - 1) * $perPage;

    $sql = $pdo->prepare("select id, titulo, valor, (select anuncio_images.url from anuncio_images where anuncio_images.anuncio_id = anuncios.id limit 1) as url,
    (select nome from categorias where categorias.id = anuncios.categoria_id) as categoria from anuncios order by id desc limit $offset, $perPage");
    if($sql->execute() && $sql->rowCount() > 0){
      $dados = $sql->fetchAll();
    }
    return $dados;
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

          // obter a largura e altura da imagem na pasta
          list($width_orig, $height_orig) = getimagesize($imageInFile);

          // alterar as medidas proporcionalmente limitando ao MAX_SIZE
          $width = MAX_SIZE;
          $height = MAX_SIZE;
          if($width_orig > $height_orig){
            $ratio = $width_orig / MAX_SIZE;
            $height = $height_orig / $ratio;
          } else {
            $ratio = $height_orig / MAX_SIZE;
            $width = $width_orig /$ratio;
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
