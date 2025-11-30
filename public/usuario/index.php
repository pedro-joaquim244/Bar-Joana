<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

$paginaAtual = "Home";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Seus CSS -->
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/home-usuario.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">

  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <title>Página Inicial - Fast Food</title>
</head>

<body>

  <!-- HEADER -->
  <?php include '../../app/components/header.php'; ?>



  <div class="carrossel">

  <div class="slide active" data-text="Aqui o churrasco estala e a resenha rola solta!">
    <img src="../assets/imgs/churrasco.jpg" alt="">
    <h1></h1>
  </div>

  <div class="slide" data-text="Boteco é festa, história e mesa cheia">
    <img src="../assets/imgs/frios.jpg" alt="">
    <h1></h1>
  </div>

  <div class="slide" data-text="O ponto certo pra comer bem e dar boas risadas">
    <img src="../assets/imgs/batata.jpg" alt="">
    <h1></h1>
  </div>

  <div class="slide" data-text="Sabor que abraça, cerveja que aproxima">
    <img src="../assets/imgs/peixe.jpg" alt="">
    <h1></h1>
  </div>

  <div class="slide" data-text="Seu boteco favorito está aqui — chega mais!">
    <img src="../assets/imgs/calabresa.jpg" alt="">
    <h1></h1>
  </div>

</div>




  <main class="container">

    <section class="panel" aria-labelledby="nossa-historia">
      <div class="content">
        <div class="kicker">Nossa História</div>

        <h1 id="nossa-historia">Uma história de tradição e barulho —   Boteco Joana</h1>

        <p class="lead">
          O Boteco Joana nasceu da união de Pedro Joaquim (Joca) e Ana: um lugar pequeno, sincero e cheio de vida.
          SSS A cozinha sempre foi de coração — temperos caseiros, porções generosas e uma cerveja que nunca decepciona.
          Aqui, a resenha é garantida e cada cliente acaba virando amigo.
        </p>

        <div class="actions">
          <div class="actions">
            <a href="../historia.php" class="btn btn-primary">Ver mais</a>
          </div>
        </div>
      </div>

      <aside class="visual" aria-hidden="false">
        <a class="image-card" href="#">
          <img src="../assets/imgs/BarLocal.jpg" alt="Fachada do Boteco Joana">
        </a>
      </aside>

    </section>

  </main>





  <script src="../assets/javascript/inicio.js"></script>


  <div class="titulo">
    <h2>DESTAQUES</h2>
  </div>

  <div class="linha"></div>
  <!-- MAIN -->
  <main id="carrosel-home">

    <div class="swiper mySwiper">
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <img src="../assets/imgs/Torresmo.jpg" alt="Torresmo" />
        </div>

        <div class="swiper-slide">
          <img src="../assets/imgs/Porçao-bolinho.jpg" alt="Bolinho" />
        </div>

        <div class="swiper-slide">
          <img src="../assets/imgs/Pururuuca.jpg" alt="Pururuuca" />
        </div>

        <div class="swiper-slide">
          <img src="../assets/imgs/Bolinho.jpg" alt="Bolinho 2" />
        </div>

      </div>

      <!-- Botões -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

      <!-- Paginação -->
      <div class="swiper-pagination"></div>
    </div>

  </main>



  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Seu JS -->
  <script src="../assets/javascript/index.js"></script>

  <div class="drinks">
    <div class="drink">
      <img src="../assets/imgs/blue.jpg" alt="">
      <h1>Blue Ice</h1>
      <p>O azul que refresca e acende a diversão.</p>
    </div>
    <div class="drink">
      <img src="../assets/imgs/caipirinha.jpg" alt="">
      <h1>Caipirinha de Morango</h1>
      <p>Vermelhinha, gelada e impossível de resistir</p>
    </div>
    <div class="drink">
      <img src="../assets/imgs/margarita.jpg" alt="">
      <h1>Marguerita</h1>
      <p>Doce na medida, cítrica no ponto</p>
    </div>
    <div class="drink">
      <img src="../assets/imgs/pinga.jpg" alt="">
      <h1>Dose de Pinga</h1>
      <p>É na dose que a conversa esquenta.</p>
    </div>
  </div>

 

<div class="review-section">
    <h2 class="review-title">O que a galera anda falando 😄</h2>

    <div class="review-carousel">

        <button class="arrow prev">&#10094;</button>

        <div class="review-wrapper">
            <div class="review-track">

                <div class="review">
                    <p>"Comida maravilhosa, atendimento perfeito! Melhor boteco disparado."</p>
                    <h3>— Ana Paula ⭐⭐⭐⭐⭐</h3>
                </div>

                <div class="review">
                    <p>"Ambiente divertido, porções bem servidas e música boa. Voltarei sempre!"</p>
                    <h3>— Gustavo Lima ⭐⭐⭐⭐⭐</h3>
                </div>

                <div class="review">
                    <p>"Experiência incrível! As bebidas chegaram rápido e estavam perfeitas."</p>
                    <h3>— Julia Andrade ⭐⭐⭐⭐⭐</h3>
                </div>

                <div class="review">
                    <p>"O melhor lugar da cidade pra relaxar! Tudo impecável."</p>
                    <h3>— Ricardo Torres ⭐⭐⭐⭐⭐</h3>
                </div>

            </div>
        </div>

        <button class="arrow next">&#10095;</button>

    </div>

    <div class="dots"></div>
</div>


<script src="../assets/javascript/comentarios.js"></script>


  <!-- FOOTER -->
  <?php include '../../app/components/footer.php'; ?>



</body>

</html>