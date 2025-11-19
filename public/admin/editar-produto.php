<!-- /public/admin/editar-produto.php -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';


if (!estaLogado() || (($_SESSION['funcao'] ?? 'cliente') !== 'admin')) {
    header('Location: /index.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo 'ID inválido.';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM produtos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$produto = $stmt->get_result()->fetch_assoc();
if (!$produto) {
    echo "Produto #$id não encontrado.";
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = $_POST['preco'] ?? '';
    $status = $_POST['status'] ?? 'inativo';

    if ($nome === '' || $descricao === '' || $preco === '') {
        $erro = 'Preencha nome, descrição e preço.';
    } else {
        $uploadDir = __DIR__ . '/../assets/imgs/produtos/';
        $novoArquivo = null;

        // Se veio nova imagem, salva com nome único
        if (!empty($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $novoArquivo = uniqid('img_', true) . ($ext ? ".$ext" : '');
            $dest = $uploadDir . $novoArquivo;
            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $dest)) {
                $erro = 'Não foi possível salvar a nova imagem.';
            }
        }

        if (!$erro) {
            $sql = "UPDATE produtos
              SET nome=?, descricao=?, preco=?, status=?, imagem=COALESCE(?, imagem)
              WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssi", $nome, $descricao, $preco, $status, $novoArquivo, $id);

            if ($stmt->execute()) {
                // Se atualizou com nova imagem, apaga a antiga
                if ($novoArquivo) {
                    $antigo = $uploadDir . basename($produto['imagem']);
                    if (is_file($antigo))
                        @unlink($antigo);
                }
                header("Location: index.php");
                exit;
            } else {
                // Se o UPDATE falhou mas já subiu arquivo novo, remove o novo
                if ($novoArquivo) {
                    $novoPath = $uploadDir . $novoArquivo;
                    if (is_file($novoPath))
                        @unlink($novoPath);
                }
                $erro = 'Erro ao salvar.';
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
    <link rel="stylesheet" href="../assets/css/editar-produto.css">
    <link rel="stylesheet" href="../assets/css/components/header.css">
    <link rel="stylesheet" href="../assets/css/components/footer.css">
    <title>Editar Produto - Fast Food</title>
</head>

<body>
    <?php include '../../app/components/header.php'; ?>

    <main>
        <h1>Editar produto #<?= $produto['id'] ?></h1>

        <?php if ($erro): ?>
            <p style="color:red;"><?= $erro ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="editar-produto">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>

            <label>Descrição:</label>
            <textarea name="descricao" required><?= htmlspecialchars($produto['descricao']) ?></textarea>

            <label>Preço:</label>
            <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required>

            <label>Status:</label>
            <select name="status" required>
                <option value="ativo"   <?= $produto['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                <option value="inativo" <?= $produto['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
            </select>

            <label>Imagem atual:</label>
            <div style="margin-bottom:8px;">
                <img src="/assets/imgs/produtos/<?= htmlspecialchars($produto['imagem']) ?>"
                     alt="<?= htmlspecialchars($produto['nome']) ?>"
                     style="max-width:150px;">
            </div>

            <label>Nova imagem (opcional):</label>
            <input type="file" name="imagem" accept="image/*">

            <button type="submit">Salvar</button>
        </form>
    </main>

    <?php include '../../app/components/footer.php'; ?>
</body>

</html>
