<?php

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

        function save($hook){
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

            $this->execute_curl($hook, $data);
        }

        static function delete($hook,$id){
            $data = http_build_query(array(
                "ID" => $id,
                ));
            
            return json_decode(Contact::execute_curl($hook, $data));
        }

        function update($hook,$id){
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
            
            return json_decode(Contact::execute_curl($hook, $data));
        }

        static function findAll($hook){
            $data = http_build_query(array(
                'filter' => array(),
                'select' => ["ID", "NAME", "LAST_NAME", "UF_CRM_1581540845", "PHONE", "EMAIL"]
                ));
            
            return json_decode(Contact::execute_curl($hook, $data));
        }

        static function find($hook,$cpf){
            $data = http_build_query(array(
                'filter' => array(
                    "UF_CRM_1581540845" => $cpf,
                ),
                'select' => ["ID", "NAME", "LAST_NAME", "UF_CRM_1581540845", "PHONE", "EMAIL"]
                ));
            
            return json_decode(Contact::execute_curl($hook, $data));
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