<!-- /public/perfil.php -->
<?php
require_once __DIR__ . '/../app/config/conexao.php';
require_once __DIR__ . '/../app/config/auth.php';

if (!estaLogado()) {
  header('Location: /index.php');
  exit;
}

$id = (int) $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $bairro = trim($_POST['bairro'] ?? '');
  $logradouro = trim($_POST['logradouro'] ?? '');
  $complemento = trim($_POST['complemento'] ?? '');

  if ($nome && $email && $bairro && $logradouro ) {
    $stmt = $conn->prepare("UPDATE usuarios SET nome=?, email=?, bairro=?, logradouro=?, complemento=? WHERE id=?");
    $stmt->bind_param("sssssi", $nome, $email, $bairro, $logradouro, $complemento, $id);
    if ($stmt->execute()) {
      $_SESSION['usuario']['nome'] = $nome;
      $_SESSION['usuario']['email'] = $email;
      header('Location: index.php');
      exit;
    }
    $erro = 'Erro ao atualizar.';
  } else {
    $erro = 'Preencha os campos obrigatórios.';
  }
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Perfil - Fast Food</title>
  <link rel="stylesheet" href="./assets/css/reset.css">
  <link rel="stylesheet" href="./assets/css/perfil.css">
  <link rel="stylesheet" href="./assets/css/components/header.css">
  <link rel="stylesheet" href="./assets/css/components/footer.css">
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
</head>

<body>
  <?php include "../app/components/header.php"; ?>

  <div class="titulo">
    <h1>Perfil</h1>
  </div>

  <section>
    <div class="perfil-wrapper">
      <div class="perfil-sidebar">
        <div class="avatar">
          <?php
          $iniciais = strtoupper(substr($usuario['nome'], 0, 1) . substr(strrchr($usuario['nome'], ' '), 1, 1));
          echo htmlspecialchars($iniciais);
          ?>
        </div>
        <h2>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h2>
      </div>

      <div class="perfil-content">
        <h1>Informações Pessoais</h1>

        <?php if (!empty($erro)): ?>
          <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
          <div class="grupo">

            <div class="campo">
              <label for="nome">Nome</label>
              <input id="nome" type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
  
            </div>
  
            <div class="campo">
              <label for="email">Email</label>
              <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>"
                required>
  
            </div>
          </div>

          <div class="grupo">

            <div class="campo">
              <label for="bairro">Bairro</label>
              <input id="bairro" type="text" name="bairro"
                value="<?php echo htmlspecialchars($usuario['bairro'] ?? ''); ?>" required>
  
            </div>
  
            <div class="campo">
              <label for="logradouro">Logradouro</label>
              <input id="logradouro" type="text" name="logradouro"
                value="<?php echo htmlspecialchars($usuario['logradouro'] ?? ''); ?>" required>
  
            </div>
          </div>

          <div class="grupo">

            <div class="campo">
              <label for="complemento">Complemento</label>
              <input id="complemento" type="text" name="complemento"
                value="<?php echo htmlspecialchars($usuario['complemento'] ?? ''); ?>">
  
            </div>
          </div>

          <button type="submit">Atualizar</button>
        </form>
      </div>
    </div>
  </section>

  <?php include "../app/components/footer.php"; ?>
</body>

</html>