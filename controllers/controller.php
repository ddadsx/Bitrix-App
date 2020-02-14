<?php
    require_once('models/Contacts.php');
    require_once('models/Companies.php');
    require_once('models/Deals.php');
    
    require_once('Hook.php');
    
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
        private $update = 'btn-hidden';
        
        private $msg = '';

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
        
        public function index(){
            require('views/index.php');
        }

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
                    //var_dump($retCompany);
                    //var_dump(' ');
                    if(count($retCompany->result) == 0){
                        $retCompany = $company->save();
                        $companyId = strval($retCompany->result);
                    }
                    else $companyId = $retCompany->result[0]->ID;

                    $ret = $contact->save();
                    
                    $contact->addCompany(strval($ret->result),$companyId);
                    $company->addContact($companyId,strval($ret->result));

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
                    
                    $this->update = 'btn-show';
                    

                    require('views/form.php');
                }
                //$contact->find(CONTACT_LIST_HOOK);
                //header('Location: index.php');

            }
        }

        function update(){
            $contact = new Contact($_POST['name'], $_POST['lastname'],$_POST['cpf'],$_POST['tel'],$_POST['email'],$_POST['cnpj']);
            $contact->update($_POST['id']);
            $this->msg = "<h2>AVISO</h2><br/><p>O contato foi atualizado com sucesso.</p>";
            $this->msg_class = 'message-success';
            require('views/index.php');
        }

        function delete($id,$op){
            if(!strcmp($op,'del-company')){
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

        public function form(){
            require('views/form.php');
        }

        function listContact(){
            $ret = Contact::findAll();
            require('views/list.php');
        }

        function listCompany(){
            $ret = Company::findAll();
            require('views/list-company.php');
        }
    } 
?>