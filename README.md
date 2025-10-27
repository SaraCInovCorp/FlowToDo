# FlowToDo

![FlowToDo Logo](public/logo.png)

FlowToDo é uma aplicação web minimalista para gestão de tarefas, criada para ajudar usuários a organizar suas atividades diárias de forma eficiente e intuitiva.

## Visão Geral

FlowToDo proporciona:
- Criação e organização de tarefas
- Definição de prioridade e data de vencimento
- Filtros inteligentes por estado, prioridade e vencimento
- Marcação rápida de tarefas concluídas
- Interface responsiva e minimalista, adaptada para desktop, tablet e mobile

## Tecnologias

- **Backend:** Laravel 12 (PHP)
- **Frontend:** Vue.js (última versão)
- **Estilização:** Tailwind CSS
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

3. Configure o arquivo `.env` com as suas credenciais MySQL, domínio e configuração.

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


7. Acesse a aplicação em [http://localhost:8000](http://localhost:8000) ou como no meu caso [http://nomedoprojeto.test](http://nomedoprojeto.test)

## Estrutura

- `app/` - Lógica backend e Models
- `resources/js/` - Código Vue.js e CSS com Tailwind
- `public/` - Arquivos estáticos como a logo da aplicação

---

Projeto desenvolvido para estágio acadêmico.

