function isValidFile(input)
{
    $("#file_error").html("");
    var value = input.value;
    var file_size = $(input)[0].files[0].size;
    if (value.substr(value.lastIndexOf('.')) == '.pdf' || value.substr(value.lastIndexOf('.')) == '.PDF') {
        var res = true;
    }
    else if (value.substr(value.lastIndexOf('.')) == '.jpeg' || value.substr(value.lastIndexOf('.')) == '.JPEG') {
        var res = true;
    }
    else if (value.substr(value.lastIndexOf('.')) == '.jpg' || value.substr(value.lastIndexOf('.')) == '.JPG') {
        var res = true;
    }
    else if (value.substr(value.lastIndexOf('.')) == '.png' || value.substr(value.lastIndexOf('.')) == '.PNG') {
        var res = true;
    }
    else {
        var res = false;
    }

    if (!res) {
        input.value = "";
        $('#custom-modal-comprovante').modal('hide');
        $('#custom-modal-file').modal('show');
    }
	else if(file_size>3097152) {
		$("#file_error").html("Arquivo maior que 3MB");
        input.value = "";
		return false;
	} 
	return true;
};

function aguarde() {
    $('#imagem-carregando').html('' +
        '<div class="bg-icon pull-request">' +
        '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
        '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
        '</div>');
}

$(document).ready(function () {
    $('#envio-comprovante').click(function() {
       $('#email-cliente').val($('#_userlogin').val()); 
    });

    $(passbutton).click(function(){                 
        var $_keyid =   "_Ar00001"; 
        $('#_keyform').val($_keyid);     
        $("#form1").submit();                 
    });
    
    $(signupbutton).click(function(){                 
        var $_keyid =   "_As00002";    
        $('#_keyform').val($_keyid);           
        $("#form1").submit();                 
    });

    // Bind normal buttons
    $('.ladda-button').ladda('bind', {timeout: 3000});

    // Bind progress buttons and simulate loading progress
    Ladda.bind('.progress-demo .ladda-button', {
        callback: function (instance) {
            var progress = 0;
            var interval = setInterval(function () {
                progress = Math.min(progress + Math.random() * 0.1, 1);
                instance.setProgress(progress);
                
                if (progress === 1) {
                    instance.stop();
                    clearInterval(interval);
                }
            }, 200);
        }
    });

    $("#form-comprovante").submit(function (e) {
        e.preventDefault();
        var data = new FormData(this);
        var acao = "acaoComprovante.php"
        
        $.ajax({
            url: acao,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: 'post',
            beforeSend: function (load) {
                $('#custom-modal-comprovante').modal('hide');
                aguarde();
                $("#custom-modal-result").modal('show');
            },
            success: function(su){
                $("#custom-modal-result").html(su);
                $('#comprovante-cliente').val("");
            }
        });
    });
});