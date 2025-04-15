
function selectYear(){


    var txt = document.getElementById("year").value;

    $.ajax({
        url:"years.php",
        method: "POST",
        data:{
            idy : txt
        },
        success:function(data){
            $("#pays").html(data);
        }
    })
}
