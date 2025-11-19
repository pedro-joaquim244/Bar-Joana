<!-- /app/actions/adicionar-ao-carrinho.php -->
<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

if (!estaLogado()) {
    header('Location: /index.php');
    exit;
}

if (isset($_POST['produto_id'], $_POST['quantidade'])) {
    $usuario_id = (int) $_SESSION['usuario_id'];
    $produto_id = (int) $_POST['produto_id'];
    $quantidade = (int) $_POST['quantidade'];

    // Já existe no carrinho?
    $stmt = $conn->prepare("SELECT 1 FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
    $stmt->bind_param("ii", $usuario_id, $produto_id);
    $stmt->execute();
    $existe = $stmt->get_result()->num_rows > 0;

    if ($existe) {
        $stmt = $conn->prepare(
            "UPDATE carrinho SET quantidade = quantidade + ? WHERE usuario_id = ? AND produto_id = ?"
        );
        $stmt->bind_param("iii", $quantidade, $usuario_id, $produto_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iii", $usuario_id, $produto_id, $quantidade);
        $stmt->execute();
    }

    header('Location: /usuario/carrinho.php');
    exit;
}

header('Location: /usuario/carrinho.php');
exit;
