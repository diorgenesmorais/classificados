<?php
  require 'pages/header.php';
  require 'classes/anuncio.class.php';
  require 'classes/usuario.class.php';
  $a = new Anuncio();
  $u = new Usuario();

  $total_anuncios = $a->getTotalAnuncios();
  $total_users = $u->getTotalUsuarios();

  // primeira página.
  $page = 1;
  $perPage = 2;
  if(assert($_GET['page']) && !empty($_GET['page'])){
    $page = addslashes($_GET['page']);
  }
  $total_pages = ceil($total_anuncios / $perPage);
  $anuncios = $a->getUltimosAnuncios($page, $perPage);
?>
    <div class="container-fluid">
      <div class="jumbotron">
        <h2>Nós temos hoje <?php echo $total_anuncios; ?> anúncios</h2>
        <p>E mais de <?php echo $total_users; ?> usuários cadastrados</p>
      </div>

      <div class="row">
        <div class="col-sm-3">
          <h4>Pesquisa avançada</h4>
        </div>
        <div class="col-sm-9">
          <h4>Últimos anúncios</h4>
          <table class="table table-striped">
            <tbody>
              <?php foreach($anuncios as $anuncio): ?>
                <tr>
                  <td>
                    <?php if(!empty($anuncio['url'])): ?>
                      <img src="<?php echo FILE_LOCATION.$anuncio['url']; ?>" height="60" alt="Foto anúncio">
                    <?php else: ?>
                      <img src="assets/images/default.png" height="60" alt="Imagem alternativa">
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="produto.php?id=<?php echo $anuncio['id']; ?>"><?php echo $anuncio['titulo']; ?></a><br>
                    <?php echo $anuncio['categoria']; ?>
                  </td>
                  <td>R$ <?php echo number_format($anuncio['valor'], 2); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <ul class="pagination">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
              <li class="<?php echo ($page==$i)?'active':''; ?>"><a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
          </ul>
        </div>
      </div>
    </div>
<?php require 'pages/footer.php'; ?>
