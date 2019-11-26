function calender(type){
    if (type=="start"){
        $( "#startDate" ).datepicker();
    }else if (type=="end"){
        $( "#endDate" ).datepicker();
    }
}
function collapsable(){

    document.getElementById("settingButton").classList.toggle("active");
    var content = document.getElementById("settingForm");
    
    if (content.style.display === "block") {
      content.style.display = "none";

    }else if (content.style.display == "none"){
        content.style.display = "block";
     
    }
     else {
        content.style.display = "block";
     
    }

}
    
    
    function processingEnd(button,type,orignalClass){
        //button.style.color="white";
        if (type='update'){
            button.innerHTML='<strong>Accept</strong>';
        }else{
            button.innerHTML='<strong>Reject</strong>';
        }
        //button.className=orignalClass;
        button.disabled=false;
    }

    function updateFeedback(index,type,mode){
    
        button=document.getElementsByName(type+index.toString())[0];
        
        button.disabled = true;
        //button.style.backgroundcolor='white';
        //button.style.color= '#092864';
        //button.className='buttonClick';
        
        if (type=='reject'){
            button.innerHTML="<strong>Rejecting...</strong>"
            orignalClass='rejectButton';
        }
        else{
            button.innerHTML="<strong>Updating...</strong>"
            orignalClass='updateButton';
        }
        id=button.value
        //window.alert("orignal"+index.toString());
        orignalDescription = document.getElementById("original"+index.toString()).innerText;
        description=document.getElementsByName("feedback"+index.toString())[0].innerText;
        description_modified =document.getElementsByName("newFeedback"+index.toString())[0].value.trim();
       
    
        $.ajax({
        type: "POST",
        url: "row.php",
        data: { 
            index:index,
            id: id,
            orignalDescription:orignalDescription,
            description: description,
            description_modified: description_modified,
            type: type, 
            mode:mode,

        },
        success: function(result) {

            //console.log("result", result);
     
            $('#row'+index.toString()).replaceWith(result);
            processingEnd(button,type,orignalClass);
            
        },
        error: function(result) {
            
        }
       
    });
    
    }