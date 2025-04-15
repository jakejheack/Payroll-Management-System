
function searchMe(){


    var txt = document.getElementById("search").value;

    $.ajax({
        url:"./finds/search.php",
        method: "POST",
        data:{
            ids : txt
        },
        success:function(data){
            $("#searches").html(data);
        }
    })
}
