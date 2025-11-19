<!-- /public/criar-conta.php (DEMO ESTÁTICA) -->
<?php
require_once __DIR__ . '/../app/config/conexao.php';
require_once __DIR__ . '/../app/config/auth.php';

$paginaAtual = "criar-conta";
$erro = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $nome = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $senha = $_POST['senha'];
  $bairro = trim($_POST['bairro'] ?? '');
  $logradouro = trim($_POST['logradouro'] ?? '');
  $complemento = trim($_POST['complemento'] ?? '');

  if (
    $nome === '' || $email === '' || $senha === '' || $bairro === '' ||
    $logradouro === '' || $complemento === ''
  ) {
    $erro = "Preencha todos os campos.";
  } else {

    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
    $funcao = "cliente";

    $sql = "INSERT INTO usuarios (nome, email, senha, bairro, logradouro, complemento, funcao)
                VALUES (?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nome, $email, $senha_hash, $bairro, $logradouro, $complemento, $funcao);

    if ($stmt->execute()) {
      header("Location: /index.php");
      exit;
    } else {
      if ($conn->errno === 1062) {
        $erro = "Este email já está em uso.";
      } else {
        $erro = "Erro ao cadastrar.";
      }
    }
  }
}


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/reset.css">
  <link rel="stylesheet" href="./assets/css/criar-conta.css">
  <link rel="stylesheet" href="./assets/css/header.css">
  <link rel="stylesheet" href="./assets/css/footer.css">
  <title>Criar conta - Fast Food</title>
</head>

<body>

  <div class="container-foto">
    <img src="./assets/imgs/telaloginfundo.png" alt="">
    <div class="container-escrita">


      <form method="POST">
        <h1>Criar conta</h1>
        <label>Email</label>
        <input type="email" name="email" placeholder="seuemail@exemplo.com" required>

        <label>Nome Completo</label>
        <input type="text" name="nome" placeholder="Seu nome completo" required>

        <label>Endereço</label>
        <input type="text" name="logradouro" placeholder="Rua, avenida..." required>

        <label>Bairro</label>
        <input type="text" name="bairro" placeholder="Ex.: Centro" required>

        <label>Complemento</label>
        <input type="text" name="complemento" placeholder="Apto, bloco, numero...">



        <label>Senha</label>
        <input type="password" name="senha" placeholder="Crie uma senha" required>

        <div class="botoes">

          <button type="submit">Cadastrar</button>

          <?php if ($erro): ?>
            <p><? $erro; ?></p>
          <?php endif; ?>


          <a href="login.php" class="btn">Fazer Login</a>
          <a href="index.php" class="btn">Página principal</a>
        </div>
      </form>
    </div>
  </div>

</body>

</html>