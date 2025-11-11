<?php

//  Conexão

require __DIR__ . '/includes/db.php';

// estamos pegando o ID que veio pela URL (ex: editar.php?id=3)
// Caso nao exista ou for invalido(0, texto, etc), volta p pagina de listagem

$id = (int)($_GET['id'] ?? 0);

if($id <=0){
    header('Location: listar.php');
    exit;
}

// ====================== Busca do registro =========================================

$sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
        FROM cadastros
        WHERE id = :id';

$stmt = db()->prepare($sql);
$stmt->execute([':id' => $id]);
$registro = $stmt-> fetch(PDO::FETCH_ASSOC);

// Caso não encontre o registro, volte para a lista
if(!$registro){
    header('Location: listar.php');
    exit;
}

// Guarda a foto atual do registro que veio do banco
// Caso contrario envia uma nova foto no form
// para não apagar a foto existente

$fotoAtual = $registro['foto'] ?? null;


// =================================== Inicio do processamento do POST para quando clicar em salvar ==================================================

$erro = '';
$ok = false;

if($_SERVER['REQUEST_METHOD'] === 'POST') {

// 1 cap dos dados
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $fotoAtual = $_POST['foto_atual'] ?? null;
// 2 validação 
    if($nome === '' || mb_strlen($nome) <3){
        $erro = 'Obrigatorio preencher o nome (min. 3 caracteres)';
    }

    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erro = 'e-mail inválido';
    }

    elseif ($telefone === '' || mb_strlen(preg_replace('/\D+/', '', $telefone)) <8){
        $erro = 'Telefone inválido.';
    }

    // 3 upload da nova foto caso tenha sido enviada, se não, mantem

    $novaFoto = null; 
    if($erro === '' && isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE){
        if($_FILES['foto']['error'] !== UPLOAD_ERR_OK){
            $erro = 'ERRO AO ENVIAR IMAGEM';
        }

        else{
            if($_FILES['foto']['size'] >2 *1024*1024){
                $erro = 'Imagem muito grande(máx. 2MB).';
            }
        
// valida tipo dos arquivos (MIME)
// clase nativa p detectar MIME - $finfo = new finfo(FILEINFO_MIME_TYPE);  
// tipo real do arquivo   $mime = $finfo->file($_FILES['foto']['tmp_name']);
        if($erro === ''){
            $finfo = new finfo(FILEINFO_MIME_TYPE);  
            $mime = $finfo->file($_FILES['foto']['tmp_name']);
            $permitidos = [
                'image/jpeg' =>'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif'
            ];

            if(!isset($permitidos[$mime])){
                $erro = 'Foto de imagem inválida. Use JPG, PNG ou GIF';
            }
// grante existencia da pasta e move arquivo
            if($erro === ''){
                $dirUpload = __DIR__ . '/uploads';
                if(!is_dir($dirUpload)){
                    mkdir($dirUpload, 0755, true);
                }

                $novoNome = uniqid('img_', true). '.' . $permitidos[$mime]; //nome unico
                $destino = $dirUpload . '/'. $novoNome;

                if(move_uploaded_file($_FILES['foto']['tmp_name'], $destino)){
                    $novaFoto = 'uploads/' . $novoNome; // SAlVA CAMINHO RELATIVO
                }

                else{
                    $erro = 'Falha ao salvar a imagem no servidor.';
                }
            }
        }

        }
    }

// 4 se tudo estiver certo, Faz o UPDATE

if($erro === ''){
    try{
        // define a foto que sera salva: nova, caso não tenha uma nova, mantém

        $fotoParaSalvar = $novaFoto !== null ? $novaFoto : $fotoAtual;


        $sql = 'UPDATE cadastros
                SET nome = :nome,
                email = :email,
                telefone = :telefone,
                foto = :foto
                WHERE id = :id';

        $stmt = db()->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':foto' => $fotoParaSalvar,
            ':id' => $id,
            

        ]);


        if($novaFoto != null && !empty($fotoAtual) && file_exists(__DIR__ . '/' . $fotoAtual)){
            unlink(__DIR__. '/' . $fotoAtual);
        }

        $ok = true;

        // Redireciona para a lista após atualização

        header('Location: listar.php?msg=atualizado');
        exit;
    }  // try, FIMM

catch (PDOException $e){
    if($e->getCode() === '23000'){
        $erro = 'Este e-mail já está cadastrado.';
    }

    else{
        $erro = 'Erro ao atualizar: '. $e->getMessage();
    }
}

}

}

?> 

<!-- Encerramento do POST -->

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <title>Editar Cadastro</title>
<body>
    
    <h1>Editar Cadastro</h1>

    <?php if ($erro): ?>
        <p style="color: brown;"><?= htmlspecialchars($erro) ?> </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <p>
<!-- Nome  -->
        <label >Nome
            <input type="text" name="nome" required minlength="3" value="<?=htmlspecialchars($registro['nome'] ?? '')?>">
        </label>
        </p>

<!-- E-mail  -->

        <p>
            <label >E-mail:
            <input type="email" name="email" required  value="<?=htmlspecialchars($registro['email'] ?? '')?>">
        </label>

        </p>

<!-- Telefone  -->

          <p>
            <label >Telefone:
            <input type="tel" name="telefone" required placeholder="(11) 99999-888" value="<?=htmlspecialchars($registro['telefone'] ?? '')?>">
        </label>

        </p>

<!-- Foto Atual  -->

          <p>
          Foto Atual:
          <?php if (!empty($fotoAtual)): ?>
          <br>
          <img src="<?=htmlspecialchars($fotoAtual) ?>" alt="Foto Atual" style="max-width:120px; max-height: 120px;">


          <?php else: ?>
            (sem foto)
            <?php endif;?>

        </p>

<!-- Trocar Foto -->
        <p>
            <label>Trocar foto (opcional)
                <input type="file" name="foto">
            </label>
        </p>

        <!-- mentem o caminho da foto atual escondido (caso nao troque) -->
         <input type="hidden" name="foto_atual" value="<?=htmlspecialchars($fotoAtual ?? '')?>">

<!-- Salvar e Cancelar -->
         <p>
            <button type="submit">Salvar Alterações</button>
            <a href="listar.php">Cancelar</a>
         </p>
         
    </form>

    </body>
    </html>

