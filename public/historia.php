<?php
require_once __DIR__ . '/../app/config/conexao.php';
require_once __DIR__ . '/../app/config/auth.php';

$paginaAtual = "historia";
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nossa Historia</title>
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/css/historia.css">
    <link rel="stylesheet" href="./assets/css/components/header.css">
    <link rel="stylesheet" href="./assets/css/components/footer.css">
</head>

<body>
    <?php
    include "../app/components/header.php";
    ?>
    <main class="container">


        <section class="panel" aria-labelledby="nossa-historia">
            <div class="content">
                <div class="kicker">Nossa História</div>

                <h1 id="nossa-historia">Uma história de tradição e barulho — o Boteco Joana</h1>

                <p class="lead">
                    O Boteco Joana nasceu da união de Pedro Joaquim (Joca) e Ana: um lugar pequeno, sincero e cheio de
                    vida.
                    A cozinha é de coração — temperos caseiros, porções generosas e uma cerveja sempre gelada.
                    Aqui, clientes viram amigos, risadas viram rotina e a comida vem com conversa junto.
                </p>

                <p class="meta">
                    <strong>Local:</strong> Esquina da Rua Principal ·
                    <strong>Horário:</strong> aberto todos os dias a partir das 11h
                </p>

                <div class="bullets" aria-hidden="true">
                    <span class="chip">Torresmo campeão</span>
                    <span class="chip">Chopp gelado</span>
                    <span class="chip">Sobremesas artesanais</span>
                    <span class="chip">Ambiente familiar</span>
                </div>

                <div class="actions">
                    <button class="btn btn-primary">Ver Cardápio</button>
                    <button class="btn btn-ghost">Reservar Mesa</button>
                </div>
            </div>

            <aside class="visual" aria-hidden="false">
                <a class="image-card" href="#" title="Foto fachada Boteco Joana">
                    <img src="./assets/imgs/BarLocal.jpg" alt="Fachada do Boteco Joana — parede vermelha e mesa externa">
                </a>
            </aside>


        </section>
    </main>
    <?php
    include "../app/components/footer.php";
    ?>
</body>

</html>