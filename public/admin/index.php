<?php
require_once __DIR__ . '/../../app/config/conexao.php';
require_once __DIR__ . '/../../app/config/auth.php';

// -------------------------
// REMOVER PRODUTO
// -------------------------
if (isset($_GET['remover_id']) && ($id = (int) $_GET['remover_id'])) {

  // Busca imagem do produto
  $stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $produto = $result->fetch_assoc();
  $stmt->close();

  if (!$produto) {
    echo "<script>alert('Produto não encontrado.');location='index.php';</script>";
    exit;
  }

  $img = $produto['imagem'];

  // -------------------------
  // Remove itens do CARRINHO
  // -------------------------
  $delCarrinho = $conn->prepare("DELETE FROM carrinho WHERE produto_id = ?");
  $delCarrinho->bind_param("i", $id);
  $delCarrinho->execute();
  $delCarrinho->close();

  // -------------------------
  // Remove itens do ITENS_PEDIDO
  // -------------------------
  $delItens = $conn->prepare("DELETE FROM itens_pedido WHERE produto_id = ?");
  $delItens->bind_param("i", $id);
  $delItens->execute();
  $delItens->close();

  // -------------------------
  // Agora remove o produto
  // -------------------------
  $del = $conn->prepare("DELETE FROM produtos WHERE id=?");
  $del->bind_param("i", $id);

  if ($del->execute()) {

    // Caminho da imagem
    $base = realpath(__DIR__ . "/../assets/imgs/produtos");
    $file = $base ? $base . DIRECTORY_SEPARATOR . basename($img) : null;

    // Remove a imagem física
    if ($file && is_file($file)) {
      @unlink($file);
    }

    echo "<script>alert('Produto removido com sucesso!');location='index.php';</script>";
  } else {
    echo "<script>alert('Erro ao remover produto.');location='index.php';</script>";
  }

  $del->close();
}

// -------------------------
// BUSCA PRODUTOS
// -------------------------
$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);

$produtos = [];
if ($result && $result->num_rows > 0) {
  $produtos = $result->fetch_all(MYSQLI_ASSOC);
}
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
  <link rel="icon" type="image/png" href="../assets/imgs/LogoJoaninha.png">
  <title>Painel Admin - Fast Food</title>
</head>

