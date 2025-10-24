function openAjax() {
var Ajax;
	try {
		Ajax = new XMLHttpRequest(); // XMLHttpRequest para browsers mais populares, como: Firefox, Safari, dentre outros.
	}catch(ee){
	try {
		Ajax = new ActiveXObject("Msxml2.XMLHTTP"); // Para o IE da MS
	}catch(e){
	try {
		Ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Para o IE da MS
	}catch(e){Ajax = false;}
	}
	}
	return Ajax;
}

function carregaAjax(div, getURL) {
	document.getElementById(div).style.display = "block";
	
	if(document.getElementById) { // Para os browsers complacentes com o DOM W3C.
		var exibeResultado = document.getElementById(div); // div que exibir� o resultado.
		var Ajax = openAjax(); // Inicia o Ajax.
		Ajax.open("GET", getURL, true); // fazendo a requisi��o
		Ajax.onreadystatechange = function(){
		if(Ajax.readyState == 1) { // Quando estiver carregando, exibe: carregando...
			exibeResultado.innerHTML = "Carregando ...";
		}
		
		if(Ajax.readyState == 4) { // Quando estiver tudo pronto.
				if(Ajax.status == 200) {
					var resultado = Ajax.responseText; // Coloca o retornado pelo Ajax nessa vari�vel
					resultado = resultado.replace(/\+/g,""); // Resolve o problema dos acentos (saiba mais aqui: http://www.plugsites.net/leandro/?p=4)
					resultado = resultado.replace(/�/g,"a");
					resultado = unescape(resultado); // Resolve o problema dos acentos
					exibeResultado.innerHTML = resultado;
				} else {
					exibeResultado.innerHTML = "Por favor, tente novamente!";
				}
			}
		}
	Ajax.send(null); // submete
	}
}
function carregaajaxEND(div, getURL) {

//var cep = document.getElementById("statuscep");

document.getElementById(div).style.display = "block";

if(document.getElementById) { // Para os browsers complacentes com o DOM W3C.

var exibeResultado = document.getElementById(div); // div que exibir� o resultado.

var Ajax = openAjax(); // Inicia o Ajax.

Ajax.open("GET", getURL, true); // fazendo a requisi��o

Ajax.onreadystatechange = function(){

if(Ajax.readyState == 1) { // Quando estiver carregando, exibe: carregando...

cep.innerHTML = "CEP - Localizando aguarde....";

exibeResultado.innerHTML = "<div><img src='img/carregando.gif'  /></div>";

}

if(Ajax.readyState == 4) { // Quando estiver tudo pronto.

if(Ajax.status == 200) {

var resultado = Ajax.responseText; // Coloca o retornado pelo Ajax nessa vari�vel

resultado = resultado.replace(/@/g,"+");

resultado = unescape(resultado); // Resolve o problema dos acentos

exibeResultado.innerHTML = resultado;
	
$('#loading').css('display', 'block');

cep.innerHTML = "CEP";

novoArray = resultado.split('|');

$('#cepbusca').html('');

	
setTimeout(function(){

	
	document.getElementById('endereco').value = novoArray[0].trim(); 

	document.getElementById('bairro').value = novoArray[1].trim(); 

	document.getElementById('cidade').value = novoArray[2].trim(); 

	document.getElementById('estado').value = novoArray[3].trim();

	
		if(document.getElementById('endereco').value != ""){

			$('#loading').css('display', 'none');

		}
	
		if(document.getElementById('endereco').value == '')
			{
				
				$('#loading').html('<i class="fa fa-times fa-2 text-danger" style="font-size: 22px;" aria-hidden="true"></i');
			}
	
}, 2000);	

} else {
	

exibeResultado.innerHTML = "Por favor, tente novamente!";

}

}

}

Ajax.send(null); // submete

}

}
