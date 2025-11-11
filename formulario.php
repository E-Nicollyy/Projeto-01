<!-- Chamando o arquivo header -->

<?php

include __DIR__ . '/includes/header.php';

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="icon" href="./uploads/form.png">
    <title>Formulário</title>
</head>

<!-- enctype - obrigatorio para armazenar arquivos -->

<main class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 100px);">
    <form class="bg-dark text-light card p-5 w-25 border border-light" action="salvar.php" method="POST" enctype="multipart/form-data">
        <h1 class="text-center mb-4">Realize o seu Cadastro!</h1>

        <label class="fw-bold w-100 mb-2">Nome
            <input type="text" name="nome" required placeholder="Emilly" class="form-control">
        </label>

        <label class="fw-bold w-100 mb-2">Telefone
            <input type="phone" name="telefone" required placeholder="() 99999-999" class="form-control">
        </label> 

        <label class="fw-bold w-100 mb-2">E-mail
            <input type="email" name="email" required placeholder="email@gmail.com" class="form-control">
        </label> 

        <label class="fw-bold w-100 mb-4">Foto
            <input type="file" name="foto" class="form-control">
        </label> 

        <button type="submit" class="btn btn-success w-50 mx-auto d-block">Enviar</button>
    </form>
</main>


<!-- Chamando meu footer no fim da pagina -->

<?php

include __DIR__ . '/includes/footer.php';

?>