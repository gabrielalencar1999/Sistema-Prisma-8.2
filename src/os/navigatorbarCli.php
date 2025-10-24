<?php
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
    $logo = "iconN2.png";

}
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
                <a href="javascript:void(0)" id="_back00000" class="logo"><span><img  src="../app/v1/assets/images/<?=$logo;?>"  alt="prisma"></span></a> 
               
            </div>
            <!-- End Logo container-->
            

            <div class="menu-extras">

                <ul class="nav navbar-nav navbar-right pull-right">
                
                   
             
                    <li class="dropdown navbar-c-items">
                     
                        <ul class="dropdown-menu">                        
                                                                        
                          
                            <li class="divider"></li> 
                            <li><a href="javascript:void(0)" id="_logout"><i class="ti-power-off text-danger m-r-10"></i> Sair</a></li>
                        </ul>
                    </li>
                </ul>
            
            </div>

        </div>
        
    </div>

  
</header>


         
                       