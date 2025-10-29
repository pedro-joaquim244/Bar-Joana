<!-- /public/login.php (DEMO ESTÁTICA) -->
<?php
require_once __DIR__ . '/../app/config/conexao.php';
require_once __DIR__ . '/../app/config/auth.php';

$paginaAtual = 'login';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="./assets/css/reset.css">
  <link rel="stylesheet" href="./assets/css/login.css">
  <link rel="stylesheet" href="./assets/css/components/header.css">
  <link rel="stylesheet" href="./assets/css/components/footer.css">
  <title>Login - Fast Food</title>
</head>

<body>
  <?php include "../app/components/header.php"; ?>

  <main>
    <h1>Login</h1>

    <div>
      <form method="POST">
        <label for="email">Email:</label>
        <input id="email" type="email" name="email" placeholder="seuemail@exemplo.com" required>

        <label for="senha">Senha:</label>
        <input id="senha" type="password" name="senha" placeholder="••••••••" required>

        <button type="submit">Entrar</button>
      </form>
    </div>

    <p>Não tem uma conta? <a href="criar-conta.php">Cadastre-se aqui</a>.</p>
  </main>

  <?php include "../app/components/footer.php"; ?>
</body>

</html>