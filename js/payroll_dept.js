function selectedDeptPayroll(){

    var txt = document.getElementById("dept").value;

    $.ajax({
        url:"./finds/payroll_dept.php",
        method: "POST",
        data:{
            idt : txt
        },
        success:function(data){
            $("#payrolls").html(data);
        }
    })
}