<body>

  <?php
  $paginaAtual = "Home";
  include '../../app/components/header.php';
  ?>

  <!-- ===============================
         FILTRO DE CATEGORIAS + ORDENAR
       (UM SÓ FORMULÁRIO PARA FICAR LADO A LADO)
       =============================== -->
  <form method="GET" id="filtrosForm" class="organizador" style="align-items:center;">
    <label for="categoriaSelect" style="display:none;">Categoria</label>
    <select name="categoria" id="categoriaSelect" aria-label="Filtrar por categoria">
      <option value="">Todas as categorias</option>

      <?php foreach ($categorias as $c): ?>
        <option value="<?= htmlspecialchars($c['categoria']); ?>" <?= ($categoriaFiltro === $c['categoria']) ? 'selected' : '' ?>>
          <?= ucfirst(htmlspecialchars($c['categoria'])); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="ordenarSelect" style="display:none;">Ordenar</label>
    <select name="ordenar" id="ordenarSelect" aria-label="Ordenar produtos">
      <option value="">Ordenar por...</option>
      <option value="az" <?= $ordenar === 'az' ? 'selected' : '' ?>>A–Z</option>
      <option value="preco_menor" <?= $ordenar === 'preco_menor' ? 'selected' : '' ?>>Menor preço</option>
      <option value="preco_maior" <?= $ordenar === 'preco_maior' ? 'selected' : '' ?>>Maior preço</option>
    </select>
  </form>

  <h2>Pesquisar no Cardápio</h2>
  <input type="text" id="searchInput" placeholder="Digite para pesquisar..." onkeyup="search()">

  <div id="menu">

    <h3>🍢 Entradas / Petiscos</h3>
    <ul>
      <li>Tábua de frios</li>
      <li>Amendoim temperado</li>
      <li>Torresmo pururuca</li>
      <li>Nachos com cheddar e bacon</li>
      <li>Batata rústica</li>
    </ul>

    <h3>🍺 Cervejas</h3>
    <ul>
      <li>Long Neck (variadas)</li>
      <li>Garrafa 600 ml</li>
      <li>Chopp Claro</li>
      <li>Chopp Escuro</li>
      <li>Cerveja Artesanal (IPA, Lager, Weiss)</li>
    </ul>

    <h3>🍹 Drinks Tradicionais</h3>
    <ul>
      <li>Caipirinha (vários sabores)</li>
      <li>Caipiroska</li>
      <li>Caipiríssima</li>
      <li>Mojito</li>
      <li>Margarita</li>
      <li>Cuba Libre</li>
      <li>Piña Colada</li>
      <li>Gin Tônica (várias versões)</li>
    </ul>

    <h3>🍸 Drinks Modernos</h3>
    <ul>
      <li>Gin com Frutas Vermelhas</li>
      <li>Gin Tropical (maracujá e hortelã)</li>
      <li>Moscow Mule</li>
      <li>Aperol Spritz</li>
      <li>Pink Lemonade com Vodka</li>
      <li>Caipirinha Frozen</li>
      <li>Caipirinha de Yakult</li>
    </ul>

    <h3>🥃 Destilados</h3>
    <ul>
      <li>Cachaça (premium e tradicional)</li>
      <li>Vodka</li>
      <li>Tequila</li>
      <li>Rum</li>
      <li>Whisky</li>
      <li>Gin</li>
    </ul>

    <h3>🧉 Bebidas Nacionais / Raiz</h3>
    <ul>
      <li>Catuaba</li>
      <li>Jurupinga</li>
      <li>Vinho Quente</li>
      <li>Quentão</li>
      <li>Capeta</li>
      <li>Batida de Coco</li>
      <li>Batida de Maracujá</li>
      <li>Batida de Amendoim</li>
    </ul>

    <h3>🍾 Coquetéis Clássicos</h3>
    <ul>
      <li>Sex on the Beach</li>
      <li>Cosmopolitan</li>
      <li>Tequila Sunrise</li>
      <li>Blue Lagoon</li>
      <li>Manhattan</li>
      <li>Negroni</li>
    </ul>

    <h3>🥤 Bebidas Sem Álcool</h3>
    <ul>
      <li>Refrigerantes</li>
      <li>Sucos Naturais</li>
      <li>Água com Gás</li>
      <li>Água sem Gás</li>
      <li>Energético</li>
      <li>Schweppes / Citrus</li>
      <li>Soda Italiana</li>
      <li>Chá Gelado</li>
    </ul>

    <h3>🍹 Mocktails (Sem Álcool)</h3>
    <ul>
      <li>Mojito Sem Álcool</li>
      <li>Piña Colada Sem Álcool</li>
      <li>Pink Lemonade</li>
      <li>Caipirinha de Frutas Sem Álcool</li>
      <li>Limonada Suíça</li>
    </ul>

    <h3>🍲 Comidas de Boteco Raiz</h3>
    <ul>
      <li>Calabresa Acebolada</li>
      <li>Feijão Tropeiro</li>
      <li>Feijoada (Porção Individual)</li>
      <li>Caldo de Feijão</li>
      <li>Caldo de Mandioca</li>
      <li>Caldo de Mocotó</li>
      <li>Camarão Alho e Óleo</li>
      <li>Torresmo com Alho</li>
      <li>Omelete de Boteco</li>
      <li>Ovos de Codorna Temperados</li>
    </ul>

    <h3>🌭 Sanduíches e Lanches</h3>
    <ul>
      <li>X-Salada</li>
      <li>X-Burger</li>
      <li>X-Tudo</li>
      <li>Bauru</li>
      <li>Pernil na Chapa</li>
      <li>Linguiça no Pão</li>
      <li>Cachorro Quente Simples</li>
      <li>Buraco Quente</li>
    </ul>

    <h3>🧀 Queijos e Frios</h3>
    <ul>
      <li>Tábua de Frios</li>
      <li>Queijo Coalho Grelhado</li>
      <li>Provolone à Milanesa</li>
    </ul>

    <h3>😋 Opções Diferentonas / Gourmet</h3>
    <ul>
      <li>Torresmo Caramelizado</li>
      <li>Dadinho de Tapioca</li>
      <li>Bolinho de Costela</li>
      <li>Mini Pastel de Costela</li>
      <li>Canoa de Batata Recheada</li>
      <li>Costelinha Barbecue</li>
    </ul>

    <h3>🫒 Aperitivos Simples</h3>
    <ul>
      <li>Amendoim Torrado</li>
      <li>Azeitonas Temperadas</li>
      <li>Pipoca Salgada</li>
      <li>Palitos de Cenoura e Pepino</li>
    </ul>

    <h3>🥩 Carnes e Porções</h3>
    <ul>
      <li>Filé Acebolado</li>
      <li>Contra-filé na Chapa</li>
      <li>Picanha Fatiada</li>
      <li>Costela no Bafo</li>
      <li>Torresmo de Rolo</li>
      <li>Torresmo Pururuca</li>
      <li>Linguiça Artesanal Acebolada</li>
      <li>Frango a Passarinho</li>
      <li>Moela ao Molho</li>
    </ul>

    <h3>🍢 Espetinhos e Grelhados</h3>
    <ul>
      <li>Espetinho de Carne</li>
      <li>Espetinho de Frango</li>
      <li>Espetinho de Coração</li>
      <li>Espetinho de Kafta</li>
      <li>Espetinho de Queijo Coalho com Mel</li>
    </ul>

    <h3>🍟 Petiscos Mais Pedidos</h3>
    <ul>
      <li>Batata Frita Crocante</li>
      <li>Mandioca Frita</li>
      <li>Polenta Frita</li>
      <li>Pastéis Sortidos</li>
      <li>Coxinha</li>
      <li>Bolinho de Carne</li>
      <li>Bolinho de Queijo</li>
      <li>Bolinho de Bacalhau</li>
      <li>Kibe Frito</li>
      <li>Isca de Frango</li>
      <li>Isca de Tilápia</li>
    </ul>

  </div>

  <script>
    function search() {
      let input = document.getElementById("searchInput").value.toLowerCase();
      let categorias = document.querySelectorAll("#menu h3");

      categorias.forEach(cat => {
        let ul = cat.nextElementSibling;
        let itens = ul.querySelectorAll("li");

        let temMatch = false;

        itens.forEach(item => {
          let texto = item.textContent.toLowerCase();

          if (texto.includes(input)) {
            item.style.display = "";
            temMatch = true;
          } else {
            item.style.display = "none";
          }
        });

        // Se nenhum item da categoria combina → esconder categoria inteira
        if (temMatch) {
          cat.classList.remove("oculto");
          ul.classList.remove("oculto");
        } else {
          cat.classList.add("oculto");
          ul.classList.add("oculto");
        }
      });
    }
  </script>


  <div class="container">
    <h1>Produtos</h1>
    <div class="Linha"></div>

    <div class="produtos">
      <?php if (empty($produtos)): ?>
        <p>Não há produtos cadastrados.</p>
      <?php else: ?>
        <?php foreach ($produtos as $produto): ?>
          <div class="produto">
            <img width="200" src="../assets/imgs/produtos/<?= htmlspecialchars($produto['imagem']); ?>"
              alt="<?= htmlspecialchars($produto['nome']); ?>">

            <h3><?= htmlspecialchars($produto['nome']); ?></h3>
            <p><?= htmlspecialchars($produto['descricao']); ?></p>
            <p>R$ <?= number_format((float) $produto['preco'], 2, ',', '.'); ?></p>
            <p>Status: <?= ucfirst(htmlspecialchars($produto['status'])); ?></p>

            <a href="editar-produto.php?id=<?= (int) $produto['id']; ?>">Editar</a>
            <a href="index.php?remover_id=<?= (int) $produto['id']; ?>"
              onclick="return confirm('Tem certeza que deseja remover este produto?')">Remover</a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <section class="counter-section">
      <div class="counter-box">
        <h2 class="counter" data-target="30">0</h2>
        <p>Total de produtos</p>
      </div>

      <div class="counter-box">
        <h2 class="counter" data-target="1500">0</h2>
        <p>Ativos</p>
      </div>

      <div class="counter-box">
        <h2 class="counter" data-target="25">0</h2>
        <p>Inativos</p>
      </div>

      
    </section>

    <script>
      const counters = document.querySelectorAll('.counter');

      counters.forEach(counter => {
        counter.innerText = "0";

        const updateCounter = () => {
          const target = +counter.getAttribute("data-target");
          const current = +counter.innerText;

          const increment = target / 200; // velocidade

          if (current < target) {
            counter.innerText = Math.ceil(current + increment);
            setTimeout(updateCounter, 10);
          } else {
            counter.innerText = target + "+";
          }
        };

        updateCounter();
      });
    </script>

  </div>

  <?php include '../../app/components/footer.php'; ?>

</body>

</html>