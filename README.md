# ERAC 61

Sistema web para divulgação, organização e inscrição do 61º E.R.A.C. (Encontro Regional de Aprendizes e Companheiros).

## Sobre o projeto

O projeto foi desenvolvido em Laravel e funciona como o portal oficial do evento. A aplicação concentra informações institucionais, programação, localização, patrocinadores e o fluxo de credenciamento dos participantes.

Atualmente o sistema contempla:

- página inicial com apresentação do evento;
- página de programação;
- página de localização;
- página sobre o encontro;
- inscrição individual;
- inscrição em lote;
- cadastro de patrocinadores;
- cadastro de lojas/capítulos e inscritos.

## Stack utilizada

- PHP 8.2+
- Laravel 12
- Livewire 3
- Filament 4
- Vite
- Tailwind CSS
- DaisyUI
- SQLite ou outro banco compatível com Laravel

## Estrutura principal

- `app/Models`: entidades da aplicação, como `Inscrito`, `Loja` e `Patrocinador`
- `app/Livewire`: componentes interativos de inscrição
- `resources/views/pages`: páginas públicas do site
- `resources/views/livewire`: interfaces dos modais e fluxos dinâmicos
- `database/migrations`: estrutura das tabelas do sistema
- `routes/web.php`: rotas web da aplicação

## Funcionalidades

### Área pública

- exibição das informações do evento;
- navegação entre páginas institucionais;
- visualização da programação;
- visualização de patrocinadores.

### Inscrições

- inscrição individual via modal;
- inscrição múltipla para lotes por loja/capítulo;
- validação de campos obrigatórios;
- persistência dos inscritos no banco.

## Como executar localmente

1. Clone o repositório.
2. Instale as dependências do PHP:

```bash
composer install
```

3. Instale as dependências do frontend:

```bash
npm install
```

4. Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

5. Gere a chave da aplicação:

```bash
php artisan key:generate
```

6. Execute as migrations:

```bash
php artisan migrate
```

7. Rode a aplicação:

```bash
composer run dev
```

## Banco de dados

As tabelas principais do projeto são:

- `users`
- `lojas`
- `patrocinadores`
- `inscritos`

## Status

Projeto em desenvolvimento, com foco na organização do evento e no fluxo de inscrições.