<?php
    require_once('Hook.php');

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

            return json_decode($this->execute_curl($function, $data));
        }

        static function delete($id){
            $function = HOOK.'crm.contact.delete.json';
            $data = http_build_query(array(
                "ID" => $id,
                ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

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

        static function findAll(){
            $function = HOOK.'crm.contact.list.json';
            $data = http_build_query(array(
                'filter' => array(),
                'select' => ["ID", "NAME", "LAST_NAME", "UF_CRM_1581540845", "PHONE", "EMAIL", "COMPANY_ID"]
                ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

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

        static function findByID($id){
            $function = HOOK.'crm.contact.get.json';
            $data = http_build_query(array(
                "ID" => $id
            ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

        function addCompany($contactId,$companyId){
            $function = HOOK.'crm.contact.company.add.json';
            $data = http_build_query(array(
                "ID" => $contactId,
                'fields' => array(
                    "COMPANY_ID" => $companyId,
                ),
                ));
            
            return json_decode(Contact::execute_curl($function, $data));
        }

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