function password_limit(){

    var txt = document.getElementById("pw").value;


    $.ajax({
        url:"js_url/pass_limit.php",
        method: "POST",
        data:{
            idpass : txt
        },
        success:function(data){
            $("#pass_limit").html(data);
        }
    })
}