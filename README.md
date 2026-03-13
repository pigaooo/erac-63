# ERAC 63

Sistema web para divulgacao, organizacao e inscricao do 63o E.R.A.C. (Encontro Regional de Aprendizes e Companheiros).

## Sobre o projeto

O projeto foi desenvolvido em Laravel e funciona como o portal oficial do evento. A aplicacao concentra informacoes institucionais, programacao, localizacao, patrocinadores e o fluxo de credenciamento dos participantes.

Atualmente o sistema contempla:

- pagina inicial com apresentacao do evento;
- pagina de programacao;
- pagina de localizacao;
- pagina sobre o encontro;
- inscricao individual;
- inscricao em lote;
- cadastro de patrocinadores;
- cadastro de lojas/capitulos e inscritos.

## Stack utilizada

- PHP 8.2+
- Laravel 12
- Livewire 3
- Filament 4
- Vite
- Tailwind CSS
- DaisyUI
- SQLite ou outro banco compativel com Laravel

## Estrutura principal

- `app/Models`: entidades da aplicacao, como `Inscrito`, `Loja` e `Patrocinador`
- `app/Livewire`: componentes interativos de inscricao
- `resources/views/pages`: paginas publicas do site
- `resources/views/livewire`: interfaces dos modais e fluxos dinamicos
- `database/migrations`: estrutura das tabelas do sistema
- `routes/web.php`: rotas web da aplicacao

## Funcionalidades

### Area publica

- exibicao das informacoes do evento;
- navegacao entre paginas institucionais;
- visualizacao da programacao;
- visualizacao de patrocinadores.

### Inscricoes

- inscricao individual via modal;
- inscricao multipla para lotes por loja/capitulo;
- validacao de campos obrigatorios;
- persistencia dos inscritos no banco.

## Como executar localmente

1. Clone o repositorio.
2. Instale as dependencias do PHP:

```bash
composer install
```

3. Instale as dependencias do frontend:

```bash
npm install
```

4. Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

5. Gere a chave da aplicacao:

```bash
php artisan key:generate
```

6. Execute as migrations:

```bash
php artisan migrate
```

7. Rode a aplicacao:

```bash
composer run dev
```

## Banco de dados

As tabelas principais do projeto sao:

- `users`
- `lojas`
- `patrocinadores`
- `inscritos`

## Status

Projeto em desenvolvimento, com foco na organizacao do evento e no fluxo de inscricoes.
