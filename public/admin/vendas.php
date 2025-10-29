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

      <!-- Venda fake 1 -->
      <div class="venda">
        <h2>Pedido</h2>
        <h2>N° 12345</h2>

        <p>19/10/2025</p>
        <p>16:32</p>

        <p><strong>Cliente:</strong> Matheus Nóbrega</p>

        <p><strong>TOTAL:</strong></p>
        <p>R$ 43,30</p>

        <p><strong>STATUS:</strong> Entregue</p>

        <!-- Formulário apenas visual (desativado) -->
        <form action="#" method="POST">
          <input type="hidden" name="pedido_id" value="12345">
          <label for="status-12345">Alterar Status:</label>
          <select id="status-12345" name="status" disabled>
            <option selected>Entregue</option>
            <option>Pendente</option>
            <option>Em andamento</option>
            <option>Saiu para entrega</option>
            <option>Cancelado</option>
          </select>
          <input type="submit" value="Atualizar" disabled>
        </form>

        <a href="detalhes-venda.php?id=12345">Ver Detalhes</a>
      </div>

    </div>
  </main>

  <?php include '../../app/components/footer.php'; ?>
</body>

</html>