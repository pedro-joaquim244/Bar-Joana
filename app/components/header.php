<?php
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['logout'])) {
    logout();
    header("Location: /index.php");
    exit;
}

$paginaAtual ??= "";
$funcao = $_SESSION['funcao'] ?? null;
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Anton&family=Audiowide&family=Be+Vietnam+Pro:wght@400;700&family=Poppins:wght@300;500;700&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="/assets/css/components/header.css">

<header>
    <div class="logo">
        <a class="logoFoto" href="/index.php">
            <img src="/assets/imgs/LogoJoaninha.png" alt="Logo" />
            <div class="logotxt">
                <h1>Boteco</h1>
                <h2>J O A N A</h2>
            </div>
        </a>

    </div>

    <nav class="menu">
        <!-- Telas comuns -->
        <a href="/index.php" class="link-menu <?= $paginaAtual === "Home" ? "ativo" : "" ?>">Home</a>
        <a href="/historia.php" class="link-menu <?= $paginaAtual === "historia" ? "ativo" : "" ?>">Nossa História</a>

        <?php if ($funcao): ?>
            <?php if ($funcao === "cliente"): ?>
                <!-- Menus do cliente -->
                <a href="/usuario/cardapio.php" class="link-menu <?= $paginaAtual === "cardapio" ? "ativo" : "" ?>">Cardápio</a>


            <?php elseif ($funcao === "admin"): ?>
                <!-- Menus do admin -->
                <a href="/admin/adicionar-produto.php"
                    class="link-menu <?= $paginaAtual === "adicionar-produto" ? "ativo" : "" ?>">Adicionar Produto</a>
                <a href="/admin/vendas.php" class="link-menu <?= $paginaAtual === "vendas" ? "ativo" : "" ?>">Vendas</a>
            <?php endif; ?>

        <?php else: ?>
            <!-- Menus de visitante -->
            <a href="/criar-conta.php" class="link-menu <?= $paginaAtual === "criar-conta" ? "ativo" : "" ?>">Cadastrar</a>
            <a href="/login.php" class="link-menu <?= $paginaAtual === "login" ? "ativo" : "" ?>">Entrar</a>
        <?php endif; ?>
    </nav>

    <nav class="icones">
        <?php if ($funcao): ?>
            <!-- Ícones só aparecem para usuários logados -->
            <?php if ($funcao === "cliente"): ?>
                <a class="icone <?= ($paginaAtual === 'carrinho') ? 'ativo' : '' ?>" href="/usuario/carrinho.php">
                <img src="/assets/imgs/carrinho.png" alt="Carrinho">
            </a>
            <?php endif; ?>

            <a class="icone <?= ($paginaAtual === 'perfil') ? 'ativo' : '' ?>" href="/perfil.php">
                <img src="/assets/imgs/usuario.png" alt="Usuário">
            </a>


            <form method="post">
                <button type="submit" name="logout" class="btn-sair">
                    <img src="/assets/imgs/Sair.png" alt="Sair">
                </button>
            </form>
        <?php endif; ?>
    </nav>

</header>