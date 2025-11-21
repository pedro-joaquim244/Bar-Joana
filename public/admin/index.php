<!-- /public/admin/index.php -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';


// Verifica se o usuário é admin
if (estaLogado() && $_SESSION['funcao'] !== 'admin') {
  header('Location: /index.php');
  exit;
}

// Deletar produto
if (isset($_GET['remover_id']) && ($id = (int) $_GET['remover_id'])) {
  $stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $img = ($r = $stmt->get_result()->fetch_assoc()) ? $r['imagem'] : null;
  $stmt->close();

  if ($img !== null) {
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      $base = realpath(__DIR__ . "/../assets/imgs/produtos");
      $file = $base ? $base . DIRECTORY_SEPARATOR . basename($img) : null;
      if ($file && is_file($file)) @unlink($file);
      echo "<script>alert('Produto removido!');location='index.php'</script>";
    } else {
      echo "<script>alert('Erro ao remover (pode haver vendas vinculadas).');</script>";
    }
    $stmt->close();
  } else {
    echo "<script>alert('Produto não encontrado.');</script>";
  }
}


$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);

// Monta array para usar no foreach
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
  <title>Painel Admin - Fast Food</title>
</head>

<body>
  <?php
  $paginaAtual = "inicio";
  include '../../app/components/header.php';
  ?>
  <div class="container">
    <h1>Produtos</h1>

    <div class="produtos">
      <?php if (empty($produtos)): ?>
        <p>Não há produtos cadastrados.</p>
      <?php else: ?>
        <?php foreach ($produtos as $produto): ?>
          <div class="produto">
            <img width="200" src="../assets/imgs/produtos/<?= $produto['imagem']; ?>" alt="<?= $produto['nome']; ?>">
            <h3><?= $produto['nome']; ?></h3>
            <p><?= $produto['descricao']; ?></p>
            <p>R$ <?= number_format((float)$produto['preco'], 2, ',', '.'); ?></p>
            <p>Status: <?= ucfirst($produto['status']); ?></p>

            <a href="editar-produto.php?id=<?= (int)$produto['id']; ?>">Editar</a>
            <a href="index.php?remover_id=<?= (int)$produto['id']; ?>" onclick="return confirm('Tem certeza que deseja remover este produto?')">Remover</a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
  <?php include '../../app/components/footer.php'; ?>
</body>

</html>