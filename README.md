<p align="center">
  <img src="public/logo.png" alt="[translate:FlowToDo Logo]" width="150" />
</p>

# FlowToDo

FlowToDo é uma aplicação web moderna, minimalista e responsiva para gestão de tarefas. Seu objetivo é ajudar pessoas a se manterem organizadas, reduzindo o estresse e trazendo leveza à rotina.

---

## Visão Geral

FlowToDo proporciona:

- Cadastro e organização inteligente de tipos de tarefas, com ativação e desativação dinâmicas
- Navegação dinâmica com menus que refletem os tipos de tarefas ativos, disponíveis globalmente em todas as páginas
- Cadastro e organização inteligente de tarefas diárias vinculadas aos tipos
- Definição de prioridade e data de vencimento
- Filtros inteligentes por estado, prioridade, vencimento e tipo de tarefa, utilizando componentes reutilizáveis personalizados (Input, Select, Label)
- Marcação rápida de tarefas concluídas com apenas um clique, por meio de botões de ação dinâmicos para cada status
- Interface adaptada para desktop, tablet e mobile, sempre clara e intuitiva

---

## Tecnologias

- **Backend:** Laravel 12 (PHP)
- **Frontend:** Vue.js (com Inertia.js)
- **Estilização:** Tailwind CSS, com componentes UI customizados reutilizáveis para formulários e botões
- **Base de dados:** MySQL
- **Monitoramento:** Laravel Telescope
- **Helpers globais:** `app/Helpers/helpers.php`
- **Gerenciamento de rotas no frontend:** Ziggy
- **Comunicação HTTP:** Axios para chamadas HTTP com suporte automático a tokens CSRF e cookies de sessão

---

## Estrutura do Projeto

- `app/` – Lógica backend e Models  
- `resources/js/` – Aplicação Vue (páginas e componentes)  
- `public/` – Arquivos estáticos e imagens  
- `routes/` – Definição de rotas Laravel  
- `database/` – Migrations, factories e seeders  

---

## Arquitetura e Funcionalidades

### Backend — Laravel 12

O backend do FlowToDo é desenvolvido com Laravel 12, seguindo o padrão arquitetural **MVC**, o que proporciona organização, escalabilidade e facilidade de manutenção.

### Controladores principais

- **CreateNewUser (App\Actions\Fortify):**  
  Responsável pela validação de entrada e criação de novos usuários no banco de dados, upload seguro da foto de perfil no disco público e registro detalhado de atividades usando o sistema *ActivityLogger*, que alimenta os logs de auditoria.

- **ActivityLogController (App\Http\Controllers\Admin):**  
  Controlador dedicado à exibição e filtragem de logs de atividades do sistema. Suporta múltiplos filtros (nome do log, tipo de evento e usuário causador) e implementa paginação via Inertia.js, garantindo respostas rápidas e atualizadas.

- **ProfileController (App\Http\Controllers\Settings):**  
  Oferece funcionalidades para visualização e edição do perfil do usuário. Garante upload seguro de fotos, remoção da imagem antiga, validação dos campos com feedback ao usuário e registro detalhado das alterações no sistema de log.

- **DashboardController:**  
  Utiliza cache para entregar uma lista otimizada e atualizada de tarefas pendentes e em andamento do usuário autenticado. Essa abordagem melhora significativamente a performance da dashboard.

- **TaskTypeController:**
  Gerencia o CRUD dos tipos de tarefas, controle de ativação/desativação e disponibiliza os dados globalmente para o frontend via Inertia share, permitindo menus dinâmicos e filtragem das tarefas por tipo.

- **TaskController:**  
  Implementa operações CRUD (Create, Read, Update, Delete) para o gerenciamento de tarefas. Suporta filtros detalhados (nome da tarefa, status, prioridade, data de vencimento) e aplica regras de negócio e políticas para garantir acesso seguro e validação dos dados enviados.

### Modelos principais

- **User:**  
  Representa os usuários da aplicação com suporte ao Laravel Fortify (autenticação, verificação por e-mail e autenticação em dois fatores). Relaciona-se com as tarefas e registros de atividades. Inclui o atributo booleano `is_admin` para controle de permissões.

- **TaskType:**
  Modelo que representa os tipos ou categorias de tarefas, com atributos como nome e campo booleano ativo para controle de disponibilidade no sistema. Relaciona-se com tarefas e é carregado globalmente para dinamicidade no menu lateral.

