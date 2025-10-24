<?php
 $_SESSION['pass'] = "";
 $idtecsession = "";
 
if($_POST['idtecsession'] != ''){
    $idtecsession = $_POST['idtecsession'];
}else{
    $idtecsession = base64_encode($_SESSION["tecnico"].";".$_SESSION['CODIGOCLI'].";".$_SESSION["nivel"]);
}
$fundobg = "bg2.jpg";
if($_SESSION['CODIGOCLI'] == '9018'){
    $fundobg = "bg3.jpg";
 } else{
    $fundobg = "bg2.jpg";
 }


include('validarlogin.php?idtecsession='.$idtecsession);

$_permissaofatura = $_SESSION['per012'];

if($_permissaofatura == "") {
    $_permissaofatura = 0;
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
        padding:15px; margin-right:-15px; float:left; width:150px;
    }
    .boxP2{
        padding:15px; margin-right:-15px; float:left;width:285px; 
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

        @media only screen and (min-width: 768px) {      

            .bg-img-3 {
            background-image: url("assets/images/<?=$fundobg;?>"); 
            background-repeat: no-repeat; 
            background-position: relative;  
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            width: 100%;
            }

  
            }
            .bg-img-3 {
            background-image: url("assets/images/<?=$fundobg;?>"); 
            background-repeat: no-repeat; 
            position: relative; 
            width: 100%;
                /*
            min-height: 468px;
            height: 100vh;
            background-size: 100% 50%;
            */
            }
            

</style>

<?php 

use Database\MySQL;
$pdo = MySQL::acessabd();
use Functions\Acesso;

$ip_address = $_SERVER['REMOTE_ADDR'];
$_retorno_html = Acesso::logAcessoTela('1',$ip_address);

require_once('navigatorbar.php');


$_vlrPagar = 0;
$_totalavisouser = 0;
$_dtvencimento =  "";

$consulta = $pdo->query("SELECT date_format(pg_dtvencimento,'%d/%m/%Y') as vencimento,pg_valor FROM info.pagamento 
                        WHERE pg_idcliente = '".$_SESSION['CODIGOCLI']."'  and pg_valorpago = '0' and pg_dtaviso < CURRENT_DATE() ");
$retorno = $consulta->fetchAll();
foreach ($retorno as $row) {
    $_vlrPagar  = $row["pg_valor"];
    $_dtvencimento = $row["vencimento"];
}

//verificar se busca aviso
$_totalaviso = 0;
$_totalavisouser  = 0;
$_totalavisoNiver = 0;
if($_SESSION['_DTUSERAVISO'] != date('Y-m-d')){
     //aviso sistema
    $consultaAvisoSistema = $pdo->query("SELECT * FROM bd_prisma.avisos WHERE av_dtinicio <= '".date('Y-m-d')."' AND '".date('Y-m-d')."'  <= av_dtfim");
    $retornoAvisoSistema = $consultaAvisoSistema->fetchAll();
    $_totalavisoSistema =  $consultaAvisoSistema->rowCount();
    if( $_totalavisoSistema > 0) { 
        $_totalavisouser  = 1;     
    }

    $consultaAviso = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".avisos WHERE av_dtinicio <= '".date('Y-m-d')."' AND '".date('Y-m-d')."'  <= av_dtfim");
    $retornoAviso = $consultaAviso->fetchAll();
    $_totalaviso =  $consultaAviso->rowCount();
    if( $_totalaviso > 0) { 
        $_totalavisouser  = 1;     
    }
    
    $consultaNiver2 = $pdo->query("SELECT usuario_CODIGOUSUARIO,usuario_NOME FROM ".$_SESSION['BASE'].".usuario
    WHERE  MONTH(datanascimento) = MONTH(CURDATE())
    AND DAY(datanascimento) = DAY(CURDATE()) AND usuario_ATIVO = 'Sim'");
    $retornoNiver2 = $consultaNiver2->fetchAll();
    $_totalavisoNiver =  $consultaNiver2->rowCount();
    if($_totalavisoNiver > 0) { 
            
        foreach ($retornoNiver2 as $row) {
            $_nomeaniversariante = $row['usuario_NOME'];
        }
        $consultaNiver = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".avisos WHERE av_id = '1'");
        $retornoNiver = $consultaNiver->fetchAll();
        if($consultaNiver->rowCount() > 0) { 
            $_totalavisouser  = 1;
        }
    }
}



?>
     
        <section class="bg-img-3" id="home">
        <div class="container "  >
        <div class="wrapper">       
        <form class="form-horizontal m-t-2p" id="form1" name="form1" method="post" action="">  
        <input type="hidden"  name="idtecsession" id="idtecsession" value="<?=$idtecsession;?>">   
        <input type="hidden"  name="garantia" id="garantia" value="">         
                <!-- Page-Title -->
            <div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="btn-group pull-right m-t-15">                           
                        </div>
                        <h4 class="page-title"><span style="color:#fff">Olá, <?=$_SESSION['APELIDO'];?>!</span></h4>
                        <p class="text-muted page-title-alt"><span style="color:#fff">Tenha um ótimo dia de Trabalho!</span></p>
                    </div>
                </div>
                <?php if ($_SESSION["nivel"] == 0) { //1 perfil tecnico
                ?>           
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row xsprisma"  >
                            <div class="col-sm-12 col-xs-12 " >
                            <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00005"  onclick="_00005()">
                                        <div class="boxIntero <?php if($_SESSION['per001'] == ""){ echo 'desativado'; } ?>"> <i class="icon-people" style="font-size:42px;"></i><p class="text-menu">Clientes<p></div>
                                    </a>
                                </div>      
                              
                                <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00003" onclick="_00003()" >
                                        <div class="boxIntero <?php if($_SESSION['per003'] == ""){ echo 'desativado'; } ?>"> <i class=" ti-bag" style="font-size:42px;"></i><p class="text-menu">Serviços - OS<p></div>
                                    </a>
                                </div>
                               
                       
                                <div class="boxP paddin-t-N1 ">
                                    <a href="javascript:void(0)" id="_trackmob2025" >
                                        
                                        <div class="boxIntero <?php if($_SESSION['per007'] == ""){ echo 'desativado'; } ?>"> <i class=" md-directions-car" style="font-size:42px;"></i><p class="text-menu">PrismaMob<p></div>
                                    </a>
                                </div>
                        

                                <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00008"  onclick="_00008()">
                                        <div class="boxIntero <?php if($_SESSION['per013'] == ""){ echo 'desativado'; } ?>"> <i class="icon-basket-loaded" style="font-size:42px;"></i><p class="text-menu">Vendas/PDV<p></div>
                                    </a>
                                </div>
                               
                                                        
                            </div>
                        </div>
                        <div class="row xsprisma"  >
                            <div class="col-sm-12 col-xs-12 " >                                            
                               
                       
                                <div class="boxP paddin-t-N1 ">
                                    <a href="javascript:void(0)" id="_00001" onclick="_00001()" >
                                        <div class="boxIntero <?php if($_SESSION['per002'] == ""){ echo 'desativado'; } ?>"> <i class="icon-calender" style="font-size:42px;"></i><p class="text-menu">Agenda<p></div>
                                    </a>
                                </div>
                                <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00004" onclick="_00004()">
                                        <div class="boxIntero <?php if($_SESSION['per005'] == ""){ echo 'desativado'; } ?>"> <i class="icon-notebook" style="font-size:42px;"></i><p class="text-menu">Roteiro</p></div>
                                    </a>
                                </div>
                                <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00002" onclick="_00002()">
                                        <div class="boxIntero  <?php if($_SESSION['per006'] == ""){ echo 'desativado'; } ?>"> <i class="icon-docs" style="font-size:42px;"></i><p class="text-menu">Requisição</p><p></p></div>
                                    </a>
                                </div>                                
                                <?php if($_SESSION['CODIGOCLI'] != '7201') { ?>
                              
                                        <div class="boxP paddin-t-N1">
                                            <a href="javascript:void(0)" id="_00009"  onclick="_00009()">
                                                <div class="boxIntero <?php if($_SESSION['per146'] == ""){ echo 'desativado'; } ?>"> <i class=" icon-calculator fa-2x" style="font-size:42px;"></i><p class="text-menu">Financeiro<p></div>
                                            </a>
                                        </div>
                                <?php } else { ?>
                                    <div class="boxP paddin-t-N1 ">                                   
                                    <a href="javascript:void(0)" onclick="_geral('S00022','221')">                                       
                                        <div class="boxIntero <?php if($_SESSION['per221'] == ""){ echo 'desativado'; } ?>"> <i class=" ion-settings" style="font-size:42px;"></i><p class="text-menu">Painel Oficina<p></div>
                                    </a>
                                </div>
                                <?php } ?>
                           
                                                          
                                                        
                            </div>
                        </div>

                       
                    </div>
                 
                </div>  
                <?php } else {
                  
                //verificar perfil para técnico
                
                $consultaPerfil = $pdo->query("SELECT usuario_CODIGOUSUARIO,usuario_perfil2 FROM ".$_SESSION['BASE'].".usuario
                WHERE usuario_CODIGOUSUARIO = '".$_SESSION['tecnico']."' and usuario_ATIVO = 'Sim' and usuario_cliente = 0");
                $retornoPerfil = $consultaPerfil->fetchAll();
                    foreach ($retornoPerfil as $row) {
                        $_tipoperfil = $row['usuario_perfil2'];                      
                    }
                   
          
                    ?> 
                    <div class="row">
                    <div class="col-sm-5">
                        <div class="row xsprisma"  >
                        <?php  if($_tipoperfil == 9){ //perfil oficina ?>
                            <div class="col-sm-12 col-xs-12 " > 
                              <div class="boxP2 paddin-t-N1 ">
                                    <a href="javascript:void(0)" id="_00011"  onclick="_00011()">
                                        <div class="boxIntero "> <i class="fa fa-taxi" style="font-size:42px;"></i><p class="text-menu">Atendimento<p></div>
                                    </a>
                                </div> 
                              
                            </div>
                                <?php }else {  ?>
                                    <div class="col-sm-10 col-xs-12 " > 
                              <div class="boxP paddin-t-N1 ">
                                    <a href="javascript:void(0)" id="_00011"  onclick="_00011()">
                                        <div class="boxIntero "> <i class="fa fa-taxi" style="font-size:42px;"></i><p class="text-menu">Atendimento <p></div>
                                    </a>
                                </div> 
                                <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00002" onclick="_00022()">
                                        <div class="boxIntero  "> <i class="icon-people" style="font-size:42px;"></i><p class="text-menu">Ordenação</p><p></p></div>
                                    </a>
                                </div> 
                            </div>
                                <?php } ;?>
                        </div>

                       
                    </div>
                 
                </div>  
                <div class="row">
                    <div class="col-sm-5">
                        <div class="row xsprisma"  >
                            <div class="col-sm-10 col-xs-12 " >
                             
                                <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00046" onclick="_00046()">
                                        <div class="boxIntero <?php if($_SESSION['per003'] == ""){ echo 'desativado'; } ?>"> <i class="fa fa-wpforms" style="font-size:42px;"></i><p class="text-menu">Consultar OS</p><p></p></div>
                                    </a>
                                </div>                               
                                <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00056"  onclick="_00056()">
                                        <div data-toggle="modal" data-target="#consultaEstoque" class="boxIntero <?php if($_SESSION['per116'] == ""){ echo 'desativado'; } ?>"> <i class="ti-package" style="font-size:42px;"></i><p class="text-menu">Estoque<p></div>
                                    </a>
                                </div>                  
                            </div>
                        </div>

                       
                    </div>
                 
                </div>  
                <div class="row">
                    <div class="col-sm-5">
                        <div class="row xsprisma"  >
                            <div class="col-sm-10 col-xs-12 " >                             
                                <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00004" onclick="_00047()">
                                        <div class="boxIntero <?php if($_SESSION['per005'] == ""){ echo 'desativado'; } ?>""> <i class="icon-notebook" style="font-size:42px;"></i><p class="text-menu">Roteiro</p><p></p></div>
                                    </a>
                                </div>                               
                                <div class="boxP paddin-t-N1">
                                    <a href="javascript:void(0)" id="_00057"  onclick="_00057()">
                                        <div  class="boxIntero <?php if($_SESSION['per006'] == ""){ echo 'desativado'; } ?>"> <i class="icon-docs" style="font-size:42px;"></i><p class="text-menu">Requisição<p></div>
                                    </a>
                                </div>               
                                                        
                            </div>
                        </div>

                       
                    </div>
                 
                </div>  
                <?php }         ?>

            </div>
        </div>
        <input type="hidden" id="_keyform" name="_keyform"  value="">
        <input type="hidden" id="_chaveid" name="_chaveid"  value="">
        </form>   
    </div>
    </section>

    <div id="custom-modal-consultaEstoque" name="custom-modal-consultaEstoque" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-sm">   
        <form  id="form10" name="form10" action="javascript:void(0)">         
            <div class="modal-content text-center" >        
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
                                    <h4 class="modal-title">Consulta Estoque</h4>
                                </div>

                                <div class="modal-body" id="result-osconsulta">
                               
                                
                                        <div class="row">                                                        
                                            <div class="col-sm-12" >       
                                        Em Breve                                      
                                                 
                                            </div>                                                             
                                        </div>
                                        <div class="row">                                                        
                                            <div class="col-sm-12"  style="margin-top: 10px ;">
                                                 
                                            </div>                                                             
                                        </div>                               
                                </div>
                            </div>  
                        </form>          
                </div>
    </div>
    <div id="custom-modal-comissao" name="custom-modal-comissao" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-sm">   
        <form  id="form10" name="form10" action="javascript:void(0)">         
            <div class="modal-content text-center" >        
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
                                    <h4 class="modal-title">Comissões</h4>
                                </div>

                                <div class="modal-body" id="result-osconsulta">
                                <input type="hidden" id="_idref" name="_idref"  value="">
                                
                                        <div class="row">                                                        
                                            <div class="col-sm-12" >       
                                        Em Breve                                      
                                                 
                                            </div>                                                             
                                        </div>
                                        <div class="row">                                                        
                                            <div class="col-sm-12"  style="margin-top: 10px ;"> 
                                                 
                                            </div>                                                             
                                        </div>
                               
                                </div>
                            </div>  
                        </form>          
                </div>
    </div>

<?php if ($_SESSION["nivel"] == 0) { //1 perfil tecnico
    ?>
    <div style="background: rgb(87,87,85,1); ">
        <div class="container" style="padding-bottom: 20px;">
            
            <div class="row centerBox" >
                
                <!----BOX 1 ---------------------  <div class="dv">---------------->
              
                                                                  
                     <div class="col-sm-4" >   
                    <h4 style="color:#FFF;">Resumo de Hoje</h4>
                    <div style="background-color:#FFF; border-radius:8px; min-height: 370px;max-height: 350px; ">
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-actions-bar m-b-0">
                                    <thead>
                                        <tr>
                                            <th></th>                                            
                                            <th></th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    <?php
                                     require_once('resumoHoje.php'); 
                                     ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!----BOX 2 ------------------------------------->
              
                                                                  
                     <div class="col-sm-4" >  
                    <h4 style="color:#FFF;">Últimas Atividades</h4>
                    <div style="background-color:#FFF; border-radius:8px; ">
                    <div class="p-20" style="min-height: 370px;max-height: 300px;">
                         <div class="nicescroll p-l-r-10" style="min-height: 350px;max-height: 300px;">
                            <div class="timeline-2">
                                <?php
                                require_once('ultimasAtividades.php') 
                                 ?>
                            </div>
                                </div>
                    </div>
                </div>
                   
                </div>
                <!----BOX 3 ------------------------------------->
                      
                                                                    
                     <div class="col-sm-4" >  
                <h4 style="color:#FFF;"></h4>
                  <h4 style="color:#FFF;">Acesso Rápido</h4>
                    <div id="acessoRapido" style="background-color:#FFF; border-radius:8px; min-height: 370px;max-height: 350px;background-size:100% 100%; text-align:center">
                    <div class="card-box">
                    <?php
                                  require_once('acessoRapido.php'); //Acesso Rápido
                                     ?>
                    </div>
                    
                </div>
                </div>
             
               
            </div>
        </div>
    </div>   
    <?php } ?>

             

            </div>
      <!-- ficha  -->

 
               <!-- End Footer -->
        
        <div class="container m-t-40">
            <p class="text-center"><b><?php 
          
          //  echo "perfil ".$_SESSION["nivel"];
        //  echo $_SESSION['BASE']."|".$_SESSION['EMPRESA']."|".$_SESSION['NOME'];
            ?>
            </b></p>
        </div>

         <!-- Modal Saldo -->
         <div id="custom-modal-saldo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
         <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-5">
                        <img src="assets/images/small/att_valor.png" width="100%">
                    </div>
                    <div class="col-sm-7">
                        <div class="row" id="info1">
                            <h3><b>Fatura Digital</b></h3>
                            <p>Olá, está disponível o valor para pagamento. Vencimento  <strong class="text-default"><?=$_dtvencimento;?></p>
                            <h4>Valor: <strong class="text-default">R$ <?=number_format($_vlrPagar, 2, ',', '.');?> </strong></h4>                 
                            <div class="alert alert-info">
                                <p>Seguem abaixo os dados do PIX para realizar o pagamento.</p>
                            </div>
                          
                        </div>
                        <div id="info2" style="width:100%; padding:1%;">
                            
                                <h3><b>Meios de pagamentos</b></h3>
                                
                                    <div class="col-xs-9" style="padding-top:15px;">
                                        <table class="table">
                                            <tbody><tr>
                                                <td style="width:100px;">Chave Pix:</td>
                                                <th style="color:#00A8E6;">11.493.284/0001-11</th>
                                            </tr>
                                            <tr>
                                                <td>Beneficiário:</td>
                                                <th>Prisma Comercio e Serviços de Informática</th>
                                            </tr>
                                            <tr>
                                                <td>Banco</td>
                                                <th>077 - Banco Inter</th>
                                            </tr>
                                            
                                        </tbody></table>
                                    </div>
                                    <div class="col-xs-3">
                                        <img src="assets/images/qrcodePrisma.jpg" width="110%">
                                    </div>
                                    <!--
                                    <div class="col-xs-12">
                                        <div class="alert alert-danger">
                                            <p>Não esqueça de nos enviar o comprovante após a realização da operação.</p>
                                        </div>
                                    </div>
                                    -->
                                                                   
                                                            
                           </div>
                           <div class="row">
                                <div class="col-xs-6" align="center">
                                    <button type="button" class="btn btn-default btn-block" data-dismiss="modal" >Fechar</button>
                                </div>
                                <div class="col-xs-6" align="center" >
                                    <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="fecharAviso()">Não mostrar mais hoje</button>
                                </div>   

                           </div>
                                                                             
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        </div>


         <!-- Modal Aviso usuario -->
         <div id="custom-modal-aviso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                        <div class="modal-header">  
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                        <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box" >                                       
                                            <div class="row">
                                                <div class="col-md-12"> 
                                              
                                                    <!-- START carousel-->
                                                    <div id="carousel-example-captions" data-ride="carousel" class="carousel slide">
                                                        <ol class="carousel-indicators">
                                                            <?php 
                                                            
                                                            if($_totalavisouser > 0) {
                                                                if(($_totalaviso + $_totalavisoNiver+$_totalavisoSistema) > 1) {  
                                                                  
                                                                    for ($i = 1; $i <= $consultaAviso->rowCount(); $i++) {
                                                                        if($i == 1) {
                                                                                echo ' <li data-target="#carousel-example-captions" data-slide-to="'.$i.'" class="active"></li>';
                                                                        }else{
                                                                                  echo ' <li data-target="#carousel-example-captions" data-slide-to="'.$i.'"></li>';
                                                                        }
                                                                      
                                                                        
                                                                    }
                                                             }?>
                                                        </ol>
                                                      
                                                        <div role="listbox" class="carousel-inner"  style="min-height:500px;" >                                                 
                                                        <?php 
                                                         $i = 0;
                                                        if($_totalaviso > 0) { 
                                                          
                                                                foreach ($retornoAviso as $row) {
                                                                    $i++;
                                                                    $_titulo  = $row["av_titulo"];
                                                                    $_mensagem = $row["av_texto"];
                                                                    $_imagem =  $row["av_imagem"];
                                                                    if($i == 1) {
                                                                        $_op = 'active';
                                                                    }else{
                                                                        $_op = '';
                                                                    }
                                                                    if(trim($_imagem) == "") {
                                                                        $_cor = 'style="background-color: #999;" ';
                                                                    } 
                                                                    ?>
                                                                    <div class="item  <?=$_op;?>">
                                                                    <div class="carousel-caption"  <?=$_cor;?>>
                                                                            <h3 class="text-white font-600"><?=$_titulo;?></h3>
                                                                            <p>
                                                                               <?=$_mensagem;?>
                                                                            </p>
                                                                        </div>
                                                                                                                                              
                                                                        <img src="data:image/png;base64,<?=$_imagem;?>" style="max-height:500px;" />                                                                      
                                                                     </div>
                                                                    <?php
                                                                }
                                                            }
                                                            if($_totalavisoNiver > 0) { 
                                                             
                                                                    foreach ($retornoNiver as $row) {
                                                                        $i++;
                                                                        $_titulo  = $row["av_titulo"];
                                                                        $_mensagem = $row["av_texto"];
                                                                        $_imagem =  $row["av_imagem"];
                                                                        if($i == 1 and $_op == "" ) {
                                                                            $_op = 'active';
                                                                        }else{
                                                                            $_op = '';
                                                                        }
                                                                        if(trim($_imagem) == "") {
                                                                            $_cor = 'style="background-color: #999;" ';
                                                                        } 
                                                                        ?>
                                                                        <div class="item  <?=$_op;?>">
                                                                        <div class="carousel-caption"  <?=$_cor;?>>
                                                                                <h3 class="text-white font-900;" ><?=strtoupper($_nomeaniversariante);?></h3>
                                                                                <p>
                                                                                   <?=$_mensagem;?>
                                                                                </p>
                                                                            </div>
                                                                                                                                                  
                                                                            <img src="data:image/png;base64,<?=$_imagem;?>" style="max-height:500px; max-with:850px" />                                                                      
                                                                         </div>
                                                                        <?php
                                                                    }
                                                                }

                                                                if($_totalavisoSistema> 0) { 
                                                                   
                                                                        foreach ($retornoAvisoSistema as $row) {
                                                                            $i++;
                                                                            $_titulo  = $row["av_titulo"];
                                                                            $_mensagem = $row["av_texto"];
                                                                            $_imagem =  $row["av_imagem"];
                                                                            if($i == 1 and $_op == "" ) {
                                                                                $_op = 'active';
                                                                            }else{
                                                                                $_op = '';
                                                                            }
                                                                            if(trim($_imagem) == "") {
                                                                                $_cor = 'style="background-color: #999;" ';
                                                                            } 
                                                                            ?>
                                                                            <div class="item  <?=$_op;?>">
                                                                            <div class="carousel-caption"  <?=$_cor;?>>
                                                                                    <h3 class="text-white font-900;" ><?=($_titulo);?></h3>
                                                                                    <p>
                                                                                       <?=$_mensagem;?>
                                                                                    </p>
                                                                                </div>
                                                                                                                                                      
                                                                                <img src="data:image/png;base64,<?=$_imagem;?>" style="max-height:500px; max-with:850px" />                                                                      
                                                                             </div>
                                                                            <?php
                                                                        }
                                                                    }
                                                           
                                                        ?>
                                                           
                                                        
                                                        </div>
                                                        <?php
                                                        if(($_totalaviso + $_totalavisoNiver+$_totalavisoSistema) > 1) {    ?>
                                                             <a href="#carousel-example-captions" role="button" data-slide="prev" class="left carousel-control"> <span aria-hidden="true" class="fa fa-angle-left"></span> <span class="sr-only">Anterior</span> </a>
                                                             <a href="#carousel-example-captions" role="button" data-slide="next" class="right carousel-control"> <span aria-hidden="true" class="fa fa-angle-right"></span> <span class="sr-only">Próximo</span> </a>
                                                        <?php  } 
                                                      }  
                                                        ?>
                                                    </div>
                                                    <!-- END carousel-->
                                                </div>
                                                <div class="row">
                            
                                 

                           </div>
                                             
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="text-align: center;" >
                                    <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="fecharAvisoUser()">Não mostrar mais hoje</button>
                                </div> 
                        </div>
                    </div>
            </div>
        </div>

        <div id="custom-modal-ficha" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            
		<div class="modal-content " id="_fichadetalhe">

        </div> <!-- end container -->
        </div>
        <!-- end wrapper -->


                    </div>
                </div>
            </div>
        </div>
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

                $(_trackmob2025).click(function(){      
                     var $_keyid =   "_ATa00006"; 
                    $('#_keyform').val($_keyid); 

                    var permissao = "7";              
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $("#form1").submit();  
                        }								  
					});	
                   
                });
	
                $(formOS).submit(function(){ //pesquisa os
                     
                     var $_keyid =   "S00001";                     
                     $('#_keyform').val($_keyid);   
                                             
                         var dados = $("#formOS :input").serializeArray();
                         dados = JSON.stringify(dados);		
                                    
                       								                                                   
                           $('#_chaveid').val($('#numOS').val());   
                           $("#form1").submit();  
             
                 
                 });

                 $(fbuscanOS).submit(function(){ //pesquisa os Nº da O.S
                    
                     var $_keyid =   "S00001";                     
                     $('#_keyform').val($_keyid);   
                                             
                         var dados = $("#fbuscanOS :input").serializeArray();
                         dados = JSON.stringify(dados);		
                                    
                      								                                                   
                           $('#_chaveid').val($('#numOS_a').val());   
                           $("#form1").submit();  
             
               

                 });

                 $(fbuscafOS).submit(function(){ //numero OS fabricante
                  
                     var $_keyid =   "S00001";                     
                     $('#_keyform').val($_keyid);   
                                             
                         var dados = $("#numOS_osfab :input").serializeArray();
                         dados = JSON.stringify(dados);		
                                    
                       								                                                   
                           $('#_chaveid').val("f"+$('#numOS_osfab').val());   
                           $("#form1").submit();  
             
                 

                 });

                 $(fbuscaserieOS).submit(function(){ //pesquisa os
                  
                  var $_keyid =   "S00001";                     
                  $('#_keyform').val($_keyid);   
                                          
                      var dados = $("#numOS_s :input").serializeArray();
                      dados = JSON.stringify(dados);                                               
                        $('#_chaveid').val("s"+$('#numOS_s').val());   
                        $("#form1").submit();  
          
              

              });

              $(fbuscapncOS).submit(function(){ //pesquisa pnc
                  
                  var $_keyid =   "S00001";                     
                  $('#_keyform').val($_keyid);   
                                          
                      var dados = $("#numOS_pnc :input").serializeArray();
                      dados = JSON.stringify(dados);                                               
                        $('#_chaveid').val("pnc"+$('#numOS_pnc').val());   
                        $("#form1").submit();  
          
              

              });

              $(fbuscapedOS).submit(function(){ //pesquisa pedido Nº Pedido O.S
                  
                  var $_keyid =   "S00001";                     
                  $('#_keyform').val($_keyid);   
                                          
                      var dados = $("#numOS_ped :input").serializeArray();
                      dados = JSON.stringify(dados);                                               
                        $('#_chaveid').val("ped"+$('#numOS_ped').val());   
                        $("#form1").submit();  

              });

              $(fbuscafCPF).submit(function(){ //pesquisa pedido Nº Pedido O.S
                  
                  var $_keyid =   "S00001";                     
                  $('#_keyform').val($_keyid);   
                                          
                      var dados = $("#numOS_CPF :input").serializeArray();
                      dados = JSON.stringify(dados);                                               
                        $('#_chaveid').val("CPFCNPJ"+$('#numOS_CPF').val());   
                        $("#form1").submit();  

              });

              $(fbuscaTelefone).submit(function(){ //pesquisa pedido Nº Pedido O.S
                  
                  var $_keyid =   "S00001";                     
                  $('#_keyform').val($_keyid);   
                                          
                      var dados = $("#numOS_telefone :input").serializeArray();
                      dados = JSON.stringify(dados);                                               
                        $('#_chaveid').val("PHONE"+$('#numOS_telefone').val());   
                        $("#form1").submit();  

              });

                      


                 $(fbuscaResumo).submit(function(){ //Resumo

                      var permissao = "161";              
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result == ""){
                            $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para Consultar.');
                        }else{
                                  var $_keyid = "S00012";
                                    $('#_chaveid').val($('#numOS_ficha').val());   
                                    var dados = $("#fbuscaResumo :input").serializeArray();
                                    dados = JSON.stringify(dados);
                                
                                    _carregando('#_fichadetalhe');      
                                    $('#custom-modal-ficha').modal('show');
                                
                                    $.post("page_return.php", {
                                        _keyform: $_keyid,
                                        dados: dados,
                                        acao: 8
                                    }, function(result) {
                                    
                                        $('#_fichadetalhe').html(result);
                                    });
                                        }								  
                                    });	
                   
             
             
                  });

              


                


               

              
        /*        
               // $("#publicidade").css("background-image","url('assets/images/banners/banner1.jpg')");

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

                    var permissao = "2";              
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $("#form1").submit();  
                        }								  
					});	
                                 
                };

                function   _00011 () {   
                   
                   <?php  if($_tipoperfil == 9){ //perfil oficina ?>
                             var $_keyid =   "_ATa00020"; 
                            
                   <?php  }else{ ?>
                            var $_keyid =   "_ATa00006"; 
                        <?php }
                    ?>

                
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

                

                function _00008 () {
                    var $_keyid =   "_Fl00004"; 
                    $('#_keyform').val($_keyid);     
                    
                    var permissao = "13";                  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                       
                            window.open("/app/v1/pdv/caixa.php", "_blank" );   
                        }								  
					});	  
                };

                      
                function _00009 () {
                    var $_keyid =   "_Fl00004"; 
                    $('#_keyform').val($_keyid);     

                    
                    var permissao = "146";                  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                       
                           $("#form1").submit();  
                        }								  
					});	  
                };


                function _geral(_keyid,permissao) {
                    $('#_keyform').val(_keyid);

                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $("#form1").submit();  
                        }								  
                    });
        }


                    function  _resumoconsultaOS(_idresOS){     
                        var $_keyid =   "S00001";                     
                         $('#_keyform').val($_keyid);   

                         var dados = $("#fbuscanOS :input").serializeArray();
                         dados = JSON.stringify(dados);		
                                    
                         $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									                                                   
                           $('#_chaveid').val(_idresOS);   
                           $("#form1").submit();  
             
                  });

                    }

                    function fecharAviso(){
                        var $_keyid =   "_As00006";                     
                         $('#_keyform').val($_keyid);   
                                $.post("page_return.php", {
                                        _keyform: $_keyid
                                    }); 
                    }

                    function fecharAvisoUser(){
                        var $_keyid =   "_As00007";                     
                         $('#_keyform').val($_keyid);   
                                $.post("page_return.php", {
                                        _keyform: $_keyid
                                    }); 
                    }

                    function  _linkresumo(_idresOS){   
                      
                        var $_keyid =   "S00002";                     
                         $('#_keyform').val($_keyid);   
                         $('#garantia').val(_idresOS);   
                    

                       

                         var permissao = "3";                  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                         var dados = $("#form1 :input").serializeArray();
                         dados = JSON.stringify(dados);		
                       
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){								                                                   
                         
                           $("#form1").submit();  
             
                          });
                        }								  
					});	 
                                    
                      

                    }

                    
                    

                    function _carregando (_idmodal){
                      $(_idmodal).html('' +
                    '<div class="bg-icon pull-request">' +
                    '<img src="assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
                    '<h4 class="text-center">Aguarde, carregando dados...</h4>' +
                    '</div>');

                  }  

         
                 
                   
                   if (<?=$_vlrPagar;?> != 0 && <?=$_permissaofatura;?> != "0" ) {
                       $('#custom-modal-saldo').modal('show');
                   }

                   if (<?=$_totalavisouser;?> != 0  ) {
                       $('#custom-modal-aviso').modal('show');
                   }

            <?php if ($_SESSION["nivel"] == 1) { //1 perfil tecnico ?>
                history.pushState({}, null, "?idtecsession=<?=$idtecsession;?>");
            <?php } ?>
            
        </script>
    </body>
</html>