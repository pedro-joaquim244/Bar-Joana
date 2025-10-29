<!-- /public/perfil.php (DEMO ESTÁTICA) -->
<?php
require_once __DIR__ . '/../app/config/conexao.php';
require_once __DIR__ . '/../app/config/auth.php';

$paginaAtual = 'perfil';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Perfil - Fast Food (Estático)</title>
  <link rel="stylesheet" href="./assets/css/reset.css">
  <link rel="stylesheet" href="./assets/css/perfil.css">
  <link rel="stylesheet" href="./assets/components/css/header.css">
  <link rel="stylesheet" href="./assets/components/css/footer.css">
</head>

<body>
  <?php include "../app/components/header.php"; ?>

  <h1>Perfil</h1>

  <section>
    <form method="POST">
      <label for="nome">Nome</label>
      <input id="nome" type="text" name="nome" value="Matheus Nobrega" required>

      <label for="email">Email</label>
      <input id="email" type="email" name="email" value="matheus@gmail.com" required>

      <label for="bairro">Bairro</label>
      <input id="bairro" type="text" name="bairro" value="Centro" required>

      <label for="logradouro">Logradouro</label>
      <input id="logradouro" type="text" name="logradouro" value="Rua dos Deuses" required>

      <label for="numero">Número</label>
      <input id="numero" type="text" name="numero" value="100" required>

      <label for="complemento">Complemento</label>
      <input id="complemento" type="text" name="complemento" value="Apto, bloco, casa...">

      <button type="submit">Atualizar</button>
    </form>
  </section>

  <?php include "../app/components/footer.php"; ?>
</body>

</html>