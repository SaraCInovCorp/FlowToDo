<p align="center">
  <img src="public/logo.png" alt="FlowToDo Logo" width="150" />
</p>

# FlowToDo

FlowToDo é uma aplicação web moderna, minimalista e responsiva para gestão de tarefas. Seu objetivo é ajudar pessoas a se manterem organizadas, reduzindo o stress e trazendo leveza à rotina.

## Visão Geral

FlowToDo proporciona:
- Cadastro e organização inteligente de tarefas diárias
- Definição de prioridade e data de vencimento
- Filtros inteligentes por estado, prioridade e vencimento
- Marcação rápida de tarefas concluídas, em apenas um clique
- Interface adaptada para desktop, tablet e mobile, sempre clara e intuitiva

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


7. Acesse em [http://localhost:8000](http://localhost:8000) ou [http://nomedoprojeto.test](http://nomedoprojeto.test).

## Estrutura do Projeto

- `app/` - Lógica backend e Models
- `resources/js/` - Frontend Vue e estilos com Tailwind CSS
- `public/` - Arquivos estáticos e imagens como logo e banners

---

Projeto desenvolvido como parte de estágio acadêmico, com o foco em produtividade, organização e experiência do usuário.

