# Tecnofit Ranking API

API desenvolvida em **PHP puro** para consulta de ranking de recordes pessoais por movimento.

------------------------------------------------------------------------

## Requisitos

-   PHP 8+
-   MySQL 8+
-   Extensão PDO habilitada
-   Banco de dados previamente populado como indicado no escopo do enunciado do teste

------------------------------------------------------------------------

## Configuração do Ambiente

Renomeie o arquivo `.env.example` para `.env` na raiz do projeto e insira seus dados de conexão com o banco:

``` env
DB_HOST=127.0.0.1
DB_NAME=tecnofit
DB_USER=root
DB_PASS=senha
```

------------------------------------------------------------------------

## Como Executar

Na raiz do projeto, execute:

``` bash
php -S localhost:8000 -t public
```

A aplicação ficará disponível em:

    http://localhost:8000

------------------------------------------------------------------------

## Endpoint

### Buscar Ranking por Movimento

    GET /movements/ranking?identifier=back squat

### Parâmetro

`identifier` pode ser:

-   **ID do movimento** (ex: `2`)
-   **Nome do movimento** (ex: `back squat`)

------------------------------------------------------------------------

## Resposta da API

``` json
{
  "movement": "Back Squat",
  "ranking": [
    {
      "position": 1,
      "user": "Jose",
      "personal_record": 130,
      "date": "2021-01-03 00:00:00"
    },
    {
      "position": 1,
      "user": "Joao",
      "personal_record": 130,
      "date": "2021-01-03 00:00:00"
    },
    {
      "position": 3,
      "user": "Paulo",
      "personal_record": 125,
      "date": "2021-01-03 00:00:00"
    }
  ]
}
```

------------------------------------------------------------------------

## Regras de Negócio

-   O maior recorde pessoal por usuário é considerado.
-   Usuários com o mesmo valor compartilham a mesma posição.
-   O ranking é calculado diretamente no banco utilizando:
    -   `WITH` (CTE)
    -   `ROW_NUMBER()`
    -   `RANK()`

------------------------------------------------------------------------


## Estrutura da Aplicação

    src/
     └── Movement/
         └── Ranking/
             ├── Domain/
             ├── Application/
             ├── Controller/
             └── Infrastructure/
    public/
     └── index.php

### Camadas

-   **Domain** → Entidades e contratos
-   **Application** → Casos de uso
-   **Controller** → Recebe requisições HTTP e chama UseCases
-   **Infrastructure** → Implementação com PDO
-   **Public** → Entrada da aplicação

------------------------------------------------------------------------

## Observações

-   Projeto sem framework.
-   Separação de responsabilidades.
-   Lógica de ranking executada no banco.
-   Compatível com execução via servidor embutido do PHP.
