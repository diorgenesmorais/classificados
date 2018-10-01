<?php
  require 'pages/header.php';
  require 'classes/anuncio.class.php';
  require 'classes/usuario.class.php';
  require 'classes/categoria.class.php';
  $a = new Anuncio();
  $u = new Usuario();
  $c = new Categoria();

  $filtros = (assert($_GET['f'])) ? $_GET['f'] : array(categoria=>'',preco=>'',estado=>'');

  $total_anuncios = $a->getTotalAnuncios($filtros);
  $total_users = $u->getTotalUsuarios();
  $categorias = $c->getLista();

  // primeira página.
  $page = 1;
  $perPage = 2;
  if(assert($_GET['p']) && !empty($_GET['p'])){
    $page = addslashes($_GET['p']);
  }
  $total_pages = ceil($total_anuncios / $perPage);
  $anuncios = $a->getUltimosAnuncios($page, $perPage, $filtros);
?>
    <div class="container-fluid">
      <div class="jumbotron">
        <h2>Nós temos hoje <?php echo $total_anuncios; ?> anúncios</h2>
        <p>E mais de <?php echo $total_users; ?> usuários cadastrados</p>
      </div>

      <div class="row">
        <div class="col-sm-3">
          <h4>Pesquisa avançada</h4>
          <form method="get">
            <div class="form-group">
              <label for="categoria">Categoria:</label>
              <select class="form-control" name="f[categoria]" id="categoria">
                <option value=""></option>
                <?php foreach($categorias as $cat): ?>
                  <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id']==$filtros['categoria'])?'selected':''; ?>><?php echo $cat['nome']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="preco">Preço:</label>
              <select class="form-control" name="f[preco]" id="preco">
                <option value=""></option>
                <option value="0-50" <?php echo ($filtros['preco']=='0-50')?'selected':''; ?>>R$ 0,00 - 50,00</option>
                <option value="51-100" <?php echo ($filtros['preco']=='51-100')?'selected':''; ?>>R$ 51,00 - 100,00</option>
                <option value="101-200" <?php echo ($filtros['preco']=='101-200')?'selected':''; ?>>R$ 101,00 - 200,00</option>
                <option value="201-500" <?php echo ($filtros['preco']=='201-500')?'selected':''; ?>>R$ 201,00 - 500,00</option>
              </select>
            </div>
            <div class="form-group">
              <label for="estado">Estado de conservação:</label>
              <select class="form-control" name="f[estado]" id="estado">
                <option value=""></option>
                <option value="0" <?php echo ($filtros['estado']=='0')?'selected':''; ?>>Ruim</option>
                <option value="1" <?php echo ($filtros['estado']=='1')?'selected':''; ?>>Bom</option>
                <option value="2" <?php echo ($filtros['estado']=='2')?'selected':''; ?>>Ótimo</option>
              </select>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-info">Buscar</button>
            </div>
          </form>
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
              <li class="<?php echo ($page==$i)?'active':''; ?>"><a href="index.php?<?php
              $w = $_GET;
              $w['p'] = $i;
              echo http_build_query($w);
              ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
          </ul>
        </div>
      </div>
    </div>
<?php require 'pages/footer.php'; ?>
