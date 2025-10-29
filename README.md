# 🍔 FastFood — PHP Puro

Projeto de cardápio/pedidos com **PHP puro**, organizado para **reaproveitar componentes** (header, footer, cards) e permitir rodar **DEMO estática** para aulas e apresentações, ou **modo dinâmico** (com banco e sessões) quando necessário.

## ▶️ Como rodar (DEV)

1) Tenha o PHP instalado.  
2) Na **raiz do projeto**, rode:

```bash
php -S localhost:3000 -t public
```

3) Acesse: http://localhost:3000

> Dica: sempre iniciar na **pasta raiz** para que os `include` relativos funcionem sem dor de cabeça.

---

## 🗂️ Estrutura de Pastas (sugerida)

```
fastfood/
├─ app/
│  ├─ config/
│  │  ├─ conexao.php        # conexão com MySQL (modo dinâmico)
│  │  └─ auth.php           # login, logout, guards (dinâmico)
│  └─ components/
│     ├─ header.php
│     └─ footer.php
│
├─ public/
│  ├─ index.php             # redireciona pelo tipo de usuario
│  ├─ login.php             # DEMO estático já pronto
│  ├─ criar-conta.php       # DEMO estático já pronto
│  ├─ perfil.php            # DEMO estático já pronto
│  ├─ usuario/
│  │  ├─ index.php          # home (cliente)
│  │  ├─ cardapio.php       # DEMO estático já pronto
│  │  ├─ carrinho.php       # DEMO estático já pronto
│  │  ├─ compras.php        # DEMO estático já pronto
│  │  └─ detalhes-pedido.php# DEMO estático já pronto
│  └─ admin/
│     ├─ index.php          # DEMO estático já pronto
│     ├─ vendas.php         # DEMO estático já pronto
│     ├─ detalhes-venda.php # DEMO estático já pronto
│     └─ editar-produto.php # DEMO estático já pronto
│
└─ assets/
   ├─ css/
   │  ├─ reset.css
   │  ├─ header.css
   │  ├─ footer.css
   │  ├─ home-usuario.css
   │  ├─ carrinho.css
   │  ├─ criar-conta.css
   │  ├─ login.css
   │  ├─ perfil.css
   │  ├─ editar-produto.css
   │  ├─ vendas.css
   │  └─ detalhes-venda.css
   ├─ imgs/
   │  └─ produtos/
   │     ├─ fake-donut.jpg
   │     └─ fake-milkshake.jpg

```

---

## 🧰 Modos de Execução

### 1) DEMO Estático (para aula/apresentação)
- As páginas **não** processam POST, não consultam DB e não dependem de sessão.
- Os **includes** (`conexao.php`, `auth.php`, `header.php`, `footer.php`) são **mantidos** para preservar a arquitetura.
- Itens e valores **fakes** (hardcoded) garantem previsibilidade.

### 2) Dinâmico (produção/treino avançado)
- Reative os trechos de validação, queries e actions.
- Garanta tables: `usuarios`, `produtos`, `carrinho`, `pedidos`, `itens_pedido`.
- Configure `app/config/conexao.php` com suas credenciais:

```php
<?php
$host = 'localhost';
$user = 'seu_usuario';
$pass = 'sua_senha';
$db   = 'fastfood';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die('Falha na conexão: ' . $conn->connect_error);
}
```
> Obs.: No modo dinâmico, reative também as validações/guards em cada página.


## 📄 Licença

Uso educacional. Faça bom proveito nas suas adaptações.
