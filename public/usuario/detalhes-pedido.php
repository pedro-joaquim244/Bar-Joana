<?php
// /public/usuario/detalhes-pedido.php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

if (!estaLogado()) {
  header('Location: /index.php');
  exit;
}

$pedido_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$pedido_id) {
  echo "<p>ID do pedido não fornecido ou inválido.</p>";
  exit;
}

$usuario_id = usuarioId();

/** Carrega o pedido + endereço do usuário */
$sql = "SELECT p.*, u.bairro, u.logradouro, u.numero, u.complemento
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = ? AND p.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $pedido_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "<p>Pedido não encontrado ou você não tem permissão para visualizar este pedido.</p>";
  exit;
}

$pedido = $result->fetch_assoc();

/** Carrega itens do pedido */
$sql_itens = "SELECT i.quantidade, i.preco AS preco_unit, p.nome
              FROM itens_pedido i
              JOIN produtos p ON i.produto_id = p.id
              WHERE i.pedido_id = ?";
$stmt_itens = $conn->prepare($sql_itens);
$stmt_itens->bind_param("i", $pedido_id);
$stmt_itens->execute();
$result_itens = $stmt_itens->get_result();
$itens = $result_itens->num_rows > 0 ? $result_itens->fetch_all(MYSQLI_ASSOC) : [];

/** Totais */
$totalItens = 0.0;
foreach ($itens as $it) {
  $qtd   = (int)$it['quantidade'];
  $preco = (float)$it['preco_unit'];
  $totalItens += $qtd * $preco;
}

/** Usa o total salvo no pedido ou a soma */
$valorTotalPago = (isset($pedido['total']) && is_numeric($pedido['total']))
  ? (float)$pedido['total']
  : $totalItens;

$metodoPagamento = $pedido['metodo_pagamento'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalhes do Pedido Nº <?= (int)$pedido['id'] ?> - Fast Food</title>

  <!-- CSS externo -->
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <link rel="stylesheet" href="../assets/css/detalhes-pedido.css">
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
</head>

<body>
  <div class="card">

    <h2>Pedido Nº <?= (int)$pedido['id'] ?></h2>

    <p class="endereco">
      Endereço:
      <?= $pedido['logradouro'] . ', ' . $pedido['numero'] ?>
      <?php if (!empty($pedido['complemento'])): ?>
        — <?= $pedido['complemento'] ?>
      <?php endif; ?>
      — <?= $pedido['bairro'] ?>
    </p>

    <h3>Itens</h3>

    <div class="itens-box">
      <?php if (!empty($itens)): ?>
        <?php foreach ($itens as $item): ?>
          <?php
          $q = (int)$item['quantidade'];
          $pu = (float)$item['preco_unit'];
          $sub = $q * $pu;
          ?>
          <div class="item">
            <?= $item['nome'] ?> — 
            Qtd: <?= $q ?> — 
            Preço unit.: R$ <?= number_format($pu, 2, ',', '.') ?> — 
            Subtotal: R$ <?= number_format($sub, 2, ',', '.') ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="item">Nenhum item neste pedido.</div>
      <?php endif; ?>
    </div>

    <h3>Resumo</h3>

    <div class="resumo-box">
      <p><strong>Método de pagamento:</strong> <?= $metodoPagamento ?></p>
    </div>

    <div class="valor-total">
      Valor total pago: R$ <?= number_format($valorTotalPago, 2, ',', '.') ?>
    </div>

  </div>
</body>

</html>
