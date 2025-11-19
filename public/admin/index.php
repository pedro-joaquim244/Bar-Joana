<!-- /public/admin/index.php (DEMO ESTÁTICA) -->
<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

$paginaAtual = "index";

if (!estaLogado() || !($_SESSION['funcao'] ?? "")) {
  header('location: /index.php');
  exit;
}

$sql = "SELECT id, nome, descricao, preco, imagem, status from produtos";

$result = $conn->query($sql);
$temProduto = $result && $result->num_rows > 0;


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/home-admin.css">
  <link rel="stylesheet" href="../assets/css/components/header.css">
  <link rel="stylesheet" href="../assets/css/components/footer.css">
  <title>Painel Admin - Fast Food</title>
</head>

<body>
  <?php include '../../app/components/header.php'; ?>


   <div class="carrossel">

    <div class="slide active">
      <img src="../assets/imgs/churrasco.jpg" alt="">
      <h1>Aqui o churrasco estala e a resenha rola solta!</h1>
    </div>

    <div class="slide">
      <img src="../assets/imgs/frios.jpg" alt="">
      <h1>Boteco é festa, história e mesa cheia</h1>
    </div>

    <div class="slide">
      <img src="../assets/imgs/batata.jpg" alt="">
      <h1>O ponto certo pra comer bem e dar boas risadas</h1>
    </div>

    <div class="slide">
      <img src="../assets/imgs/peixe.jpg" alt="">
      <h1>Sabor que abraça, cerveja que aproxima</h1>
    </div>

    <div class="slide">
      <img src="../assets/imgs/salgados.jpg" alt="">
      <h1>Seu boteco favorito está aqui — chega mais!</h1>
    </div>

  </div>



<main class="container">

    <section class="panel" aria-labelledby="nossa-historia">
        <div class="content">
            <div class="kicker">Nossa História</div>

            <h1 id="nossa-historia">Uma história de tradição e barulho — o Boteco Joana</h1>

            <p class="lead">
                O Boteco Joana nasceu da união de Pedro Joaquim (Joca) e Ana: um lugar pequeno, sincero e cheio de vida.
                A cozinha sempre foi de coração — temperos caseiros, porções generosas e uma cerveja que nunca decepciona.
                Aqui, a resenha é garantida e cada cliente acaba virando amigo.
            </p>

            <div class="actions">
                <button class="btn btn-primary">Ver mais</button>
            </div>
        </div>

        <aside class="visual" aria-hidden="false">
            <a class="image-card" href="#">
                <img src="../assets/imgs/BarJoana.jpg" alt="Fachada do Boteco Joana">
            </a>
        </aside>

    </section>

</main>





  <script src="../assets/javascript/inicio.js"></script>

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

  <main>
    <h1>Produtos</h1>


    <div class="produtos">
      <?php if(!$temProduto):?>
        <p>Não ha produts cadastrados</p>
        <?php else: ?>
      <?php while($produto = $result->fetch_assoc()) : ?>
      <div class="produto">
        <img width="200" src="../assets/imgs/produtos/<?=$produto['imagem']?>" alt="Donut de Chocolate">
        <h3><?=$produto['nome']?></h3>
        <p><?=$produto['descricao']?></p>
        <p><?=$produto['preco']?></p>
        <p><?=$produto['status']?></p>

        <a href="editar-produto.php?id=<?=$produto['id']?>">Editar</a>
        <a href="#" aria-disabled="true">Remover</a>
      </div>
    <?php endwhile; ?>
    <?php endif; ?>
    </div>
  </main>

  <?php include '../../app/components/footer.php'; ?>
</body>

</html>