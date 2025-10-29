<!-- /public/admin/adicionar-produto.php -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/editar-produto.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <title>Adicionar Produto - Fastfood</title>
</head>

<body>
  <?php include '../../app/components/header.php'; ?>

  <main>
    <h1>Adicionar Produto</h1>

    <form method="POST" enctype="multipart/form-data" class="editar-produto">
      <label>Nome</label>
      <input type="text" name="nome" placeholder="Ex.: Donut de Chocolate" required>

      <label>Descrição</label>
      <textarea name="descricao" placeholder="Fale brevemente sobre o produto" required></textarea>

      <label>Preço</label>
      <input type="number" step="0.01" name="preco" placeholder="Ex.: 12.90" required>

      <label>Status</label>
      <select name="status" required>
        <option value="ativo" selected>Ativo</option>
        <option value="inativo">Inativo</option>
      </select>

      <label>Imagem</label>
      <input type="file" name="imagem" accept="image/*" required>

      <button type="submit">
        Salvar
      </button>
    </form>
  </main>

  <?php include '../../app/components/footer.php'; ?>
</body>

</html>