- **Task:**  
  Modelo de tarefa vinculado a um usuário. Integra-se com o pacote *Spatie* para registro automatizado e seletivo de alterações importantes (ex.: título, status, prioridade, data). Permite histórico detalhado das operações.

- **ActivityLog:**  
  Representa os logs gerados, estendendo a funcionalidade do pacote *Spatie*. Possui vínculos morfológicos com o sujeito da ação e seu responsável, armazenando detalhes de eventos para auditoria e monitoramento.

### Autorização e Controle de Acesso

As *Policies* do Laravel asseguram permissões granulares para ações em tarefas e logs, limitando usuários comuns a seus próprios dados e concedendo acesso irrestrito a administradores. *Middleware* controla autenticação e verificação de e-mail em rotas protegidas.

---

## Configurações Essenciais

### Activity Logger

As configurações permitem ativar ou desativar o registro de atividades via variável de ambiente `ACTIVITY_LOGGER_ENABLED`. Há também uma rotina automatizada para exclusão de logs antigos (configurada para 365 dias). O armazenamento ocorre em uma tabela customizada ligada ao modelo `ActivityLog`.

### Sistema de Arquivos

O sistema de arquivos utiliza o disco local para armazenar imagens públicas e privadas, com possibilidade de integração opcional com AWS S3, configurável via `.env`.

### Monitoramento com Laravel Telescope

Ativado apenas em ambiente de desenvolvimento local, o Telescope é configurado para ocultar dados sensíveis e possui controle de acesso restrito via *gates* e *middleware*. Monitora detalhadamente requisições, jobs, eventos, consultas e outras métricas importantes.

### Variáveis de Ambiente

O arquivo `.env` contém variáveis que controlam ambiente, banco de dados, sessão, cache, filas, armazenamento, e-mail, logging e configuração de ferramentas auxiliares.

---

## Sistema de Rotas e Integração Frontend–Backend

As rotas Laravel são definidas em arquivos convencionais (`web.php`, `settings.php`), organizadas em grupos que aplicam *middleware* de autorização e autenticação.

O projeto utiliza **Ziggy** para exportar rotas nomeadas do backend para o JavaScript do frontend, permitindo o uso seguro de rotas Laravel nas interfaces Vue. Isso elimina a necessidade de URLs fixas (*hardcoded*), tornando as rotas tipadas e flexíveis.

### Métodos comuns

- **GET** – Listagem (index), exibição (show), criação (create), edição (edit)  
- **POST** – Criação (store)  
- **PUT/PATCH** – Atualização (update)  
- **DELETE** – Remoção (destroy)

**Exemplos:**

- Criação de usuário: `/register` (POST)  
- Lista de tarefas: `/tasks` (GET)  
- Edição de perfil: `/settings/profile` (GET)

Nem todas as rotas Laravel são expostas via Ziggy — apenas as necessárias para consumo no frontend.

---

## Frontend e Funcionalidades de Tarefas

- Baseado em **Vue 3** e **Composition API**, com **Inertia.js** facilitando a comunicação reativa e comportamento de SPA real.
- Componentes de UI reutilizáveis e responsivos estilizados com **Tailwind CSS**, incluindo botões, inputs, selects, labels e cards estruturados para máxima clareza e acessibilidade.
- Layouts específicos, como `AppLayout`, `AuthLayout` e `SettingsLayout`, estruturam as páginas principais e áreas funcionais.
- A listagem de tarefas apresenta cartões com detalhes resumidos (título, descrição truncada, tipo, status, prioridade e vencimento formatado).
- Filtros dinâmicos permitem buscas refinadas com atualização em tempo real, preservando o estado e os links amigáveis.
- Botões de ação facilitam mudanças rápidas de status das tarefas, com integração direta ao backend.

---

## Frontend — Axios para requisições HTTP seguras

Para garantir a comunicação segura com o backend, especialmente em chamadas que alteram dados (PUT, PATCH, DELETE), a aplicação utiliza o **axios** no frontend para enviar as requisições HTTP. O axios automaticamente utiliza o token CSRF gerado pelo Laravel (disponível na meta tag) e envia junto os cookies de sessão, prevenindo erros comuns como o “CSRF token mismatch” (Erro 419).

Exemplo de uso para ativar ou desativar o status de um tipo de tarefa:

```
import axios from 'axios';

function toggleAtivo(tipo: any) {
axios.patch(/task-types/${tipo.id}/toggle-ativo)
.then(response => {
tipo.ativo = response.data.ativo;
})
.catch(error => {
console.error('Erro ao ativar/desativar tipo:', error);
alert('Erro ao alterar status. Tente novamente.');
});
}
```

