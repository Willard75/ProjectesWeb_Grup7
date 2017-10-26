    function valida3() {
        var ok = true;
        var user = document.forms["myForm2"]["titol"].value;
        if (user == '') {
            alert("El campo Titulo no puede estar vacio");
            ok = false;
        }
        return ok;
    }