<!-- /app/actions/remover-do-carrinho.php -->
<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

if (!estaLogado()) {
  header('Location: /index.php');
  exit;
}

$usuario_id = (int) $_SESSION['usuario_id'];
$produto_id = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);
if (!$produto_id) {
  // Se não veio produto_id, volta pro carrinho
  header('Location: /usuario/carrinho.php');
  exit;
}

$sql = "DELETE FROM carrinho WHERE usuario_id = ? AND produto_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $produto_id);
$stmt->execute();

// Sempre redireciona de volta para o carrinho
header('Location: /usuario/carrinho.php');
exit;
