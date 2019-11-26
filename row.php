<?php
include "update.php";
include "log.php";

$id='';
$description_modified='';
$type='';
if (isset($_POST['mode'])){
    $mode=$_POST['mode'];
}
if (isset($_POST['orignalDescription'])){
    $orignal_description=$_POST['orignalDescription'];
}
if (isset($_POST['description_modified'])){
    $description_modified=$_POST['description_modified'];
}
if (isset($_POST['description'])){
    $description=$_POST['description'];
}
if (isset($_POST['index'])){
    $index=$_POST['index'];
}

if (isset($_POST['id'])){
    $id=$_POST['id'];
}
if (isset($_POST['type'])){
    $type=$_POST['type'];
}
if ($type=="reject"){
    $description_modified=' ';
}else if ($type=="update" and trim($description_modified)=="" and trim($description)==""){
    $description_modified=$orignal_description;
}else if ($type=="update" and trim($description_modified)=="" and trim($description)!=""){
    $description_modified=$description;
}else if ($type!="update" and $type!="reject"){
    return;
}

$response=array();
$response['code']=0;
if ($id!=''){
    $parameters=array();
    $parameters['id']=$id;
    $parameters['description_modified']=$description_modified;
    if ($type=="reject"){
        $parameters["feedback_reviewed"]="2";
    }
    else if ($type=="update"){
        $parameters["feedback_reviewed"]="1";
    }
    
   

    $response=titanUpdateService($module, $parameters ,$mode); 
    $response=(array)$response; 
     
    
    
}
$rowId="row".$index;
$orignalFeedback="original".strval($index);
$feedback="feedback".strval($index);
$newFeedback="newFeedback".strval($index);

$error="";
if ($mode=="live"){
    $linkmode="laravel";
}
else {
    $linkmode="laravel-beta";
}

if ($response['code']==0){
    $description_modified=$description;
    $error="Sorry, we could not ".$type." the feedback";
    $newFeedbackField=$description_modified;



?>

<tr  id='<?php echo $rowId ?>'
<?php if ($type=="reject" and $response['code']==1){ ?>
 style="background-color:#FF0033"
<?php }elseif ($type=="update" and $response['code']==1){ ?>
 style="background-color:#ADFF2F;"
<?php } ?>
>

    <td>
    <a target="_blank" href="<?php echo "http://titanhub.co.uk/$linkmode/crm?module=Viewings&viewtype=DetailView&id=".$id?>">
                <u>
    <label style="cursor: pointer;text-decoration: underline;"   id="<?php echo $orignalFeedback?>" name="<?php echo $orignalFeedback ?>"  size="20"  ><?php  echo nl2br($orignal_description); ?></label>
    </u>
    </a>
    </td>

    <td>
        <label  id="<?php echo $feedback ?>" name="<?php echo $feedback ?>"  size="20"  >
    <?php
        echo  nl2br($description_modified);
        ?>
        </label>

    </td>
    <td>
        <textarea rows="3" cols="50" style="width:200px" id="<?php echo $newFeedback ?>" name="<?php echo $newFeedback ?>" size="20" ><?php echo $newFeedbackField?></textarea>
        <?php if ($error!=''){ ?>
        <br>
        <label style="color:red" size=5><?php echo $error ?></label>
        <?php } ?>
    </td>
    
    <td > 
        
        <button class="btn btn-outline-primary accept-btn"  onclick="updateFeedback(<?php echo $index ?>,'update','<?php echo $mode ?>')" class=updateButton name="<?php echo 'update'.strval($index) ?>" value="<?php echo $id?>"   ><strong>Accept</strong></button>
       
    </td>
    <td > 
        <button  class="btn btn-outline-primary reject-btn" onclick="updateFeedback(<?php echo $index ?>,'reject','<?php echo $mode ?>')" name="<?php echo 'reject'.strval($index) ?>" value="<?php echo $id?>"   class="rejectButton" ><strong>Reject</strong></button>

    </td>
 <tr>

 <?php

}
?>
