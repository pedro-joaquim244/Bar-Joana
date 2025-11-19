<!-- /public/admin/vendas.php (DEMO ESTÁTICA) -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

// Para o header destacar o menu atual
$paginaAtual = "vendas";
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

          <p><?= date('d/m/Y', strtotime($pedido['created_at'])); ?></p>
          <p><?= date('H:i', strtotime($pedido['created_at'])); ?></p>

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