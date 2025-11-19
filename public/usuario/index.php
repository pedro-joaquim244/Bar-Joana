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

  <!-- Swiper CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
  />

  <title>Página Inicial - Fast Food</title>
</head>

<body>

  <!-- HEADER -->
  <?php include '../../app/components/header.php'; ?>

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

  <!-- FOOTER -->
  <?php include '../../app/components/footer.php'; ?>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Seu JS -->
  <script src="../assets/javascript/index.js"></script>

</body>

</html>
