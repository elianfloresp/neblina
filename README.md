# Neblina — Tema WordPress

Tema custom do site da **Editora Neblina**, uma editora que leva literatura e pensamento crítico brasileiros ao mercado espanhol.

Site bilíngue PT/ES sem plugin de tradução. Operação Brasil ↔ Espanha, com venda online de livros via WooCommerce (vendas apenas na Europa).

---

## 📋 Sobre o projeto

- **Cliente:** Editora Neblina (Rafael Farina, João Nunes Junior, Paulo Silva)
- **Prestadores:** Elian Flores (dev) + Luisa Rech (coord/estratégia)
- **Domínio final:** editorialneblina.com
- **Stack:** WordPress 6+ · WooCommerce · ACF (free) · Gulp + SASS · PHP 8+
- **Hospedagem:** a definir (Hostinger ou Pudim)

### Decisões arquiteturais principais

- **Bilíngue sem plugin** — rewrite rules próprias, campos ACF duplicados `_pt` / `_es`, função helper `neblina_field()` + array de UI em `inc/i18n.php`.
- **URLs com prefixo de idioma** — `/pt/livros/`, `/es/libros/`, com detecção automática por `Accept-Language` do navegador na raiz.
- **Textos institucionais fixos no código** — por decisão da editora (combinado em call de 21/10). Cliente só edita CPTs (Livros, Autores, Banners).
- **Vendas apenas Europa** — moeda única EUR; Brasil fica fora (importação/impostos).

---

## 🚀 Setup local

### Pré-requisitos

- XAMPP (Apache + MySQL + PHP 8+)
- Node.js 18+ (recomendado 20+)
- Git

### Passo a passo

```bash
# 1. Clona o repositório dentro de wp-content/themes/
cd C:\xampp\htdocs\neblina\wp-content\themes\
git clone https://github.com/elianfloresp/neblina.git

# 2. Entra no tema e instala dependências do Gulp
cd neblina
npm install

# 3. Compila SCSS -> CSS (ou roda o watch em paralelo durante dev)
npm run build        # one-shot
npm run watch        # fica observando mudanças
```

### WordPress + banco

