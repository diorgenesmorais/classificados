<?php
  require 'pages/header.php';
  require 'classes/anuncio.class.php';
  require 'classes/usuario.class.php';
  $a = new Anuncio();
  $u = new Usuario();

  if(assert($_GET['id']) && !empty($_GET['id'])){
    $id = addslashes($_GET['id']);
  } else {
    ?>
    <script type="text/javascript">
      window.location.href="index.php";
    </script>
    <?php
    exit;
  }

  $info = $a->getAnuncio($id);
?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-5">
        <?php require_once 'carousel.php'; ?>
      </div>
      <div class="7">
        <h1><?php echo $info['titulo']; ?></h1>
        <h4><?php echo $info['categoria']; ?></h4>
        <p><?php echo $info['descricao']; ?></p>
        <br>
        <h3>R$ <?php echo number_format($info['valor'], 2); ?></h3>
        <h4>Telefone: <?php echo $info['telefone']; ?></h4>
      </div>
    </div>
  </div>
<?php require 'pages/footer.php'; ?>
