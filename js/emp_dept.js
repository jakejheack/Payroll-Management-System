function selectedDept(){


    var txt = document.getElementById("dept").value;

    $.ajax({
        url:"./finds/emp_dept.php",
        method: "POST",
        data:{
            idt : txt
        },
        success:function(data){
            $("#searches").html(data);
        }
    })
}
