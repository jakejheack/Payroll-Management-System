function Cut_off(){


    var txt = document.getElementById("cut_off").value;

    $.ajax({
        url:"./finds/cut_off.php",
        method: "POST",
        data:{
            idt : txt
        },
        success:function(data){
            $("#cut_period").html(data);
        }
    })
}
