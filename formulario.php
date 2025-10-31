<!-- Chamando o arquivo header -->

<?php

include __DIR__ . '/includes/header.php';

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário</title>
</head>
<body>

<!-- enctype - obrigatorio para armazenar arquivos -->

<form action="salvar.php" method="POST" enctype="multipart/form-data">

<h1>Cadastro</h1>

<label for=""> Nome
    <input type="text" name="nome" required placeholder="Emilly "> <br>
</label> <br>


<label for=""> Telefone
    <input type="phone" name="telefone" required  placeholder="() 99999-999"> <br>
</label> <br>


<label for=""> E-mail
    <input type="email" name="email" required  placeholder="email@gmail.com"> <br>
</label> <br>


<label for=""> Foto
    <input type="file" name='foto'>
</label> <br>

<button type="submit">Enviar</button>

</form>


</body>
</html>

<!-- Chamando meu footer no fim da pagina -->

<?php

include __DIR__ . '/includes/footer.php';

?>