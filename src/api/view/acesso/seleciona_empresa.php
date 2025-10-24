<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Dvet plataforma de gestão completa para sua empresa.">

   
<link rel="shortcut icon" href="assets/images/iconN.png">   
    <title>Dvet - Login</title>
    <!-- Load div style -->
    <link href="assets/css/load-ajax.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/message.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/ladda-buttons/css/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="assets/js/modernizr.min.js"></script>
</head>
<body>
    <div class="account-pages"></div>
    <div class="clearfix"></div>
        <div class="wrapper-page">
        <div class=" card-box">
            <div class="panel-heading"> 
           <div class="text-center">  <img src="assets/images/logo_md.png" style="width: 40%;"> </div>
                <h4 class="text-center"> Selecione a empresa que deseja acessar. <strong class="text-custom"></strong> </h4>
            </div> 
            <div class="panel-body">
                <form class="form-horizontal m-t-20" id="form1" name="form1" method="post" action="">
                    <div class="form-group ">
                        <div class="col-xs-12">
                           <select class="form-control" id="empresa" name="empresa" onchange="selEmpresa(this.value)">
                               <option value=""></option>
                           
                                <?php
                                        foreach($_retorno1['empresa'] as $indice => $valor){
                                            $explode = explode("|",$valor);

                                            ?>
                                                <option value="<?=base64_encode($valor);?>"><?=$explode[1];?></option>
                                            <?php
                                        } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="ladda-button btn btn-default btn-block waves-effect waves-light" data-style="expand-left" disabled>
                                Continuar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer" style="display: <?=!empty($message) ? 'block' : 'none'?>;">
                <?php if(!empty($message)): ?>
                    <p class="text-danger text-center m-t-5">
                        <?=$message?>
                    </p>
                <?php endif ?>
            </div> 
        </div>                            
        <div class="row">
            <div class="col-sm-12 text-center">
                <p>
                   <a href="https://dvet.com.br/" class="text-primary m-l-5"><b>Sair</b></a>
                </p>
            </div>
        </div>
    </div>
    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>

    <!-- Modal-Effect -->
    <script src="assets/plugins/custombox/js/custombox.min.js"></script>
    <script src="assets/plugins/custombox/js/legacy.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>

    <!-- ladda js -->
    <script src="assets/plugins/ladda-buttons/js/spin.min.js"></script>
    <script src="assets/plugins/ladda-buttons/js/ladda.min.js"></script>
    <script src="assets/plugins/ladda-buttons/js/ladda.jquery.min.js"></script>

    <script>
        function selEmpresa(valor){
            var $_keyid = "_S00002A";
            if(valor != ""){
                $.post("../page_returnSite.php", {_keyform:$_keyid, acao:4,valor:valor},function(result){});
                $(".ladda-button").removeAttr("disabled");
            }else{
                $(".ladda-button").attr("disabled","disabled");
            }
        }
    </script>
</body>
</html>



