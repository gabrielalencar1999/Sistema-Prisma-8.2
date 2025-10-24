<?php  
	session_start();
/*	if($_SESSION['LOGADO'] == ''){ 
        require_once('../../api/view/navegacao/logout.php');
    }	
	*/	
header('Cache-Control: no cache'); //no cache
?><head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="description" content="Sistema Prisma Gestão completa para sua empresa.">

   
        <link rel="shortcut icon" href="../app/v1/assets/images/iconN.png">   
        
        <title>Prisma Gestão</title>
        <!--Form Wizard-->
        <link rel="stylesheet" type="text/css" href="../app/v1/assets/plugins/jquery.steps/css/jquery.steps.css" />

        <!-- Load div style -->
        <link href="../app/v1/assets/css/load-ajax.css" rel="stylesheet" type="text/css" />
	    <link href="../app/v1/../app/v1/assets/css/message.css" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert -->
        <link href="../app/v1/../app/v1/assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet" type="text/css">

        <!-- Custom box css -->
        <link href="../app/v1/../app/v1/assets/plugins/custombox/css/custombox.css" rel="stylesheet">

        <!--calendar css-->
        <link href="../app/v1/assets/plugins/fullcalendar/css/fullcalendar.min.css" rel="stylesheet" />

        <!-- DataTables -->
        <link href="../app/v1/assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="../app/v1/assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../app/v1/assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../app/v1/assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../app/v1/assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../app/v1/assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="../app/v1/assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../app/v1/assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>

        <!-- Select2 -->
        <link href="../app/v1/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
        <link href="../app/v1/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap table css -->
        <link href="../app/v1/assets/plugins/bootstrap-table/css/bootstrap-table.min.css" rel="stylesheet" type="text/css" />

        <link href="../app/v1/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/core.css?v=1.3" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/responsive.css" rel="stylesheet" type="text/css" />

           <!--Footable
        <link href="../app/v1/assets/plugins/footable/css/footable.core.css" rel="stylesheet">
        -->
           <!--Footable
        <link href="../app/v1/assets/plugins/footable/css/footable.paging.css" rel="stylesheet">
-->
        <link href="../app/v1/assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="../app/v1/assets/js/modernizr.min.js"></script>
        <style>
            @media only screen and (max-width: 934px) {
                #spaceDesktop{
                    display:none;
                }
                #desktop{
                    display:none;
                }
                #smartphone{

                    padding:10px;
                    text-align:center;
                }
            }
            @media only screen and (min-width: 935px) {
                #smartphone{
                    display:none;

                }
            }
            .box {
                padding:15px;
                padding-bottom:10px;
                min-width: 120px;
                height: 120px;
                cursor:pointer;
            }
            .scrolling-wrapper {
                text-align:center;
                display: flex;
                flex-wrap: nowrap;
                overflow-x: scroll;

                -webkit-box-shadow: inset 0px -22px 34px 10px rgba(255,255,255,1);
                -moz-box-shadow: inset 0px -22px 34px 10px rgba(255,255,255,1);
                box-shadow: inset 0px -22px 34px 10px rgba(255,255,255,1);
            }

            .categoria_title{
                font-size:12px;
                line-height:15px;
                color:#666;

            }
            .catCor{
                 width:30px;
                 height:30px;
                 margin:5px; float:left;
                 cursor:pointer;
                 border-radius:50%;
            }
            .styleIcon{
                margin:5px;
                color:#666;
                cursor:pointer;
                font-size:20px;
            }
            .styleIcon2{
                font-size:28px;
            }
            .boxC{
                margin-top:15px;
                border:1px solid #D9D9D9;
                border-radius:4px;
                padding:15px;
            }
            .boxD{
                margin-top:15px;
                border:1px solid #D9D9D9;
                border-radius:4px;
                padding:15px;
            }
            .nc{
                cursor:pointer;
            }

            .selCor{
                 width:40px;
                 height:40px;
            }
            .message-sucess {
                display: block;
                padding: 10px;
                border: 2px solid #555555;
                border-left-width: 32px;
                -webkit-border-radius: 6px;
                -moz-border-radius: 6px;
                border-radius: 6px;
                margin-bottom: 20px;

                font-size: 0.875em;
                font-weight: 600;

                color: #1dd1a1;
                border-color: #1dd1a1;
            }
/*prisma*/
            .mod{
                display:block;
                position:absolute;
                height: 200px;
                overflow-y: scroll;
                background: #eaf8ff; 
                top:30px;
                z-index: 15;           
                text-align:justify;
                font: 14px arial, verdana, helvetica, sans-serif;
                padding-top: 5px;
                padding-left: 10px;
                color:#000;
            }

            .test-modal .modal-dialog {
                max-width: 100%;
                margin: 0;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                height: 100vh;
                display: flex;
                position: fixed;
                z-index: 100000;
            }

            .backgroundelemento:hover 

            { background-color:#e4e6e7; 

            transition: 0.5s;

            opacity: 0.7;

            }

            @media screen and (max-width: 576px) {
                .hidden-xs{
                     display:none;
                }
                .visible-xs {
                    display:block;
                }
            }
        </style>

    </head>
	