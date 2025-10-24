<?php session_start();
require_once('../api/config/config.inc.php');
require '../api/vendor/autoload.php';


use Database\MySQL;

$pdo = MySQL::acessabd();

//$_SESSION['BASE'] = 'bd_tecfast';
//$_SESSION['BASE'] = 'bd_novo';


function remove($_texto)
{
	$_texto =    str_replace(")", "", $_texto);
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	return $_texto;
}

?>
<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>
<body>


<style>
    .desativado{
       
        opacity:0.4;        
        color:#999;
        cursor:no-drop !important;
      
    }
    @media (max-width: 768px) {
    .xsprisma{
        padding-left:40px;
    }
    }
    .boxP{
        padding:15px; margin-right:-15px; float:left; width:165px;
    }
    .boxIntero{
        width:100%; background-color:#FFF; padding:10px; text-align:center; border-radius:8px;
        padding-bottom:8px;
        padding-top:15px;
        color:#00abe9;
        cursor:pointer;
    }
    .boxIntero:hover .text-menu{
        color:#FFF;
    }
    .boxIntero:hover{
        background-color:rgba(255,255,255,0.5);
        color:#FFF;
    }
    .paddin-t-N1{
        padding-top:0px;
    }
    .text-menu{
        padding-top:10px;
        color:#333;
    }
    .btn-yellow{
        background-color:#ffc022;
        color:#232323;
        border:1px solid #ffc022;
        border-radius:20px;
        padding:20px;
        padding-top:10px;
        padding-bottom:10px;
        font-size:14px;
        margin-top:-10px;
        font-weight:bold;
        }
        .btn-yellow:hover{
        background-color:#f0b216;    
        
        }
        @media only screen and (min-width: 1060px) {
            .centerBox{
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }
        }
        .dv{
            padding:0.3%;
            width:20%;
            float:left;
            width:400px;
        }
</style>

<?php 

require_once('navigatorbarCli.php');




