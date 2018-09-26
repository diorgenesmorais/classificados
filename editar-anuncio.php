<?php
  require 'pages/header.php';
  require 'classes/anuncio.class.php';
  if(empty($_SESSION['cLogin'])){
    ?>
    <script type="text/javascript">
      window.location.href="login.php";
    </script>
    <?php
    exit;
  }
  $get_id = $_GET['id'];

  $a = new Anuncio();
  if(isset($_POST['titulo']) && !empty($_POST['titulo'])){
    $titulo = addslashes($_POST['titulo']);
    $cat = addslashes($_POST['categoria']);
    $valor = addslashes($_POST['valor']);
    $descricao = addslashes($_POST['descricao']);
    $estado = addslashes($_POST['estado']);
    if(isset($_FILES['fotos'])){
      $fotos = $_FILES['fotos'];
    } else {
      $fotos = array();
    }

    $a->editarAnuncio($titulo, $cat, $valor, $descricao, $estado, $fotos, $get_id);
    ?>
      <div class="alert alert-success">
        Anúncio editado com sucesso!
      </div>
    <?php
  }

  if(isset($get_id) && !empty($get_id)){
    $info = $a->getAnuncio($get_id);
  } else {
    ?>
    <script type="text/javascript">
      window.location.href="meus-anuncios.php";
    </script>
    <?php
    exit;
  }
?>
  <div class="container">
    <h1>Meus anúncios - editar anúncio</h1>

    <form method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="categoria">Categoria:</label>
        <select class="form-control" name="categoria" id="categoria">
          <?php
            require 'classes/categoria.class.php';
            $c = new Categoria();
            $categorias = $c->getLista();
            foreach ($categorias as $categoria):
              ?>
                <option value="<?php echo $categoria['id']; ?>" <?php echo ($info['categoria_id']==$categoria['id'])?'selected':''; ?>><?php echo $categoria['nome']; ?></option>
              <?php
            endforeach;
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo $info['titulo']; ?>">
      </div>
      <div class="form-group">
        <label for="valor">Valor:</label>
        <input type="text" name="valor" id="valor" class="form-control" value="<?php echo $info['valor']; ?>">
      </div>
      <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" rows="4" cols="80" class="form-control"><?php echo $info['descricao']; ?></textarea>
      </div>
      <div class="form-group">
        <label for="estado">Estado de conservação:</label>
        <select class="form-control" name="estado">
          <option value="0" <?php echo ($info[estado]==0)?'selected':''; ?>>Ruim</option>
          <option value="1" <?php echo ($info[estado]==1)?'selected':''; ?>>Bom</option>
          <option value="2" <?php echo ($info[estado]==2)?'selected':''; ?>>Ótimo</option>
        </select>
      </div>
      <div class="form-group">
        <label for="addFotos">Fotos do anúncio</label>
        <input type="file" name="fotos[]" multiple id="addFotos"><br>

        <div class="panel panel-default">
          <div class="panel-heading">
            Fotos do anúncio
          </div>
          <div class="panel-body">
            <?php foreach($info['fotos'] as $foto): ?>
              <div class="foto-item">
                <img src="<?php echo FILE_LOCATION.$foto['url']; ?>" class="img-thumbnail" border="0"><br>
                <a href="excluir-foto.php?id=<?php echo $foto['id']; ?>" class="btn btn-default">Excluir imagem</a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <button class="btn btn-default" type="submit" name="button">Salvar</button>
    </form>
  </div>
<?php require 'pages/footer.php'; ?>
