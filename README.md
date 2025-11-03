<<p align="center">
  <img src="public/logo.png" alt="[translate:FlowToDo Logo]" width="150" />
</p>

# FlowToDo

[translate:FlowToDo] é uma aplicação web moderna, minimalista e responsiva para gestão de tarefas. Seu objetivo é ajudar pessoas a se manterem organizadas, reduzindo o stress e trazendo leveza à rotina.

## Visão Geral

[translate:FlowToDo] proporciona:
- Cadastro e organização inteligente de tarefas diárias
- Definição de prioridade e data de vencimento
- Filtros inteligentes por estado, prioridade e vencimento usando componentes reutilizáveis personalizados (Input, Select, Label)
- Marcação rápida de tarefas concluídas, em apenas um clique, com botões de ação dinâmicos para cada status
- Interface adaptada para desktop, tablet e mobile, sempre clara e intuitiva

## Tecnologias

- **Backend:** Laravel 12 (PHP)
- **Frontend:** Vue.js (última versão)
- **Estilização:** Tailwind CSS, com componentes UI customizados reutilizáveis para formulários e botões
- **Base de dados:** MySQL

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

3. Configure seu `.env` com as credenciais MySQL e informações de domínio.

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

7. Acesse em [translate:http://localhost:8000] ou [translate:http://nomedoprojeto.test].

## Estrutura do Projeto

- `app/` - Lógica backend e Models
- `resources/js/` - Frontend Vue com componentes UI customizados reutilizáveis (Input, Select, Label, Button, Card)
- `public/` - Arquivos estáticos e imagens como logo e banners

## Novidades nas Últimas Atualizações

- Inclusão de componente [translate:Select.vue] customizado, estilizado igual ao componente [translate:Input.vue], para melhor consistência visual nos formulários
- Implementação de filtros avançados na listagem de tarefas com componentes [translate:Input], [translate:Select] e [translate:Label]
- Botões dinâmicos para gerenciamento rápido do status das tarefas dentro de Cards, com opções para iniciar, concluir, cancelar, desfazer ações e visualizar detalhes
- Melhorias na visualização de datas com formato [translate:d-m-Y]
- Paginação que preserva os filtros aplicados para navegação contínua e intuitiva

---

Projeto desenvolvido como parte de estágio acadêmico, focado em produtividade, organização e experiência intuitiva do usuário.


