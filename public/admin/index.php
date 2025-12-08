<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

/* ============================================
   DEFINIR VARIÁVEIS PARA NÃO GERAR WARNING
============================================ */
$categoriaFiltro = $_GET['categoria'] ?? "";
$ordenar = $_GET['ordenar'] ?? "";

/* ============================================
   BUSCAR CATEGORIAS
============================================ */
$sqlCategorias = "SELECT DISTINCT categoria FROM produtos ORDER BY categoria ASC";
$resCat = $conn->query($sqlCategorias);
$categorias = [];

if ($resCat && $resCat->num_rows > 0) {
  $categorias = $resCat->fetch_all(MYSQLI_ASSOC);
}

/* ============================================
   REMOVER PRODUTO
============================================ */
if (isset($_GET['remover_id']) && ($id = (int) $_GET['remover_id'])) {

  $stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $produto = $result->fetch_assoc();
  $stmt->close();

  if (!$produto) {
    echo "<script>alert('Produto não encontrado.');location='index.php';</script>";
    exit;
  }

  $img = $produto['imagem'];

  // Remove itens do carrinho
  $delCarrinho = $conn->prepare("DELETE FROM carrinho WHERE produto_id = ?");
  $delCarrinho->bind_param("i", $id);
  $delCarrinho->execute();
  $delCarrinho->close();

  // Remove itens_pedido
  $delItens = $conn->prepare("DELETE FROM itens_pedido WHERE produto_id = ?");
  $delItens->bind_param("i", $id);
  $delItens->execute();
  $delItens->close();

  // Remove o produto
  $del = $conn->prepare("DELETE FROM produtos WHERE id=?");
  $del->bind_param("i", $id);

  if ($del->execute()) {
    $base = realpath(__DIR__ . "/../assets/imgs/produtos");
    $file = $base ? $base . DIRECTORY_SEPARATOR . basename($img) : null;

    if ($file && is_file($file)) @unlink($file);

    echo "<script>alert('Produto removido com sucesso!');location='index.php';</script>";
  } else {
    echo "<script>alert('Erro ao remover produto.');location='index.php';</script>";
  }

  $del->close();
}

/* ============================================
   BUSCAR PRODUTOS (COM FILTROS)
============================================ */

$sql = "SELECT * FROM produtos WHERE 1=1";

// filtragem por categoria
if (!empty($categoriaFiltro)) {
  $sql .= " AND categoria = '" . $conn->real_escape_string($categoriaFiltro) . "'";
}

// ordenação
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
$produtos = [];

if ($result && $result->num_rows > 0) {
  $produtos = $result->fetch_all(MYSQLI_ASSOC);
}

/* ============================================
   CONTADORES REAIS DO BANCO
============================================ */

$total = $conn->query("SELECT COUNT(*) AS total FROM produtos")->fetch_assoc()['total'];
$ativos = $conn->query("SELECT COUNT(*) AS total FROM produtos WHERE status='ativo'")->fetch_assoc()['total'];
$inativos = $conn->query("SELECT COUNT(*) AS total FROM produtos WHERE status='inativo'")->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/home-admin.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
  <title>Painel Admin - Fast Food</title>
</head>

<body>

  <?php
  $paginaAtual = "Home";
  include '../../app/components/header.php';
  ?>

  <!-- FILTROS -->
  <form method="GET" id="filtrosForm" class="organizador" style="align-items:center;">

    <select name="categoria" id="categoriaSelect">
      <option value="">Todas as categorias</option>

      <?php foreach ($categorias as $c): ?>
        <option value="<?= htmlspecialchars($c['categoria']); ?>"
          <?= ($categoriaFiltro === $c['categoria']) ? 'selected' : '' ?>>
          <?= ucfirst(htmlspecialchars($c['categoria'])); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="ordenar" id="ordenarSelect">
      <option value="">Ordenar por...</option>
      <option value="az" <?= ($ordenar === 'az') ? 'selected' : '' ?>>A–Z</option>
      <option value="preco_menor" <?= ($ordenar === 'preco_menor') ? 'selected' : '' ?>>Menor preço</option>
      <option value="preco_maior" <?= ($ordenar === 'preco_maior') ? 'selected' : '' ?>>Maior preço</option>
    </select>

  </form>

  <script>
    document.getElementById("categoriaSelect").addEventListener("change", () => {
      document.getElementById("filtrosForm").submit();
    });

    document.getElementById("ordenarSelect").addEventListener("change", () => {
      document.getElementById("filtrosForm").submit();
    });
  </script>

  <h2>Pesquisar Produto</h2>
  <input type="text" id="searchInput" placeholder="Digite para pesquisar..." onkeyup="searchProduct()">

  <script>
    function searchProduct() {
      let input = document.getElementById("searchInput").value.toLowerCase();
      let cards = document.querySelectorAll(".produto");

      cards.forEach(card => {
        let texto = card.innerText.toLowerCase();
        card.style.display = texto.includes(input) ? "block" : "none";
      });
    }
  </script>

      <!-- ==========================================
 <!-- ==========================================
         CONTADORES REAIS
    =========================================== -->
    <section class="counter-section">
      <div class="counter-box">
        <h2 class="counter" data-target="<?= $total ?>">0</h2>
        <p>Total de produtos</p>
      </div>

      <div class="counter-box">
        <h2 class="counter" data-target="<?= $ativos ?>">0</h2>
        <p>Ativos</p>
      </div>

      <div class="counter-box">
        <h2 class="counter" data-target="<?= $inativos ?>">0</h2>
        <p>Inativos</p>
      </div>
    </section>

  <div class="container">
    <h1>Produtos</h1>
    <div class="Linha"></div>

    <div class="produtos">
      <?php if (empty($produtos)): ?>
        <p>Não há produtos cadastrados.</p>
      <?php else: ?>
        <?php foreach ($produtos as $produto): ?>
          <div class="produto">

            <img width="200" src="../assets/imgs/produtos/<?= htmlspecialchars($produto['imagem']); ?>"
              alt="<?= htmlspecialchars($produto['nome']); ?>">

            <h3><?= htmlspecialchars($produto['nome']); ?></h3>
            <p><?= htmlspecialchars($produto['descricao']); ?></p>
            <p>R$ <?= number_format((float)$produto['preco'], 2, ',', '.'); ?></p>
            <p>Status: <?= ucfirst(htmlspecialchars($produto['status'])); ?></p>

            <a href="editar-produto.php?id=<?= (int)$produto['id']; ?>">Editar</a>
            <a href="index.php?remover_id=<?= (int)$produto['id']; ?>"
              onclick="return confirm('Tem certeza que deseja remover este produto?')">Remover</a>

          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

   

    <script>
      const counters = document.querySelectorAll('.counter');

      counters.forEach(counter => {
        counter.innerText = "0";

        const updateCounter = () => {
          const target = +counter.getAttribute("data-target");
          const current = +counter.innerText;

          const increment = target / 200;

          if (current < target) {
            counter.innerText = Math.ceil(current + increment);
            setTimeout(updateCounter, 10);
          } else {
            counter.innerText = target;
          }
        };

        updateCounter();
      });
    </script>

  </div>

  <?php include '../../app/components/footer.php'; ?>

</body>
</html>
