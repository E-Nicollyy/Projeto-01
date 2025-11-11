<!-- Chamando o arquivo header -->

<?php

include __DIR__ . '/includes/header.php';

?>



<?php

// SENPRE CONECTE O BANCO

require __DIR__ . '/includes/db.php';

// get - PEGAR o que foi digitado | usamos ele, pois estamos consultando e não salvando.
// trim() remove espaços

$busca = trim($_GET['busca'] ?? '');

// se o usuario digitar algo ...
if($busca !== ''){
    //  do maior para o menos
    // se tiver texto na caixa busca o qsl vai filtrar correto pelo nome ou email
    // LIKE é o que permite que ele busque e mostre exatamente o que foi determinado.
    // DESC - do maior para o menor
    $sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
            FROM cadastros
            WHERE nome LIKE :busca OR email LIKE :busca
            ORDER BY id DESC';

            // prepara os comandos sql
            $stmt = db()->prepare ( $sql );

            // % serve para que de para buscar qualquer parte(texto) do nome e email
            $stmt->execute([':busca' => "%$busca%"]);
        
        }
else{

    // se o usuario pesquisa e re torne vazio
    $sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
            FROM cadastros
            ORDER BY id DESC';
            $stmt = db()->prepare ( $sql );
            $stmt->execute();
}

// fetchAll() busca todos os resultados e retorna como array associativo
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
    <meta charset="UTF-8">
    <title>LISTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

<body class="" style="background-color:rgb(102, 102, 153);">
    
<div class="card sessao border border-light p-5 mx-4 bg-dark text-light" style="rgb(102, 102, 153);">
<h1 class="text-center fw-bold p-4 ">Lista de Cadastros</h1>

<!-- INICIO - Formulário de busca -->

<form method="get" class="text-center">

<input type="text" name="busca" placeholder="Pesquisar..." value="<?=htmlspecialchars($busca)?>">

<button class="btn btn-primary"  type="submit">Buscar</button>
<a href="listar.php" class="btn btn-warning ">Limpar</a>

<a href="formulario.php" class="btn btn-success ">+ Novo Cadastro</a> 

</form>

<!-- NOVO REGISTRO -->


 <?php if(!$registros): ?>
 
 <!-- Se não houver resultados | ! = se não -->

 <p>Nenhum cadastro encontrado.</p>


 <?php else: ?>

<table class="table table-bordered border border-black text-center mx-4 mt-4">

<thead>

<tr class="table-dark ">  <!-- Linhas -->

<!-- Colunas da tabela -->
<th>ID</th>
<th>Nome</th>
<th>E-mail</th>
<th>Telefone</th>
<th>Foto</th>
<th>Data de Cadastro</th>
<th>Ações</th>


</tr>
</thead>


<tbody>   <!--  -->
    
    <?php

// foreach -> Estrutura que percorre os registros do banco
// $registros -> lista com os cadastros vindos do banco
// $r -> um registro por vez dentro do loop

    foreach($registros as $r):
    ?>

</tbody>

<tr class="table-secondary">

<td><?=(int)$r['id'] ?> </td>
<td><?= htmlspecialchars($r['nome']) ?></td>
<td><?= htmlspecialchars($r['email']) ?></td>
<td><?= htmlspecialchars($r['telefone']) ?></td>

<!-- Se tiver imagem, mostra miniatura -->

<td>
    <?php if (!empty($r['foto'])): ?>
        <img src="<?=htmlspecialchars($r['foto']) ?>"
        alt="Foto"
        style="max-width:80px; max-height:80px;">

        <?php else: ?>
            -
         <?php endif; ?>   
</td>

<!-- Exibe data se esxistir -->

<td>
    <?=htmlspecialchars($r['data_cadastro'] ?? '') ?>
</td>

<!-- links edição/exclusão -->
<td>
    <a href="editar.php?id=<?= (int)$r['id']?>" class="editar btn btn-primary">Editar</a>
    <a href="deletar.php?id=<?= (int)$r['id']?>" onclick="return confirm('tem certeza que seja excluir este registro?');" class="excluir btn btn-danger">Excluir</a>
</td>

</tr>

<?php endforeach; ?>

</tbody>
</table>
</div>
<?php endif; ?>        

<!-- Chamando meu footer no fim da pagina -->

<?php

include __DIR__ . '/includes/footer.php';

?>