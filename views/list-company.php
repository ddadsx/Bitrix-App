<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Bitrix Home</title>
</head>
<body>
    <div class="container">
        <?php
            if(isset($ret)) {
                if(count($ret->result) == 0)
                    print('<h2>Nenhuma empresa cadastrada.</h2>');
                else{
        ?>
        <table id="company">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Negócios Ganhos</th>
            </tr>
            <?php
                if(isset($ret)) {
                    foreach($ret->result as $key=>$value){
                        print(' <tr>
                                    <td>'.$value->ID.'</td>
                                    <td>'.$value->TITLE.'</td>
                                    <td>'.$value->BANKING_DETAILS.'</td>
                                    <td>R$ '.$value->REVENUE.'</td>
                                    <td><a class="icon" href="?op=del-company&id='.$value->ID.'"><img src="./public/trash.png" alt="" height="20" width="20"></a></td>
                                </tr>');
                    }
                }
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