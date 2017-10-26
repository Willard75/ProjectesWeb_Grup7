$(document).ready(function () {
    console.log("hi");
    $('.button').click(function (e) {
        console.log("hi");
        //var numComents = $('#comentaris').children().length -1;

        $.ajax({
            type: "POST",
            url: '/mescomentaris',
            data: {
                "id": $('.button').attr('id'),
                "numComents": ($('#div_comentaris').children().length - 1),
            },
            success: function (response) {

                console.log(response);
                console.log("id");
                var mostra = 0;
                //console.log(response);
                var data_array = $.parseJSON(response);
                if(data_array.count[0] != null){
                    if (data_array.count.length > 3) {
                        mostra = 3
                    }
                    else {
                        mostra = data_array.count.length;
                        document.getElementById($('.button').attr('id')).style.display = "none";
                    }
                    for (var c = 0; c < mostra; c++) {
                        //var data_array = response;
                        console.log(data_array.count[c].id);
                        var div = document.createElement("div");
                        div.id = "text";
                        var div2 = document.createElement("div");
                        var img = document.createElement("img");
                        //aixo no ha de ser aixi ha de ser la imatge de cada usuari
                        img.src = "http://www.grup7.com/" + data_array.count[c].img_u;
                        img.alt = "image";
                        div2.id = "fot2";
                        div2.appendChild(img);

                        var a = document.createElement("a");
                        a.href = "/perfil/" + data_array.count[c].user_id;
                        var label = document.createElement("label");
                        label.innerHTML = data_array.count[c].nom;
                        a.appendChild(label);
                        var label2 = document.createElement("label");
                        label2.id = "comcom";
                        label2.innerHTML = data_array.count[c].content;

                        if (data_array.count[c].id_user_log == data_array.count[c].user_id) {
                            var a2 = document.createElement("a");
                            a2.href = "/editacomentari/" + data_array.count[c].id;
                            var img2 = document.createElement("img");
                            img2.src = "http://www.grup7.com/assets/images/pencil_4076.png" ;
                            img2.alt = "image";
                            a2.appendChild(img2);
                            div.appendChild(a2);

                        }

                        var element = document.getElementById("div_comentaris");
                        div.appendChild(div2);
                        div.appendChild(a);

                        div.appendChild(label2);

                        element.appendChild(div);

                    }

                }

            }
        });

    });
});
