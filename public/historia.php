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
    <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
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
                    <img src="./assets/imgs/BarLocal.jpg"
                        alt="Fachada do Boteco Joana — parede vermelha e mesa externa">
                </a>
            </aside>


        </section>
    </main>


    <div class="equipes">
        <div class="membro">
            <img src="../assets/imgs/HelenaDuarte.jpg" alt="">
            <h1>Chefe de cozinha - Helena Duarte</h1>
            <p>Helena é a alma do “Sabor & Alma”. Aos 42 anos, comanda a cozinha com firmeza e carinho. Formada em
                gastronomia, trabalhou em hotéis de luxo antes de abrir o restaurante com o sonho de unir sabor e
                sentimento em cada prato. Exigente, mas justa, ela acredita que cozinhar é um ato de amor. Apesar da
                aparência dura, Helena tem um coração enorme e vê sua equipe como uma família.</p>
        </div>
        <div class="membro">
            <img src="../assets/imgs/RafaelLima.jpg" alt="">
            <h1>Cozinheiro - Rafael Lima</h1>
            <p>Rafael tem 27 anos e é apaixonado por massas. Aprendeu a cozinhar com a avó italiana e sonha em abrir sua
                própria cantina um dia. No restaurante, é o responsável pelas massas frescas e molhos. Vive rindo, canta
                enquanto cozinha e é o alívio cômico da equipe — mas por trás do bom humor, carrega a saudade da avó,
                que o inspirou a seguir esse caminho.</p>
        </div>
        <div class="membro">
            <img src="../assets/imgs/Joana.jpg" alt="">
            <h1>Cozinheira - Joana Silva</h1>
            <p>Joana, 34 anos, é especialista em comidas típicas brasileiras. Veio do interior de Minas e tem o tempero
                que conquista qualquer um. É simples, calma e sempre tem uma palavra amiga. No “Sabor & Alma”, é quem
                faz o feijão, o arroz, e os pratos do dia que lembram comida de mãe. Para ela, o segredo da culinária
                está no amor que se coloca em cada refeição.</p>
        </div>
        <div class="membro">
            <img src="../assets/imgs/PedroSantos.jpg" alt="">
            <h1>Cozinheiro - Pedro Santos</h1>
            <p>Pedro tem 22 anos e é o confeiteiro da equipe. Criativo e detalhista, faz sobremesas que parecem obras de
                arte. Entrou como ajudante, mas Helena viu talento nele e o incentivou a crescer. Sonha em estudar
                confeitaria em Paris. É tímido, mas quando está com batedeira e chocolate, o mundo inteiro desaparece.
            </p>
        </div>
        <div class="membro">
            <img src="../assets/imgs/Cida.jpg" alt="">
            <h1>Diarista - Cida oliveira</h1>
            <p>Cida é o oposto de Rosa: falante, divertida e cheia de histórias. Tem 30 anos e sonha em ser
                cabeleireira. Faz piadas até quando está esfregando o chão, e sua energia contagia a todos. Gosta de
                dizer que “o bom humor limpa a alma”. Mesmo com pouco, sempre encontra motivos para sorrir.</p>
        </div>
        <div class="membro">
            <img src="../assets/imgs/RosaFernandez.jpg" alt="">
            <h1>Diarista - Rosa Fernandes</h1>
            <p>Rosa, 45 anos, é quem mantém o restaurante impecável. Trabalha com orgulho e cuida dos detalhes que
                ninguém nota. É mãe de três filhos e vê no emprego a chance de dar a eles uma vida melhor. Sempre chega
                antes de todos e sai por último. Para ela, cada mesa limpa e cada prato brilhando é um sinal de respeito
                pelo cliente</p>
        </div>
        <div class="membro">
            <img src="../assets/imgs/MarcosRibeiro.jpg" alt="">
            <h1>Balconista - Marcos Ribeiro</h1>
            <p>Marcos, 26 anos, trabalha no balcão e é o primeiro rosto que os clientes veem. Educado, ágil e
                observador, sonha em ser gerente um dia. É organizado e gosta de anotar tudo. Admira a chefe Helena e
                aprende com ela sobre liderança. Vive um amor secreto por Joana, mas nunca teve coragem de contar.</p>
        </div>

        <div class="membro">
            <img src="../assets/imgs/BiancaBalconista.jpg" alt="">
            <h1>Balconista - Bianca Costas</h1>
            <p>
                Bianca tem 19 anos e está no primeiro emprego. É alegre, sonhadora e ama doces — por isso, vive
                “roubando” provas das sobremesas do Pedro. Está juntando dinheiro para fazer faculdade de nutrição.
                Mesmo jovem, mostra responsabilidade e traz leveza aos dias mais corridos da equipe.
            </p>
        </div>
    </div>


    <div class="chefes">
        <div class="chefe">
            <img src="../assets/imgs/JocaChefe.jpg" alt="">
            <h2>Pedro Joaquim</h2>
            <p>Chefe</p>
        </div>
        <div class="chefe">
            <img src="../assets/imgs/AnaChefe.jpg" alt="">
            <h2>Ana Clara</h2>
            <p>Chefe</p>
        </div>
    </div>


    <?php
    include "../app/components/footer.php";
    ?>
</body>

</html>