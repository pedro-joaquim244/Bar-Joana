<!-- /public/admin/editar-produto.php (DEMO ESTÁTICA) -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

// Para o header marcar navegação (opcional)
$paginaAtual = 'inicio';
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
        <h1>Editar produto #1</h1>

        <form method="POST" enctype="multipart/form-data" class="editar-produto">
            <label>Nome:</label>
            <input type="text" name="nome" value="Donut de Chocolate" required>

            <label>Descrição:</label>
            <textarea name="descricao" required>Massa macia com cobertura de chocolate meio amargo.</textarea>

            <label>Preço:</label>
            <input type="number" step="0.01" name="preco" value="12.90" required>

            <label>Status:</label>
            <select name="status" required>
                <option value="ativo" selected>Ativo</option>
                <option value="inativo">Inativo</option>
            </select>

            <label>Imagem atual:</label>
            <div style="margin-bottom:8px;">
                <img src="/assets/imgs/produtos/fake-donut.jpg" alt="Donut de Chocolate" style="max-width:150px;">
            </div>

            <label>Nova imagem (opcional):</label>
            <input type="file" name="imagem" accept="image/*">

            <button type="submit">Salvar</button>
        </form>
    </main>

    <?php include '../../app/components/footer.php'; ?>
</body>

</html>