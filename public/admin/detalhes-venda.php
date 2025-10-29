<!-- /public/admin/detalhes-venda.php (DEMO ESTÁTICA) -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

$paginaAtual = 'vendas';
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
  <title>Detalhes da Venda #1- Fast Food</title>
</head>

<body>
  <?php include '../../app/components/header.php'; ?>

  <main>
    <h1>Detalhes da Venda</h1>


    <section>
      <h2>Pedido</h2>
      <h2>N° 12345</h2>

      <p><strong>Cliente:</strong> Matheus Nóbrega</p>
      <p><strong>Método de pagamento:</strong> PIX</p>

      <p><strong>TOTAL</strong></p>
      <p>R$ 43,30</p>
    </section>

    <!-- Endereço -->
    <section>
      <h3>Endereço de Entrega</h3>
      <p><strong>Bairro:</strong> Centro</p>
      <p><strong>Logradouro:</strong> Rua das Flores</p>
      <p><strong>Número:</strong> 100</p>
      <p><strong>Complemento:</strong> Apto 12</p>
    </section>

    <h3>Itens do Pedido</h3>

    <div class="itens-pedido">
      <!-- Item 1 -->
      <div class="item">
        <img width="200" src="/assets/imgs/produtos/fake-donut.jpg" alt="Donut de Chocolate" class="imagem-item" />
        <div class="item-detalhe">
          <h4>Donut de Chocolate</h4>
          <p>Preço Unitário: R$ 12,90</p>
          <p>Quantidade: 2</p>
          <p>Subtotal: R$ 25,80</p>
        </div>
      </div>


      <p><a href="/public/admin/vendas.php">Voltar para Vendas</a></p>
    </div>

    <?php include '../../app/components/footer.php'; ?>
</body>

</html>