<?php
// /public/usuario/compras.php (lista de pedidos do usuário)
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

if (!estaLogado()) {
  header('Location: /index.php');
  exit;
}

// Pedidos do usuário logado
$usuario_id = (int)($_SESSION['usuario_id'] ?? 0);
$sql = "SELECT *
        FROM pedidos
        WHERE usuario_id = ?
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

// Transforma em array para foreach
$pedidos = ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/compras.css">
  <link rel="stylesheet" href="../assets/css/componentes/header.css">
  <link rel="stylesheet" href="../assets/css/componentes/footer.css">
  <title>Minhas Compras - Fast Food</title>
</head>

<body>
  <?php
  $paginaAtual = 'compras';
  include "../../app/components/header.php";
  ?>

  <div class="container">
    <h1>Minhas Compras</h1>

    <?php if (empty($pedidos)): ?>
      <p>Você ainda não fez nenhuma compra.</p>
    <?php else: ?>
      <div class="lista-pedidos">
        <?php foreach ($pedidos as $pedido): ?>
          <?php
          $id        = (int)$pedido['id'];
          $data      = date('d/m/Y', strtotime($pedido['created_at']));
          $hora      = date('H:i', strtotime($pedido['created_at']));
          $total     = isset($pedido['total']) && is_numeric($pedido['total']) ? (float)$pedido['total'] : 0.0;
          $status    = isset($pedido['status']) ? (string)$pedido['status'] : '—'; // sem formatação
          $metodo    = $pedido['metodo_pagamento'] ?? null; // ENUM ou NULL
          ?>
          <div class="pedido">
            <h3>Pedido Nº <?= $id; ?></h3>
            <span><?= $data; ?> às <?= $hora; ?></span>

            <strong>Total:</strong>
            <span>R$ <?= number_format($total, 2, ',', '.'); ?></span>

            <strong>Status:</strong>
            <span><?= $status ?></span>

            <strong>Método de pagamento:</strong>
            <span><?= $metodo ?></span>

            <a href="detalhes-pedido.php?id=<?= $id; ?>" class="btn-detalhes">Ver Detalhes</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php include "../../app/components/footer.php"; ?>
</body>

</html>