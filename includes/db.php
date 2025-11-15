<?php 

// Função que retorna uma conexão PDO com o banco online
function db() : PDO {

    // Variável estática: mantém a mesma conexão durante toda a execução
    static $pdo;

    // Se AINDA não existe conexão, cria uma nova
    if(!$pdo){
        try{

            // ======================================================
            //  CONFIGURAÇÕES DO BANCO NO INFINITYFREE
            // ======================================================

            // Host do servidor MySQL do InfinityFree
            $host = 'sql312.infinityfree.com';

            // Nome do banco que o InfinityFree criou
            $dbname = 'if0_40421490_mysqlPHP00';

            // DSN = string que o PDO usa para conectar
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

            // Usuário e senha do MySQL no InfinityFree
            $usuario = 'if0_40421490';
            $senha   = '@E_nicollyy3'; // <-- Troque pela senha real do painel

            // ======================================================
            //  CRIA A CONEXÃO COM O BANCO
            // ======================================================
            $pdo = new PDO(
                $dsn,
                $usuario,
                $senha,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Mostra erros
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retorna arrays associativos
                ]
            );

        } catch(PDOException $e){

            // Se der erro ao conectar, mostra o motivo na tela
            echo "<b>Erro ao conectar ao banco: </b>" . $e->getMessage();
            exit; // Encerra o script
        }
    }

    // Retorna o objeto PDO já conectado
    return $pdo;
}

// Se este arquivo for aberto diretamente no navegador, testa a conexão
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    db(); // Testa a conexão
}

?>
