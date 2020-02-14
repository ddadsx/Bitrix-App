<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Bitrix Home</title>
</head>
<body>
    <div class="container column">
        <?php
            if(isset($ret)) {
                if(count($ret->result) == 0)
                    print('<h2>Nenhum contato cadastrado.</h2>');
                else{
        ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
            </tr>
            <?php
                if(isset($ret)) {
                    foreach($ret->result as $key=>$value){
                        print('<tr><th>'.$value->ID.'</th><th>'.$value->NAME.'</th><th>'.$value->UF_CRM_1581540845.'</th><th><a class="delete-btn" href="?op=del&id='.$value->ID.'">X</a></th></tr>');
                    }
                }
                //print(count($ret->result));
            ?>
        </table>
        <?php
                }
            }
        ?>
        <a class="btn" href="?op=index">Voltar</a>
    </div>
</body>
</html>