?>
     
        <section >
        <div class="container "  >
        <div class="wrapper">  
            
                        <div class="member-info" style="margin: 0px  10px 15px 15px;">
                        <?php       
                        if($_ref  != ""){
                            $queryOS = ("SELECT *  from bd_prisma.os 
                            WHERE codigo = '$_ref' ");                        
                            $stm = $pdo->prepare("$queryOS");
                            $stm->execute();	
                            if( $stm->rowCount() == 0) {
                                
                                ?>
                                <div class="ex-page-content text-center">
                                        <div class="text-error"><i class="ti-face-sad text-pink"></i></div>
                                        <h2>Ops !!! </h2><br>
                                        <p class="text-muted">Não Encontramos O.S solicitada</p>
                                        <br>
                                        <a class="btn btn-default waves-effect waves-light" href=""> Retornar</a>
                                        
                                    </div>
                                    <?php
                                    exit();

                            }
                                                   
                                    while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                    {
                                       $_os = $linha->os;
                                       $_idcli = $linha->cliente ;
                                       $codigo_p = $linha->login;
                                      
                                    }

                              
                                $queryOS = ("Select consumidor_base,Nome_Fantasia from info.consumidor where CODIGO_CONSUMIDOR = '$codigo_p'");                        
                                $stm = $pdo->prepare("$queryOS");
                                $stm->execute();	
                                                         
                                        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                        {
                                            $_bd = $linha->consumidor_base;                                    
                                          
                                        }

                                        $queryOS = ("Select empresa_fromtelefone from $_bd.empresa LIMIT 1");                        
                                        $stm = $pdo->prepare("$queryOS");
                                        $stm->execute();                                                                 
                                                while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                                {
                                                    $_FONEENVIO = $linha->empresa_fromtelefone;   
                                                    $_FONEENVIO = str_replace('55', '', $_FONEENVIO);   
                                                    $_FONEENVIO = '55'.$_FONEENVIO;                                                                                
                                                }      

                            $sql = "SELECT CODIGO_CONSUMIDOR,trackO_periodo,Nome_Consumidor,
                            DATE_FORMAT(datahora_trackfim,'%Y-%m-%d') as datahora_trackfim,
                            trackO_data,trackO_tecnico,trackO_ordem,usuario_APELIDO
                             FROM ".$_bd.".trackOrdem                                               
                            left JOIN ".$_bd.".consumidor ON consumidor.CODIGO_CONSUMIDOR =   	trackO_idcli  
                            left JOIN ".$_bd.".usuario ON usuario_CODIGOUSUARIO = trackO_tecnico 
                            WHERE trackO_chamada = '".$_os."'  AND CODIGO_CONSUMIDOR = '".$_idcli."'
                            ORDER BY trackO_data DESC
                            limit 1 "; 

                        }else{
                     
                        $celular = remove($_telefone);
                        $_os = remove($_os);

                        $queryOS = ("SELECT *  from bd_prisma.os 
                        WHERE os = '$_os'  and telefone like '%$celular%' limit 1");                        
                        $stm = $pdo->prepare("$queryOS");
                        $stm->execute();	
                                                
                                while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                {
                                   $_os = $linha->os;
                                   $_idcli = $linha->CODIGO_CONSUMIDOR;
                                   $codigo_p = $linha->login;
                                  
                                }
                            
                            $queryOS = ("Select consumidor_base,Nome_Fantasia from info.consumidor where CODIGO_CONSUMIDOR = '$codigo_p'");                        
                            $stm = $pdo->prepare("$queryOS");
                            $stm->execute();	
                                                    
                                    while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                    {
                                        $_bd = $linha->consumidor_base;                                     
                                      
                                    }
                               
                                    $queryOS = ("Select empresa_fromtelefone from $_bd.empresa LIMIT 1");                        
                                    $stm = $pdo->prepare("$queryOS");
                                    $stm->execute();                                                                 
                                            while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                            {
                                                $_FONEENVIO = $linha->empresa_fromtelefone;   
                                                $_FONEENVIO = str_replace('55', '', $_FONEENVIO);   
                                                $_FONEENVIO = '55'.$_FONEENVIO;                                                                                
                                            }      

                           

                            //buscar dados 
                            $sql = "SELECT CODIGO_CONSUMIDOR,trackO_periodo,Nome_Consumidor,
                            DATE_FORMAT(datahora_trackfim,'%Y-%m-%d') as datahora_trackfim,
                            trackO_data,trackO_tecnico,trackO_ordem,usuario_APELIDO
                             FROM ".$_bd.".trackOrdem                                               
                            left JOIN ".$_bd.".consumidor ON consumidor.CODIGO_CONSUMIDOR =   	trackO_idcli  
                            left JOIN ".$_bd.".usuario ON usuario_CODIGOUSUARIO = trackO_tecnico 
                            WHERE trackO_chamada = '".$_os."'  AND FONE_RESIDENCIAL = '".$_telefone."' AND FONE_RESIDENCIAL <> '' AND FONE_RESIDENCIAL <> 'NULL'
                            OR
                            trackO_chamada = '".$_os."'  AND FONE_CELULAR = '".$_telefone."' AND FONE_CELULAR <> '' AND FONE_CELULAR <> 'NULL'
                            OR
                            trackO_chamada = '".$_os."'  AND FONE_COMERCIAL = '".$_telefone."' AND FONE_COMERCIAL <> '' AND FONE_COMERCIAL <> 'NULL'
                            ORDER BY trackO_data DESC
                            limit 1 "; 
                            // and trackO_tecnico = '".$row['code']."'
                            //$_filtecnico
                        }
                        $_SEQUENCIAL = 0;
                            $stm = $pdo->prepare("$sql");
                            $stm->execute();	
                            $_REGISTRO =  $stm->rowCount();
                                if ($_REGISTRO > 0 ){                                
                                    while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                    {
                                        $_nomeatend =  $linha->usuario_APELIDO;
                                       $_idcli =  $linha->CODIGO_CONSUMIDOR; 
                                       $periodo = $linha->trackO_periodo;  
                                       $ordem = $linha->trackO_ordem;                                    
                                       $_nome = $linha->Nome_Consumidor;
                                       $fim =  $linha->datahora_trackfim;
                                       $tecnico =  $linha->trackO_tecnico;
                                       $dataatendimento = $linha->trackO_data;
                                       if($fim != "0000-00-00" ){
                                        $finalizado = 1;
                                       }
                                        
                                        if($periodo == "0" ) {                     
                                                    $_corAtendimento  = 'inverse' ;
                                        }elseif($periodo == 2){
                                                    $_corAtendimento  = 'warning' ;
                                                    $_PERIDOESCOLHIDO = "Manhã";
                                        }else{
                                                $_corAtendimento  = 'info' ;
                                                $_PERIDOESCOLHIDO = "Tarde";
                                            }
                                    }

                                 } 
                                 
                                 if ($_REGISTRO > 0 ){  
                                    $_seq = 0;
                                    // BUSCAR NUMERO POSIÇÃO
                                    $sql = "SELECT trackO_chamada,trackO_periodo,
                                    DATE_FORMAT(datahora_trackfim,'%Y-%m-%d') as dt,
                                    trackO_data,trackO_tecnico,trackO_ordem
                                    FROM ".$_bd.".trackOrdem  
                                   
                                    WHERE  trackO_tecnico = '".$tecnico."' and trackO_data  = '".$dataatendimento."' 
                                    and trackO_ordem > 0 and trackO_situacaoEncerrado <> '10'

                                    ORDER BY trackO_ordem ASC";     
                                
                                    $stm = $pdo->prepare("$sql");
                                    $stm->execute();	
                                        if ( $stm->rowCount() > 0 ){  
                                            while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                            { 
                                            $_seq++;
                                            if($linha->trackO_chamada == $_os  ){
                                                $POSICAO = $_seq;
                                            }
                                        }
                                            
                                        }

                                        $_seq = 0;
                                                    ?>
                                        <div class="row">
                                            <div class="col-md-12" style="text-align:justify" > Olá <p> <h4 class="m-t-0 m-b-5 header-title"><b><?=$_nome;?></b></h4></p>
                                            <p>Sua posição na fila de atendimento é a Nº <strong><?=$POSICAO;?> </strong></p>
                                            <p><h5>Segue abaixo a fila de espera com <strong>PREVISÃO</strong> de atendimento:</h5></p>
                                            <h5> <strong>ATENÇÃO:</strong> A fila abaixo é uma previsão e pode sofrer alterações dependendo do roteiro do técnico. Caso aconteça, nossa central de atendimento entrará em contato.</h5>
                                            
                                            </div>
                                        </div>
                                        <div class="member-info">
                                  <div class="row">
                        
                                    <div class="col-md-12">
                                        <section id="cd-timeline" class="cd-container">
                                        <?php
                                            $sql = "SELECT trackO_chamada,trackO_periodo,
                                            DATE_FORMAT(datahora_trackfim,'%Y-%m-%d') as dt,
                                            trackO_data,trackO_tecnico,trackO_ordem
                                            FROM ".$_bd.".trackOrdem  
                                           
                                            WHERE  trackO_tecnico = '".$tecnico."' and trackO_data  = '".$dataatendimento."' 
                                            and trackO_ordem > 0 and trackO_situacaoEncerrado <> '10'

                                            ORDER BY trackO_ordem ASC";     
                                        
                                            $stm = $pdo->prepare("$sql");
                                            $stm->execute();	
                                                if ( $stm->rowCount() > 0 ){   
                                                    $_finalizaloop = 0;                            
                                                    while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                                    {

                                                        $_seq++;

                                                        $_valida = 1;
                                                        if($linha->trackO_chamada == $_os  ){
                                                            $_valida = 2;
                                                            $img = "fa-star";
                                                            $cor = "warning";
                                                        
                                                        }elseif($linha->dt == "0000-00-00"  ){
                                                                
                                                            $status = "Aguardando";
                                                            $_valida = 1;
                                                            $img = "fa-tag";
                                                            $cor = "info";
                                                        }else{
                                                            $status = "Finalizado";
                                                            $_valida = 1;
                                                            $img = "fa-thumbs-up";
                                                            $cor = "success"; 
                                                    
                                                        }

                                                        if( $_valida == 1) {
                                                    
                                                    
                                                        ?>
                                                            <div class="cd-timeline-block">
                                                                <div class="cd-timeline-img cd-<?=$cor;?>">
                                                                    <i class="fa <?=$img;?>"></i>
                                                                </div> <!-- cd-timeline-img -->

                                                                <div class="cd-timeline-content">
                                                                    <h3><?=$_seq;?><?php //$linha->trackO_ordem;?>-<?=$status;?></h3>
                                                                
                                                                </div> <!-- cd-timeline-content -->
                                                            </div> <!-- cd-timeline-block -->
                                                    <?php 
                                                }else{ 
                                                    if($finalizado == 1){
                                                                           
                                                    ?>
                                                    <div class="cd-timeline-block">
                                                            <div class="cd-timeline-img cd-success">
                                                                <i class="fa <?=$img;?>"></i>
                                                            </div> <!-- cd-timeline-img -->

                                                            <div class="cd-timeline-content">
                                                            <h3>Seu atendimento já foi finalizado</h3>
                                                                <p>O.S <?=$_os;?><br>
                                                              </p>
                                                                <a href="https://api.whatsapp.com/send?phone=<?=$_FONEENVIO;?>"><button type="button" class="btn btn-success btn-rounded waves-effect waves-light m-t-15"><i class="fa fa-whatsapp m-r-5"></i>Falar via Whatsapp</button></a>

                                                            </div> <!-- cd-timeline-content -->
                                                        </div> <!-- cd-timeline-block -->
                                                <?php

                                                    }else{

                                                    
                                                    ?>
                                                        <div class="cd-timeline-block">
                                                                <div class="cd-timeline-img cd-<?=$cor;?>">
                                                                    <i class="fa <?=$img;?>"></i>
                                                                </div> <!-- cd-timeline-img -->

                                                                <div class="cd-timeline-content">
                                                                <h3><?=$_seq;?> - Você está aqui na fila</h3>
                                                                    <p>O.S <?=$_os;?><br>
                                                                    Período: <?=$_PERIDOESCOLHIDO;?>
                                                                    <p>Técnico: <?=$_nomeatend;?><br> </p>
                                                                    <a href="https://api.whatsapp.com/send?phone=<?=$_FONEENVIO;?>"><button type="button" class="btn btn-success btn-rounded waves-effect waves-light m-t-15"><i class="fa fa-whatsapp m-r-5"></i>Falar via Whatsapp</button></a>

                                                                </div> <!-- cd-timeline-content -->
                                                            </div> <!-- cd-timeline-block -->
                                                    <?php
                                                    }
                                                    break; 
                                                    }
                                                    
                                                } 
                                                } ?>

                                        
                                        
                                        </section> <!-- cd-timeline -->
                     
                                        </div>
                                    </div><!-- Row -->  
                                    <?php }else  { ?>
                                        <div class="ex-page-content text-center">
                                                <div class="text-error"><i class="ti-face-sad text-pink"></i></div>
                                                <h2>Ops !!! </h2><br>
                                                <p class="text-muted">Não Encontramos O.S solicitada</p>
                                                <br>
                                                <a class="btn btn-default waves-effect waves-light" href=""> Retornar</a>
                                                
                                            </div>

                                    <?php } ?>
            <form class="form-horizontal m-t-2p" id="form1" name="form1" method="post" action="">           
                    <!-- Page-Title -->
            
        
                <input type="hidden" id="_keyform" name="_keyform"  value="">
                <input type="hidden" id="_chaveid" name="_chaveid"  value="">
            </form>   
         </div>
    </section>


             

            </div>
            
               <!-- End Footer -->
        
        <div class="container m-t-40">
            <p class="text-center"><b><?php 
          //  echo "perfil ".$_SESSION["nivel"];
        //  echo $_SESSION['BASE']."|".$_SESSION['EMPRESA']."|".$_SESSION['NOME'];
            ?>
            </b></p>
        </div>


        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
		<script src="assets/js/routes.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/plugins/peity/jquery.peity.min.js"></script>

        <!-- jQuery  -->
        <script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
        <script src="assets/plugins/counterup/jquery.counterup.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>
        <script src="assets/plugins/jquery-knob/jquery.knob.js"></script>
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <!-- Modal-Effect -->
        <script src="assets/plugins/custombox/js/custombox.min.js"></script>
        <script src="assets/plugins/custombox/js/legacy.min.js"></script>

        <!-- Notification js -->
        <script src="assets/plugins/notifyjs/js/notify.js"></script>
        <script src="assets/plugins/notifications/notify-metro.js"></script>    

        <script type="text/javascript">
            $(document).ready(function () {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });
/*
                $(_00013).click(function(){
                      window.open("/app/v1/pdv/caixa.php", "_blank" );              
                });
*/
                $(formOS).submit(function(){ //pesquisa os
                     
                     var $_keyid =   "S00001";                     
                     $('#_keyform').val($_keyid);   
                                             
                         var dados = $("#formOS :input").serializeArray();
                         dados = JSON.stringify(dados);		
                                    
                         $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									                                                   
                           $('#_chaveid').val($('#numOS').val());   
                           $("#form1").submit();  
             
                  });

                 });


               

                /*
                window.onload = function () {
                    var saldo = document.getElementById('saldo-login').innerText;
                    var saldo = saldo.replace(/[\R\$\s]/g, '');
                    var saldo = saldo.replace(',', '.');

                    if (saldo <= 0) {
                        $('#custom-modal-saldo').modal('show');
                    }
                }
                */
                $("#publicidade").css("background-image","url('assets/images/banners/banner1.jpg')");
