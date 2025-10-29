<?php
// /public/usuario/carrinho.php (DEMO ESTÁTICA, sem JS)
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

// Para o header destacar o menu atual
$paginaAtual = 'carrinho';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/carrinho.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <title>Meu Carrinho - Fast Food</title>
</head>

<body>
  <?php include "../../app/components/header.php"; ?>

  <main>
    <div class="produtos">

      <!-- Item fake 1 -->
      <div class="produto">
        <img src="/assets/imgs/produtos/fake-donut.jpg" alt="Donut de Chocolate">
        <h3>Donut de Chocolate</h3>
        <h4>R$ 12,90</h4>

        <button type="button" class="btn-quantidade" disabled>-</button>
        <p>2</p>
        <button type="button" class="btn-quantidade" disabled>+</button>
        <button type="button" class="btn-remover" title="Remover" disabled>
          Remover
        </button>
      </div>
    </div>

    <div class="total-carrinho">
      <h2>Total: R$ 43,30</h2>

      <!-- Formulário apenas visual -->
      <form method="POST">
        <h3>Selecione o Método de Pagamento:</h3>
        <div>
          <label for="metodo_pagamento">Método de pagamento</label>
          <select id="metodo_pagamento" name="metodo_pagamento" disabled>
            <option value="" selected>Escolha...</option>
            <option value="credito">Crédito</option>
            <option value="debito">Débito</option>
            <option value="dinheiro">Dinheiro</option>
            <option value="pix">PIX</option>
          </select>
          <input type="submit" value="Finalizar Pedido" disabled>
        </div>
      </form>

    </div>

  </main>

  <?php include "../../app/components/footer.php"; ?>
</body>

</html>