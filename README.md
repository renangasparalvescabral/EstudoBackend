# EstudoBackend

Projeto de estudo de desenvolvimento web backend, evoluindo progressivamente de manipulação do DOM com JavaScript até autenticação de usuários com PHP, MySQL e sessões.

## Tecnologias

- HTML5, CSS3, JavaScript (ES6+)
- PHP 8+
- MySQL / PDO
- LocalStorage API
- Fetch API (AJAX)

## Pré-requisitos

- [XAMPP](https://www.apachefriends.org/) (ou qualquer servidor com PHP 8+ e MySQL)
- Projeto deve estar dentro de `htdocs` para funcionar corretamente

## Como rodar

1. Clone o repositório dentro da pasta `htdocs` do XAMPP:
   ```bash
   git clone https://github.com/seu-usuario/EstudoBackend.git
   ```

2. Inicie o Apache e o MySQL pelo painel do XAMPP.

3. Para os dias 7 e 8, crie o banco de dados executando o script SQL:
   - Acesse `http://localhost/phpmyadmin`
   - Importe ou execute o arquivo `Dia-7/database.sql`

4. Acesse pelo navegador: `http://localhost/EstudoBackend/`

---

## Estrutura do Projeto

```
EstudoBackend/
├── dia2.html         # Dia 2 - DOM e manipulação de classes
├── dia3.html         # Dia 3 - CSS com JavaScript
├── Dia-4/            # Dia 4 - AJAX e API PHP
├── Dia-5/            # Dia 5 - LocalStorage
├── Dia-6/            # Dia 6 - Formulários e validação PHP
├── Dia-7/            # Dia 7 - MySQL com PDO (CRUD)
└── Dia-8/            # Dia 8 - Autenticação com sessões
```

---

## Dias

### Dia 2 — Manipulação do DOM
**Arquivo:** `dia2.html`

Conceitos praticados:
- Seleção de elementos com `querySelector`
- Manipulação de classes com `classList` (add, remove, toggle)
- Validação básica de formulário com feedback visual
- Alternância de tema claro/escuro

---

### Dia 3 — CSS com JavaScript
**Arquivo:** `dia3.html`

Conceitos praticados:
- Adição e remoção de classes CSS via JavaScript
- Transições CSS (`transition`) ativadas por eventos
- Feedback de estado para o usuário via DOM

---

### Dia 4 — AJAX e API com PHP
**Pasta:** `Dia-4/`

Conceitos praticados:
- Requisições assíncronas com `fetch` e `async/await`
- API em PHP que recebe e responde JSON
- Cabeçalhos CORS e tratamento de métodos HTTP
- Prepared statements e filtragem de dados no backend
- Exibição dinâmica de resultados sem recarregar a página

**Arquivos:**
| Arquivo | Descrição |
|---|---|
| `index.html` | Interface de busca de produtos |
| `main.js` | Lógica de requisição e exibição dos resultados |
| `api.php` | Endpoint PHP que processa a busca e retorna JSON |
| `style.css` | Estilos da página |

---

### Dia 5 — LocalStorage
**Pasta:** `Dia-5/`

Conceitos praticados:
- Leitura e escrita no `localStorage`
- Persistência de dados entre sessões do navegador
- Lista de tarefas com adição, conclusão e exclusão
- Contador de visitas persistido localmente
- Alternância de tema salvo no navegador

**Arquivos:**
| Arquivo | Descrição |
|---|---|
| `index.html` | Interface da lista de tarefas |
| `main.js` | Toda a lógica de localStorage e renderização |
| `style.css` | Estilos com suporte a tema claro/escuro |

---

### Dia 6 — Formulários e Validação Server-Side
**Pasta:** `Dia-6/`

Conceitos praticados:
- Recebimento de dados com `$_POST`
- Verificação de método HTTP com `$_SERVER['REQUEST_METHOD']`
- Sanitização com `trim()` e `htmlspecialchars()`
- Validação: campos obrigatórios, email, faixa numérica, confirmação de senha
- Manutenção dos valores preenchidos após erro de validação

**Arquivos:**
| Arquivo | Descrição |
|---|---|
| `index.php` | Formulário com validação completa server-side |
| `style.css` | Estilos do formulário e mensagens de erro |

---

### Dia 7 — MySQL com PDO (CRUD completo)
**Pasta:** `Dia-7/`

Conceitos praticados:
- Conexão com MySQL usando PDO
- Prepared Statements para prevenir SQL Injection
- Operações CRUD: Create, Read, Update, Delete
- Hash seguro de senha com `password_hash()`
- Verificação de duplicidade de email
- Redirecionamento após ações (`header('Location: ...')`)

**Arquivos:**
| Arquivo | Descrição |
|---|---|
| `database.sql` | Script de criação do banco e tabela |
| `config.php` | Conexão PDO com singleton e helper `e()` |
| `index.php` | Formulário de cadastro |
| `listar.php` | Listagem de todos os usuários |
| `editar.php` | Formulário de edição de usuário |
| `excluir.php` | Exclusão de usuário com redirecionamento |
| `style.css` | Estilos compartilhados |

**Para rodar:** execute `database.sql` no phpMyAdmin antes de acessar.

---

### Dia 8 — Autenticação com Sessões
**Pasta:** `Dia-8/`

Conceitos praticados:
- Sessões PHP com `$_SESSION`
- Login com `password_verify()`
- Proteção de páginas com `exigirLogin()`
- Regeneração de ID de sessão no login para prevenir Session Fixation
- Logout com destruição completa da sessão e cookie
- Redirecionamento pós-login para a página original solicitada
- Edição de perfil com troca opcional de senha

**Arquivos:**
| Arquivo | Descrição |
|---|---|
| `config.php` | Configuração da conexão e início de sessão |
| `auth.php` | Funções de autenticação (login, logout, registrar) |
| `index.php` | Página inicial pública |
| `login.php` | Formulário de login |
| `registrar.php` | Formulário de criação de conta |
| `dashboard.php` | Área restrita (exige login) |
| `perfil.php` | Edição de perfil e troca de senha |
| `logout.php` | Encerra a sessão e redireciona |
| `style.css` | Estilos compartilhados |

**Para rodar:** requer o banco criado no Dia 7 (`database.sql`).

---

## Segurança implementada

| Proteção | Onde |
|---|---|
| Prevenção de XSS | `htmlspecialchars()` em todas as saídas PHP |
| Prevenção de SQL Injection | PDO com Prepared Statements (Dia 7 e 8) |
| Senhas seguras | `password_hash()` / `password_verify()` |
| Session Fixation | `session_regenerate_id(true)` no login |
| Validação de entrada | Server-side em todos os formulários |

---

## Aprendizados por área

**JavaScript:**
- DOM, eventos, classList, fetch, async/await, LocalStorage

**PHP:**
- Formulários POST, validação, sanitização, sessões, PDO

**MySQL:**
- Modelagem de tabela, CRUD com PDO, índices, charset utf8mb4

**Segurança Web:**
- XSS, SQL Injection, Session Fixation, hash de senhas
