    <?php
    require_once __DIR__ . '/../../app/config/conexao.php';
    require_once __DIR__ . '/../../app/config/auth.php';

    if (!estaLogado()) {
        header('Location: /index.php');
        exit;
    }

    $usuario_id = (int) $_SESSION['usuario_id'];
    $metodosPermitidos = ['credito', 'debito', 'dinheiro', 'pix'];

    /* ================================
    REMOVER ITEM
    ================================ */
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover'], $_POST['produto_id'])) {
        require_once __DIR__ . '/../../app/actions/remover-do-carrinho.php';
        exit;
    }

    /* ================================
    BUSCAR ITENS DO CARRINHO
    ================================ */
    $sql = "SELECT c.quantidade, p.id AS produto_id, p.nome, p.preco, p.imagem
            FROM carrinho c
            JOIN produtos p ON c.produto_id = p.id
            WHERE c.usuario_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $total = 0.0;
    $items = [];

    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
        $total += $row['preco'] * $row['quantidade'];
    }

    $temItens = !empty($items);

    /* ================================
    ALTERAR QUANTIDADE
    ================================ */
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'], $_POST['produto_id'], $_POST['quantidade'])) {

        $produto_id = (int) $_POST['produto_id'];
        $quantidade = (int) $_POST['quantidade'];

        if ($_POST['acao'] === 'aumentar') $quantidade++;
        if ($_POST['acao'] === 'diminuir' && $quantidade > 1) $quantidade--;

        $stmt = $conn->prepare("UPDATE carrinho SET quantidade = ? WHERE usuario_id = ? AND produto_id = ?");
        $stmt->bind_param("iii", $quantidade, $usuario_id, $produto_id);
        $stmt->execute();

        header('Location: carrinho.php');
        exit;
    }

    /* ================================
    FINALIZAR PEDIDO
    ================================ */
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metodo_pagamento']) && !isset($_POST['acao'])) {

        if (!$temItens) {
            $erro = "Seu carrinho está vazio. Adicione produtos antes de finalizar.";
        } else {

            $metodo = $_POST['metodo_pagamento'];

            if (!in_array($metodo, $metodosPermitidos)) {
                $erro = "Método de pagamento inválido.";
            } else {

                $stmt = $conn->prepare("
                    INSERT INTO pedidos (usuario_id, total, metodo_pagamento, status)
                    VALUES (?, ?, ?, 'pendente')
                ");
                $stmt->bind_param("ids", $usuario_id, $total, $metodo);

                if ($stmt->execute()) {

                    $pedido_id = $stmt->insert_id;

                    foreach ($items as $i) {
                        $stmtI = $conn->prepare("
                            INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco)
                            VALUES (?, ?, ?, ?)
                        ");
                        $stmtI->bind_param(
                            "iiid",
                            $pedido_id,
                            $i['produto_id'],
                            $i['quantidade'],
                            $i['preco']
                        );
                        $stmtI->execute();
                    }

                    $stmt = $conn->prepare("DELETE FROM carrinho WHERE usuario_id = ?");
                    $stmt->bind_param("i", $usuario_id);
                    $stmt->execute();

                    header('Location: compras.php');
                    exit;

                } else {
                    $erro = "Erro ao finalizar o pedido. Tente novamente.";
                }
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/reset.css">
        <link rel="stylesheet" href="../assets/css/carrinho.css">
        <link rel="stylesheet" href="../assets/css/components/header.css">
        <link rel="stylesheet" href="../assets/css/components/footer.css">
        <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">

        <title>Meu Carrinho - Fast Food</title>
    </head>

    <body>
        <?php
        $paginaAtual = 'carrinho';
        include "../../app/components/header.php";
        ?>

        <div class="container">
            <?php if ($temItens): ?>

                <div class="produtos">
                    <?php foreach ($items as $item): ?>
                        <div class="produto">

                            <img src="../assets/imgs/produtos/<?= $item['imagem'] ?>" alt="<?= $item['nome'] ?>">

                            <div class="info-produto">
                                <h3><?= $item['nome'] ?></h3>
                                <h4>R$ <?= number_format($item['preco'], 2, ',', '.') ?></h4>
                            </div>

                            <div class="botoes">
                                <form action="carrinho.php" method="POST">
                                    <input type="hidden" name="produto_id" value="<?= $item['produto_id'] ?>">
                                    <input type="hidden" name="quantidade" value="<?= $item['quantidade'] ?>">
                                    <input type="hidden" name="acao" value="diminuir">
                                    <button type="submit" class="btn-quantidade">-</button>
                                </form>

                                <p><?= $item['quantidade'] ?></p>

                                <form action="carrinho.php" method="POST">
                                    <input type="hidden" name="produto_id" value="<?= $item['produto_id'] ?>">
                                    <input type="hidden" name="quantidade" value="<?= $item['quantidade'] ?>">
                                    <input type="hidden" name="acao" value="aumentar">
                                    <button type="submit" class="btn-quantidade">+</button>
                                </form>

                                <form action="" method="POST">
                                    <input type="hidden" name="produto_id" value="<?= $item['produto_id'] ?>">
                                    <input type="hidden" name="remover" value="1">
                                    <button type="submit">
                                        <img id="imagemLixeira" src="../assets/imgs/iconelixeira.png" alt="Remover">
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="total-carrinho">
                    <h2>Total: R$ <?= number_format($total, 2, ',', '.') ?></h2>

                    <form action="carrinho.php" method="POST">
                        <h3>Selecione o Método de Pagamento:</h3>
                        <select name="metodo_pagamento" required>
                            <option value="" disabled selected>Escolha...</option>
                            <option value="credito">Crédito</option>
                            <option value="debito">Débito</option>
                            <option value="dinheiro">Dinheiro</option>
                            <option value="pix">PIX</option>
                        </select>

                        <input type="submit" value="Finalizar Pedido">
                    </form>

                    <?php if (!empty($erro)): ?>
                        <p class="erro"><?= $erro ?></p>
                    <?php endif; ?>
                </div>

            <?php else: ?>

                <div class="carrinho-vazio">
                    <img src="../assets/imgs/carrinho-vazio.png" alt="Carrinho vazio">
                    <h2>Seu carrinho está vazio</h2>
                    <p>Que tal explorar nossos produtos incríveis?</p>
                    <a href="cardapio.php">Ver produtos</a>
                </div>

            <?php endif; ?>
        </div>

        <?php include "../../app/components/footer.php"; ?>
    </body>

    </html>
