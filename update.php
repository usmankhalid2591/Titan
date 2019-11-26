<?php
function getAssignedUser($mode){
    if ($mode=="live"){
        $mode="laravel";
    }else{
        $mode="laravel-beta";
    }
    $link="http://titanhub.co.uk/$mode/crmmanager/service/api_users.php?method=get_users";
    $data=(array)curlPostRequest($link);
    return $data;
}

function getTranslatedTimeZoneForSpecifiedDateTime($dateTime, $currentTimeZone = 'GMT', $newTimeZone = 'Europe/London', $dateTimeFormat = 'Y-m-d H:i:s') {

    $date_time_old = new \DateTime($dateTime, new \DateTimeZone($currentTimeZone));

    $date_time_old->setTimezone(new \DateTimeZone($newTimeZone));

    $result = $date_time_old->format($dateTimeFormat);

    return $result;
}

function dbRequest($limit,$offset,$desc,$dateStart,$sortBy,$orderBy,$assignedUser,$tab,$mode){
    
    $desc=trim($desc);
    $orderBy=rawurlencode($orderBy);
    $sortBy=rawurlencode($sortBy);
    if ($mode=="live"){
        $mode="laravel";
    }else{
        $mode="laravel-beta";
    }
    $dateStart = date_format(date_create_from_format('m/d/Y', $dateStart), 'Y-m-d');
    $dateStart=getTranslatedTimeZoneForSpecifiedDateTime($dateStart, $currentTimeZone = 'Europe/London', $newTimeZone = 'GMT', $dateTimeFormat = "Y-m-d");

    $dateStart=rawurlencode($dateStart." 00:00:00");
    $link="http://titanhub.co.uk/$mode/crmmanager/service/api_viewings.php?method=get_viewings_feedback&limit=$limit&offset=$offset&date_start=$dateStart&sort_date=$sortBy&sort_order=$orderBy&feedback_status=$tab";
  
    if($desc!=""){
        $desc=rawurlencode($desc);
        $link=$link."&description=$desc";  
    }
    if ($assignedUser!="all"){
        $assignedUser=rawurldecode($assignedUser);
        $link=$link."&assigned_user_id=$assignedUser";
    }
    
    $data=(array)curlPostRequest($link);
    return $data;
}

function titanUpdateService($module, $parameters = array(), $mode='beta') {

    $url = "http://6gvt-nynn.accessdomain.com/spcentralengine/sugarcrm/interface/service.php?action=updaterecord&module=" . $module . "&mode=".$mode."&source=titan&update_date_modified=false";
   
    /* $parameters = array();
    
    $parameters['id'] = '41572BE5-49B5-44A1-89FE-81D986C6EF3F';
    $parameters['status'] = 'Cancelled_No_Show_Dead'; */
    
    $displayFields = array_keys($parameters);
    
    $postFields = array('displayfields' => json_encode($displayFields), 'parameters' => json_encode($parameters));
    //print_r($postFields);
    $result = curlPostRequest($url, $postFields);
    return $result;
    }
  
function curlPostRequest($link, $postFields=array(), $jsonDecoded = true) {
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
    if(count($postFields) > 0) {
    // echo "curl";
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($jsonDecoded === false) {
        #echo ($response);
    return $response;
    } else {
       
    return json_decode($response);
    }
    }

?>