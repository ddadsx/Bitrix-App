<?php
    require_once('Hook.php');

    /**
     * Classe contendo toda lógica de manipulação da entidade Contato
     * 
     * @author Douglas Silva
     */
    class Contact{
        private $name = '';
        private $lastname = '';
        private $cpf = '';
        private $tel = '';
        private $email = '';
        private $cnpj = '';

        function __construct($name,$lastname,$cpf,$tel,$email,$cnpj){
            $this->name = $name;
            $this->lastname = $lastname;
            $this->cpf = $cpf;
            $this->tel = $tel;
            $this->email = $email;
            $this->cnpj = $cnpj;
        }

        /**
         * Salva o contato no sistema Bitrix
         */
        function save(){
            $function = HOOK.'crm.contact.add.json';
            $data = http_build_query(array(
                'fields' => array(
                "NAME" => $this->name,
                "LAST_NAME" => $this->lastname,
                "OPENED" => "Y",
                "ASSIGNED_BY_ID" => 1,
                "TYPE_ID" => "CLIENT",
                "SOURCE_ID" => "SELF",
                "UF_CRM_1581540845" => $this->cpf,
                "COMMENTS" => "CPNJ: ".$this->cnpj,
                "PHONE" => array(array("VALUE" => $this->tel, "VALUE_TYPE" => "WORK" )),
                "EMAIL" => array(array("VALUE" => $this->email, "VALUE_TYPE" => "WORK" )),
                ),
                'params' => array("REGISTER_SONET_EVENT" => "Y")
                ));

            return json_decode(Contact::execute_curl($function, $data));
        }

        /**
         * Atualiza os dados do contato
         * 
         * @param string ID do contato que será atualizado
         */
        function update($id){
            $function = HOOK.'crm.contact.update.json';
            $data = http_build_query(array(
                "ID" => $id,
                'fields' => array(
                "NAME" => $this->name,
                "LAST_NAME" => $this->lastname,
                "OPENED" => "Y",
                "ASSIGNED_BY_ID" => 1,
                "TYPE_ID" => "CLIENT",
                "SOURCE_ID" => "SELF",
                "UF_CRM_1581540845" => $this->cpf,
                "COMMENTS" => "CPF: ".$this->cpf."\n CPNJ: ".$this->cnpj,
                "PHONE" => array(array("VALUE" => $this->tel, "VALUE_TYPE" => "WORK" )),
                "EMAIL" => array(array("VALUE" => $this->email, "VALUE_TYPE" => "WORK" )),
                ),
                ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

        /**
         * Remove o contato do sistema
         * 
         * @param string ID do contato que será removido
         */
        static function delete($id){
            $function = HOOK.'crm.contact.delete.json';
            $data = http_build_query(array(
                "ID" => $id,
                ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

        /**
         * Lista todos os contatos cadastrados
         */
        static function findAll(){
            $function = HOOK.'crm.contact.list.json';
            $data = http_build_query(array(
                'filter' => array(),
                'select' => ["ID", "NAME", "LAST_NAME", "UF_CRM_1581540845", "PHONE", "EMAIL", "COMPANY_ID"]
                ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

        /**
         * Procura um contato específico pelo CPF
         * 
         * @param string CPF
         */
        static function findByCpf($cpf){
            $function = HOOK.'crm.contact.list.json';
            $data = http_build_query(array(
                'filter' => array(
                    "UF_CRM_1581540845" => $cpf,
                ),
                'select' => ["ID", "NAME", "LAST_NAME", "UF_CRM_1581540845", "PHONE", "EMAIL"]
                ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

        /**
         * Procura um contato específico pelo ID interno
         * 
         * @param string ID
         */
        static function findByID($id){
            $function = HOOK.'crm.contact.get.json';
            $data = http_build_query(array(
                "ID" => $id
            ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

        /**
         * Adiciona o contato a uma empresa específica
         * 
         * @param string ID do contato
         * @param string ID da empresa
         */
        static function addCompany($contactId,$companyId){
            $function = HOOK.'crm.contact.company.add.json';
            $data = http_build_query(array(
                "ID" => $contactId,
                'fields' => array(
                    "COMPANY_ID" => $companyId,
                ),
                ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

        /**
         * Remove o contato de uma empresa específica
         * 
         * @param string ID do contato
         * @param string ID da empresa
         */
        static function removeCompany($contactId,$companyId){
            $function = HOOK.'crm.contact.company.delete.json';
            $data = http_build_query(array(
                "ID" => $contactId,
                'fields' => array(
                    "COMPANY_ID" => $companyId,
                ),
                ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

        /**
         * Executa o cURL para fazer acesso à API do Bitrix
         * 
         * @param string URL da API específica para cada função executada
         * @param array Dados a serem enviados ao sistema Bitrix
         */
        private static function execute_curl($function, $data) {
            $ch = curl_init($function);

            curl_setopt_array($ch, array(
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $data,
                ));

            $ret = curl_exec($ch);
            curl_close($ch);

            return $ret;
        }


    } 
?>