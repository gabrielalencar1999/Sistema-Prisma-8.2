<?php session_start();

$_esconder = "";

$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
$symbian =  strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");

if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true) {
   $logo = "iconN2.png";
  
} else {
    $logo = "logo_sm_2.png";

}

//buscar logo 
?>
<!-- Navigation Bar-->
<style>
    .diss{
        color:#999;
        opacity:0.8;
        cursor:no-drop;
    }
    .divP{
        border:1px solid #EEE; border-radius:8px; width:100%; background-color:#EEE; text-align:center;
    }
    .divS{
        width:100%; margin-top:-11px; padding-top:8px; padding-bottom:8px; border-radius:8px 8px 0px 0px;
    }
    .table > tbody > tr > th, .table > tbody > tr > td{
        border-top:0px !important;
    }
</style>
<header id="topnav">
    <div class="topbar-main">
        <div class="container">

            <!-- Logo container-->
            <div class="logo">
                <a href="javascript:void(0)" id="_back00000" class="logo"><span><img  src="assets/images/<?=$logo;?>"  alt="prisma"></span></a> 
               
            </div>
            <!-- End Logo container-->
            

            <div class="menu-extras">

                <ul class="nav navbar-nav navbar-right pull-right">
                
                    <li class="dropdown navbar-c-items">
                       
                          <?php if ($_SESSION["nivel"] == 1) { //1 perfil tecnico 
                           $_esconder = "none";
                          ?>
                               <span id="_prodmenu" ></span>                        
                           <?php }else{ ?>
                                <a href="javascript:void(0)" id="_prodmenu" <?php if($_SESSION['per116'] == ""){ ?>class="diss hidden-xs"<?php } ?>><i class="ti-package <?php if($_SESSION['per116'] == ""){ echo 'diss'; } ?>"></i> Peças</a>                         
                            <?php } ?>
                         
                    </li>          
                <li class="navbar-c-items"  style="display:<?=$_esconder;?> ;"> 
               
                                <form name="formOS" id= "formOS"  class="navbar-left app-search pull-left hidden-xs"  action="javascript:void(0)">
                                     <input type="text" placeholder="Nº OS..." class="form-control" id="numOS" name="numOS">                                    
                                     <a ><i class="fa fa-search"></i></a>                                     
                                </form>
                            </li>
                            <?php
                                use Functions\Acesso;
                                $qtdependecia  = Acesso::pendenciafinanceira(); 
                               
                                if($qtdependecia > 0) { 

                               
                                ?>
                           
                             <li class="dropdown navbar-c-items">                            
                       
                                <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                                    <i class="ion-alert-circled text-danger"></i> 
                                    <?php if($qtdependecia > 0){ ?>
                                     <span class="badge badge-xs badge-danger">1</span>
                                     <?php } ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg">
                                    <li class="notifi-title"><span class="label label-default pull-right"></span>Pendência Financeira</li>
                                    <li class="list-group slimscroll-noti notification-list">                               
                                   
                                      
                                       <!-- list item-->
                                      
                                          <div class="media">
                                             
                                             <div class="media-body" >
                                                <h5 class="media-heading" style="white-space:normal"><br>Há uma pendência financeira! 
                                                    <br> Entre em contato com o Suporte Prisma para regularização</h5>
                                                    
                                                <p class="m-0">
                                                    <small>Suporte Prisma </small><a href="https://wa.me/5541991458007"  target="_blank">
                                               
                                                    <em class="fa   fa-whatsapp noti-success fa-2x" style="border: none;"></em></a>
                                                </p>
                                             </div>
                                          </div>
                                      

                                </ul>
                            </li> 
                            <?php  } ?>
                            <li class="dropdown navbar-c-items">                            
                                <?php
                               
                                $qtdenotificacao  = Acesso::notificacao(); 
                               
                                ?>
                           
                                <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                                    <i class="icon-bell"></i> 
                                    <?php if($qtdenotificacao > 0){ ?>
                                     <span class="badge badge-xs badge-danger"><?=$qtdenotificacao;?></span>
                                     <?php } ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg">
                                    <li class="notifi-title"><span class="label label-default pull-right">Nova <?=$qtdenotificacao;?></span>Notificações</li>
                                    <li class="list-group slimscroll-noti notification-list">
                                  <?php if($qtdenotificacao > 0){
                                         $_retorno  = Acesso::notificacaolist(); 
                                   
                                        foreach($_retorno as $value){
                                          
                                        ?>
                                      
                                       <!-- list item-->
                                       <a href="javascript:void(0);" class="list-group-item" data-toggle="modal" data-target="#custom-modal-notificacao" onclick="_notificao('<?=$value->not_id;?>')" >
                                          <div class="media">
                                             <div class="pull-left p-r-10">
                                                 <em class="fa  fa-wrench noti-primary"></em>
                                             </div>
                                             <div class="media-body" >
                                                <h5 class="media-heading" style="white-space:normal"> <?=$value->not_mensagem;?></h5>
                                                <p class="m-0">
                                                    <small>Estoque</small>
                                                </p>
                                             </div>
                                          </div>
                                       </a>                                   

                               
                                    <?php } 
                                } ?>  
                                    <li>
                                        <a href="javascript:void(0);" class="list-group-item text-right">                                           
                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#custom-modal-notificacao " onclick="_notificao('<?=$value->not_id;?>')"> <small class="font-600">ver notifiçações</small>   </a>
                                    </li>
                                </ul>
                            </li>  
                            <?php if ($_SESSION["nivel"] == 1) { //1 perfil tecnico   
                                $_esconder = "none";
                            }
                            ?>    
                    <li class="dropdown navbar-c-items" style="display:<?=$_esconder;?> ;">
                        <a href="" class="dropdown-toggle waves-effect waves-light hidden-xs" data-toggle="dropdown" aria-expanded="true">
                        <i class="md md-dashboard"></i> Meu Negócio  </a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" id="_menu"><i class=" ti-desktop text-custom m-r-10"></i> <strong>Menu</strong></a></li>
                            <li class="divider"></li> 
                            <li><a href="javascript:void(0)" id="_trackmob2025" <?php if($_SESSION['per007'] == ""){ ?>class="diss"<?php } ?>><i class="fa fa-taxi  text-success m-r-10 <?php if($_SESSION['per007'] == ""){ echo 'diss'; } ?>"></i> PrismaMob</a></li>
                        </li>
                            <li class="divider"></li> 
                            <li><a href="javascript:void(0)" id="_menuadmin" <?php if($_SESSION['per007'] == ""){ ?>class="diss"<?php } ?>><i class="ti-briefcase text-custom m-r-10 <?php if($_SESSION['per007'] == ""){ echo 'diss'; } ?>"></i> Adminstrativo</a></li>
                            <li><a href="javascript:void(0)" id="_menufin" <?php if($_SESSION['per016'] == ""){ ?>class="diss"<?php } ?>><i class="ti-credit-card text-custom m-r-10 <?php if($_SESSION['per016'] == ""){ echo 'diss'; } ?>"></i> Financeiro</a></li>
                            <li> <a href="javascript:void(0)" id="_menuServ" <?php if($_SESSION['per008'] == ""){ ?>class="diss"<?php } ?>><i class="ti-notepad text-custom m-r-10 <?php if($_SESSION['per008'] == ""){ echo 'diss'; } ?>"></i> Serviços</a></li>
                            <li><a href="javascript:void(0)" id="_menuvend" <?php if($_SESSION['per014'] == ""){ ?>class="diss"<?php } ?>><i class="ti-shopping-cart-full text-custom m-r-10 <?php if($_SESSION['per014'] == ""){ echo 'diss'; } ?>"></i>Vendas</a></li>
  
                            <li><a href="javascript:void(0)" id="_menuestoq" <?php if($_SESSION['per009'] == ""){ ?>class="diss"<?php } ?>><i class="ti-package text-custom m-r-10 <?php if($_SESSION['per009'] == ""){ /*echo 'diss';*/ } ?>"></i> Estoque</a></li>                             
                           
                           
                            <li class="divider"></li>
                            <li><a href="javascript:void(0)" id="_menuconf" <?php if($_SESSION['per010'] == ""){ ?>class="diss"<?php } ?>><i class="ti-settings text-custom m-r-10 <?php if($_SESSION['per010'] == ""){/*echo 'diss';*/ } ?>"></i> Configurações</a></li>
          
                    </li>                                          

                        </ul>
                    </li>
                  
                 
                    <li class="dropdown navbar-c-items">
                        <a href="" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown" aria-expanded="true">
                        <img src="assets/images/usersnew/<?=$_SESSION["imglogin"];?>" alt="user-img" class="img-circle">
                     </a>
                        <ul class="dropdown-menu">                           
                        <li class="notifi-title" style="padding:0px 0px 5px ;"><span class="label label-default pull-center"><?=$_SESSION['fantasia'];?></span></li>
                     
                            <?php 
                            /* if($_SESSION['COLABORADOR_ID'] != $_SESSION['BASE_ID']): ?>
                                <li><a href="javascript:void(0)" id="_dadoscolab"><i class="md md-business text-custom m-r-10"></i> Dados</a></li>
                            <?php endif */?>                                                     
                            <li><a href="javascript:void(0)" <?php if($_SESSION['per012'] == ""){ ?>class="diss"<?php }else{
                                ?> data-toggle="modal" data-target="#custom-modal-faturaPrisma" onclick="_extratofatura(1)"   <?php
                                 } ?> ><i class="fa fa-cogs text-custom m-r-10"></i> Minha Conta</a></li>                                                            
                            <li><a href="javascript:void(0)" id="_treinamentos"><i class="fa  fa-info-circle text-custom m-r-10"></i> Central Ajuda</a></li>                                                               
                            <li><a href="https://wa.me/5541991458007" target="_blank"><i class="ti-headphone-alt text-custom m-r-10"></i> Suporte</a></li>                                                               
                            <li class="divider"></li> 
                            <li><a href="javascript:void(0)" id="_logout"><i class="ti-power-off text-danger m-r-10"></i> Sair</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="menu-item">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </div>
            </div>

        </div>
        
    </div>

    <?php if ($_SESSION["nivel"] == 1) { //1 perfil tecnico   
        $_esconder = "none";
    }
    ?>
    <div class="navbar-custom" style="display:<?=$_esconder;?> ;">
        <div class="container">
            <div id="navigation" >
                <!-- Navigation Menu-->
                <ul class="navigation-menu" >                       
                    <li class="has-submenu">   
                        <li><a href="javascript:void(0)" id="_menu"><i class=" ti-desktop text-custom m-r-10"></i> <strong>Menu</strong></a></li>
                        <li class="divider"></li>  
                        <li><a href="javascript:void(0)" id="_trackmob"><i class=" fa fa-taxi  text-success m-r-10"></i> <strong>Atend.Campo</strong></a></li>
                            <li class="divider"></li>                            
                        <li><a href="javascript:void(0)" id="_menuadmin" <?php if($_SESSION['per007'] == ""){ ?>class="diss"<?php } ?>><i class=" ti-briefcase text-custom m-r-10"></i> Adminstrativo</a></li>
                            <li><a href="javascript:void(0)" id="_menufin" <?php if($_SESSION['per016'] == ""){ ?>class="diss"<?php } ?>><i class="  ti-credit-card text-custom m-r-10"></i> Financeiro</a></li>
                        <li><a href="javascript:void(0)" id="_menuServ" <?php if($_SESSION['per008'] == ""){ ?>class="diss"<?php } ?>><i class="  ti-notepad  text-custom m-r-10"></i> Serviços</a></li> 
                        <li><a href="javascript:void(0)" id="_menuvend" <?php if($_SESSION['per014'] == ""){ ?>class="diss"<?php } ?>><i class="ti-shopping-cart-full text-custom m-r-10"> </i>Vendas</a></li>
                        <li><a href="javascript:void(0)" id="_menuestoq" <?php if($_SESSION['per009'] == ""){ ?>class="diss"<?php } ?>><i class=" ti-package text-custom m-r-10"></i> Estoque</a></li>                               
                        <li class="divider"></li>
                        <li><a href="javascript:void(0) " id="_menuconf" <?php if($_SESSION['per010'] == ""){ ?>class="diss"<?php } ?>> <i class="ti-settings text-custom m-r-10"></i> Configurações</a></li>
                    </li>                                          
                </ul>
                <!-- End navigation menu        -->
            </div>
        </div> <!-- end container -->
    </div> <!-- end navbar-custom -->
  