1. Instala WordPress em `htdocs/neblina/`
2. Cria DB vazio no phpMyAdmin (`neblina_db`)
3. Roda o instalador (http://localhost/neblina/) com user `root` e senha vazia
4. **Admin → Aparência → Temas → Ativar "Neblina Editorial"**
5. **Admin → Configurações → Links permanentes → "Nome do post" → Salvar** (crítico pros `/pt/` e `/es/`)
6. **Admin → Plugins → Instalar "Advanced Custom Fields" (free) → Ativar**

Pronto, site acessível em `http://localhost/neblina/` (redireciona pra `/pt/` ou `/es/` conforme idioma do navegador).

---

## 📁 Estrutura

```
neblina/
├── style.css                     Metadados do tema (identificação pro WP)
├── functions.php                 Setup geral + requires
├── header.php                    Header global (logo, nav, botão ES/PT, controls)
├── footer.php                    Footer global (azul royal com til)
├── index.php                     Fallback
├── 404.php                       Página não encontrada
│
├── front-page.php                Home (banners + livros + manifesto + equipe)
├── page-catalogo.php             Listagem paginada de livros
├── page-autores.php              Lista de autores
├── page-sobre.php                Texto institucional (13 blocos, bilíngue)
├── page-equipe.php               Rafa, João, Paulo
├── page-contato.php              Email + Instagram + WhatsApp
│
├── single-livro.php              Ficha individual de livro
├── single-autor.php              Perfil individual de autor
│
├── inc/                          Lógica modular
│   ├── i18n.php                  Idioma, campos bilíngues, URLs localizadas, UI strings
│   ├── rewrite.php               Rewrite /pt/ e /es/, redirect por Accept-Language, cookie
│   ├── enqueue.php               CSS/JS + Google Fonts (vai sair quando fontes oficiais chegarem)
│   ├── cpts.php                  Registra Livro, Autor, Banner
│   └── acf-fields.php            Campos ACF registrados via PHP (Tabs PT/ES/Comum)
│
├── template-parts/               Partials reutilizáveis
│   ├── book-card.php             Card de livro (home + catálogo)
│   └── author-card.php           Card de autor (listagem)
│
├── styles/scss/                  SOURCE do CSS (editar aqui)
│   ├── _variables.scss           Paleta oficial + fontes + breakpoints
│   ├── _typography.scss          Escala tipográfica (papéis + tokens)
│   ├── global.scss               Reset, header, footer, componentes compartilhados
│   ├── home.scss                 Carrossel, manifesto, team-preview
│   ├── catalogo.scss, livro.scss, autores.scss, autor.scss,
│   ├── sobre.scss, equipe.scss, contato.scss
│
├── assets/
│   ├── css/                      CSS compilado pelo Gulp (não editar — commitado pra deploy sem build)
│   ├── js/
│   │   ├── main.js               Menu mobile + theme toggle
│   │   └── carousel.js           Carrossel de banners na home
│   └── images/
│       └── brand/                Logos (dark/light), símbolo do til, imagens de capa
│
├── gulpfile.js                   Build SASS → CSS (compressed + autoprefixer via clean-css)
├── package.json                  Dependências do build
└── README.md                     Este arquivo
```

---

## 🌐 Como funciona o bilíngue

### Lado do visitante

1. Chega em `localhost/neblina/` → PHP lê `Accept-Language` do navegador → redireciona pra `/pt/` ou `/es/`
2. Navega pelo site com URLs localizadas: `/pt/sobre/`, `/es/nosotros/`, `/pt/livros/dom-casmurro/`, etc.
3. Clica no botão **ES / PT** no header → vai pra URL equivalente no outro idioma + grava cookie `neblina_lang`
4. Em visitas futuras, o cookie > Accept-Language (respeita a escolha manual)

### Lado do desenvolvedor

**Nos templates PHP:**

```php
<h1><?php echo esc_html(neblina_field('titulo')); ?></h1>
<a href="<?php echo esc_url(neblina_url('livros')); ?>">
    <?php echo neblina_i('see_catalog'); ?>
</a>
```

- `neblina_field('titulo')` → busca `titulo_pt` ou `titulo_es` do ACF conforme idioma ativo
- `neblina_url('livros')` → monta `/pt/livros/` ou `/es/libros/` (slugs localizados)
- `neblina_i('see_catalog')` → traduz string de UI do array em `inc/i18n.php`

**Pra adicionar/trocar frase de UI:** edita `inc/i18n.php` → array `$strings`.

---

## 🔤 Sistema tipográfico

Fonte única de verdade: `styles/scss/_typography.scss`. Todo texto usa um **papel** da escala via `@include type(<papel>)`. Não declare `font-size`, `font-weight` ou `letter-spacing` soltos nos componentes.

### Escala

| Papel | Fonte | Tamanho (desktop → mobile) | Uso |
|---|---|---|---|
| `display-1` | display | 76 → 44px | Título de página |
| `display-2` | display | 56 → 34px | Título do livro |
| `headline` | display | 52 → 25px | Frase longa sobre imagem (banner) |
| `display-3` | display | 40 → 27px | Título de seção |
| `display-4` | display | 24 → 22px | Título de card (livro, autor, pessoa) |
| `title` | display | 20px | Subtítulo dentro do conteúdo ("Sobre o livro") |
| `quote` | literária, itálico | 46 → 30px | Manifesto |
| `lead` | literária, itálico | 28 → 20px | Frase de abertura de página, lead do autor |
| `excerpt` | literária, itálico | 20 → 18px | Trecho de livro, citação |
| `body-l` | texto | 20 → 18px | Parágrafo de abertura |
| `body` | texto | 17 → 16px | Texto corrido |
| `body-s` | texto | 15px | Descrição de card, texto de apoio |
| `ui` | texto, 500 | 14px | Navegação, links, botões |
| `caption` | texto | 13px | Notas, contadores, rodapé |
| `label` | texto, 600, MAIÚSCULAS | 12 → 11px | Rótulos e eyebrows |

### Regras

- **Pesos:** títulos sempre no peso display (500); texto 400; interface 500; negrito 600. Nada em 700.
- **Negrito:** só em rótulos (`label`) e em destaques que o cliente marcar no editor (`<strong>` sai em 600).
- **Itálico:** só no registro literário: manifesto, frase de abertura, trecho de livro e citação. No texto corrido, itálico serve para título de obra e palavra estrangeira (DM Sans itálico real, carregado). Nunca em interface.
- **Maiúsculas:** só no papel `label`, sempre com o mesmo espaçamento (.14em).
- **Exceções gráficas:** iniciais das fotos provisórias, o glifo ◐ do tema e o símbolo do til são elementos gráficos, fora da escala.

### Trocar a fonte

Mudar `$font-display` / `$font-body` / `$font-literary` em `_variables.scss` e o peso em `--weight-display` (`_typography.scss`), e ajustar os pesos carregados em `inc/enqueue.php`. O resto do site acompanha.

---

## 📚 Custom Post Types

Todos registrados em `inc/cpts.php`. Grupos ACF registrados em `inc/acf-fields.php` (via PHP, não via admin).

### Livro

| Campo | Tipo | Aba |
|---|---|---|
| `titulo_pt` / `titulo_es` | text | PT / ES |
| `sinopse_pt` / `sinopse_es` | wysiwyg | PT / ES |
| `excerto_pt` / `excerto_es` | wysiwyg | PT / ES |
| `metadados_tradutor_pt/es` | text | PT / ES |
| `metadados_formato_pt/es` | text | PT / ES |
| `link_compra_pt` / `link_compra_es` | url | PT / ES |
| `autor_relacionado` | post object → autor | Comum |
| `metadados_isbn` | text | Comum |
| `metadados_paginas` | number | Comum |
| `metadados_ano` | number | Comum |
| Capa | imagem destacada nativa | — |

### Autor

| Campo | Tipo | Aba |
|---|---|---|
| Nome | título do post | — |
| `genero_pt` / `genero_es` | text | PT / ES |
| `micro_pt` / `micro_es` | textarea | PT / ES |
| `bio_pt` / `bio_es` | wysiwyg | PT / ES |
| Retrato | imagem destacada nativa | — |

### Banner

| Campo | Tipo | Aba |
|---|---|---|
| `eyebrow_pt` / `eyebrow_es` | text | PT / ES |
| `titulo_pt` / `titulo_es` | textarea | PT / ES |
| `link_destino` | url | Comum |
| Imagem de fundo | imagem destacada nativa | — |
| Ordem | atributo "Ordem" nativo | — |

---

## 🛠️ Workflow de desenvolvimento

### Branches

- `main` → código estável, deploy em produção
- `dev` → integração contínua, QA antes de merge pra main
- `feature/<nome>` → nova funcionalidade (ex: `feature/checkout`, `feature/fontes-oficiais`)
- `fix/<nome>` → bugfix (ex: `fix/mobile-menu`)

### Fluxo

```bash
git checkout -b feature/minha-feature
# ... código ...
npm run build                      # compila CSS final
git add .
git commit -m "feat: adiciona X"
git push origin feature/minha-feature
# abre PR pra dev, review, merge
```

### Convenções de commit

Padrão simples inspirado em Conventional Commits:
- `feat:` nova funcionalidade
- `fix:` correção de bug
- `style:` CSS/visual
- `refactor:` reorganização sem mudança de comportamento
- `docs:` README ou comentários
- `chore:` build, deps, config

---

## 🚦 Status atual

- ✅ Esqueleto do tema + bilíngue funcional
- ✅ CPTs e grupos ACF criados
- ✅ 8 page templates + 2 template-parts
- ✅ Bilíngue PT/ES com switch funcionando em todas rotas
- ✅ Símbolo oficial do til no nav/manifesto/footer
- ✅ Sistema tipográfico com escala e regras documentadas
- 🔴 Fontes oficiais bloqueadas: a Mundial Narrow comprada tem licença **desktop** (proíbe `@font-face`) e a Atlantix SSi veio de agregador sem licença. Falta licença webfont.
- 🟡 Checkout WooCommerce (EUR only) em configuração
- 🟡 Favicon a trocar pelo símbolo oficial
- 🔴 Deploy pendente de contratação de hospedagem pelo cliente

**Prazo:** 12-13/11/2026.

---

## 📞 Comunicação

- Decisões ficam em commits + PRs
- Reuniões com cliente têm transcrição salva em local separado
- Dúvidas de implementação: discussão em PR
