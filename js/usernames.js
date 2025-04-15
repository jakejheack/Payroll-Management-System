function Username(){

    var txt = document.getElementById("user").value;


    $.ajax({
        url:"js_url/usernames.php",
        method: "POST",
        data:{
            idu : txt
        },
        success:function(data){
            $("#uname").html(data);
        }
    })
}