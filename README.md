# Integração de pagamento com Asaas

## Instalação
- git clone;
- composer install;
- cp .env.example .env;
- php artisan key:generate;
- substituir o valor da variável ASAAS_API_KEY com a chave do projeto da AsaaS e na variável ASAAS_API_URL pode manter: https://sandbox.asaas.com/api
- php artisan migrate (utilizei banco de dados MySql local para desenvolvimento)

## Telas do sistema
- Criar Clientes: customers/create
- Listar Clientes: customers
- Criar Transações: transactions/create
- Listar Transações: transactions
