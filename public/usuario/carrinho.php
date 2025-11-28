<?php
// /public/usuario/carrinho.php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

if (!estaLogado()) {
  header('Location: /index.php');
  exit;
}

/** INTERCEPTA REMOVER -> delega pra action e sai */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover'], $_POST['produto_id'])) {
  require_once __DIR__ . '/../../app/actions/remover-do-carrinho.php';
  exit;
}

// Métodos de pagamento permitidos (batem com o ENUM do banco)
$metodosPermitidos = ['credito', 'debito', 'dinheiro', 'pix'];

// Itens do carrinho
$usuario_id = (int) $_SESSION['usuario_id'];
$sql = "SELECT c.quantidade, p.id AS produto_id, p.nome, p.preco, p.imagem
        FROM carrinho c
        JOIN produtos p ON c.produto_id = p.id
        WHERE c.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0.0;
$items = [];
while ($row = $result->fetch_assoc()) {
  $items[] = $row;
  $total += ((float) $row['preco']) * ((int) $row['quantidade']);
}
$temItens = !empty($items);

// Aumentar/diminuir quantidade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'], $_POST['produto_id'], $_POST['quantidade'])) {
  $produto_id = (int) $_POST['produto_id'];
  $quantidade = (int) $_POST['quantidade'];
  $acao = $_POST['acao'];

  if ($acao === 'aumentar')
    $quantidade++;
  if ($acao === 'diminuir' && $quantidade > 1)
    $quantidade--;

  $stmt = $conn->prepare("UPDATE carrinho SET quantidade = ? WHERE usuario_id = ? AND produto_id = ?");
  $stmt->bind_param("iii", $quantidade, $usuario_id, $produto_id);
  $stmt->execute();

  header('Location: carrinho.php');
  exit;
}

// Finalizar pedido — só se houver itens
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metodo_pagamento']) && !isset($_POST['acao'])) {
  if (!$temItens) {
    $erro = "Seu carrinho está vazio. Adicione produtos antes de finalizar.";
  } else {
    $metodo_pagamento = $_POST['metodo_pagamento'];

    if (!in_array($metodo_pagamento, $metodosPermitidos, true)) {
      $erro = "Método de pagamento inválido.";
    } else {
      // Cria o pedido já gravando o metodo_pagamento
      $stmt = $conn->prepare("
        INSERT INTO pedidos (usuario_id, total, metodo_pagamento, status)
        VALUES (?, ?, ?, 'pendente')
      ");
      // i = int (usuario_id), d = double (total), s = string (metodo_pagamento)
      $stmt->bind_param("ids", $usuario_id, $total, $metodo_pagamento);

      if ($stmt->execute()) {
        $pedido_id = $stmt->insert_id;

        // Copia itens do carrinho para itens_pedido com preço do momento
        foreach ($items as $i) {
          $stmtI = $conn->prepare("
            INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco)
            VALUES (?, ?, ?, ?)
          ");
          $pid = (int) $i['produto_id'];
          $qtd = (int) $i['quantidade'];
          $preco = (float) $i['preco'];
          $stmtI->bind_param("iiid", $pedido_id, $pid, $qtd, $preco);
          $stmtI->execute();
        }

        // Limpa o carrinho do usuário
        $stmt = $conn->prepare("DELETE FROM carrinho WHERE usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();

        header('Location: compras.php');
        exit;
      } else {
        $erro = "Erro ao finalizar o pedido. Tente novamente.";
      }
    }
  }
}
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
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">

  <title>Meu Carrinho - Fast Food</title>
</head>

<body>
  <?php
  $paginaAtual = 'carrinho';
  include "../../app/components/header.php";
  ?>

  <div class="container">
    <?php if ($temItens): ?>
      <?php foreach ($items as $item): ?>
        <div class="produto">
          <img src="../assets/imgs/produtos/<?= $item['imagem']; ?>" alt="<?= $item['nome']; ?>">
          <h3><?= $item['nome'] ?></h3>
          <h4>R$ <?= number_format((float) $item['preco'], 2, ',', '.') ?></h4>

          <form action="carrinho.php" method="POST" style="display:inline;">
            <input type="hidden" name="produto_id" value="<?= (int) $item['produto_id'] ?>">
            <input type="hidden" name="quantidade" value="<?= (int) $item['quantidade'] ?>">
            <input type="hidden" name="acao" value="diminuir">
            <button type="submit" class="btn-quantidade">-</button>
          </form>

          <p><?= (int) $item['quantidade'] ?></p>

          <form action="carrinho.php" method="POST" style="display:inline;">
            <input type="hidden" name="produto_id" value="<?= (int) $item['produto_id'] ?>">
            <input type="hidden" name="quantidade" value="<?= (int) $item['quantidade'] ?>">
            <input type="hidden" name="acao" value="aumentar">
            <button type="submit" class="btn-quantidade">+</button>
          </form>

          <!-- Remover: posta para a MESMA página; o topo delega pra action -->
          <form action="" method="POST">
            <input type="hidden" name="produto_id" value="<?= (int) $item['produto_id'] ?>">
            <input type="hidden" name="remover" value="1">
            <button type="submit">
              <img id="imagemLixeira" class="lixeira" src="../assets/imgs/iconelixeira.png" alt="Remover">
            </button>
          </form>
        </div>
      <?php endforeach; ?>

      <div class="total-carrinho">
        <h2>Total: R$ <?= number_format($total, 2, ',', '.') ?></h2>

        <form action="carrinho.php" method="POST">
          <h3>Selecione o Método de Pagamento:</h3>
          <div class="pagar">
            <label for="metodo_pagamento" class="sr-only">Método de pagamento</label>
            <select id="metodo_pagamento" name="metodo_pagamento" required>
              <option value="" disabled selected>Escolha...</option>
              <option value="credito">Crédito</option>
              <option value="debito">Débito</option>
              <option value="dinheiro">Dinheiro</option>
              <option value="pix">PIX</option>
            </select>
            <div class="botao">
              <input type="submit" value="Finalizar Pedido">
            </div>
          </div>
        </form>

        <?php if (!empty($erro)): ?>
          <p class="erro"><?= $erro ?></p>
        <?php endif; ?>
      </div>

    <?php else: ?>
      <div class="carrinho-vazio">
        <img src="../assets/imgs/carrinho-vazio.png" alt="Carrinho vazio">
        <h2>Seu carrinho está vazio</h2>
        <p>Que tal explorar nossos produtos incríveis?</p>
        <a href="cardapio.php">Ver produtos</a>
      </div>


    <?php endif; ?>
  </div>

  <?php include "../../app/components/footer.php"; ?>
</body>

</html>