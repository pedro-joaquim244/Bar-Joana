<!-- /public/admin/index.php -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

// -------------------------
// REMOVER PRODUTO
// -------------------------
if (isset($_GET['remover_id']) && ($id = (int) $_GET['remover_id'])) {

    // Busca imagem do produto
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

    // REMOVE os itens relacionados ao produto antes de excluir o produto
    $delItens = $conn->prepare("DELETE FROM itens_pedido WHERE produto_id = ?");
    $delItens->bind_param("i", $id);
    $delItens->execute();
    $delItens->close();

    // AGORA pode remover o produto
    $del = $conn->prepare("DELETE FROM produtos WHERE id=?");
    $del->bind_param("i", $id);

    if ($del->execute()) {

        // Caminho da imagem
        $base = realpath(__DIR__ . "/../assets/imgs/produtos");
        $file = $base ? $base . DIRECTORY_SEPARATOR . basename($img) : null;

        // Remove imagem física
        if ($file && is_file($file)) {
            @unlink($file);
        }

        echo "<script>alert('Produto removido com sucesso!');location='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao remover produto.');location='index.php';</script>";
    }

    $del->close();
}


// -------------------------
// BUSCA PRODUTOS
// -------------------------
$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);

// Monta array de produtos
$produtos = [];
if ($result && $result->num_rows > 0) {
  $produtos = $result->fetch_all(MYSQLI_ASSOC);
}
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

  </div>

  <?php include '../../app/components/footer.php'; ?>
</body>

</html>
