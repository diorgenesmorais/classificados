<?php
  require 'pages/header.php';
  if(empty($_SESSION['cLogin'])){
    ?>
    <script type="text/javascript">
      window.location.href="login.php";
    </script>
    <?php
    exit;
  }
?>
  <div class="container">
    <h1>Meus anúncios</h1>
    <a class="btn btn-default" href="add-anuncio.php">Adicionar anúncio</a>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Foto</th>
          <th>Título</th>
          <th>Valor</th>
          <th>Ações</th>
        </tr>
        <?php
          require_once 'classes/anuncio.class.php';
          $a = new Anuncio();
          $anuncios = $a->getMeusAnuncios();
          foreach ($anuncios as $anuncio):
          ?>
            <tr>
              <td><img src="assets/images/anuncios/<?php echo $anuncio['url']; ?>" alt="Foto anúncio"></td>
              <td><?php echo $anuncio['titulo']; ?></td>
              <td>R$ <?php echo number_format($anuncio['valor'], 2); ?></td>
              <td></td>
            </tr>
          <?php
          endforeach;
        ?>
      </thead>
    </table>
  </div>
<?php require 'pages/footer.php'; ?>
