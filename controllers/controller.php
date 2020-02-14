<?php
    require_once('models/Contacts.php');
    require_once('models/Companies.php');
    require_once('models/Deals.php');
    
    define('CONTACT_ADD_HOOK', 'https://b24-o10r3l.bitrix24.com.br/rest/1/015ap0dmalp0n2jd/crm.contact.add.json');
    define('CONTACT_LIST_HOOK', 'https://b24-o10r3l.bitrix24.com.br/rest/1/015ap0dmalp0n2jd/crm.contact.list.json');
    define('CONTACT_UPDATE_HOOK', 'https://b24-o10r3l.bitrix24.com.br/rest/1/015ap0dmalp0n2jd/crm.contact.update.json');
    define('CONTACT_DELETE_HOOK', 'https://b24-o10r3l.bitrix24.com.br/rest/1/015ap0dmalp0n2jd/crm.contact.delete.json');
    define('COMPANY_ADD_HOOK', 'https://b24-o10r3l.bitrix24.com.br/rest/1/015ap0dmalp0n2jd/crm.company.add.json');
    define('COMPANY_LIST_HOOK', 'https://b24-o10r3l.bitrix24.com.br/rest/1/015ap0dmalp0n2jd/crm.company.list.json');
    define('COMPANY_DELETE_HOOK', 'https://b24-o10r3l.bitrix24.com.br/rest/1/015ap0dmalp0n2jd/crm.company.delete.json');
    
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
                session_start();
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

                $contact = new Contact($_POST['name'], $_POST['lastname'],$_POST['cpf'],$_POST['tel'],$_POST['email'],$_POST['cnpj']);
                $company = new Company($_POST['companyName'],$_POST['cnpj']);
                $company->save(COMPANY_ADD_HOOK);

                $ret = Contact::find(CONTACT_LIST_HOOK,$_POST['cpf']);
                if(count($ret->result) == 0){
                    $contact->save(CONTACT_ADD_HOOK);
                    $this->msg = "<h2>AVISO</h2><br/><p>O contato foi salvo com sucesso.</p>";
                    $this->msg_class = 'message-success';
                    require('views/index.php');
                }
                else{
                    $this->msg = "<h2>AVISO</h2><br/><p>O contato já existe, se deseja realizar a alteração deste contato clique no botão \"Atualizar\".</p>";
                    $this->msg_class = 'message-warning';

                    $_SESSION['id'] = $ret->result[0]->ID;
                    $_SESSION['contact'] = $contact;
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
            $_SESSION['contact']->update(CONTACT_UPDATE_HOOK,$_SESSION['id']);
            $this->msg = "<h2>AVISO</h2><br/><p>O contato foi atualizado com sucesso.</p>";
            $this->msg_class = 'message-success';
            require('views/index.php');
        }

        function delete($id,$op){
            if(!strcmp($op,'del-company')){
                $ret = Company::delete(COMPANY_DELETE_HOOK,$id);
                $this->msg = "<h2>AVISO</h2><br/><p>A empresa foi removida com sucesso.</p>";
                $this->msg_class = 'message-success';
            }
            else{
                $ret = Contact::delete(CONTACT_DELETE_HOOK,$id);
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
            $ret = Contact::findAll(CONTACT_LIST_HOOK);
            require('views/list.php');
        }

        function listCompany(){
            $ret = Company::findAll(COMPANY_LIST_HOOK);
            require('views/list-company.php');
        }
    } 
?>