<!-- /public/admin/adicionar-produto.php -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

$paginaAtual = "adicionar-produto";

if (!estaLogado() || !($_SESSION['funcao'] ?? "")) {
  header('location: /index.php');
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $nome = trim($_POST['nome'] ?? '');
  $descricao = trim($_POST['descricao'] ?? '');
  $preco = $_POST['preco'] ?? '';
  $status = $_POST['status'] ?? 'ativo';
  $img = $_FILES['imagem'] ?? null;

  if ($nome === '' || $descricao === '' || $preco === '' || !$img) {
    $erro = "Preencha todos os campos";
  } else if (($img['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $erro = "Falha ao enviar a imagem";
  } else {
    $uploadDir = __DIR__ . '/../assets/imgs/produtos';
    $ext = strtolower(pathinfo($img['name'] ?? '', PATHINFO_EXTENSION));

    $arquivo = uniqid('img_', true) . ($ext ? '.' . $ext : '');
    $destino = $uploadDir . DIRECTORY_SEPARATOR . $arquivo;

    if (!move_uploaded_file($img['tmp_name'], $destino)) {
      $erro = "Não foi possível salvar a imagem";
    } else {
      $sql = "INSERT INTO produtos (nome, descricao, preco, imagem, status) VALUES (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssdss", $nome, $descricao, $preco, $arquivo, $status);

      if ($stmt->execute()) {
        header('location: /index.php');
        exit;
      }

      $erro = "Erro interno";
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

  <h1 class="titulo-principal">Adicionar Produto</h1>
  <main class="conteudo-principal">
    <div class="tudinho">
      <div class="Basicas">
        <form method="POST" enctype="multipart/form-data" class="form-produto">
          <div class="campo">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Ex.: Donut de Chocolate" required>
          </div>

          <div class="grupo-flex">
            <div class="campo metade">
              <label for="preco">Preço</label>
              <input type="number" id="preco" step="0.01" name="preco" placeholder="Ex.: 12.90" required>
            </div>

            <div class="campo metade">
              <label for="status">Status</label>
              <select id="status" name="status" required>
                <option value="ativo" selected>Ativo</option>
                <option value="inativo">Inativo</option>
              </select>
            </div>
          </div>

        </form>
        <div class="descrição">

          <form method="POST" enctype="multipart/form-data" class="form-descri">
            <div class="campo">
              <label for="descricao">Descrição</label>
              <textarea id="descricao" name="descricao" placeholder="Fale brevemente sobre o produto"
                required></textarea>
            </div>
          </form>

        </div>
      </div>

      <div class="campo campo-upload">
        <label for="imagem">Imagem</label>
        <label for="imagem" class="botao-upload">Selecionar imagem</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" onchange="mostrarNomeArquivo(this)" required>
        <span class="nome-arquivo" id="nome-arquivo">Nenhum arquivo selecionado</span>
      </div>
    </div>
    <button type="submit">
      Salvar
    </button>

    <?php if ($erro): ?>
      <p><?= $erro ?></p>
    <?php endif; ?>
  </main>

  <?php include '../../app/components/footer.php'; ?>
</body>

</html>