/*
                banner = "2";
                setInterval(function () {
                    
                    if(banner == "1"){
                        $("#publicidade").css("background-image","url('assets/images/banners/banner1.jpg')");
                        document.getElementById("saibaMais").setAttribute("onclick","publicidade('1')");    
                        $("#saibaMais").html("Saiba Mais");                    
                    }
                    
                    if(banner == "2"){
                        $("#publicidade").css("background-image","url('assets/images/banners/banner2.jpg')");
                        document.getElementById("saibaMais").setAttribute("onclick","publicidade('2')");    
                        $("#saibaMais").html("Em Breve");
                    }
                    if(banner == "3"){
                        $("#publicidade").css("background-image","url('assets/images/banners/banner3.jpg')");
                        document.getElementById("saibaMais").setAttribute("onclick","publicidade('3')"); 
                        $("#saibaMais").html("Em Breve");   
                        banner = "0";
                    }
                    banner =  parseInt(banner) +  parseInt('1');

                }, 8000);
                */

            });
            

            function   _00001 () {      
                    var $_keyid =   "_ATa00001"; 
                    $('#_keyform').val($_keyid); 

                    var permissao = "1";              
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $("#form1").submit();  
                        }								  
					});	
                                 
                };

                function   _00011 () {      
                    var $_keyid =   "_ATa00006"; 
                    $('#_keyform').val($_keyid);    

                  //  var permissao = "1";              
                  //  $.post("verPermissao.php", {permissao:permissao}, function(result){
                      //  if(result != ""){
                        //    $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                      //  }else{
                            $("#form1").submit();  
                      //  }								  
					//});	
                                 
                };


                
                function _00003(){
                    var $_keyid =   "S00002"; 
                    $('#_keyform').val($_keyid);  

                    var permissao = "3";                  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $("#form1").submit();  
                        }								  
					});	             
                };
          
                function _00002 () {
                    var $_keyid =   "RE0001"; 
                    $('#_keyform').val($_keyid);  
                    var permissao = "6";      
                      
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                   
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $("#form1").submit();  
                        }								  
					});	             
                };

              
              
                function _00004 () { 
                    var $_keyid =   "S00007"; 
                    $('#_keyform').val($_keyid);     
                    
                    var permissao = "5";                  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $("#form1").submit();  
                        }								  
					});	  
                };

                function _00047 () { 
                    var $_keyid =   "S00017"; 
                    $('#_keyform').val($_keyid);     
                    
                    var permissao = "5";                  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $("#form1").submit();  
                        }								  
					});	  
                };

                function _00022 () { 
                    var $_keyid =   "_ATa00015"; 
                    $('#_keyform').val($_keyid);                     
                  //  var permissao = "3";                  
                   // $.post("verPermissao.php", {permissao:permissao}, function(result){
                     //  if(result != ""){
                     //       $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                   //     }else{
                            $("#form1").submit();  
                     //   }								  
				//	});	  
                };

                
                function _00006 () {
                    var $_keyid =   "_Vl00003"; 
                    $('#_keyform').val($_keyid);     

                    
                    var permissao = "4";                  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                       
                           $("#form1").submit();  
                        }								  
					});	  
                };

                function  _00005 () {     
                    var $_keyid =   "S00005";
                     
                     $('#_keyform').val($_keyid); 

                     var permissao = "1";
                     $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            var dados = $("#formOS :input").serializeArray();
                         dados = JSON.stringify(dados);	
                                    
                         $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){							
                                $("#form1").submit();               
                          });      
                        }								  
					});	  
                                             
                       
                };

                function _00055 () {      
                
                    var $_keyid =   "_ATa00006"; 
                    ('#_keyform').val($_keyid);   
                                             
                         var dados = $("#formOS :input").serializeArray();
                         dados = JSON.stringify(dados);		
                                    
                         $.post("page_return.php", {__idrefkeyform:$_keyid,dados:dados}, function(result){									
                          
                           $("#form1").submit();  
             
                  });      
                                 
                };

                function  _00046 () {     

                    var $_keyid =   "S00015";                     
                     $('#_keyform').val($_keyid); 

                     var permissao = "3";
                     $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            var dados = $("#formOS :input").serializeArray();
                            dados = JSON.stringify(dados);                                    
                         $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){							
                                $("#form1").submit();               
                          });      
                        }								  
					});	  
                                             
                       
                };


                function  _00056 () {     

                    var $_keyid =   "PRDLTtec";  
                    $('#_keyform').val($_keyid); 

                    var permissao = "116";
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            var dados = $("#formOS :input").serializeArray();
                            dados = JSON.stringify(dados);                                    
                        $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){							
                                $("#form1").submit();               
                        });      
                        }								  
                    });	  
                                            
                    
                    };

                    function _00057 () {
                    var $_keyid =   "RE0001tec"; 
                    $('#_keyform').val($_keyid);  
                    var permissao = "6";      
                      
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                   
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $("#form1").submit();  
                        }								  
					});	             
                };

                function  _000077(){     
                                 
                    $('#_idref').val($('#numeroostec').val());
                                    
                     var $_keyid =   "_ATa00014";    
                     var dados = $("#form10 :input").serializeArray();
                      dados = JSON.stringify(dados);        
                         _carregando('#form10');
                                $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:0}, function(result){                         
                                     $("#form10").html(result);                                    
                                                             
                                });                           

                    };

                    function _carregando (_idmodal){
                      $(_idmodal).html('' +
                    '<div class="bg-icon pull-request">' +
                    '<img src="assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
                    '<h4 class="text-center">Aguarde, carregando dados...</h4>' +
                    '</div>');

                  }  
            function publicidade(tipo){
                if(tipo == 1){
                    window.open("https://wa.me/5541991458007", "_blank");
                    
                }
                if(tipo == 2){
                   
                }
                if(tipo == 3){
                      
                }
            }
            
        </script>
    </body>
</html>