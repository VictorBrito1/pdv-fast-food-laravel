## Descrição
Um restaurante precisa registrar suas vendas de forma fácil e rápida. São utilizadas
comandas para anotar os pedidos de seus clientes. O restaurante gostaria de ter um
ambiente intuitivo, listando os produtos mais vendidos e facilitando a inserção dos
mesmos em um checkout.

## Configuração
* Duplique o arquivo ".env.example" para o arquivo .env e coloque as configurações do banco de dados.
* Rode o comando "php artisan migrate --seed" para criar as tabelas e inserir alguns produtos.
* Rode o comando "composer install" para instalar as dependências.
* Rode o comando "php artisan serve" para iniciar o servidor.

## Fluxo para consumo da API
1. Para listar os produtos: GET -> /products.
2. Para buscar produtos pelo id ou nome: GET -> /products/search passando o parâmetro "text".
3. O cliente poderá adicionar um produto ao pedido de duas formas (ambas recebem a quantidade do produto através do parâmetro "amount"):  
3.1. Caso não tenha salvo o id do último pedido: POST -> /orders/products/{productId}. Essa rota irá criar um pedido, inserir o produto e retornar o pedido.  
3.2. Caso tenha o id: POST -> /orders/{orderId}/products/{productId}.
4. Para remover um produto do pedido: DELETE -> /orders/{orderId}/products/{productId}. Essa rota também pode receber a quantidade do produto.
5. Para finalizar um pedido (enviar para a cozinha): POST -> /orders/{id}/finish com o nome do cliente, método de pagamento (cash, credit_card_credit, credit_card_debit), valor pago e observação (opcional).
6. Para acompanhar o resumo de um pedido: GET -> /orders/{id].
7. Para mudar o status de um pedido, por exemplo, para a cozinha dar baixa: POST -> /orders/{id}/change-status passando o status (open, sent_to_the_kitchen, ready, finished).
8. Os pedidos poderão ser listados todos ou filtrado por status na mesma rota: GET /orders, podendo ser enviado o status (por exemplo, listagem de pedidos que foram enviados para a cozinha ou que estão prontos para a entrega).

Obs: todas as rotas têm o prefixo "/api".
