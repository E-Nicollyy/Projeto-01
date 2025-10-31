<?php 
function db() : PDO{

    // Criando função para devolver uma conexão PDO(PHP Data Object ) com o banco de dados

    // declarado variavel statica onde ela reaproveita a mesma conexão com meu banco de dados
    static $pdo;

    if(!$pdo){
        // ! ao contrario. Verifica se aind não existe uma conexão ativa
        try{
            // tentar executar o bloco abaixo 
            $dsn = 'mysql:host=localhost;dbname=emilly_db;charset=utf8mb4';

            // define a string de conexão (DNS) dizendo o tipo de banco (mysql)
            // O servidor (localhost) o nome do banco (emmilly_db)
            // o conjunto de caracteres (utf8mb4)

        $pdo =new PDO(
        $dsn,   
        'root',  
        '',      

            [
                PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Define que se der erro, PDO vai lançar uma exceção 

                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // define que quando buscar dados, eles virão como arrays associativos

            ]

            );  
        echo "<br> <br>";
        }

    catch(PDOException $e){
        // Se der algum erro no bloco try(acima), cai aqui
        echo "<b> Erro ao conectar ao banco: </b>". $e->getMessage();
        // mostra a mensagem de erro diretamente na tela

        exit;
        // Encerra a execução do script (opcional)
    }    
    }

    return $pdo;
    // Retorna objeto de conexaão 

}
// Chamar a função automaticamente se o arquivo for aberto direto no navegador
if(basename(__FILE__) === basename ($_SERVER['SCRIPT_FILENAME']) ){
    db();    
    // Executa a conexão e mostra a mensagem na tela
}