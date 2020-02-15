<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Bitrix Home</title>
</head>
<body>
    <div class="container index">
        <div id="msg-box" class="<?=$this->msg_class?>"><button onclick="document.getElementById('msg-box').className = 'message-hidden';"></button><?=$this->msg?></div>
        <a class="btn" href="?op=form">Cadastrar Contato</a>
        <a class="btn" href="?op=list-contact">Listar Contatos</a>
        <a class="btn" href="?op=list-company">Listar Empresas</a>
    </div>
</body>
</html>