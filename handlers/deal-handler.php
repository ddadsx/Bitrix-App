<?php

    /**
     * Ao receber uma requisição, busca o Negócio pelo ID e caso ele tenha sido ganho,
     * o seu valor é somado à Empresa em questão
     * 
     * @author Douglas Silva
     */
    if(isset($_REQUEST)){
        require_once('../models/Deals.php');
        require_once('../models/Companies.php');
            
        if(($_REQUEST['event'] == "ONCRMDEALADD") or ($_REQUEST['event'] == "ONCRMDEALUPDATE")) {
            $ret = Deal::find($_REQUEST['data']['FIELDS']['ID']);            
            if ($ret->result->STAGE_ID == "WON"){
                $companyId = $ret->result->COMPANY_ID;
                $amount = $ret->result->OPPORTUNITY;
                $ret = Company::addRevenue($companyId,$amount);
            }
        }
    }
?>