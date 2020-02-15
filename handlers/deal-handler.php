<?php
    function log_dump($str){
        ob_start();
        var_dump($str);
        $debug_dump = ob_get_clean();

        $myFile = fopen("../dump.txt", "a");
        fwrite($myFile,$debug_dump);
        fclose($myFile);
    }

    if(isset($_REQUEST)){
        require_once('../models/Deals.php');
        require_once('../models/Companies.php');
        //log_dump($_REQUEST);

        if($_REQUEST['event'] == "ONCRMDEALADD") {
            $ret = Deal::find($_REQUEST['data']['FIELDS']['ID']);
            
            if ($ret->result[count($ret->result)-1]->STAGE_ID == "WON"){
                $companyId = $ret->result[count($ret->result)-1]->COMPANY_ID;
                $amount = $ret->result[count($ret->result)-1]->OPPORTUNITY;

                $ret = Company::addRevenue($companyId,$amount);  
                log_dump($ret);              
            }
        }
    }
?>