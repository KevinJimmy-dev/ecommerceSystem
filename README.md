# ecommerceSystem
Criando a API de um e-commerce com Laravel, um framework do PHP.

# Development

Para realizar uma manutenção corretiva ou evolutiva, você precisará instalar em seu computador o GIT, Docker CE e Docker Compose.

* GIT (https://git-scm.com/downloads)
* Docker CE (https://docs.docker.com/install/)
* Docker Compose (https://docs.docker.com/compose/install/)

Após instar essas ferramentas, siga os seguintes passos:

#### 1) Faça o clone desse repoitório:

```shell
$ git clone <REPO_URL>
```

#### 2) Suba o(s) container(s):

```shell
$ docker-compose up -d
```

#### 3) Instale as dependencias do projeto:

```shell
$ docker-compose exec api composer create-project -vvv
```

#### 4) Acesse o MySQL e crie os bancos de dados do projeto:

```shell
$ docker-compose exec mysql8-database mysql -h0.0.0.0 -uroot -proot -e "CREATE DATABASE ecommerce;"
```

#### 5) Execute o comando abaixo para recriar as tabelas com alguns dados iniciais.

```shell
$ docker-compose exec api php artisan migrate:refresh --seed
```

Agora basta acessar pelo browser o endereço http://localhost:8080

## Quando você puxar as atualizações

Observe se aconteceu alguma alteração no `composer.json`. Se sim, significa que as dependencias do projeto foram atualizadas e você deverá rodar o comando abaixo em sua maquina local para deixar tudo certinho:

```shell
$ docker-compose exec api composer update
```

Também vale observar se ocorreu alguma alteração nas migrations. Caso positivo você deverá executar o comando abaixo para atualizar o seu banco de dados local:

```shell
$ docker-compose exec api php artisan migrate:refresh --seed
```
