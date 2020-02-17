<?php
    require_once('Hook.php');

    /**
     * Classe contendo toda lógica de manipulação da entidade Empresa
     * 
     * @author Douglas Silva
     */
    class Company {
        private $companyName;
        private $cnpj;

        function __construct($companyName,$cnpj){
            $this->companyName = $companyName;
            $this->cnpj = $cnpj;
        }

        /**
         * Salva a empresa no sistema Bitrix
         */
        function save(){
            $function = HOOK.'crm.company.add.json';
            $data = http_build_query(array(
                'fields' => array(
                    "TITLE" => $this->companyName,
                    "OPENED" => "Y",
                    "ASSIGNED_BY_ID" => 1,
                    "SOURCE_ID" => "SELF",
                    "BANKING_DETAILS" => $this->cnpj,
                    "CURRENCY_ID" => "BRL",
                    "REVENUE" => "0.0",
                    "COMMENTS" => "CPNJ: ".$this->cnpj
                ),
                'params' => array("REGISTER_SONET_EVENT" => "Y")
            ));

            return json_decode(Company::execute_curl($function, $data));
        }

        /**
         * Atualiza os dados da empresa
         * 
         * @param string ID da empresa que será atualizada
         */
        function update($id){
            $function = HOOK.'crm.company.update.json';
            $data = http_build_query(array(
                "ID" => $id,
                'fields' => array(
                    "TITLE" => $this->companyName,
                    "OPENED" => "Y",
                    "ASSIGNED_BY_ID" => 1,
                    "SOURCE_ID" => "SELF",
                    "BANKING_DETAILS" => $this->cnpj,
                    "CURRENCY_ID" => "BRL",
                    "REVENUE" => "0.0",
                    "COMMENTS" => "CPNJ: ".$this->cnpj
                ),
            ));
            
            return json_decode(Company::execute_curl($function, $data));
        }

        /**
         * Remove a empresa do sistema
         * 
         * @param string ID da empresa que será removida
         */
        static function delete($id){
            $function = HOOK.'crm.company.delete.json';
            $data = http_build_query(array(
                "ID" => $id,
                ));
            
            return json_decode(Company::execute_curl($function, $data));
        }

        /**
         * Lista todas as empresas cadastradas
         */
        static function findAll(){
            $function = HOOK.'crm.company.list.json';
            $data = http_build_query(array(
                'filter' => array(),
                'select' => ["ID", "TITLE", "BANKING_DETAILS", "REVENUE"]
                ));
            
            return json_decode(Company::execute_curl($function, $data));
        }

        /**
         * Procura uma empresa específica pelo CNPJ
         * 
         * @param string CNPJ
         */
        static function findByCnpj($cnpj){
            $function = HOOK.'crm.company.list.json';
            $data = http_build_query(array(
                'filter' => array(
                    "BANKING_DETAILS" => $cnpj,
                ),
                'select' => ["ID", "TITLE", "BANKING_DETAILS", "REVENUE"]
                ));
            
            return json_decode(Company::execute_curl($function, $data));
        }

        /**
         * Procura uma empresa específica pelo ID interno
         * 
         * @param string ID
         */
        static function findById($id){
            $function = HOOK.'crm.company.get.json';
            $data = http_build_query(array(
                    "ID" => $id
                ));
            
            return json_decode(Company::execute_curl($function, $data));
        }

        /**
         * Adiciona um contato específico à empresa
         * 
         * @param string ID da empresa
         * @param string ID do contato
         */
        static function addContact($companyId,$contactId){
            $function = HOOK.'crm.company.contact.add.json';
            $data = http_build_query(array(
                "ID" => $companyId,
                'fields' => array(
                    "CONTACT_ID" => $contactId,
                ),
                ));
            
            return json_decode(Company::execute_curl($function, $data));
        }

        /**
         * Remove um contato específico da empresa
         * 
         * @param string ID da empresa
         * @param string ID do contato
         */
        static function removeContact($companyId,$contactId){
            $function = HOOK.'crm.company.contact.delete.json';
            $data = http_build_query(array(
                "ID" => $companyId,
                'fields' => array(
                    "CONTACT_ID" => $contactId,
                ),
                ));
            
            return json_decode(Company::execute_curl($function, $data));
        }

        /**
         * Adiciona o valor de um negócio ganho à quantia total da empresa
         * 
         * @param string ID da empresa
         * @param float Valor do negócio ganho
         */
        static function addRevenue($companyId,$amount){
            $ret = Company::findById($companyId);

            $amount += floatval($ret->result->REVENUE);

            $function = HOOK.'crm.company.update.json';
            $data = http_build_query(array(
                "ID" => $companyId,
                'fields' => array(
                    "REVENUE" => $amount
                )
            ));
            
            return json_decode(Company::execute_curl($function, $data));
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