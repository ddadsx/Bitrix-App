<?php
    define('HOOK', 'https://b24-o10r3l.bitrix24.com.br/rest/1/015ap0dmalp0n2jd/');
    
    class Deal{

        static function find($id){
            $function = HOOK.'crm.deal.list.json';

            $data = http_build_query(array(
                "fields" => array(
                    //"STAGE_ID"=> "WON", 					
                    "ID" => $id,
                ),
                "select" => ["ID", "TITLE", "OPPORTUNITY", "COMPANY_ID", "STAGE_ID"]));
            
            return json_decode(Deal::execute_curl($function, $data));
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