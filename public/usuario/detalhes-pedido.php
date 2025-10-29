<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';


$paginaAtual = 'compras';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/detalhes-pedido.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <title>Detalhes do Pedido Nº 12345 - Fast Food</title>
</head>

<body>
  <?php include '../../app/components/header.php'; ?>

  <main>

    <section>
      <h2>Pedido Nº 12345</h2>
      <p>
        Endereço:
        Rua das Flores, 100 — Apto 12 — Centro
      </p>

      <h3>Itens</h3>

      <!-- Item 1 -->
      <div>
        Donut de Chocolate — Qtd: 2 — Preço unit.: R$ 12,90 — Subtotal: R$ 25,80
      </div>

      <h3>Resumo</h3>
      <p><strong>Método de pagamento:</strong> PIX</p>

      <p style="margin-top:8px;font-size:1.1rem;">
        <strong>Valor total pago:</strong> R$ 43,30
      </p>
    </section>
  </main>

  <?php include '../../app/components/footer.php'; ?>
</body>

</html>