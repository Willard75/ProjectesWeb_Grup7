    function previewFile() {
        var preview = document.querySelector('.imag');
        var file    = document.querySelector('input[type=file]').files[0];
        var reader  = new FileReader();

        reader.onloadend = function () {
        preview.src = reader.result;
        }

        if (file) {
          reader.readAsDataURL(file);
          document.getElementById("im").style.display = "block";
        } else {
        }
      }

    function valida2() {
        var ok = true;
        var user = document.forms["myForm2"]["titol"].value;
        var foto = document.forms["myForm2"]["imatge"].value;
        if (user == '') {
            alert("El campo Titulo no puede estar vacio");
            ok = false;
        }
        if (foto == "") {
            alert("El campo Foto no puede estar vacio");
            ok = false;
        }
        return ok;
    }

    function valida3() {
        var ok = true;
        var user = document.forms["myForm2"]["titol"].value;
        if (user == '') {
            alert("El campo Titulo no puede estar vacio");
            ok = false;
        }
        return ok;
    }