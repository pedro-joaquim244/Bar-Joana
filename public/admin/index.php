<!-- /public/admin/index.php (DEMO ESTÁTICA) -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

$paginaAtual = "index";

if (!estaLogado() || !($_SESSION['funcao'] ?? "")) {
  header('location: /index.php');
  exit;
}

$sql = "SELECT id, nome, descricao, preco, imagem, status from produtos";

$result = $conn->query($sql);
$temProduto = $result && $result->num_rows > 0;


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
  <?php include '../../app/components/header.php'; ?>

  <main>
    <h1>Produtos</h1>


    <div class="produtos">
      <?php if(!$temProduto):?>
        <p>Não ha produts cadastrados</p>
        <?php else: ?>
      <?php while($produto = $result->fetch_assoc()) : ?>
      <div class="produto">
        <img width="200" src="../assets/imgs/produtos/<?=$produto['imagem']?>" alt="Donut de Chocolate">
        <h3><?=$produto['nome']?></h3>
        <p><?=$produto['descricao']?></p>
        <p><?=$produto['preco']?></p>
        <p><?=$produto['status']?></p>

        <a href="editar-produto.php?id=<?=$produto['id']?>">Editar</a>
        <a href="#" aria-disabled="true">Remover</a>
      </div>
    <?php endwhile; ?>
    <?php endif; ?>
    </div>
  </main>

  <?php include '../../app/components/footer.php'; ?>
</body>

</html>