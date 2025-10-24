<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Prisma Gestão plataforma completa para sua empresa.">

   
<link rel="shortcut icon" href="../app/v1/assets/images/iconN.png">   
    <title>Prisma O.S</title>
    <!-- Load div style -->
    <link href="../app/v1/assets/css/load-ajax.css" rel="stylesheet" type="text/css" />
    <link href="../app/v1/assets/css/message.css" rel="stylesheet" type="text/css" />
    <link href="../app/v1/assets/plugins/ladda-buttons/css/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
    <link href="../app/v1/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../app/v1/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="../app/v1/assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="../app/v1/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../app/v1/assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="../app/v1/assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="../app/v1/assets/js/modernizr.min.js"></script>
</head>
<body>
    <div class="account-pages"></div>
    <div class="clearfix"></div>
        <div class="wrapper-page">
        <div class=" card-box">
            <div class="panel-heading"> 
           <div class="text-center">  <img src="../app/v1/assets/images/logo_md.png" style="width: 40%;"> </div>
                <h4 class="text-center"> Preencha dados abaixo para consultar atendimento<strong class="text-custom"></strong> </h4>
            </div> 
            <div class="panel-body">
                <form class="form-horizontal m-t-20" id="form1" name="form1" method="post" action="">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="number" name="numberOS178" id="numberOS178" placeholder="Número da Ordem Serviço" value="<?=$_userlogin;?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="fonenumberOS178" id="fonenumberOS178" placeholder="Telefone " value="<?=$_usersenha;?>" required>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="ladda-button btn btn-default btn-block waves-effect waves-light" data-style="expand-left">
                                Consultar
                            </button>
                        </div>
                    </div>
                    <div class="form-group m-t-10 text-center">
                        <div class="col-sm-12">
                            <span class="text-dark " ><i class="fa fa-lock m-r-5"></i> Caso não tenha dados, entre em contato com Assistência Técnica
                            <input type="hidden" id="_keyform" name="_keyform"  value="cli_00001">
                        </div>
                    </div>
                </form>
            </div>
          
        </div>                            
      
    </div>

   
  

    <!-- jQuery  -->
    <script src="../app/v1/assets/js/jquery.min.js"></script>
    <script src="../app/v1/assets/js/bootstrap.min.js"></script>
    <script src="../app/v1/assets/js/detect.js"></script>
    <script src="../app/v1/assets/js/fastclick.js"></script>
    <script src="../app/v1/assets/js/jquery.slimscroll.js"></script>
    <script src="../app/v1/assets/js/jquery.blockUI.js"></script>
    <script src="../app/v1/assets/js/waves.js"></script>
    <script src="../app/v1/assets/js/wow.min.js"></script>
    <script src="../app/v1/assets/js/jquery.nicescroll.js"></script>
    <script src="../app/v1/assets/js/jquery.scrollTo.min.js"></script>
    <script src="../app/v1/assets/js/jquery.core.js"></script>
    <script src="../app/v1/assets/js/jquery.app.js"></script>

 
    <!-- Bootstrap -->
    <script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>

    <!-- ladda js -->
    <script src="assets/plugins/ladda-buttons/js/spin.min.js"></script>
    <script src="assets/plugins/ladda-buttons/js/ladda.min.js"></script>
    <script src="assets/plugins/ladda-buttons/js/ladda.jquery.min.js"></script>
    <script src="assets/js/form-signin.js"></script>  
</body>
</html>



