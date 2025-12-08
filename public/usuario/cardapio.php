<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';


/* -------------------------------
   ADICIONAR AO CARRINHO (GET)
--------------------------------*/
function addCarrinho(mysqli $conn, int $usuarioId, int $produtoId, int $qtd = 1): void
{
  $stmt = $conn->prepare("
      INSERT INTO carrinho (usuario_id, produto_id, quantidade)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE quantidade = quantidade + VALUES(quantidade)
  ");
  $stmt->bind_param('iii', $usuarioId, $produtoId, $qtd);
  $stmt->execute();
}

if (estaLogado() && isset($_GET['add'])) {
  $produtoId = (int) $_GET['add'];
  $qtd = max(1, (int) ($_GET['qty'] ?? 1));
  addCarrinho($conn, $_SESSION['usuario_id'], $produtoId, $qtd);

  header("Location: cardapio.php");
  exit;
}


/* -------------------------------
   ADICIONAR AO CARRINHO (POST)
--------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto_id'], $_POST['quantidade'])) {
  require_once __DIR__ . '/../../app/actions/adicionar-ao-carrinho.php';
  exit;
}


/* -------------------------------
   FILTROS: CATEGORIA + ORDENAR
--------------------------------*/
$categoriaFiltro = $_GET['categoria'] ?? '';
$ordenar = $_GET['ordenar'] ?? '';

$sql = "SELECT * FROM produtos WHERE status = 'ativo'";

if (!empty($categoriaFiltro)) {
  $sql .= " AND categoria = '" . $conn->real_escape_string($categoriaFiltro) . "'";
}

switch ($ordenar) {
  case 'az':
    $sql .= " ORDER BY nome ASC";
    break;
  case 'preco_menor':
    $sql .= " ORDER BY preco ASC";
    break;
  case 'preco_maior':
    $sql .= " ORDER BY preco DESC";
    break;
  default:
    $sql .= " ORDER BY id DESC";
}

$result = $conn->query($sql);
$produtos = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];


/* Buscar categorias */
$cats = $conn->query("SELECT DISTINCT categoria FROM produtos WHERE categoria IS NOT NULL ORDER BY categoria ASC");
$categorias = $cats ? $cats->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/cardapio.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
  <title>Cardápio - Fast Food</title>
</head>

<body>

  <?php
  $paginaAtual = "cardapio";
  include '../../app/components/header.php';
  ?>

  <h1 id="titu">Cardápio</h1>
  <div class="Linha"></div>

  <!-- ===============================
         FILTRO DE CATEGORIAS + ORDENAR
       =============================== -->
  <form method="GET" id="filtrosForm" class="organizador" style="align-items:center;">
    <select name="categoria" id="categoriaSelect">
      <option value="">Todas as categorias</option>

      <?php foreach ($categorias as $c): ?>
        <option value="<?= htmlspecialchars($c['categoria']); ?>" <?= ($categoriaFiltro === $c['categoria']) ? 'selected' : '' ?>>
          <?= ucfirst(htmlspecialchars($c['categoria'])); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="ordenar" id="ordenarSelect">
      <option value="">Ordenar por...</option>
      <option value="az" <?= $ordenar === 'az' ? 'selected' : '' ?>>A–Z</option>
      <option value="preco_menor" <?= $ordenar === 'preco_menor' ? 'selected' : '' ?>>Menor preço</option>
      <option value="preco_maior" <?= $ordenar === 'preco_maior' ? 'selected' : '' ?>>Maior preço</option>
    </select>
  </form>

  <h2>Pesquisar no Cardápio</h2>
  <input type="text" id="searchInput" placeholder="Digite para pesquisar..." onkeyup="search()">



  <!-- ======================================
          PRODUTOS AGRUPADOS POR CATEGORIA
       ====================================== -->

  <?php
  // Reorganizando produtos por categoria
  $agrupados = [];

  foreach ($produtos as $p) {
    $cat = $p['categoria'] ?: 'sem categoria';
    if (!isset($agrupados[$cat])) {
      $agrupados[$cat] = [];
    }
    $agrupados[$cat][] = $p;
  }
  ?>


  <div class="produtos-container">

    <?php foreach ($agrupados as $categoria => $lista): ?>
      <h2 class="titulo-categoria"><?= ucfirst(htmlspecialchars($categoria)); ?></h2>
      <div class="Linha"></div>

      <div class="produtos">
        <?php foreach ($lista as $p): ?>
          <div class="produto">

            <img src="/assets/imgs/produtos/<?= htmlspecialchars($p['imagem']); ?>"
              alt="<?= htmlspecialchars($p['nome']); ?>">

            <h3><?= htmlspecialchars($p['nome']); ?></h3>
            <p class="descricao"><?= htmlspecialchars($p['descricao']); ?></p>
            <h4>R$ <?= number_format($p['preco'], 2, ',', '.'); ?></h4>

            <?php if (estaLogado() && ($_SESSION['funcao'] ?? null) === 'cliente'): ?>
              <form method="POST" class="form-add">
                <input type="hidden" name="produto_id" value="<?= (int) $p['id']; ?>">
                <input type="number" name="quantidade" value="1" min="1">
                <button id="BOTAO" type="submit">Adicionar</button>
              </form>

            <?php else: ?>
              <a class="botao-login-para-adicionar" href="/login.php">Adicionar</a>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      </div>

    <?php endforeach; ?>

  </div>


  <?php include '../../app/components/footer.php'; ?>


  <!-- AUTO-SUBMIT FILTROS -->
  <script>
    (function () {
      const form = document.getElementById('filtrosForm');
      document.getElementById('categoriaSelect').addEventListener('change', () => form.submit());
      document.getElementById('ordenarSelect').addEventListener('change', () => form.submit());
    })();
  </script>

  <!-- PESQUISA -->
  <script>
    function search() {
      let input = document.getElementById("searchInput").value.toLowerCase();
      let cards = document.querySelectorAll(".produto");

      cards.forEach(card => {
        let texto = card.textContent.toLowerCase();

        card.style.display = texto.includes(input) ? "" : "none";
      });
    }
  </script>

</body>

</html>