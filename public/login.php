<!-- /public/login.php (DEMO ESTÁTICA) -->
<?php
require_once __DIR__ . '/../app/config/conexao.php';
require_once __DIR__ . '/../app/config/auth.php';

$paginaAtual = 'login';

$erro = null;
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $email = trim($_POST['email'] ?? '');
  $senha = trim($_POST['senha'] ?? '');
  if (login($conn, $email, $senha)) {
    header("Location: /index.php");
    exit;
  } else {
    $erro = "Email ou senha invalidos";
  }
}
''
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
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
  <title>Login - Fast Food</title>
</head>

<body>



  <div class="container-foto">
    <div class="container-escrita">


      <main>
        <div class="pai">
          <form method="POST">
            <h1>Login</h1>
            <div class="linha"></div>


            <label for="email">Email:</label>
            <input id="email" type="email" name="email" placeholder="seuemail@exemplo.com" required>

            <label for="senha">Senha:</label>
            <input id="senha" type="password" name="senha" placeholder="••••••••" required>

            <div class="botoes">
              <button type="submit">Entrar</button>
            </div>

            <p>Não tem uma conta? <a id="idDois" href="criar-conta.php">Cadastre-se aqui</a>.</p>


            <?php if (!empty($erro)): ?>
              <p class="erro-login"><?= $erro ?></p>
            <?php endif; ?>
          </form>















        </div>






      </main>
    </div>
  </div>

</body>

</html>