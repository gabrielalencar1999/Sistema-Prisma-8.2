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
		$('#custom-modal-extrato').modal('hide');
		$('#custom-modal-file').modal('show');
	}
	else if(file_size>3097152) {
		$("#file_error").html("Arquivo maior que 3MB");
		input.value = "";
		return false;
	} 
	return true;
};
$(function () {
	$(_back00000).click(function(){ 
		var $_keyid =   "_Am00002"; 
		$('#_keyform').val($_keyid);   
		$("#form1").submit();   
	});
	
	$(_logout).click(function(){
		var $_keyid =   "_off00001"; 
		$('#_keyform').val($_keyid);   
		$("#form1").submit();   
	});

	$('#form-extrato').submit(function (e) {

		var form = $(this);
        var action = 'page_return.php';
        var keyid = 'ACEXTR';
		var data = form.serializeArray();
		var dados = JSON.stringify(data);
		var data_inicio = data[0].value;
		var data_fim = data[1].value;
		var data_inicio = data_inicio.split('-').reverse().join('/');
		var data_fim = data_fim.split('-').reverse().join('/');

		$.ajax({
			url: action,
			data: {
				_keyform: keyid,
				dados: dados
			},
			type: "post",
			beforeSend: function (load) {
				ajax_load("open");
			},
			success: function (su) {
				ajax_load("close");
				$('#extrato-painel').html(su);
				$('#data-inicial-extrato').html(data_inicio);
				$('#data-final-extrato').html(data_fim);
			}
		});

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

	var imported = document.createElement('script');
	imported.src = '../app/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js';
	document.head.appendChild(imported);

	$('#envio-comprovante').click(function (){
		var div = $('#div-comprovante');
		if(div.is(':visible')) {
			div.hide();
		}
		else {
			div.show();
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
				ajax_load("open");
			},
            success: function(su){
				ajax_load("close");
				$('#custom-modal-extrato').modal('hide');
                $("#custom-modal-result").modal('show').html(su);
				$('#comprovante-cliente').val("");
				$('#div-comprovante').hide();
            }
        });
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
});
