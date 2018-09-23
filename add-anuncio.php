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

  $a = new Anuncio();
  if(isset($_POST['titulo']) && !empty($_POST['titulo'])){
    $titulo = addslashes($_POST['titulo']);
    $cat = addslashes($_POST['categoria']);
    $valor = addslashes($_POST['valor']);
    $descricao = addslashes($_POST['descricao']);
    $estado = addslashes($_POST['estado']);

    $a->addAnuncio($titulo, $cat, $valor, $descricao, $estado);
    ?>
      <div class="alert alert-success">
        Anúncio adicionado com sucesso!
      </div>
    <?php
  }
?>
  <div class="container">
    <h1>Meus anúncios - adicionar anúncio</h1>

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
                <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nome']; ?></option>
              <?php
            endforeach;
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" class="form-control">
      </div>
      <div class="form-group">
        <label for="valor">Valor:</label>
        <input type="text" name="valor" id="valor" class="form-control">
      </div>
      <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" rows="4" cols="80" class="form-control"></textarea>
      </div>
      <div class="form-group">
        <label for="estado">Estado de conservação:</label>
        <select class="form-control" name="estado">
          <option value="0">Ruim</option>
          <option value="1">Bom</option>
          <option value="2">Ótimo</option>
        </select>
      </div>
      <button class="btn btn-default" type="submit" name="button">Cadastrar</button>
    </form>
  </div>
<?php require 'pages/footer.php'; ?>