Para instalar o axios e garantir seu funcionamento:

```
npm install axios
```
---

## Instalação e Uso

1. Clone este repositório:

```
git clone https://github.com/seu-usuario/flowtodo.git
cd flowtodo
```

2. Instale dependências backend e frontend:

```
composer install
npm install
```

3. Crie e configure o arquivo .env com as credenciais do banco de dados e a URL da aplicação:

```
APP_URL=http://flowtodo.test
DB_DATABASE=flowtodo
DB_USERNAME=root
DB_PASSWORD=

```

4. Execute as migrations:

```
php artisan migrate
```

5. Compile os assets do frontend:

```
npm run dev
```

6. Inicie o servidor local (Caso nao use o herd):

```
php artisan serve
```

7. Acesse em http://localhost:8000 ou no domínio configurado no .env.

---

## Acessibilidade

Todas as páginas principais do FlowToDo foram avaliadas individualmente usando ferramentas automáticas de validação de acessibilidade (como **Wave**, **AIM Score**, **Lighthouse**). Quase todas as páginas atingiram pontuação máxima (**10/10**), e as demais estão acima de **9.8/10**, em total alinhamento com as diretrizes **WCAG**.

- Foram corrigidos problemas de contraste, hierarquia de títulos, estrutura semântica, labels de formulários e alternativas para imagens.
- Todos os alertas detectados (como “redundant link” e “redundant title text”) foram revisados e corrigidos sempre que possível, priorizando navegação por teclado e uso por leitores de tela.
- O sistema garante navegação fluida, estrutura lógica e clareza para usuários de tecnologias assistivas em todas as telas.

A análise de acessibilidade foi realizada **página por página**, garantindo um ambiente seguro, simples e inclusivo.

> **Resumo:**  
> FlowToDo foi desenvolvido com foco em acessibilidade, usabilidade e conformidade técnica moderna, proporcionando uma experiência acessível para todos os usuários.

---

## Personalizações no Frontend

- Inclusão de componente `Select.vue` customizado, estilizado igual ao componente `Input.vue`, para melhor consistência visual nos formulários
- Implementação de filtros avançados na listagem de tarefas com componentes `Input`, `Select` e `Label`
- Botões dinâmicos para gerenciamento rápido do status das tarefas dentro de Cards, com opções para iniciar, concluir, cancelar, desfazer ações e visualizar detalhes
- Melhorias na visualização de datas com formato `d-m-Y`
- Paginação que preserva os filtros aplicados para navegação contínua e intuitiva
- Os links para os tipos de tarefa no menu direcionam para a lista de tarefas filtrada pelo tipo, utilizando URLs com query string, por exemplo: /tasks?type=3. Isso permite que o usuário veja rapidamente as tarefas daquele tipo
- O método Inertia::share é usado no backend para compartilhar dados comuns, como os tipos de tarefa, para que menus e componentes reativos possam consumir estes dados facilmente

---

## Rotas e Ziggy

- Rotas Laravel definidas em arquivos convencionais (web.php, settings.php).
- O Ziggy exporta rotas nomeadas essenciais para o frontend via JavaScript, facilitando uso dinâmico e seguro das URLs.
- Rotas principais: dashboard, tasks (CRUD), logs administrativos, perfil e configurações.
- Nem todas rotas são expostas via Ziggy, somente as usadas no frontend Vue para evitar vazamento desnecessário.

### Instalação

Se necessário, instale com:

```
composer require tightenco/ziggy
```

E publique as configurações (opcional):

```
php artisan ziggy:generate
```

No arquivo app.blade.php, o Ziggy é carregado automaticamente via:

```
@routes
```

Com isso, é possível usar as rotas Laravel dentro do Vue sem precisar definir URLs manualmente.

---

## Helpers Globais

O projeto conta com um arquivo de helpers globais em app/Helpers/helpers.php, permitindo a criação de funções reutilizáveis acessíveis em todo o sistema.

Essas funções são carregadas automaticamente via composer.json, na seção "autoload", e recompiladas com:

```
composer dump-autoload
```
---

## Monitoramento em Desenvolvimento com Laravel Telescope

O projeto já está configurado para uso com o Laravel Telescope, ferramenta que auxilia no monitoramento de requisições, queries SQL, autenticação e eventos durante o desenvolvimento.

