<?php
class Usuario {
  public function cadastrar($nome, $email, $senha, $telefone){
    global $pdo;

    $sql = $pdo->prepare("select id from usuarios where email=:email");
    $sql->bindValue(":email", $email);
    $sql->execute();

    if($sql->rowCount() == 0){
      $sql = $pdo->prepare("insert into usuarios set nome = :nome, email = :email, senha = :senha, telefone = :telefone");
      $sql->bindValue(":nome", $nome);
      $sql->bindValue(":email", $email);
      $sql->bindValue(":senha", md5($senha));
      $sql->bindValue(":telefone", $telefone);
      $sql->execute();

      return true;
    } else {
      return false;
    }
  }

  public function login($email, $senha){
    global $pdo;

    $sql = $pdo->prepare("select id, nome from usuarios where email = :email and senha = :senha");
    $sql->bindValue(":email", $email);
    $sql->bindValue(":senha", md5($senha));
    $sql->execute();

    if($sql->rowCount() > 0){
      $dado = $sql->fetch();
      $_SESSION['cLogin'] = array("id" => $dado['id'], "nome" => $dado['nome']);
      return true;
    }
    return false;
  }
}
?>
