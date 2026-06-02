<div align="center">
  <h1>Água Inox</h1>
</div>

O projeto consistiu no desenvolvimento de um site institucional moderno e responsivo para a marca Águia Inox. Nesse site é apresentado sobre a história, produtos, postagens, contato, orçamento e etc.
  
---

## Índice

- [Sobre](#sobre)
- [Visualização](#visualizacao)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Arquitetura do Projeto](#arquitetura-do-projeto)
- [Como Executar o Projeto](#como-executar-o-projeto)

---

<h2 id="sobre">Sobre:</h2>

Através do painel de gerenciamento (manager), é possível:

- Gerenciar conteúdos da Home
- Configurar SEO para cada página
- Atualizar dados gerais
- Gerenciar conteúdos Institucional
- Gerenciar produtos, segmentos e opcionais
- Gerenciar downloads
- Gerenciar news
- Gerenciar conteúdos da página de 'Trabalhe Conosco'
- Visualizar emails de contato e gerenciar conteúdo desta página
- Alterar a política de privacidade
- Alterar conteúdos para cada idioma, sendo eles: espanhol e português - BR


E através do site para o público:

- Visualizar as páginas:
    - **Home**: apresenta um pouco sobre os cases, segmentos, clientes, sobre resumido, news destaques e formulários para ser um associado
    - **Empresa**: dividido em segmentos em que falam um pouco sobre a história, selos, missão, visão e valores e diferenciais
    - **Produtos**: separada em segmentos, cada segmento apresenta um resumo sobre ele e os produtos relacionados
    - **News**: posts escritos com eventos, cases, produtos e recursos humanos
    - **Contato**: formulário para envio de email de contato, apresenta informações relacionadas à localidade e demais informações de contato
    - **Trabalhe Conosco**: site externo + video com depoimentos de colaboradores
    - **Solicite um orçamento**: formulário para envio de email

---


<h2 id="visualizacao">Visualização:</h2>

<img width="400" alt="image home" src="https://github.com/user-attachments/assets/319e62ec-f77f-47b4-8f7b-cb000b0533a7" />
<img width="400" alt="image produtos" src="https://github.com/user-attachments/assets/9d6483f7-1b23-4884-825d-0a59460a40ae" />
<img width="400" alt="image news" src="https://github.com/user-attachments/assets/730ffd21-5eda-492a-89c6-2eff5c3bcc2f" />
<img width="400" alt="image contato" src="https://github.com/user-attachments/assets/03057c11-5b25-456d-ad1f-b20e7929b13e" />
<img width="400" alt="image trabalhe conosco" src="https://github.com/user-attachments/assets/9709b281-6862-46e3-8ed0-06f4126e8a12" />
<img width="400" alt="image orçamento" src="https://github.com/user-attachments/assets/ecd058c8-dff0-4c42-9202-288e1c721375" />

---

<h2 id="tecnologias-utilizadas">Tecnologias Utilizadas:</h2>

### Back-end:
- **Laravel (^12.0)**: framework PHP para construção do projeto, gerenciamento de rotas, autenticação e etc.
- **PHP (^8.2)**: linguagem de desenvolvimento
- **Laravel Sanctum (^4.0)**: autenticação e proteção de rotas
- **Inertia.js (^2.0)**: integração entre backend Laravel e frontend React sem necessidade de API tradicional
- **Laravel Localization (^2.2)**: gerenciamennto de idiomas e rotas traduzidas
- **Ziggy (^2.0)**: compartilhamento de rotas Laravel diretamente no frontend React
- **Laravel Tinker (^2.10.1)**: ferramenta para testes e execução de comandos no ambiente
- **Laravel PT-BR Validator (*)**: validações adaptadas para formato brasileiro

### Front-end:
- **React (^18.2.0)**: biblioteca para construção de interfaces
- **React DOM (^18.2.0)**: renderização de componentes React no navegador
- **Inertia React (^2.0.0)**: integração entre Laravel e React sem necessidade de API REST tradicional
- **Vite (^6.2.4)**: ferramenta de build e desenvolvimento rápido
- **Laravel Vite Plugin (^1.2.0)**: integração entre Laravel e Vite
- **Tailwind CSS (^3.2.1)**: framework para estilização
- **Tailwind Forms (^0.5.3)**: plugin para estilização consistente de formulários
- **Tailwind Vite (^4.0.0)**: integração entre Tailwind e Vite
- **PostCSS (^8.4.31)**: processador de CSS usado junto do Tailwind
- **Autoprefixer (^10.4.12)**: adiciona prefixos CSS automaticamente

### UI e experiência do usuário:
- **Font Awesome React (^0.2.2)**: biblioteca de ícones
- **Font Awesome Free Solid Icons (^6.7.2)**: conjunto de ícones sólidos
- **Lucide React (^0.525.0)**: biblioteca moderna de ícones
- **Headless UI (^2.0.0)**: componentes acessíveis e sem estilos pré-definidos
- **Radix UI**: componentes:
  - **Checkbox (^1.3.2)**
  - **Label (^2.1.7)**
  - **Scroll Area (^1.2.9)**
  - **Select (^2.2.5)**
  - **Separator (^1.1.7)**
- **Swiper (^11.2.10)**: criação de sliders e carrosséis
- **Embla Carousel React (^8.6.0)**: sistema de carrosséis leve e customizável
- **Embla Carousel Autoplay (^8.6.0)**: autoplay para Embla
- **Gsap (^3.13.0)**: biblioteca para animações
- **Lenis (^1.3.8)**: implementação de scroll suave
- **PhotoSwipe (^5.4.4)**: galeria de imagens responsiva
- **React Select (^5.10.2)**: select customizado
- **React Tag Input (^6.10.6)**: gerenciamento e criação de tags
- **Class Variance Authority (CVA) (^0.7.1)**: gerenciamento de variantes de componentes
- **Tailwind Merge (^3.3.1)**: combinação de classes Tailwind

### Formulários e manipulação de dados:
- **React Input Mask (^2.0.4)**: máscaras para inputs como CPF e telefones
- **React SortableJS (^6.1.4)**: drag and drop para ordenação de elementos

### Upload e manipulação de arquivos:
- **React Image Crop (^11.0.10)**: recorte de imagens no navegador

### Editor de texto:
- **Tiptap React (^2.24.0)**: editor de texto altamente customizável
- Extensões utilizadas:
    - **Link**: gerenciamento de links
    - **Underline**: sublinhado no texto
    - **Table**: criação de tabelas
    - **Table Row**: gerenciamento de linhas
    - **Table Header**: cabeçalhos de tabelas
    - **Table Cell**: células de tabelas
    - **List Item**: manipulação de listas
    - **Text**: manipulação de texto
    - **Figure Extension (^1.0.11)**: suporte a figuras
    - **Starter Kit**: funcionalidades básicas do editor
 

---

<h2 id="arquitetura-do-projeto">Arquitetura principal do Projeto:</h2>

```bash
Pizzato-2025
│
├── app
│   ├── Http
│   │   ├── Controllers    # Controladores responsáveis pelas requisições e retornar respostas (separado por Manager)
│   │   ├── Middleware     # Interceptação, autenticação e tratamento de requisições
│   │   ├── Requests       # Validação e autorização de formulários e requisições (separado por Manager)
│   │   ├── helpers.php    # Auxiliares globais utilizados no projeto
│   ├── Models             # Representação das tabelas do banco (Eloquent)
│   ├── Providers          # Configuração de pacotes
│   ├── Services           # Regras de negócio
├── bootstrap              # Inicialização do framework
├── config                 # Arquivos de configuração
├── database               # Migrations, seeds e factories
├── public                 # Diretório público acessível pelo navegador
│   ├── admin              # Arquivos relacionados ao Manager
│   ├── content            # Arquivos relacionados as páginas e gerenciáveis pelo Manager
│   ├── site               # Arquivos do site institucional
├── resources              # Frontend e recursos
│   ├── css                # Estilização 
│   ├── js                 # Componentes, páginas, hooks e layouts (separados por Manager)
│   ├── views              # Templates e views do Laravel/Inertia
├── routes                 # Definição das rotas web e Manager
├── storage                # Arquivos gerados (logs, cache e etc.)
├── tests
│

```

---

<h2 id="como-executar-o-projeto">Como Executar o Projeto:</h2>

1. Clone o repositório:

```bash
git clone https://github.com/Octal-web/Aguia-Inox.git
cd Aguia-Inox
```

2. Instale as dependências do Front-end:

```bash
npm install
```

3. Instale as dependências do Back-end:

```bash
composer install
```

4. Configure o ambiente

Crie o arquivo .env:

```bash
cp .env.example .env
```

Gere a chave da aplicação:
```bash
php artisan key:generate
```

Configure o banco de dados SQL e preencha com o acesso no .env

5. Rode o projeto:
```bash
npm run dev
php artisan serve
```


