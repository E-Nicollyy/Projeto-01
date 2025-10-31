## Criando BD caso ele não exista
    CREATE DATABASE IF NOT EXISTS emilly_db 

## Quando inserir acentos ele não fique desconfigurado e bagunçado 
	DEFAULT CHARACTER SET utf8mb4 		 
	
## Definindo e crianod regras de ordenação    
    COLLATE utf8mb4_general_ci;   

## Excluindo Banco de Dados caso ele exista
    DROP DATABASE IF EXISTS emilly_bd - Drop

##  Criando função para devolver uma conexão PDO(PHP Data Object ) com o banco de dados
function db() : PDO{ tudo dentro da função  }

## // declarado variavel statica onde ela reaproveita a mesma conexão com meu banco de dados
static  $pdo;

## Condição // ! ao contrario. Verifica se aind não existe uma conexão ativa
if(!$pdo){
    
        try{
## tentar executar o bloco abaixo 
            $dsn = 'http://localhost/phpmyadmin/index.php?route=/database/structure&db=emilly_db;charset-utf8mb4'

## define a string de conexão (DNS) dizendo o tipo de banco (mysql)
## O servidor (localhost) o nome do banco (emmilly_db)
## o conjunto de caracteres (utf8mb4)
        }
    }

##  data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
data de cadastro automatica com horario atual

##  UNIQUE KEY uk_email(email) 
impede e-mails repetidos