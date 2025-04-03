Travel Orders Service API

Este é um microsserviço de gerenciamento de pedidos de viagem corporativa, desenvolvido com Laravel 12, utilizando autenticação via API com JWT. A API permite operações CRUD para pedidos de viagem, com filtros por status, período e destino. Além disso, permite a alteração de status e o cancelamento de pedidos, com validação de permissões e notificações para o usuário.

Requisitos
PHP 8.2 ou superior
Docker e Docker Compose
Composer
MySQL 8.x

Instalação
1. Clonar o repositório
Primeiro, clone o repositório para sua máquina local:

git clone https://github.com/seuusuario/travel-orders-service.git
cd travel-orders-service

2. Construir a imagem Docker
A API pode ser executada localmente utilizando Docker. Para isso, execute o comando abaixo para construir as imagens e iniciar os containers:
docker-compose up --build -d

4. Instalar as dependências
Após o container estar rodando, instale as dependências do projeto:
docker-compose exec app composer install --no-dev --optimize-autoloader

4. Configurar o ambiente
Você precisará configurar as variáveis de ambiente. Uma vez o projeto clonado, copie o arquivo .env.example para .env:
cp .env.example .env

Edite o arquivo .env conforme necessário. As configurações padrão incluem as variáveis de banco de dados, como:
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=travel_orders
DB_USERNAME=root
DB_PASSWORD=root

5. Gerar a chave de aplicação
Para garantir que a chave de criptografia da aplicação esteja configurada corretamente, execute:
docker-compose exec app php artisan key:generate

7. Rodar as migrações
Execute as migrações para configurar o banco de dados:
docker-compose exec app php artisan migrate

7. Popular o banco de dados (opcional)
Se você quiser popular o banco com dados de exemplo, você pode rodar o seeder:
docker-compose exec app php artisan db:seed

Execução Local
Agora, sua aplicação Laravel estará rodando na porta 8000 dentro do container. Você pode acessar a API na seguinte URL:
http://localhost:8000

Para testar se a API está funcionando, você pode acessar a rota de teste que retorna uma resposta simples:
GET /test
Isso retornará a seguinte resposta:
{
    "message": "API está funcionando"
}

Exemplo de rotas da API
A API está protegida por autenticação JWT. Para acessar as rotas protegidas, você precisa enviar um token JWT no cabeçalho Authorization da seguinte forma:
Authorization: Bearer {seu-token-jwt}

1. Listar Pedidos de Viagem
GET /api/travel-orders

Você pode filtrar os pedidos de viagem com os seguintes parâmetros:
status – Filtra por status do pedido.
destination – Filtra por destino (parcial).
start_date e end_date – Filtra por intervalo de datas.

2. Criar Pedido de Viagem
POST /api/travel-orders
O corpo da requisição deve conter os dados do pedido, como:

{
    "destination": "Paris",
    "start_date": "2025-05-01",
    "end_date": "2025-05-07",
    "status": "pendente"
}

3. Mostrar Pedido de Viagem
GET /api/travel-orders/{id}

5. Alterar Status de Pedido
PATCH /api/travel-orders/{id}/status

Exemplo de corpo de requisição:
{
    "status": "aprovado"
}

5. Cancelar Pedido de Viagem
POST /api/travel-orders/{id}/cancel
Esse endpoint só pode ser usado para pedidos com status "aprovado".

Testes
Para rodar os testes do projeto, execute o seguinte comando:
docker-compose exec app ./vendor/bin/phpunit
Isso executará todos os testes automatizados do projeto utilizando PHPUnit.

Informações Adicionais
- A aplicação utiliza JWT para autenticação via API. Certifique-se de passar o token correto nos cabeçalhos das requisições.
- O banco de dados utilizado é MySQL 8.x e a aplicação é executada no PHP 8.2 com o servidor PHP-FPM.
- O projeto foi configurado para rodar com Docker e Docker Compose, facilitando a execução em qualquer ambiente.
