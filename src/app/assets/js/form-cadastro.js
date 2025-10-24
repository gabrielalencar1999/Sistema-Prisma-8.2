$(function () {
    $("form").submit(function (e) {
        e.preventDefault();

        var form = $(this);
        var checkbox = $('#checkbox-signup');
        var action = 'acaoCadastro.php';
        var data = form.serialize();

        if (checkbox.is(':checked')) {
            $.ajax({
                url: action,
                data: data,
                type: "post",
                dataType: "json",
                beforeSend: function (load) {
                    ajax_load("open");
                },
                success: function (su) {
                    ajax_load("close");
    
                    console.log(su);

                    if (su.message.type === 'success') {
                        form.trigger("reset");
                        var image = '<img src="../images/svg/congratulations.svg" class="img-responsive center-block" id="image-result" width="60%"/>';
                        var icon = '<i class="fa fa-5x fa-check-circle-o"></i>';
                        var view = '<h4 class="message ' + su.message.type + '">' + su.message.message + '</h4>';
                        $("#result-ajax").html(image + icon + view);
                        $("#custom-modal-result").modal('show');
                        return;
                    }
                    else if (su.message.type === 'info') {
                        var icon = '<i class="md-5x md-info-outline icon-'+ su.message.type +'"></i>';
                        var view = '<h4 class="message ' + su.message.type + '">' + su.message.message + '</h4>';
                        $("#result-ajax").html(icon + view);
                        $("#custom-modal-result").modal('show');
                        return;
                    }
                    else {
                        var icon = '<i class="md-5x md-highlight-remove icon-'+ su.message.type +'"></i>';
                        var view = '<h4 class="message ' + su.message.type + '">' + su.message.message + '</h4>';
                        $("#result-ajax").html(icon + view);
                        $("#custom-modal-result").modal('show');
                        return;
                    }
                }
            });
        } else {
            alert('Aceite os termos para realizar o cadastro!');
        }

        function ajax_load(action) {
            ajax_load_div = $(".ajax_load");

            if (action === "open") {
                ajax_load_div.fadeIn(200).css("display", "flex");
            }

            if (action === "close") {
                ajax_load_div.fadeOut(200);
            }
        }
    });

    /* Máscaras ER */
    function mascara(o,f){
        v_obj=o
        v_fun=f
        setTimeout(execmascara(),1)
    }
    function execmascara(){
        v_obj.value=v_fun(v_obj.value)
    }
    function mtel(v){
        v=v.replace(/\D/g,""); //Remove tudo o que não é dígito
        v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
        v=v.replace(/(\d)(\d{4})$/,"$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
        return v;
    }
    function id( el ){
        return document.getElementById( el );
    }
    window.onload = function(){
        id('contato-numero').onkeyup = function(){
            mascara( this, mtel );
        }
    }
});