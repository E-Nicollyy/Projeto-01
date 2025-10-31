<?php

//carrega função db() para conecatr no MYsql
require __DIR__ . '/includes/db.php';

// guarda mensagens de erro (caso tenha algum)
$erro = '';

// Indica que salvou corretamente
$ok = false;

//trim() para remover espaços extras no começo e no final
// pega os valores do form via POST

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');


// verificar se o campo est vazio ou for menor que 3
// mb_strlen contagem correta dos caracteres incluindo acentos José ele contaria exatamente como 4 caracteres e não como 5
if ($nome === '' || mb_strlen($nome) < 3) {
    $erro = 'Nome é obrigatório (mínimo é de 3 caracteres).';
}

// //verificando se o e-mail é valido no caso se o formato é válido
// filter_var() - validar o filtrar valores
// FILTER_VALIDATE_EMAIL - valida o formato de e-mail
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erro = 'E-mail inválido';
}

// verifica se o telefone tem no minimo 8 digitos, 
// preg_replace substitui partes de texto usando expressoes regulares; 
// mb_strlen() para contar quantos digitos sobraram 
// \D+ - qualquer caracterere não numerico, removendo tudo que não for numero
elseif ($telefone === '' || mb_strlen(preg_replace('/\D+/', '', $telefone)) < 8) {
    $erro = 'Telefone inválido';
}

// executa se não houver erro anterior 
$foto = null;

if ($erro === '' && isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $erro = 'Erro ao enviar a imagem.';
    }

    // valida tamanho
    elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        $erro = 'Imagem muito grande (máx. 2MB).';
    }

    // valida tipo
    if ($erro === '') {

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['foto']['tmp_name']);

        // tipos de imagem que aceitamos (extensão associada)
        $permitidos = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
        ];

        // Verifica se o tipo que enviou é um dos que permitimos.
        if (!isset($permitidos[$mime])) {
            $erro = 'Formato de imagem inválido. Use JPG, PNG ou GIF.';
        }
    }

    // Se tudo ok, processa upload
    if ($erro === '') {

        // cria a pasta uploads se ainda não existir
        $dirUpload = __DIR__ . '/uploads';
        if (!is_dir($dirUpload)) {
            // 0777 para garantir permissão local
            mkdir($dirUpload, 0777, true);
        }

        // Gera um nome único e adiciona a extensão certa
        $novoNome = uniqid('img_', true) . '.' . $permitidos[$mime];

        // Caminho completo de onde o arquivo será salvo
        $destino = $dirUpload . '/' . $novoNome;

        // move_uploaded_file() - função nativa do php que move o arquivo
        // local temporario (tmp_name) p o destino (uploads/)
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            // guarda caminho relativo p salvar no banco
            $foto = 'uploads/' . $novoNome;
        } else {
            $erro = 'Falha ao salvar a imagem no servidor.';
        }
    }
}

// Se não teve erro de validação e o campo "foto" veio no post 
if ($erro === '') {

    try {

        //sql com placeholders nomeados (evita sql injection)
        // : indicam variáveis que serão substituídas depois, temporárias 
        $sql = 'INSERT INTO cadastros (nome, email, telefone, foto)
                VALUES (:nome, :email, :telefone, :foto)';

        // db é a nossa função que retorna a nossa conexão
        // prepare() pré-compila o sql no servidor
        $query = db()->prepare($sql);

        // execute - executa comando preparado. passando os valores que vão substituir os placeholders nomeados
        $query->execute([
            ':nome'     => $nome,
            ':email'    => $email,
            ':telefone' => $telefone,
            ':foto'     => $foto,
        ]);

        $ok = true;
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $erro = 'Este e-mail já é cadastrado.';
        } else {
            $erro = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}

?>

<!doctype html>
<meta charset="utf-8">
<title>Salvar</title>

<!-- Se deu tudo certo no cadastro, mostra mensagem de sucesso -->
<?php if ($ok): ?>
  <p>Dados salvos com sucesso!</p>
  <p><a href="formulario.php">Voltar</a></p>

<!-- Se não deu certo, entra aqui -->
<?php else: ?>

  <!-- Se existe mensagem de erro, exibe em vermelho -->
  <?php if ($erro): ?>
    <!-- htmlspecialchars() → função nativa do PHP que converte caracteres especiais em HTML seguro -->
    <!-- Evita que alguém insira tags HTML ou scripts maliciosos dentro da mensagem -->
    <p style="color:red;"><?= htmlspecialchars($erro) ?></p>

  <!-- Se chegou aqui sem erro e sem POST, o usuário acessou a página diretamente -->
  <?php else: ?>
    <p>Nada enviado.</p>
  <?php endif; ?>

  <!-- Link pra voltar pro formulário -->
  <p><a href="formulario.html">Voltar</a></p>

<?php endif; ?>
