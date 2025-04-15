function userAccess(){

    var txt = document.getElementById("access").value;


    $.ajax({
        url:"js_url/useraccess.php",
        method: "POST",
        data:{
            idacc : txt
        },
        success:function(data){
            $("#useraccess").html(data);
        }
    })
}