<?php

// conexão com o banco
require __DIR__ . '/includes/db.php';

$id = (int) ($_GET['id'] ?? '');

// verifica se veio o ID pela URL (GET)
if($id <= 0) {

    header('Location: listar.php');
    exit;
}


// busca p excluir

$sql = 'SELECT * FROM cadastros WHERE id = :id';
$stmt = db()->prepare($sql);
$stmt->execute([':id' => $id]);
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

// caso nao encontre, volte p lista
if(!$registro){
    header('Location: listar.php');
    exit;
}

// inicio da exclusão do reistro

try{
// verifica se o campo foto nao esta vazio
// confirma se o arquivo realamente  é exostente na pasat antes de apagar
// função nativa que deleta aqruivo fisico.

    if(!empty($registro['foto']) && file_exists(__DIR__ . '/' . $registro['foto'])){
        unlink(__DIR__ . '/' . $registro['foto']);
    }

$sql = 'DELETE FROM cadastros WHERE id = :id';
$stmt = db()->prepare($sql);
$stmt ->execute([':id' => $id]);

// volta p lista após excluir
header('Location: listar.php?msg=excluido');
exit;
}

catch(PDOException $e){
    // se o banco de algum erro, exiba a mensagem:
    echo '<p style="color:red;"> Erro ao excluir: ' . htmlspecialchars($e->getMessage()) . '</p>';
    
}