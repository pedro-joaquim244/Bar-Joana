<?php
$paginaAtual ??= "";
$funcao = $_SESSION['funcao'] ?? null;
?>

<header>

    <a class="logo" href="/index.php">
        <img src="/assets/imgs/logo.jpg" alt="Logo" />
    </a>
    <nav class="menu">
        <a href="/index.php" class=" link-menu<?= $paginaAtual === "Home" ? "ativo" : "" ?>">Home</a>
        <a href="/usuario/cardapio.php"
            class=" link-menu <?= $paginaAtual === "cardapio" ? "ativo" : "" ?>">Cardápio</a>
        <a href="/usuario/cardapio.php" class=" link-menu <?= $paginaAtual === "Nossa-Hisória" ? "ativo" : "" ?>">Nossa
            História</a>
        <?php
        if ($funcao): ?>
            <?php
            if ($funcao === "cliente"): ?>
                <a href="/usuario/carrinho.php"
                    class=" link-menu <?= $paginaAtual === "carrinho" ? "ativo" : "" ?>">Carrinho</a>
                <a href="/usuario/compras.php" class=" link-menu <?= $paginaAtual === "status-pedido" ? "ativo" : "" ?>">Status
                    do Pedido</a>
                <a href="/usuario/compras.php" class=" link-menu <?= $paginaAtual === "pedidos" ? "ativo" : "" ?>">Pedidos</a>

            <?php elseif ($funcao === "admin"): ?>


                <a href="/admin/vendas.php" class=" link-menu <?= $paginaAtual === "pedidos" ? "ativo" : "" ?>">Pedidos</a>
                <a href="/admin/adicionar-produto.php"
                    class=" link-menu <?= $paginaAtual === "adicionar-produto" ? "ativo" : "" ?>">Adicionar Produto</a>



            <?php endif; ?>
            <a href="/perfil.php" class=" link-menu <?= $paginaAtual === "perfil" ? "ativo" : "" ?>">Perfil</a>
            <form method="post">
                <button type="submit" name="logout" class="">
                    Sair
                </button>
            </form>
        <?php else: ?>
            <a href="/login.php" class=" link-menu <?= $paginaAtual === "login" ? "ativo" : "" ?>">Entrar</a>
            <a href="/criar-conta.php" class=" link-menu <?= $paginaAtual === "criar-conta" ? "ativo" : "" ?>">Cadastrar</a>
        <?php endif; ?>

        <a class="carrinho" href="/index.php">
            <img src="/assets/imgs/carrinho.png" alt="Logo" />
        </a>
        <a class="usuario" href="/index.php">
            <img src="/assets/imgs/usuario.png" alt="Logo" />
        </a>


    </nav>


</header>