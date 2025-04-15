function confirm_password(){

    var txt = document.getElementById("confirm").value;


    $.ajax({
        url:"js_url/con_pass.php",
        method: "POST",
        data:{
            idcon : txt
        },
        success:function(data){
            $("#con_pass").html(data);
        }
    })
}