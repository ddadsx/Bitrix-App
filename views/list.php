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
            if(isset($contact)) {
                if(count($contact->result) == 0)
                    print('<h2>Nenhum contato cadastrado.</h2>');
                else{
        ?>
        <table id="contact">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Sobrenome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>CPF</th>
                <th>Empresa</th>
                <th>CNPJ</th>
            </tr>
            <?php
                if(count($contact->result)>0 and count($company->result)>0) {
                    foreach($contact->result as $value){
                        $companyIndex = -1;
                        foreach($company->result as $index=>$c){
                            if(in_array($value->COMPANY_ID,(array)$c)){
                                $companyIndex = $index;
                            }
                        }
                        if($companyIndex == -1) {
                            $companyName = '-';
                            $companyCnpj = '-';
                        }
                        else{
                            $companyName = $company->result[$companyIndex]->TITLE;
                            $companyCnpj = $company->result[$companyIndex]->BANKING_DETAILS;
                        }

                        print(  '<tr>
                                    <td>'.$value->ID.'</td>
                                    <td>'.$value->NAME.'</td>
                                    <td>'.$value->LAST_NAME.'</td>
                                    <td>'.$value->PHONE[0]->VALUE.'</td>
                                    <td>'.$value->EMAIL[0]->VALUE.'</td>
                                    <td>'.$value->UF_CRM_1581540845.'</td>
                                    <td>'.$companyName.'</td>
                                    <td>'.$companyCnpj.'</td>
                                    <td><a class="icon" href="?op=del&id='.$value->ID.'"><img src="./public/trash.png" alt="" height="20" width="20"></a></td>
                                    <td><a class="icon" href="?op=fill-form&id='.$value->ID.'"><img src="./public/refresh.png" alt="" height="20" width="20"></a></td>
                                </tr>');
                    }
                }
                //print(count($contact->result));
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