</header>
<!-- End Navigation Bar-->

<!-- Modal alerta -->
<div id="custom-modal-notificacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-overflow modal-lg">
        <div class="modal-content">
            <div id="extrato-modal">
                <div class="modal-header">
                    <div class="alert alert-info text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <strong>Notificações</strong>
                    </div>
                    <form name="formNoti" id="formNoti" action="javascript:void(0)" method="post">
                        <div class="form-group col-md-6">
                            <label for="data-ini-extrato">Data Inicial:</label>
                            <input type="date" name="data-ini-extrato" id="data-ini-extrato" class="form-control" value="<?php echo date('Y-m-d', strtotime("-5 days"))?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="data-fim-extrato">Data Final:</label>
                            <div class="input-group">
                                <input type="date" name="data-fim-extrato" id="data-fim-extrato" class="form-control" value="<?php echo date('Y-m-d')?>">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default waves-effect waves-light">Buscar<span class="btn-label btn-label-right"><i class="fa fa-search"></i></span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-body modal-body-overflow" id="extrato-painel">
                    <table id="datatable-responsive-extrato" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-overflow">
                            <tr>
                                <th class="text-center">Data</th>
                                <th class="text-center">Mensagem</th>
                                <th class="text-center">Solicitante</th>
                               
                              
                            </tr>
                        </thead>
                        <tbody class="tbody-overflow" id="listnotificacao">
                       
                        </tbody>
                    </table>
                </div>
            </div>
           
            <div class="modal-footer">
                    
                </div>           
         
        </div>
    </div>
</div>
<!-- Modal Extrato -->
<div id="custom-modal-faturaPrisma" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-overflow modal-lg">
        <div class="modal-content">
                 <div class="modal-header">
                    <div class="alert alert-info text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <strong>Minha Conta</strong>
                    </div>
                    <form name="formfatExtrato" id="formfatExtrato" action="javascript:void(0)" method="post">
                         <div class="form-group col-md-6">
                            <label for="data-ini-extrato">Data Inicial:</label>
                            <input type="date" name="data-ini-fatextrato" id="data-ini-fatextrato" class="form-control" value="<?php echo date('Y-m-d', strtotime("-5 days"))?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="data-fim-extrato">Data Final:</label>
                            <div class="input-group">
                                <input type="date" name="data-fim-fatextrato" id="data-fatfim-extrato" class="form-control" value="<?php echo date('Y-m-d')?>">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default waves-effect waves-light" onclick="_extratofatura(1)">Buscar<span class="btn-label btn-label-right"><i class="fa fa-search"></i></span></button>
                                </div>
                            </div>
                        </div>
                       
                    </form>
                </div>
       
                     
                  
            <div id="faturaextrato">
            </div>
        </div>
    </div>
</div>

         
                       