- Facilite o debug de problemas, gargalos de performance e eventos importantes.
- Veja todos os detalhes de requests, logs, queries e métricas relevantes em uma interface intuitiva acessando http://localhost:8000/telescope (apenas em ambiente local).


### Instalação (caso não esteja ativa)

```
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

```

Acesse em:

Acesse o painel do Telescope em http://localhost:8000/telescope ou seusite.test/telescope.


> Recomendado manter ativado apenas em ambiente local para segurança e performance.

---

## Seeds e Factories

- UserFactory: cria usuários de teste com dados realistas (nome, e-mail único, bio, foto de perfil simulada, status, etc). Suporta criação de administradores e usuários comuns.
- TaskTypeFactory: gera tipos de tarefa com atributos como nome e status (ativo/inativo), garantindo testes consistentes para funcionalidades relacionadas a categorias de tarefas.
- TaskFactory: gera tarefas associadas aos usuários, com variações de status, prioridade e datas coerentes.
- ActivityLogFactory: cria registros de atividade simulados para auditoria.
- DatabaseSeeder: apaga administradores duplicados, gera novos admins fixos e usuários comuns com suas respectivas tarefas e logs.

---

## Explicação Detalhada dos Testes

### Autenticação

**login screen can be rendered**  
Verifica se a página de login é carregada sem erros (status HTTP 200), garantindo acesso inicial ao sistema.

**users can authenticate using the login screen**  
Testa o fluxo de login com credenciais válidas, assegurando autenticação do usuário e redirecionamento correto para a dashboard.  
Exemplo de código (Pest):  

```
test('users can authenticate using the login screen', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
```

**users with two factor enabled are redirected to two factor challenge**  
Assegura que usuários com autenticação em dois fatores avançam corretamente para o desafio 2FA após o login, e que seu estado é preservado na sessão.

**users can not authenticate with invalid password**  
Garante que tentativas de login com senha errada não autenticam o usuário, prevenindo acessos indevidos.

**users can logout**  
Testa processo de logout, garantindo término correto da sessão e redirecionamento para home.

**users are rate limited**  
Simula múltiplas tentativas de login em curto tempo e verifica se o sistema bloqueia novas tentativas, prevenindo ataques de força bruta.

---

### Verificação de Email

**email verification screen can be rendered**  
Verifica se a tela que avisa o usuário para verificar o e-mail é exibida.

**email can be verified**  
Testa o processo de verificação via link temporário, confirmando o evento e atualização correta no banco.

**email is not verified with invalid hash**  
Garante que links de verificação inválidos não confirmam e-mail, assegurando segurança.

**email is not verified with invalid user id**  
Similar ao anterior, evita verificação incorreta para usuários errados.

**verified user is redirected to dashboard from verification prompt**  
Um usuário já verificado não vê a tela de verificação, sendo redirecionado direto à dashboard.

**already verified user visiting verification link is redirected without firing event again**  
Evita reprocessar verificação para usuários já confirmados.

---

### Confirmação de Senha e Redefinição

**confirm password screen can be rendered**  
Verifica se a tela para confirmação de senha é acessível para usuários autenticados.

**password confirmation requires authentication**  
Garante que a página de confirmação não seja acessível sem autenticação.

**reset password link screen can be rendered**  
Testa visualização da página para solicitar link de redefinição de senha.

**reset password link can be requested**  
Garante que um e-mail de redefinição seja enviado corretamente.

**password can be reset with valid token**  
Valida processo completo de redefinição de senha com token válido.

**password cannot be reset with invalid token**  
Impede redefinição de senha com tokens inválidos ou expirados.

---

### Cadastro e Configurações de Usuário

**registration screen can be rendered**  
Testa se a página de registro fica disponível para novos usuários.

**new users can register**  
Verifica que o fluxo de registro cria contas novas com dados válidos e autentica o usuário após.

**profile page is displayed**  
Confirma o carregamento da página de edição de perfil para usuários autenticados.

**profile information can be updated**  
Assegura que alterações no perfil são salvas e refletidas corretamente.

**user can delete their account**  
Testa remoção de conta exigindo senha correta, invalida sessão, e remove usuário do banco.

---

### Autenticação em Dois Fatores (2FA)

**two factor settings page can be rendered**  
Testa se o usuário pode acessar a página de configurações de autenticação multifator.

**two factor settings page requires password confirmation when enabled**  
Confirma que, com 2FA ativado, certas páginas exigem confirmação re-autenticação.

**two factor settings page does not requires password confirmation when disabled**  
Confirma permissões sem a necessidade de autenticação adicional se 2FA estiver desativado.

