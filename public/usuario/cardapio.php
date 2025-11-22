<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

/** Função mínima para adicionar ao carrinho (use sua action se preferir) */
function addCarrinho(mysqli $conn, int $usuarioId, int $produtoId, int $qtd = 1): void
{
  // Ajuste conforme seu schema (ON DUPLICATE KEY é opcional)
  $stmt = $conn->prepare("
    INSERT INTO carrinho (usuario_id, produto_id, quantidade)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE quantidade = quantidade + VALUES(quantidade)
  ");
  $stmt->bind_param('iii', $usuarioId, $produtoId, $qtd);
  $stmt->execute();
}

/* 1) Se veio com intenção via GET (deep-link) e já está logado, efetiva e limpa a URL */
if (estaLogado() && isset($_GET['add'])) {
  $produtoId = (int)($_GET['add'] ?? 0);
  $qtd       = max(1, (int)($_GET['qty'] ?? 1));
  addCarrinho($conn, $_SESSION['usuario_id'], $produtoId, $qtd);
  header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?')); // limpa os parâmetros
  exit;
}

/* 2) Se for POST “adicionar ao carrinho”, faz via action (como você já tinha) */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto_id'], $_POST['quantidade'])) {
  require_once __DIR__ . '/../../app/actions/adicionar-ao-carrinho.php';
  exit;
}

/* 3) Carrega produtos */
$sql = "SELECT * FROM produtos WHERE status = 'ativo'";
$result = $conn->query($sql);
$produtos = $result && $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/home-usuario.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
  <title>Cardapio - Fast Food</title>
</head>

<body>
  <?php
  $paginaAtual = "cardapio";
  include '../../app/components/header.php';
  ?>

  <h1>Cardapio</h1>
  <div class="produtos">
    <?php if (empty($produtos)): ?>
      <p>Não há produtos cadastrados.</p>
    <?php else: ?>
      <?php foreach ($produtos as $p): ?>
        <div class="produto">
          <img width="200" src="/assets/imgs/produtos/<?= $p['imagem']; ?>" alt="<?= $p['nome']; ?>">
          <h3><?= $p['nome']; ?></h3>
          <p><?= $p['descricao']; ?></p>
          <h4>R$<?= $p['preco']; ?></h4>

          <?php if (estaLogado() && ($_SESSION['funcao'] ?? null) === 'cliente'): ?>
            <!-- Logado: formulário normal -->
            <form action="" method="POST">
              <input type="hidden" name="produto_id" value="<?= (int)$p['id']; ?>">
              <label for="q<?= (int)$p['id']; ?>">Quantidade</label>
              <input id="q<?= (int)$p['id']; ?>" type="number" name="quantidade" value="1" min="1" required>
              <button type="submit">Adicionar</button>
            </form>
          <?php else: ?>
            <a class="botao-login-para-adicionar" href="/login.php">Adicionar</a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <?php include '../../app/components/footer.php'; ?>
</body>

</html>