<?php
    require_once('Hook.php');
    
    /**
     * Classe contendo a lógica de manipulação da entidade Negócio.
     * Utilizada apenas pelo webhook de saída
     * 
     * @author Douglas Silva
     */
    class Deal{

        /**
         * Busca um Negócio pelo ID
         * @param string ID do Negócio
         */
        static function find($id){
            $function = HOOK.'crm.deal.get.json';

            $data = http_build_query(array(					
                "ID" => $id
            ));
            return json_decode(Deal::execute_curl($function, $data));
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