**two factor settings page returns forbidden response when two factor is disabled**  
Garante que usuários não permitidos não acessam a configuração de 2FA.

---

### Gestão de Tarefas

**can show list of tasks**  
Valida que o usuário autenticado obtém lista de suas tarefas corretamente.

**can show create task page**  
Testa o acesso à página de criação de tarefas.

**can create a new task**  
Assegura que o formulário cria uma nova tarefa e a armazena no banco de dados.

**can show a specific task**  
Valida visualização detalhada de uma tarefa específica.

**can show edit task page**  
Testa acesso à página de edição da tarefa.

**can update a task**  
Garante que o formulário de edição atualiza corretamente os dados da tarefa.

**can delete a task**  
Testa exclusão de tarefa e reforça ausência no banco após remoção.

**can filter tasks by name, status, priority and due date**  
Confirma que filtros funcionam combinado critérios e refletem na listagem.

---

### Gestão de Tipos de Tarefas

**can show list of task types**  
Valida que o sistema exibe corretamente a lista de tipos de tarefas para o usuário.

**can create a new task type**  
Assegura que o sistema permite criar novos tipos com validação adequada.

**can edit task type**  
Testa a edição e atualização das informações de um tipo de tarefa existente.

**can toggle ativo status for task type**  
Verifica a ativação e desativação dinâmica de tipos de tarefa, essencial para controle da disponibilidade.

**can delete a task type**  
Confirma que tipos podem ser removidos com sucesso do sistema.

```
it('can toggle ativo status for task type', function () {
$taskType = TaskType::factory()->for($this->user)->create(['ativo' => true]);
```

```
// Toggle para false (0)
$response = patch(route('task-types.toggle-ativo', $taskType->id));
$response->assertOk();
$taskType = $taskType->fresh();
expect($taskType->ativo)->toBeFalsy();

// Toggle para true (1)
$response = patch(route('task-types.toggle-ativo', $taskType->id));
$response->assertOk();
$taskType = $taskType->fresh();
expect($taskType->ativo)->toBeTruthy();

```

---

### Testes de Modelos e Relacionamentos

**um usuário pode ter várias tasks**  
Confirma cardinalidade one-to-many entre usuário e tarefas.

**um usuário pode ter várias activities**  
Garante o registro correto das atividades de usuários no sistema.

---

### Como usar esses testes

Certifique-se de que o ambiente de testes esteja configurado, normalmente usando banco separado.  
Rode os testes com o comando: 

```
php artisan test
```

Verifique o terminal para mensagens de sucesso ou falha de cada teste.  
Em caso de erros, utilize os detalhes apresentados para debug e correção.

---

### Benefícios dos Testes

- Protegem contra regressões em funcionalidades críticas.  
- Garantem que fluxos importantes como autenticação, registro, reset de senha e gestão de tarefas funcionem conforme esperado.  
- Facilitam adição de novas funcionalidades com confiança.  
- Melhoram a qualidade e segurança da aplicação.  
- Documentam, por exemplo, regras de negócio e requisitos de segurança de forma prática e testável.

---

## Variáveis de Ambiente Mais Usadas no Projeto

```
APP_NAME=FlowToDo
APP_ENV=local
APP_DEBUG=true
APP_URL=https://flowtodo.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flowtodo
DB_USERNAME=root
DB_PASSWORD=
```

### Variáveis Adicionais

Essas variáveis garantem o funcionamento de recursos extras como logs de atividade, Telescope e integração frontend.

**Activity Logger**

Controla o registro automático das ações do usuário no sistema.

```
ACTIVITY_LOGGER_ENABLED=true
ACTIVITY_LOGGER_TABLE_NAME=activity_log
```
**Telescope (Monitoramento e Debug)**

Controla a ativação do Laravel Telescope, ferramenta de inspeção de requisições, jobs, logs, queries e exceções.

```
TELESCOPE_ENABLED=true
TELESCOPE_PATH=telescope
TELESCOPE_DRIVER=database
TELESCOPE_BATCH_WATCHER=true
TELESCOPE_QUERY_WATCHER=true
TELESCOPE_VIEW_WATCHER=true
```

**Importante:** O Telescope deve permanecer ativado apenas em ambiente local (APP_ENV=local).
Em produção, desative com:

```
TELESCOPE_ENABLED=false
```

---

Projeto desenvolvido como parte de estágio acadêmico, focado em produtividade, organização e experiência intuitiva do usuário.


