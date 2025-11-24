<?php
// /public/admin/detalhes-venda.php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

$erro = null;
$pedido = null;
$itens = [];

// 1) Permissão: só admin
if (!estaLogado() || (($_SESSION['funcao'] ?? 'cliente') !== 'admin')) {
  header('Location: /index.php');
  exit;
}

// 2) ID do pedido válido?
$pedido_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$pedido_id) {
  $erro = 'ID do pedido não fornecido ou inválido.';
}

// 3) Busca detalhes do pedido + endereço do cliente
if (!$erro) {
  $sql_pedido = "SELECT p.*, u.nome, u.bairro, u.logradouro, u.complemento
                 FROM pedidos p
                 JOIN usuarios u ON p.usuario_id = u.id
                 WHERE p.id = ?";
  $stmt_pedido = $conn->prepare($sql_pedido);
  $stmt_pedido->bind_param("i", $pedido_id);
  $stmt_pedido->execute();
  $result_pedido = $stmt_pedido->get_result();

  if ($result_pedido->num_rows === 0) {
    $erro = "Pedido #{$pedido_id} não encontrado ou sem permissão para visualizar.";
  } else {
    $pedido = $result_pedido->fetch_assoc();

    // 4) Carrega itens do pedido (usa preço histórico de itens_pedido)
    $sql_itens = "SELECT i.quantidade,
                         i.preco AS preco_unit,
                         p.nome,
                         p.imagem
                  FROM itens_pedido i
                  JOIN produtos p ON i.produto_id = p.id
                  WHERE i.pedido_id = ?";
    $stmt_itens = $conn->prepare($sql_itens);
    $stmt_itens->bind_param("i", $pedido_id);
    $stmt_itens->execute();
    $result_itens = $stmt_itens->get_result();
    $itens = $result_itens->num_rows > 0 ? $result_itens->fetch_all(MYSQLI_ASSOC) : [];
  }
}

// 5) Totais (fallback se pedidos.total não estiver preenchido)
$totalItens = 0.0;
foreach ($itens as $it) {
  $qtd = (int) $it['quantidade'];
  $pu = (float) $it['preco_unit'];
  $totalItens += $qtd * $pu;
}
$valorTotal = (isset($pedido['total']) && is_numeric($pedido['total'])) ? (float) $pedido['total'] : $totalItens;

// 6) Método de pagamento (coluna adicionada no schema)
$metodoPagamento = $pedido['metodo_pagamento'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/detalhes-venda.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <title>Detalhes da Venda - Fast Food</title>
</head>

<body>

  <?php include '../../app/components/header.php'; ?>

  <div>
    <h1>Detalhes das Vendas</h1>
  </div>

  <div class="container">

    <?php if ($erro): ?>

      <!-- CARD DE ERRO -->
      <section class="aviso-erro">
        <p><?= $erro ?></p>
        <p><a href="/index.php">Voltar para o início</a></p>
      </section>

    <?php else: ?>

      <!-- CARD PRINCIPAL DO PEDIDO -->
      <div class="card-pedido">

        <h2>Pedido</h2>
        <h2>N° <?= $pedido['id']; ?></h2>

        <!-- GRID DE INFO -->
        <div class="info-grid">

          <div class="info-card">
            <p><strong>Cliente:</strong><br><?= $pedido['nome']; ?></p>
          </div>

          <div class="info-card">
            <p><strong>Método de pagamento:</strong><br><?= $metodoPagamento ?></p>
          </div>

          <div class="info-card">
            <p><strong>Valor total:</strong><br>
              R$ <?= number_format($valorTotal, 2, ',', '.'); ?></p>
          </div>

        </div>

        <!-- ENDEREÇO -->
        <h3>Endereço</h3>

        <div class="endereco">
          <p><strong>Bairro:</strong> <?= $pedido['bairro']; ?></p>
          <p><strong>Logradouro:</strong> <?= $pedido['logradouro']; ?></p>
          <p><strong>Complemento:</strong> <?= $pedido['complemento']; ?></p>
        </div>

        <!-- ITENS -->
        <h3>Itens do Pedido</h3>

        <?php if (!empty($itens)): ?>

          <div class="itens-pedido">

            <?php foreach ($itens as $item): ?>
              <?php
              $q = (int) $item['quantidade'];
              $pu = (float) $item['preco_unit'];
              $sub = $q * $pu;
              ?>

              <!-- CARD DE CADA ITEM -->
              <div class="item-card">
                <img src="/assets/imgs/produtos/<?= $item['imagem']; ?>" alt="<?= $item['nome']; ?>" class="imagem-item" />

                <h3><?= $item['nome']; ?></h3>
                <p><strong>Preço Unitário:</strong> R$ <?= number_format($pu, 2, ',', '.'); ?></p>
                <p><strong>Quantidade:</strong> <?= $q; ?></p>
                <p><strong>Subtotal:</strong> R$ <?= number_format($sub, 2, ',', '.'); ?></p>
              </div>

            <?php endforeach; ?>

          </div>

        <?php else: ?>

          <p>Não há itens neste pedido.</p>

        <?php endif; ?>

      </div> <!-- fim do card-pedido -->

    <?php endif; ?>

  </div>


  <?php include '../../app/components/footer.php'; ?>
</body>

</html>