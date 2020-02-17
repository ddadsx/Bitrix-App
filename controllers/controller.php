<?php
    require_once('models/Contacts.php');
    require_once('models/Companies.php');
    require_once('models/Deals.php');

    /**
     * Classe controladora do CRUD integrado ao Bitrix
     * 
     * @author Douglas Silva
     */
    class Controller{
        private $id = '';
        private $name = '';
        private $lastname = '';
        private $email = '';
        private $tel = '';
        private $cpf = '';
        private $companyName = '';
        private $cnpj = '';

        private $msg_class = 'message-hidden';
        private $update_class = 'btn-hidden';
        
        private $msg = '';

        /**
         * Função que inicia o sistema e direciona as requisições internamente
         */
        public function init(){
            if (isset($_GET['op'])) {
                $op = $_GET['op'];
            } else {
                $op = "";
            }

            switch ($op) {
                case 'save':
                    $this->save();
                break;
                case 'update':
                    $this->update();
                break;
                case 'del':
                    if(isset($_GET['id']))
                        $this->delete($_GET['id'],$op);
                break;
                case 'fill-form':
                    if(isset($_GET['id']))
                        $this->fillForm($_GET['id'],$op);
                break;
                case 'del-company':
                    if(isset($_GET['id']))
                        $this->delete($_GET['id'],$op);
                break;
                case 'form':
                    $this->form();
                break;
                case 'list-contact':
                    $this->listContact();
                break;
                case 'list-company':
                    $this->listCompany();
                break;                
                default:
                    $this->index();
                break;
            }
        }

        /**
         * Apresenta a página inicial
         */
        function index(){
            require('views/index.php');
        }

        /**
         * Processa o formulário de cadastro e salva o contato no sistema Bitrix
         * Caso o contato já exista, ele poderá ser atualizado
         * Apresenta a página inicial com uma mensagem ao usuário
         */
        function save(){
            if(isset($_POST['process_form'])){
                unset($_POST['process_form']);
                
                if($_POST['name'] == '' || $_POST['lastname'] == '' 
                    || $_POST['cpf'] == '' || $_POST['tel'] == '' 
                    || $_POST['email'] == '' || $_POST['companyName'] == ''
                    || $_POST['cnpj'] == ''){

                    $this->msg = "<h2>AVISO</h2><br/><p>Todos os campos são obrigatórios.</p>";
                    $this->msg_class = 'message-warning';

                    $this->name = $_POST['name'];
                    $this->lastname = $_POST['lastname'];
                    $this->email = $_POST['email'];
                    $this->tel = $_POST['tel'];
                    $this->cpf = $_POST['cpf'];
                    $this->companyName = $_POST['companyName'];
                    $this->cnpj = $_POST['cnpj'];

                    require('views/form.php');
                    exit();
                }

                $contact = new Contact($_POST['name'], $_POST['lastname'],$_POST['cpf'],$_POST['tel'],$_POST['email'],$_POST['cnpj']);
                $company = new Company($_POST['companyName'],$_POST['cnpj']);

                $ret = Contact::findByCpf($_POST['cpf']);
                if(count($ret->result) == 0){

                    $retCompany = Company::findByCnpj($_POST['cnpj']);
                    if(count($retCompany->result) == 0){
                        $retCompany = $company->save();
                        $companyId = strval($retCompany->result);
                    }
                    else $companyId = $retCompany->result[0]->ID;
                        
                    $ret = $contact->save();

                    Contact::addCompany(strval($ret->result),$companyId);
                    Company::addContact($companyId,strval($ret->result));

                    $this->msg = "<h2>AVISO</h2><br/><p>O contato foi salvo com sucesso.</p>";
                    $this->msg_class = 'message-success';
                    require('views/index.php');
                }
                else{
                    $this->msg = "<h2>AVISO</h2><br/><p>O contato já existe, se deseja realizar a alteração deste contato clique no botão \"Atualizar\".</p>";
                    $this->msg_class = 'message-warning';

                    $this->id = $ret->result[0]->ID;
                    $this->name = $_POST['name'];
                    $this->lastname = $_POST['lastname'];
                    $this->email = $_POST['email'];
                    $this->tel = $_POST['tel'];
                    $this->cpf = $_POST['cpf'];
                    $this->companyName = $_POST['companyName'];
                    $this->cnpj = $_POST['cnpj'];
                    
                    $this->update_class = 'btn-show';
                    
                    require('views/form.php');
                }
            }
        }

        /**
         * Preenche o formulário com os dados do contato buscado que será atualizado
         * Apresenta a página do formulário com os campos preenchidos
         * 
         * @param string ID do contato
         */
        function fillForm($id){
            $contact = Contact::findById($id);
            $company = Company::findById($contact->result->COMPANY_ID);
            
            $this->id = $id;
            $this->name = $contact->result->NAME;
            $this->lastname = $contact->result->LAST_NAME;
            $this->email = $contact->result->EMAIL[0]->VALUE;
            $this->tel = $contact->result->PHONE[0]->VALUE;
            $this->cpf = $contact->result->UF_CRM_1581540845;
            $this->companyName = property_exists($company,'error') ? '' : $company->result->TITLE;
            $this->cnpj = property_exists($company,'error') ? '' :  $company->result->BANKING_DETAILS;
            
            $this->update_class = 'btn-show';
            
            require('views/form.php');
        }

        /**
         * Atualiza o contato com os dados preenchidos do formulário
         * Apresenta a página inicial com uma mensagem ao usuário
         */
        function update(){
            $contact = new Contact($_POST['name'], $_POST['lastname'],$_POST['cpf'],$_POST['tel'],$_POST['email'],$_POST['cnpj']);

            $ret = Company::findByCnpj($_POST['cnpj']);
            if(!count($ret->result)){
                $company = new Company($_POST['companyName'],$_POST['cnpj']);
                $retCompany = $company->save();
                $companyId = strval($retCompany->result);
                $contact->update($_POST['id']);
                
                Contact::removeCompany($_POST['id'],$companyId);
                Contact::addCompany($_POST['id'],$companyId);
                Company::removeContact($companyId,$_POST['id']);
                Company::addContact($companyId,$_POST['id']);
            }
            else {
                $company = new Company($_POST['companyName'],$_POST['cnpj']);
                $companyId = $ret->result[0]->ID;
                $company->update($companyId);

                $retContact = $contact::findById($_POST['id']);

                $contact->update($_POST['id']);

                Contact::removeCompany($_POST['id'],$retContact->result->COMPANY_ID);
                Contact::addCompany($_POST['id'],$companyId);

                Company::removeContact($retContact->result->COMPANY_ID,$_POST['id']);
                Company::addContact($companyId,$_POST['id']);
            }
            $this->msg = "<h2>AVISO</h2><br/><p>O contato foi atualizado com sucesso.</p>";
            $this->msg_class = 'message-success';
            require('views/index.php');
        }

        /**
         * Remove o contato ou a empresa de acordo com o ID
         * Apresenta a página inicial com uma mensagem ao usuário
         * 
         * @param string ID do contato ou ID da empresa
         * @param string Operação a ser realizada: remover contato (del) ou remover empresa (del-company)
         */
        function delete($id,$op){
            if(!strcmp($op,'del-company')){

                $contact = Contact::findAll();
                foreach($contact->result as $value){
                    if($value->COMPANY_ID == $id){
                        Company::removeContact($id,$value->ID);
                        Contact::removeCompany($value->ID,$id);
                    }
                }

                $ret = Company::delete($id);
                $this->msg = "<h2>AVISO</h2><br/><p>A empresa foi removida com sucesso.</p>";
                $this->msg_class = 'message-success';
            }
            else{
                $ret = Contact::delete($id);
                $this->msg = "<h2>AVISO</h2><br/><p>O contato foi removido com sucesso.</p>";
                $this->msg_class = 'message-success';
            }
            unset($_GET['id']);
            require('views/index.php');
        }

        /**
         * Apresenta a página do formulário de cadastro
         */
        public function form(){
            require('views/form.php');
        }

        /**
         * Busca por todos os contatos e os exibe em uma lista
         * Apresenta a página de listagem
         */
        function listContact(){
            $contact = Contact::findAll();
            $company = Company::findAll();

            $header = 'CONTATOS';
            $fields = ' <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Sobrenome</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Empresa</th>
                            <th>CNPJ</th>
                            <th colspan="2">Ações</th>
                        </tr>';

            $rows = '';
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

                $rows .=  '<tr>
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
                        </tr>';
            }

            $error = '<h1>Nenhum contato cadastrado.</h1>';

            require('views/list.php');
        }

        /**
         * Busca por todas as empresas e as exibe em uma lista
         * Apresenta a página de listagem
         */
        function listCompany(){
            $company = Company::findAll();

            $header = 'EMPRESAS';
            $fields = ' <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Negócios Ganhos</th>
                            <th>Ações</th>
                        </tr>';
            
            $rows = '';
            foreach($company->result as $value){
                $rows .= '  <tr>
                                <td>'.$value->ID.'</td>
                                <td>'.$value->TITLE.'</td>
                                <td>'.$value->BANKING_DETAILS.'</td>
                                <td>R$ '.$value->REVENUE.'</td>
                                <td><a class="icon" href="?op=del-company&id='.$value->ID.'"><img src="./public/trash.png" alt="" height="20" width="20"></a></td>
                            </tr>';
            }

            $error = '<h1>Nenhuma empresa cadastrada.</h1>';
            require('views/list.php');
        }
    } 
?>