# Cronos Test

## Descrição

Construir uma API em laravel com 3 endpoints:
 - Cadastrar customer
 - Atualizar customer
 - Retornar saldo
A tabela customer deve ter algumas colunas:
 - id
 - amount
 - befoure_amount
 - created_at
 - updated_at
 - name

Deve existir um método que receba como parâmetro um novo saldo a ser atualizado na tabela customer, e o customer_id.
Esse método deve ser chamado de maneira concorrente, executando-as através de threads, valores de amounts diferentes, onde o customer deve manter o maior valor passado.
O script que chama esse método pode chamar o método da seguinte forma:
Nesse caso, o parâmetro passado para execução do script, deve ser valores diferentes.
$amount = $argv[1];
for($i=0; $i < 10; $i++)
{
       metodoAtualizarAmount($customer_id,$amount);
       $amount++;
}
Deve-se executar 5 threads concorrentes que atualizem o saldo do customer.


# Maintence

Publish lang
```php artisan lang:publish```