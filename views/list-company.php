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
                    print('<h2>Nenhuma empresa cadastrada.</h2>');
                else{
        ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CNPJ</th>
            </tr>
            <?php
                if(isset($ret)) {
                    foreach($ret->result as $key=>$value){
                        print('<tr><th>'.$value->ID.'</th><th>'.$value->TITLE.'</th><th>'.$value->BANKING_DETAILS.'</th><th><a class="delete-btn" href="?op=del-company&id='.$value->ID.'">X</a></th></tr>');
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