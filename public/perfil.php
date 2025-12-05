<?php
require_once __DIR__ . '/../app/config/conexao.php';
require_once __DIR__ . '/../app/config/auth.php';

$paginaAtual = "perfil";

if (!estaLogado()) {
    header('Location: /index.php');
    exit;
}

$id = (int) ($_SESSION['usuario_id'] ?? 0);

/* =======================
   BUSCA DADOS DO USUÁRIO
=========================*/
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    echo "<h2 style='color:red;'>Erro: usuário não encontrado.</h2>";
    exit;
}

/* =======================
   ATUALIZAÇÃO DE PERFIL
=========================*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome        = trim($_POST['nome'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $bairro      = trim($_POST['bairro'] ?? '');
    $logradouro  = trim($_POST['logradouro'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');

    if ($nome && $email && $bairro && $logradouro) {

        $stmt = $conn->prepare("
            UPDATE usuarios SET 
                nome=?, 
                email=?, 
                bairro=?, 
                logradouro=?, 
                complemento=?
            WHERE id=?
        ");

        $stmt->bind_param("ssssssi", 
            $nome, $email, $bairro, $logradouro, $complemento, $id
        );

        if ($stmt->execute()) {
            $_SESSION['usuario']['nome']  = $nome;
            $_SESSION['usuario']['email'] = $email;
            header("Location: perfil.php");
            exit;
        } else {
            $erro = "Erro ao atualizar.";
        }
    } else {
        $erro = "Preencha todos os campos obrigatórios.";
    }
}

/* =======================
   BUSCA PEDIDOS DO USUÁRIO
=========================*/
$sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY criado_em DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Perfil - Fast Food</title>
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/css/perfil.css">
    <link rel="stylesheet" href="./assets/css/compras.css">
    <link rel="stylesheet" href="./assets/css/components/header.css">
    <link rel="stylesheet" href="./assets/css/components/footer.css">
    <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
</head>

<body>
<?php include "../app/components/header.php"; ?>

<h1 id="titulo" class="titulo">Perfil</h1>
<div class="Linha"></div>

<section>
    <div class="perfil-wrapper">

        <!-- ========= SIDEBAR ========= -->
        <div class="perfil-sidebar">
            <div class="avatar">
                <?php
                $nome = $usuario['nome'] ?? '';
                $iniciais = strtoupper(
                    substr($nome, 0, 1) . (strrchr($nome, ' ') ? substr(strrchr($nome, ' '), 1, 1) : '')
                );
                echo htmlspecialchars($iniciais);
                ?>
            </div>
            <h2>Bem-vindo, <?= htmlspecialchars($usuario['nome']); ?>!</h2>
        </div>

        <!-- ========= INFORMAÇÕES PESSOAIS ========= -->
        <div class="perfil-content">
            <h1>Informações Pessoais</h1>

            <?php if (!empty($erro)): ?>
                <p style="color:red;"><?= $erro ?></p>
            <?php endif; ?>

            <!-- Botão Editar -->
            <button type="button" id="btnEditar" class="btn-editar-perfil">Editar Perfil</button>

            <form method="POST" id="formPerfil">

                <div class="grupo">
                    <div class="campo">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome"
                        value="<?= htmlspecialchars($usuario['nome']); ?>" required disabled>
                    </div>

                    <div class="campo">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                        value="<?= htmlspecialchars($usuario['email']); ?>" required disabled>
                    </div>
                </div>

                <div class="grupo">
                    <div class="campo">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro"
                        value="<?= htmlspecialchars($usuario['bairro']); ?>" required disabled>
                    </div>

                    <div class="campo">
                        <label for="logradouro">Logradouro</label>
                        <input type="text" id="logradouro" name="logradouro"
                        value="<?= htmlspecialchars($usuario['logradouro']); ?>" required disabled>
                    </div>
                </div>

                <div class="grupo">

                    <div class="campo">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento"
                        value="<?= htmlspecialchars($usuario['complemento']); ?>" disabled>
                    </div>
                </div>

                <!-- BOTÕES DE SALVAR E CANCELAR -->
                <div class="area-botoes oculto" id="areaBotoes">
                    <button type="submit" class="btn-salvar-perfil">Salvar</button>
                    <button type="button" class="btn-cancelar-perfil" id="btnCancelar">Cancelar</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        const btnEditar = document.getElementById("btnEditar");
        const btnCancelar = document.getElementById("btnCancelar");
        const areaBotoes = document.getElementById("areaBotoes");
        const inputs = document.querySelectorAll("#formPerfil input");

        btnEditar.addEventListener("click", () => {
            inputs.forEach(campo => campo.disabled = false);
            btnEditar.style.display = "none";
            areaBotoes.classList.remove("oculto");
        });

        btnCancelar.addEventListener("click", () => {
            inputs.forEach(campo => campo.disabled = true);
            btnEditar.style.display = "inline-block";
            areaBotoes.classList.add("oculto");
        });
    </script>

</section>


<!-- ===========================================
        MINHAS COMPRAS
============================================ -->
<?php if ($usuario['funcao'] !== 'admin'): ?>

<h1 id="titulo" class="titulo">Minhas Compras</h1>
<div class="linha"></div>

<div class="container">

<?php if (empty($pedidos)): ?>
    <p>Você ainda não fez nenhuma compra.</p>

<?php else: ?>
    <div class="lista-pedidos">

        <?php foreach ($pedidos as $p): ?>
            <div class="pedido">
                <h3>Pedido Nº <?= $p['id'] ?></h3>
                <span><?= date('d/m/Y H:i', strtotime($p['criado_em'])) ?></span>

                <strong>Total:</strong>
                <span>R$ <?= number_format($p['total'], 2, ',', '.') ?></span>

                <strong>Status:</strong>
                <span><?= $p['status'] ?></span>

                <strong>Método de pagamento:</strong>
                <span><?= $p['metodo_pagamento'] ?></span>

                <a href="./usuario/detalhes-pedido.php?id=<?= $p['id']; ?>" class="btn-detalhes">Ver Detalhes</a>
            </div>
        <?php endforeach; ?>

    </div>
<?php endif; ?>

</div>

<?php endif; ?>

<?php include "../app/components/footer.php"; ?>

</body>
</html>
