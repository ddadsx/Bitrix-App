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

        if($_REQUEST['event'] == "ONCRMDEALADD") {
            $ret = Deal::find($_REQUEST['data']['FIELDS']['ID']);            
            if ($ret->result->STAGE_ID == "WON"){
                $companyId = $ret->result->COMPANY_ID;
                $amount = $ret->result->OPPORTUNITY;
                $ret = Company::addRevenue($companyId,$amount);              
            }
        }
    }
?>