<?php require_once 'pages/header.php'; ?>
<div class="container">
  <h1>Login</h1>
  <?php
    require 'classes/usuario.class.php';
    $u = new Usuario();
    if(isset($_POST['email']) && !empty($_POST['email'])){
      $email = addslashes($_POST['email']);
      $senha = $_POST['senha'];

      if($u->login($email, $senha)){
        ?>
          <script type="text/javascript">
            window.location.href="./";
          </script>
        <?php
      } else {
        ?>
          <div class="alert alert-danger">
            <strong>Usuário e/ou senha inválida!</strong>
          </div>
        <?php
      }
    }
  ?>
  <form method="post">
    <div class="form-group">
      <label for="email">E-mail:</label>
      <input type="email" name="email" id="email" class="form-control">
    </div>
    <div class="form-group">
      <label for="senha">Senha:</label>
      <input type="password" name="senha" id="senha" class="form-control">
    </div>
    <button class="btn btn-default" type="submit" name="button">Login</button>
  </form>
</div>
<?php require_once 'pages/footer.php'; ?>
