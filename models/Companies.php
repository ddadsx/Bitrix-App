<?php
    class Company {
        private $companyName;
        private$cnpj;

        function __construct($companyName,$cnpj){
            $this->companyName = $companyName;
            $this->cnpj = $cnpj;
        }

        function save($hook){
            $data = http_build_query(array(
                'fields' => array(
                    "TITLE" => $this->companyName,
                    "OPENED" => "Y",
                    "ASSIGNED_BY_ID" => 1,
                    "SOURCE_ID" => "SELF",
                    "BANKING_DETAILS" => $this->cnpj,
                    "COMMENTS" => "CPNJ: ".$this->cnpj
                ),
                'params' => array("REGISTER_SONET_EVENT" => "Y")
            ));

            return json_decode($this->execute_curl($hook, $data));
        }

        static function delete($hook,$id){
            $data = http_build_query(array(
                "ID" => $id,
                ));
            
            return json_decode(Company::execute_curl($hook, $data));
        }

        static function findAll($hook){
            $data = http_build_query(array(
                'filter' => array(),
                'select' => ["ID", "TITLE", "BANKING_DETAILS"]
                ));
            
            return json_decode(Company::execute_curl($hook, $data));
        }

        static function find($hook,$cnpj){
            $data = http_build_query(array(
                'filter' => array(
                    "BANKING_DETAILS" => $cnpj,
                ),
                'select' => ["ID", "TITLE", "BANKING_DETAILS"]
                ));
            
            return json_decode(Company::execute_curl($hook, $data));
        }

        function addContact($hook,$companyId,$contactId){
            $data = http_build_query(array(
                "ID" => $companyId,
                'fields' => array(
                    "CONTACT_ID" => $contactId,
                ),
                ));
            
            return json_decode(Company::execute_curl($hook, $data));
        }

        private static function execute_curl($hook, $data) {
            $ch = curl_init($hook);

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