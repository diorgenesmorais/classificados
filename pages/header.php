<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Classificados</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a href="./" class="navbar-brand">Classificados</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
          <?php if(isset($_SESSION['cLogin']) && !empty($_SESSION['cLogin'])): ?>
            <li><a href="#"><?php echo $_SESSION['cLogin']['nome']; ?></a></li>
            <li><a href="meus-anuncios.php">Meus anúncios</a></li>
            <li><a href="logout.php">Sair</a></li>
          <?php else: ?>
            <li><a href="cadastre-se.php">Cadastre-se</a></li>
            <li><a href="login.php">Login</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
