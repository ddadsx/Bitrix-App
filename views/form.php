<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Bitrix Form</title>
</head>
<body>
    <div class="container">
    <div class="<?=$this->msg_class?>"><?=$this->msg?></div>
        <form method="POST">

            <input type="hidden" id="id" name="id" value="<?=$this->id;?>">

            <div class="form-field">
                <label for="name">Nome:</label>
                <input type="text" name="name" id="name" value="<?=$this->name;?>">
            </div>

            <div class="form-field">
                <label for="lastname">Sobrenome:</label>
                <input type="text" name="lastname" id="lastname" value="<?=$this->lastname;?>">
            </div>

            <div class="form-field">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?=$this->email;?>">
            </div>

            <div class="form-field">
                <label for="tel">Telefone:</label>
                <input type="number" name="tel" id="tel" value="<?=$this->tel;?>">
            </div>

            <div class="form-field">
                <label for="cpf">CPF: <span>* Somente números</span></label>
                <input type="text" name="cpf" id="cpf" pattern="\d{11}" value="<?=$this->cpf;?>">
            </div>

            <div class="form-field">
                <label for="companyName">Nome da Empresa:</label>
                <input type="text" name="companyName" id="companyName" value="<?=$this->companyName;?>">
            </div>

            <div class="form-field">
                <label for="cnpj">CNPJ: <span>* Somente números</span></label>
                <input type="text" name="cnpj" id="cnpj" pattern="\d{14}" value="<?=$this->cnpj;?>">
            </div>

            <div class="buttons">
                <button class="btn" type="submit" formaction="?op=save" name="process_form">Enviar</button>
                <button class="btn" type="submit" formaction="?op=index" name="index">Voltar</button>
                <button class="btn <?=$this->update_class;?>"type="submit" formaction="?op=update" name="update_contact">Atualizar</button>
            </div>

        </form>
    </div>
</body>
</html>