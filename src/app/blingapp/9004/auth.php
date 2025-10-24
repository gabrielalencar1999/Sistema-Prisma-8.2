<?php
$code   = $_GET['code'];
$basic  = $client_id.':'.$client_secret;

$dados['grant_type']    = 'authorization_code';
$dados['code']          = $code;

$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/oauth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query($dados),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic '.base64_encode($basic)
    ),
    ));
    $response = curl_exec($curl);
    $resultado = json_decode($response);
       
    curl_close($curl);

  
    if($resultado->refresh_token <> ''){
        date_default_timezone_set('America/Sao_Paulo');
        $dia       = date('d');
        $mes       = date('m');
        $ano       = date('Y');
        $hora = date("H:i:s");
    
        $datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
    /*
    $query = "UPDATE token SET
            refresh_token   = '".$resultado->refresh_token."',
            access_token    = '".$resultado->access_token."'
    ";
    mysqli_query($conexao, $query);
    
    */
    $query = "UPDATE token SET      
            datahora = '$datahora',
            refresh_token   = '".$resultado->refresh_token."',
            access_token    = '".$resultado->access_token."',           
            code = '".$code."' 
            WHERE 
            refresh_token ='' AND idlogin = '9004'  ";
            ?>
            <!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">


		<link rel="shortcut icon" href="assets/images/favicon_1.ico">

		<title>Prisma - Autenticação</title>

		<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

     

	</head>
	<body>

		<div class="account-pages"></div>
		<div class="clearfix"></div>
		<div class="wrapper-page">
			<div class=" card-box">
				<div class="panel-heading">
					<h3 class="text-center"> PRISMA <strong class="text-custom">Autenticação</strong> </h3>
				</div>

				<div class="panel-body">
				

                <div class="form-group ">
							<div class="col-xs-12">
								<input class="form-control" type="text" value="REF:<?=$resultado->refresh_token;?>">
							</div>
						</div>

						<div class="form-group ">
							<div class="col-xs-12">
								<input class="form-control" type="text" value="ACC:<?=$resultado->access_token;?>" >
							</div>
						</div>
                        <div class="form-group ">
							<div class="col-xs-12">
								<input class="form-control" type="text"  value="CODE:<?=$code;?>">
							</div>
						</div>


						

				

				</div>
			</div>


		</div>


		<!-- jQuery  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/detect.js"></script>
        <script src="../assets/js/fastclick.js"></script>
        <script src="../assets/js/jquery.slimscroll.js"></script>
        <script src="../assets/js/jquery.blockUI.js"></script>
        <script src="../assets/js/waves.js"></script>
        <script src="../assets/js/wow.min.js"></script>
        <script src="../assets/js/jquery.nicescroll.js"></script>
        <script src="../assets/js/jquery.scrollTo.min.js"></script>


        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

	</body>
</html>
            
            <?php

    mysqli_query($conexao, $query);
}else{
    ?>
            
            <!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">


		<link rel="shortcut icon" href="assets/images/favicon_1.ico">

		<title>Prisma - Autenticação</title>

		<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

     

	</head>
	<body>

		<div class="account-pages"></div>
		<div class="clearfix"></div>
		<div class="wrapper-page">
			<div class=" card-box">
				<div class="panel-heading">
					<h3 class="text-center"> PRISMA <strong class="text-custom">Autenticação</strong> </h3>
				</div>

				<div class="panel-body">
				

						<div class="form-group ">
							<div class="col-xs-12">
							<h3>Ops !! algo deu errado</h3>
                            <?=print_r($resultado);?>
							</div>
						</div>

						

				

				</div>
			</div>


		</div>


		<!-- jQuery  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/detect.js"></script>
        <script src="../assets/js/fastclick.js"></script>
        <script src="../assets/js/jquery.slimscroll.js"></script>
        <script src="../assets/js/jquery.blockUI.js"></script>
        <script src="../assets/js/waves.js"></script>
        <script src="../assets/js/wow.min.js"></script>
        <script src="../assets/js/jquery.nicescroll.js"></script>
        <script src="../assets/js/jquery.scrollTo.min.js"></script>


        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

	</body>
</html>
             
    <?php

}


?>