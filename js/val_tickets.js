function form_validate(form,act) {
    // Calling validation function
        if (validation()){
            form.action=act;
            form.submit();
        } 
    }
// Name and Email validation Function.
function validation(){
    var Q0 = document.getElementById("qty0").value;
    var Q1 = document.getElementById("qty1").value;
    var Q2 = document.getElementById("qty2").value;
    var Q3 = document.getElementById("qty3").value;
    if( Q0 == 0 && Q1 == 0 && Q2 == 0 && Q3 == 0){
        alert("Specify atleast one ticket quantity");
        return false;
        }else{
        return true;
        }
    }    