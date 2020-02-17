<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Bitrix List</title>
</head>
<body>
    <div class="container">
        <?php
            if(isset($contact) or isset($company)) {
                if ($header == 'CONTATOS' and count($contact->result) == 0) {
                    print($error);
                }
                elseif ($header == 'EMPRESAS' and count($company->result) == 0) {
                    print($error);
                }
                else{
        ?>
        <div class="table-header"><?=$header?></div>
        <table>
                <?=$fields?>
                <?=$rows?>
        </table>
        <?php
                }
            }
        ?>
        <a class="btn" href="?op=index">Voltar</a>
    </div>
</body>
</html>