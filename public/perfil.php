<?php
require_once __DIR__ . '/../app/config/conexao.php';
require_once __DIR__ . '/../app/config/auth.php';

$paginaAtual = "perfil";

if (!estaLogado()) {
  header('Location: /index.php');
  exit;
}

$id = (int) $_SESSION['usuario_id'];

/* =======================
   ATUALIZAÇÃO DE PERFIL
=========================*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $bairro = trim($_POST['bairro'] ?? '');
  $logradouro = trim($_POST['logradouro'] ?? '');
  $complemento = trim($_POST['complemento'] ?? '');

  if ($nome && $email && $bairro && $logradouro) {
    $stmt = $conn->prepare("UPDATE usuarios 
                            SET nome=?, email=?, bairro=?, logradouro=?, complemento=? 
                            WHERE id=?");
    $stmt->bind_param("sssssi", $nome, $email, $bairro, $logradouro, $complemento, $id);

    if ($stmt->execute()) {
      $_SESSION['usuario']['nome'] = $nome;
      $_SESSION['usuario']['email'] = $email;
      header('Location: perfil.php');
      exit;
    }
    $erro = 'Erro ao atualizar.';
  } else {
    $erro = 'Preencha os campos obrigatórios.';
  }
}

/* =======================
   BUSCA DADOS DO USUÁRIO
=========================*/
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

/* =======================
   BUSCA PEDIDOS DO USUÁRIO
=========================*/
$sql = "SELECT *
        FROM pedidos
        WHERE usuario_id = ?
        ORDER BY criado_em DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pedidos = ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Perfil - Fast Food</title>
  <link rel="stylesheet" href="./assets/css/reset.css">
  <link rel="stylesheet" href="./assets/css/perfil.css">
  <link rel="stylesheet" href="./assets/css/compras.css">
  <link rel="stylesheet" href="./assets/css/components/header.css">
  <link rel="stylesheet" href="./assets/css/components/footer.css">
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
</head>

<body>
  <?php include "../app/components/header.php"; ?>

  <h1 id="titulo" class="titulo">Perfil</h1>
  <div class="Linha"></div>

  <section>
    <div class="perfil-wrapper">

      <!-- ========= SIDEBAR ========= -->
      <div class="perfil-sidebar">
        <div class="avatar">
          <?php
          $iniciais = strtoupper(
            substr($usuario['nome'], 0, 1) .
              substr(strrchr($usuario['nome'], ' '), 1, 1)
          );
          echo htmlspecialchars($iniciais);
          ?>
        </div>
        <h2>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h2>
      </div>

      <!-- ========= INFORMAÇÕES PESSOAIS ========= -->
      <div class="perfil-content">
        <h1>Informações Pessoais</h1>

        <?php if (!empty($erro)): ?>
          <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
          <div class="grupo">

            <div class="campo">
              <label for="nome">Nome</label>
              <input id="nome" type="text" name="nome"
                value="<?= htmlspecialchars($usuario['nome']); ?>" required>
            </div>

            <div class="campo">
              <label for="email">Email</label>
              <input id="email" type="email" name="email"
                value="<?= htmlspecialchars($usuario['email']); ?>" required>
            </div>

          </div>

          <div class="grupo">

            <div class="campo">
              <label for="bairro">Bairro</label>
              <input id="bairro" type="text" name="bairro"
                value="<?= htmlspecialchars($usuario['bairro']); ?>" required>
            </div>

            <div class="campo">
              <label for="logradouro">Logradouro</label>
              <input id="logradouro" type="text" name="logradouro"
                value="<?= htmlspecialchars($usuario['logradouro']); ?>" required>
            </div>

          </div>

          <div class="grupo">
            <div class="campo">
              <label for="complemento">Complemento</label>
              <input id="complemento" type="text" name="complemento"
                value="<?= htmlspecialchars($usuario['complemento']); ?>">
            </div>
          </div>

          <button type="submit">Atualizar</button>
        </form>

      </div>
    </div>
  </section>

  <!-- ===========================================
        MINHAS COMPRAS (MOSTRAR APENAS SE NÃO FOR ADMIN)
  ============================================= -->
  <?php if ($usuario['funcao'] !== 'admin'): ?>

    <h1 id="titulo" class="titulo">Minhas Compras</h1>
    <div class="linha"></div>

    <div class="container">

      <?php if (empty($pedidos)): ?>
        <p>Você ainda não fez nenhuma compra.</p>

      <?php else: ?>
        <div class="lista-pedidos">

          <?php foreach ($pedidos as $pedido): ?>
            <?php
            $idPedido = (int) $pedido['id'];
            $data = date('d/m/Y', strtotime($pedido['criado_em']));
            $hora = date('H:i', strtotime($pedido['criado_em']));
            $total = number_format((float) $pedido['total'], 2, ',', '.');
            $status = $pedido['status'] ?? '—';
            $metodo = $pedido['metodo_pagamento'] ?? '—';
            ?>

            <div class="pedido">
              <h3>Pedido Nº <?= $idPedido; ?></h3>
              <span><?= $data; ?> às <?= $hora; ?></span>

              <strong>Total:</strong>
              <span>R$ <?= $total; ?></span>

              <strong>Status:</strong>
              <span><?= $status ?></span>

              <strong>Método de pagamento:</strong>
              <span><?= $metodo ?></span>

              <a href="./usuario/detalhes-pedido.php?id=<?= $idPedido; ?>" class="btn-detalhes">Ver Detalhes</a>
            </div>


          <?php endforeach; ?>

        </div>
      <?php endif; ?>

    </div>

  <?php endif; ?>

  <?php include "../app/components/footer.php"; ?>

</body>

</html>