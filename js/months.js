
function selectMonth(){


    var txt = document.getElementById("month").value;

    $.ajax({
        url:"months.php",
        method: "POST",
        data:{
            idm : txt
        },
        success:function(data){
            $("#pays").html(data);
        }
    })
}
