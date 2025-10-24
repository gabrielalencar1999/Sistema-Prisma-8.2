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

function _notificao($_id,$_acao) {
	var $_keyid = "_As00005";
	var dados = $("#formNoti :input").serializeArray();
	dados = JSON.stringify(dados);      

	$.post("page_return.php", {
		_keyform: $_keyid,
		dados: dados,
		acao: $_acao
	}, function(result) {
		
		$('#listnotificacao').html(result);
	});
}

function _extratofatura($_acao) {
	var $_keyid = "ACEXTR";
	var dados = $("#formfatExtrato :input").serializeArray();
	dados = JSON.stringify(dados);      

	$.post("page_return.php", {
		_keyform: $_keyid,
		dados: dados,
		acao: $_acao
	}, function(result) {		
		$('#faturaextrato').html(result);
	});
}


$(function () {
	$(_menuadmin).click(function(){ 
		var $_keyid =   "_Na00001"; 
		$('#_keyform').val($_keyid); 

		var permissao = "15";              
		$.post("verPermissao.php", {permissao:permissao}, function(result){
			if(result != ""){
				$.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
			}else{
				$("#form1").submit();  
			}								  
		});	
	});

	$(_menuvend).click(function(){ 
		var $_keyid =   "_Nv00003"; 
		$('#_keyform').val($_keyid);   

		var permissao = "14";              
		$.post("verPermissao.php", {permissao:permissao}, function(result){
			if(result != ""){
				$.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
			}else{
				$("#form1").submit();  
			}								  
		});	
	});	

	$(_menuestoq).click(function(){ 
		var $_keyid =   "_Na00006"; 
		$('#_keyform').val($_keyid); 

		var permissao = "9";              
		$.post("verPermissao.php", {permissao:permissao}, function(result){
			if(result != ""){
				$.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
			}else{
				$("#form1").submit();  
			}								  
		});	
	});
	
	$(_menufin).click(function(){ 
		var $_keyid =   "_Nf00002"; 
		$('#_keyform').val($_keyid);   

		var permissao = "16";              
		$.post("verPermissao.php", {permissao:permissao}, function(result){
			if(result != ""){
				$.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
			}else{
				$("#form1").submit();  
			}								  
		});	
	});
	
	$(_menuServ).click(function(){ 
		var $_keyid =   "_Na00007"; 
		$('#_keyform').val($_keyid);  

		var permissao = "8";              
		$.post("verPermissao.php", {permissao:permissao}, function(result){
			if(result != ""){
				$.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
			}else{
				$("#form1").submit();  
			}								  
		});	 
	});
	
	$(_back00000).click(function(){ 
		var $_keyid =   "_Am00001"; 
		$('#_keyform').val($_keyid);   
		$("#form1").submit();   
	});
	
	$(_menu).click(function(){      
		var $_keyid =   "_Am00001"; 
		$('#_keyform').val($_keyid);   
		$("#form1").submit();   
	});

	$(_trackmob).click(function(){      
		var $_keyid =   "_ATa00006"; 
		$('#_keyform').val($_keyid);   
		$("#form1").submit();   
	});
	
		
	$(_menuconf).click(function(){    
		var $_keyid =   "_Nc00005";
		$('#_keyform').val($_keyid);   

		var permissao = "10";              
		$.post("verPermissao.php", {permissao:permissao}, function(result){
			if(result != ""){
				$.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
			}else{
				$("#form1").submit();  
			}								  
		});	  
	});
	
	$(_logout).click(function(){
		var $_keyid =   "_off00001"; 
		$('#_keyform').val($_keyid);   
		$("#form1").submit();   
	});

	$(_treinamentos).click(function(){
		var $_keyid =   "_Am00003"; 
		$('#_keyform').val($_keyid);   
		$("#form1").submit();   
	});


	$(_prodmenu).click(function(){    
		var $_keyid =   "PRDLT"; 
		$('#_keyform').val($_keyid); 
	   
		var permissao = "116";              
		$.post("verPermissao.php", {permissao:permissao}, function(result){
			if(result != ""){
				$.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
			}else{
				$("#form1").submit();  
			}								  
		});	
					 
	
	});



	
   
	
	
	var imported = document.createElement('script');
	imported.src = 'assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js';
	document.head.appendChild(imported);


});
