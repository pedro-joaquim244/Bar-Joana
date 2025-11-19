<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

// Só admin
if (!estaLogado() || (($_SESSION['funcao'] ?? 'cliente') !== 'admin')) {
  header('Location: ../login.php');
  exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome'] ?? '');
  $descricao = trim($_POST['descricao'] ?? '');
  $preco = $_POST['preco'] ?? '';
  $status = $_POST['status'] ?? 'ativo';

  if ($nome === '' || $descricao === '' || $preco === '' || empty($_FILES['imagem'])) {
    $erro = 'Preencha todos os campos e selecione uma imagem.';
  } else {
    $uploadDir = __DIR__ . '/../assets/imgs/produtos';

    if (($_FILES['imagem']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
      $erro = 'Falha no upload da imagem.';
    } else {
      $orig = $_FILES['imagem']['name'] ?? '';
      $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
      $final = uniqid('img_', true) . ($ext ? '.' . $ext : '');
      $destino = $uploadDir . DIRECTORY_SEPARATOR . $final;

      if (!is_uploaded_file($_FILES['imagem']['tmp_name']) || !move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
        $erro = 'Não foi possível salvar a imagem.';
      } else {
        $sql = "INSERT INTO produtos (nome, descricao, preco, imagem, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $nome, $descricao, $preco, $final, $status);

        if ($stmt->execute()) {
          header('Location: index.php');
          exit;
        } else {
          $erro = 'Erro ao cadastrar o produto.';
        }
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/cadastrar-produto.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <title>Adicionar Produto - Fastfood</title>
</head>

<body>
  <?php include '../../app/components/header.php'; ?>

  <div class="conteudo-principal">
    <h1 class="titulo-principal">Adicionar Produto</h1>

    <?php if ($erro): ?>
      <p><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="adicionar-produto">
      <div class="grupo-flex">
        <div class="Basicas form-produto metade">
          <div class="campo">
            <label>Nome</label>
            <input type="text" name="nome" required>
          </div>

          <div class="campo">
            <label>Preço</label>
            <input type="number" step="0.01" name="preco" required>
          </div>

          <div class="campo">
            <label>Status</label>
            <select name="status" required>
              <option value="ativo" selected>Ativo</option>
              <option value="inativo">Inativo</option>
            </select>
          </div>
          <div class="campo">
            <label>Descrição</label>
            <textarea name="descricao" required></textarea>
          </div>
        </div>

        <div class="campo-upload metade">
          <label>Imagem</label>
          <input type="file" name="imagem" id="imagem" accept="image/*" required onchange="previewImagem(this)">
          <label class="botao-upload" for="imagem">Selecionar arquivo</label>
          <span class="nome-arquivo">Nenhum arquivo selecionado</span>
          <img id="preview" src="" alt="">
        </div>

      </div>



      <button type="submit">Salvar</button>
    </form>
  </div>

  <?php include '../../app/components/footer.php'; ?>

  <script>
    function previewImagem(input) {
      const arquivoNome = document.querySelector('.nome-arquivo');
      const preview = document.getElementById('preview');

      if (input.files && input.files[0]) {
        arquivoNome.textContent = input.files[0].name;
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      } else {
        arquivoNome.textContent = 'Nenhum arquivo selecionado';
        preview.style.display = 'none';
      }
    }
  </script>

</body>

</html>