<?php
// /public/usuario/compras.php (DEMO ESTÁTICA)
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';


$paginaAtual = 'compras';
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
  include "../../app/components/header.php";
  ?>

  <main>
    <h1>Minhas Compras</h1>

    <div class="lista-pedidos">
      <!-- Pedido fake 1 -->
      <div class="pedido">
        <h3>Pedido Nº 12345</h3>
        <span>19/10/2025 às 16:32</span>

        <strong>Total:</strong>
        <span>R$ 43,30</span>

        <strong>Status:</strong>
        <span>entregue</span>

        <strong>Método de pagamento:</strong>
        <span>PIX</span>

        <a href="detalhes-pedido.php?id=12345">Ver Detalhes</a>
      </div>

    </div>
  </main>

  <?php include "../../app/components/footer.php"; ?>
</body>

</html>