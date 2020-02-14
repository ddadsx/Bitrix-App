<?php
    function log_dump($str){
        ob_start();
        var_dump($str);
        $debug_dump = ob_get_clean();

        $myFile = fopen("../dump.txt", "w");
        fwrite($myFile,$debug_dump);
        fclose($myFile);
    }

    if(isset($_REQUEST)){
        require_once('../models/Deals.php');
        if(!strcmp($_REQUEST['event'],"ONCRMDEALADD")) {
            $ret = Deal::find($_REQUEST['data']['FIELDS']['ID']);
            
            log_dump($ret);
        }
    }
?>



