<!-- /public/admin/vendas.php -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

$paginaAtual = 'vendas';
// Verifica se o usuário é admin
if (estaLogado() && ($_SESSION['funcao'] ?? 'cliente') !== 'admin') {
  header('Location: /index.php');
  exit;
}

// Valores permitidos para o status
$statuses = ['pendente', 'em andamento', 'saiu para entrega', 'entregue', 'cancelado'];

// Mensagens
$msg = null;
$erro = null;

// Atualiza o status do pedido se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['status'])) {
  $pedido_id = (int) $_POST['pedido_id'];
  $status = $_POST['status'];

  if (in_array($status, $statuses, true)) {
    $sql_update = "UPDATE pedidos SET status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $status, $pedido_id);

    if ($stmt_update->execute() && $stmt_update->affected_rows > 0) {
      $msg = "Status do pedido #{$pedido_id} atualizado com sucesso!";
    } else {
      $erro = "Erro ao atualizar o status do pedido ou pedido não encontrado.";
    }
  } else {
    $erro = "Status inválido.";
  }
}

// Obtém todos os pedidos
$sql = "SELECT p.id, p.usuario_id, p.total, p.status, p.criado_em, u.nome
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.criado_em DESC";
$result = $conn->query($sql);

// Transforma em array para uso no foreach
$pedidos = [];
if ($result && $result->num_rows > 0) {
  $pedidos = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/vendas.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
  <title>Vendas - Fast Food</title>
</head>

<body>
  <?php include '../../app/components/header.php'; ?>

  <main>
    <h1>Gerenciar Vendas</h1>

    <div class="vendas">

      <?php if (empty($pedidos)): ?>
        <p>Não há vendas registradas.</p>
      <?php else: ?>

        <?php foreach ($pedidos as $pedido): ?>
          <div class="venda">

            <h2>Pedido</h2>
            <h2>N° <?= $pedido['id']; ?></h2>

            <p><?= date('d/m/Y', strtotime($pedido['criado_em'])); ?></p>
            <p><?= date('H:i', strtotime($pedido['criado_em'])); ?></p>

            <p><strong>Cliente:</strong> <?= $pedido['nome']; ?></p>

            <p><strong>TOTAL:</strong></p>
            <p>R$ <?= number_format($pedido['total'], 2, ',', '.'); ?></p>

            <p><strong>STATUS:</strong> <?= ucfirst($pedido['status']); ?></p>

            <!-- Formulário funcional -->
            <form action="vendas.php" method="POST">
              <input type="hidden" name="pedido_id" value="<?= $pedido['id']; ?>">

              <label for="status-<?= $pedido['id']; ?>">Alterar Status:</label>
              <select id="status-<?= $pedido['id']; ?>" name="status" required>
                <?php foreach ($statuses as $status): ?>
                  <option value="<?= $status; ?>" <?= $status === $pedido['status'] ? 'selected' : ''; ?>>
                    <?= ucfirst($status); ?>
                  </option>
                <?php endforeach; ?>
              </select>

              <input type="submit" value="Atualizar">
            </form>

            <a class="detalhes" href="detalhes-venda.php?id=<?= $pedido['id']; ?>">Ver Detalhes</a>
          </div>
        <?php endforeach; ?>

      <?php endif; ?>

    </div>
  </main>


  <?php include '../../app/components/footer.php'; ?>
</body>

</html>