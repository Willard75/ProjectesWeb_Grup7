
    function myFunction() {
      alert("La contraseña debe contener:\n    - Entre 6 y 12 carácteres\n    - Mayúsculas y minúsculas\n    - Como mínimo un número.");
    }
    function previewFile() {
        var preview = document.querySelector('.imag');
        var file    = document.querySelector('input[type=file]').files[0];
        var reader  = new FileReader();

        reader.onloadend = function () {
        preview.src = reader.result;
        }

        if (file) {
          reader.readAsDataURL(file);
        } else {
          reader.readAsDataURL("assets/images/pred.jpg")
        }
      }

      function valida() {
        var ok = true;
        //nom d'usuari
        var user = document.forms["myForm"]["username"].value;
        var Exp = /^[a-z0-9]+$/i;
        if(!user.match(Exp)) {
          alert("ERROR: El nombre de usuario solo puede contener caracteres alfanumericos");
          ok = false;
        }
        
        //email
        var email = document.forms["myForm"]["mail"].value;
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(email)){
          alert("ERROR: El formato del email no es valido");
          ok = false;
        }
        //data
        var data = document.forms["myForm"]["date"].value;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        if(dd<10) {
            dd='0'+dd
        } 
        if(mm<10) {
            mm='0'+mm
        } 
        today = yyyy+'-'+mm+'-'+dd;
        if (!data < today){
          alert("ERROR: La fecha de cumpleaños no es correcta");
          ok = false;
        }
        //contraseña
        var pw = document.forms["myForm"]["password"].value;
        var Max_Length = 12;
        var Min_Length = 6;
        if (pw.length < Max_Length) {
          if (pw.length >= Min_Length) {
            if (pw.match(`[a-z]`)) {
              if (pw.match(`[A-Z]`)) {
                if (pw.match(`[0-9]`)) {
                  //alert("SUCCESS");
                }
                else {
                  alert("ERROR: La clave debe tener al menos un caracter numérico");
                  ok = false;
                }
              }
              else {
                alert("ERROR: La clave debe tener al menos una letra mayúscula");
                ok = false;
              }
            }
            else {
              alert("ERROR: La clave debe tener al menos una letra minúscula");
              ok = false;
            }
          }
          else {
            alert("ERROR: La clave debe tener al menos 6 caracteres");
            ok = false;
          }
        }
        else {
          alert("ERROR: La clave no puede tener más de 12 caracteres");
          ok = false;
        }
        //passwordconf
        var pwc = document.forms["myForm"]["passwordconf"].value;
        if(!pwc == pw){
          alert("ERROR: la verificación del password no coincide");
          ok = false;
        }
        return ok;
      }