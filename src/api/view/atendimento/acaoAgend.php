<?php
session_start();
use Database\MySQL;
//require_once('../../api/config/config.inc.php');
//require FILE_BASE_API . '/vendor/autoload.php';
use Functions\Atividade;


$pdo = MySQL::acessabd();


/*
 * BUSCA AGENDA USUARIOS.
 * */
function validar_hora($hora)
{
    return date('H:i', strtotime($hora)) == $hora;
}

if ($acao  == "" or $acao  == 0) {
    try {
       
       $_filtro = "";
       if($_parametros['search_001'] != "") { 
        $_filtro = " AND usuario_APELIDO LIKE '%".$_parametros['search_001']."%'";
        $_filtro2 = " AND ag_nome LIKE '%".$_parametros['search_001']."%'";
       }
       if($_SESSION['per220'] != "") {
            $statement2 = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".agendatab        
            WHERE  ag_ativo = '1' "); // $_filtro2
       }else{
            $statement2 = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".agendatab        
            WHERE  ag_ativo = '1' and ag_id = '1' "); // $_filtro2
       }
      
    
       $retorno2 = $statement2->fetchAll(\PDO::FETCH_OBJ);

        ?>
        <table class="table table-hover mails m-0 table table-actions-bar">
                                    <thead>
                                                                         
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                                foreach ($retorno2 as $row) {
                                                    if($row->ag_background == ""){
                                                        $usuario_background = "#00a8e6";
                                                    }else{
                                                        $usuario_background = $row->ag_background;
                                                    }
                                                    if($row->ag_avatar == ""){
                                                        $usuario_avatar = "0000.png";
                                                    }else{
                                                        $usuario_avatar = $row->ag_avatar;
                                                    }		

                                                    ?>
                                                                <tr >
                                                                    <td>
                                                                
                                                                
                                                                    <!-- <img src="assets/images/users/avatar-2.jpg" alt="contact-img" title="contact-img" class="img-circle thumb-sm" />      -->
                                                                    
                                                                    <a href="javascript:void(0)" onclick="_pDet('<?=$row->ag_id;?>')" ><img src="/app/v1/assets/images/avatar/<?=$usuario_avatar;?>" alt="user-img" class="img-circle thumb-sm" style="background-color:<?=$usuario_background;?>"></a>
                                                                    </td>

                                                                    <td>
                                                                    <a href="javascript:void(0)" onclick="_pDet('<?=$row->ag_id;?>')" ><h5> <?=$row->ag_nome;?></h5></a>
                                                                    </td>
                                                                
                                                                   
                                                                </tr>
                                                    <?php }
                                                    
                                         foreach ($retorno as $row) {
                                            if($row->usuario_background == ""){
                                                $usuario_background = "#00a8e6";
                                            }else{
                                                $usuario_background = $row->usuario_background;
                                            }
                                            if($row->usuario_avatar == ""){
                                                $usuario_avatar = "0017.png";
                                            }else{
                                                $usuario_avatar = $row->usuario_avatar;
                                            }		

                                            ?>
                                                        <tr >
                                                            <td>
                                                          
                                                           
                                                            <!-- <img src="assets/images/users/avatar-2.jpg" alt="contact-img" title="contact-img" class="img-circle thumb-sm" />      -->
                                                            
                                                                <img src="/app/assets/images/avatar/<?=$usuario_avatar;?>" alt="user-img" class="img-circle thumb-sm" style="background-color:<?=$usuario_background;?>">
                                                            </td>

                                                            <td>
                                                            <?=$row->usuario_APELIDO;?>
                                                            </td>
                                                        
                                                            <td>
                                                                <a href="javascript:void(0)" onclick="_pDet('<?=$row->usuario_CODIGOUSUARIO;;?>','')" class="table-action-btn"><i class="md  md-today"></i></a>
                                                            
                                                            </td>
                                                        </tr>
                                            <?php }
                                            
                                            
                                            ?>


                                    </tbody>
                                </table>
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}
//CARREGA PERFIL USUARIO
if ($acao  == 1) {
    try { 

        //VERIFICAR ID DO USUARIO AGENDA
  
    if($_filuser != "") {
        /*
        $statement = $pdo->query("SELECT usuario_CODIGOUSUARIO,usuario_APELIDO,usuario_background,usuario_avatar FROM ".$_SESSION['BASE'].".usuario u 
        LEFT JOIN ".$_SESSION['BASE'].".empresa_cadastro e ON u.usuario_base = e.id 
        WHERE e.base = '".$_SESSION['BASE']."' $_filuser");
        */
    }else{
       
        $statement = $pdo->query("SELECT ag_id  as usuario_CODIGOUSUARIO,
        ag_nome as usuario_APELIDO,
        ag_avatar as usuario_background,
        ag_avatar as usuario_avatar
         FROM ".$_SESSION['BASE'].".agendatab 
        
        "); // $_filuser2
      
      
    }
       
        $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
        foreach ($retorno as $row) {
            if($row->usuario_background == ""){
                $usuario_background = "#00a8e6";
            }else{
                $usuario_background = $row->usuario_background;
            }
            if($row->usuario_avatar == ""){
                $usuario_avatar = "0000.png";
            }else{
                $usuario_avatar = $row->usuario_avatar;
            }
            $_nome = 	$row->usuario_APELIDO;	
        }
        ?>
        
         <div class="card-box">
                            <div class="contact-card">
                                <a class="pull-left" href="#">
                                <img src="/app/assets/images/avatar/<?=$usuario_avatar;?>" alt="user-img" class="img-circle thumb-sm" style="background-color:<?=$usuario_background;?>">
                                </a>
                                <div class="member-info">
                                    <h4 class="m-t-0 m-b-5 header-title"><b><?=$_nome;?></b></h4>
                                    <p class="text-muted"><!--Cardiologia perfil --></p>
                                    
                                    <div class="m-t-20">
                                         <button onclick="_list()" class="btn btn-icon waves-effect waves-light btn-danger btn-sm"> <i class="fa fa-remove"></i> </button>
                                                                          
                                       <!-- <a href="#" class="btn btn-inverse waves-effect waves-light btn-sm m-l-5">Contato</a>-->
                                    </div>
                                </div>

                            </div>
             </div>   
                            <div >

                                <div class="p-10">
                                    <h4 class="m-b-10 header-title"><b><!--Atividades--></b></h4>
                                    <div  class="table-responsive" class="nicescroll p-l-r-10" style="max-height: 300px; padding-left: 15px; display:none">
                                    <div class="timeline-2">
                                        <div class="time-item">
                                            <div class="item-info">
                                                <div class="text-muted"><small>5 minutos atrás</small></div>
                                                <p><strong><span class="text-info">Finalizado</span></strong> Atendimento ao  PET <strong>"Rambo"</strong></p>
                                            </div>
                                        </div>

                                        <div class="time-item">
                                            <div class="item-info">
                                                <div class="text-muted"><small>25 minutos atrás</small></div>
                                                <p><strong><span class="text-warning">Atendimento</span></strong> Inciado ao PET <strong>"Rambo"</strong></p>
                                            </div>
                                        </div>

                                         <div class="time-item">
                                            <div class="item-info">
                                                <div class="text-muted"><small>55 minutos atrás</small></div>
                                                <p><strong><span class="text-danger">Aviso</span></strong> Estou atrasado, chego em 5 minutos</p>
                                                
                                            </div>
                                          </div>
                                           <div class="time-item">
                                                    <div class="item-info">
                                                        <div class="text-muted"><small>2 dias atrás</small></div>
                                                        <p><strong><span class="text-danger">Aviso</span></strong> Estarei ausente por 3 dias </p>
                                                        
                                                    </div>
                                                    <div class="item-info">
                                                        <div class="text-muted"><small>2 dias atrás</small></div>
                                                        <p><strong><span class="text-danger">Aviso</span></strong> Estarei ausente por 3 dias </p>
                                                        
                                                    </div>
                                            </div>
                               
                                        </div>
                                    </div>
                                 </div>
                        </div>
                  
        
        <?php

      }  catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    }



    //CARREGA MODAL AGENDA
if ($acao  == 3) {
  ?>
 <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Agenda Lista </h4>
            </div>
            <div class="modal-body">
            <input type="hidden" id="_idag" name="_idag"  value="">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                <thead>
                    <tr>                   
                        <th>Nome </th>                        
                        <th>Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                         $_SQL = "SELECT *
                         FROM ".$_SESSION['BASE'].".agendatab  WHERE    ag_ativo = '1' ";
                         $statement = $pdo->query($_SQL);
                         $retorno = $statement->fetchAll();
                   
                         if($retorno > 0){
                         foreach ($retorno as $row) {
                             ?>
                             <tr class="gradeX">
                                 <td class="text-left"><?=$row["ag_nome"]?></td>  
                                 <td class="text-center">
                                     <a href="javascript:void(0);" class="on-default " style="padding-right: 10px;" onclick="_selAG('<?=$row['ag_id']?>')"><i class="fa fa-users fa-1x"></i></a>
                                     <a href="javascript:void(0);" class="on-default " onclick="_incluirAG('<?=$row['ag_id']?>')"><i class="fa fa-pencil-square-o fa-1x"></i></a>
                                 </td>
                             </tr>
                             <?php                           
                         }
                       } 
                     
                        ?>
                    </tbody>
                </table>
                <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light"  onclick="_incluirAG()">+ Novo</button>
            </div>
            </div>
  <?php
    try { 
 ?>
 <div id="calendar"></div>
<?php
    }  catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}

    //INCLUIR e ALTERAR NOVA AGENDA
    if ($acao  == 4) {
      
        $titulo = "Cadastro";    
        if($_parametros["_idag"] != "" ) {           
            $_SQL = "SELECT *
            FROM ".$_SESSION['BASE'].".agenda  WHERE   ag_empresa = '" . $_SESSION['BASE_ID'] . "' and  ag_id = '".$_parametros["_idag"]."' ";
            $statement = $pdo->query($_SQL);
            $retorno = $statement->fetchAll();
      
            if($retorno > 0){
                $titulo = "Alteração";    
                    foreach ($retorno as $row) {
                        $_nome = $row['ag_nome'];
                    }
                }               

          }
        ?>

        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Agenda <?=$titulo;?> </h4>
        </div>
        <div class="modal-body">                         
               <div class="row">        
                       <div class="col-sm-8">            
                           <label >Nome Agenda</label>
                           <input type="text" class="form-control" name="_nomeag" id="_nomeag" value="<?=$_nome;?>">   
                           <input type="hidden" class="form-control" name="_idag" id="_idag" value="<?=$_parametros["_idag"];?>"> 
                       </div> 
           
               </div>
               <div class="row">        
                       <div class="col-sm-8"><p></p>                                       
                       </div> 
           
               </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" onclick="_AGE()">Fechar</button>
                <?php
                 if($_parametros["_idag"] != "" ) {    ?>
                <button type="button" class="btn btn-danger waves-effect" onclick="_AGE_EXCLUIR()">Excluir</button>
                <?php } ?>
                <button id="cadastrar" type="button" class="btn btn-success waves-effect waves-light mb-auto"  onclick="_gravar()">  Salvar</button>      
            </div>
        </div>

<?php
    }

 //GRAVAR NOVA AGENDA
 if ($acao  == 5) {
    try {     

        $titulo = "Cadastro";    
        if($_parametros["_nomeag"] == "" ) { 
            $_errorlog = $_errorlog."- Informe o <b>Nome Agenda</b> ! \n";
        }

        if($_errorlog == "") {  
            if($_parametros["_idag"] == "" ) { 
                    $_sql = "INSERT INTO ".$_SESSION['BASE'].".agendatab
                    (  ag_empresa,
                    ag_nome,
                    ag_ativo,
                    ag_data
                    ) VALUES(
                    ? ,
                    ? ,
                    '1',
                    CURRENT_DATE())";
                
                    $statement = $pdo->prepare("$_sql");                
                    $_cliente = $_SESSION['BASE_ID'];
                    $statement->bindParam(1, $_cliente);
                    $statement->bindParam(2, $_parametros["_nomeag"]);           
                    $statement->execute();
                    $_msg = "<p> Cadastro efetuado com <strong>Sucesso !!!</strong></p>";
            }else{
                $titulo = "Alteração";
                $_sql = "UPDATE  ".$_SESSION['BASE'].".agenda 
                SET ag_nome = ? WHERE   ag_empresa = ? AND  ag_id = ?     ";
            
                $statement = $pdo->prepare("$_sql");                
                $_cliente = $_SESSION['BASE_ID'];
                $statement->bindParam(1, $_parametros["_nomeag"]);  
                $statement->bindParam(2, $_cliente); 
                $statement->bindParam(3, $_parametros["_idag"]);            
                $statement->execute();

                $_msg = "<p> Alterado com <strong>Sucesso !!!</strong></p>";
            }
            ?>
                    <div class="modal-body">                                 
                                   
                                   <div class="col-sm-12" align="center">			
                                        <?=$_msg;?> 
                                          <p></p>
                                  </div>
                     
                                  <div class="row">
                                     <div class="col-sm-12" align="center">
                                           <img src="assets/images/small/img_0003.jpg" alt="image" class="img-responsive " width="200"/>
                                                       
                                   </div>
                                  </div> 
                                                             
                             </div> 
                                
                             <div class="modal-footer">
                                 
                             <button type="button" class="btn btn-default waves-effect" onclick="_AGE()">Fechar</button>
                             </div> 
            <?php
        }else{
         
             ?>

                    <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Agenda <?=$titulo;?> </h4>
                    </div>
                    <div class="modal-body">                         
                        <div class="row">        
                                <div class="col-sm-8">            
                                    <label >Nome Agenda</label>
                                    <input type="text" class="form-control" name="_nomeag" id="_nomeag" value="<?=$_nome;?>">   
                                     <input type="hidden" class="form-control" name="_idag" id="_idag" value="<?=$_parametros["_idag"];?>"> 
                                </div> 
                    
                        </div>
                        <div class="row">        
                          <div class="col-sm-11 ">
                          <div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
                                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                  ×
                                              </button>
                                              <span style="color:#6b1010"><?=nl2br($_errorlog) ?></span>
                                          </div>
                          </div>
                        </div>
                        <div class="row">        
                                <div class="col-sm-8"><p></p>                                       
                                </div> 
                    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" onclick="_AGE()">Fechar</button>
                        <button id="cadastrar" type="button" class="btn btn-success waves-effect waves-light mb-auto"  onclick="_gravar()">  Salvar</button>      
                        </div>
                    </div>

<?php

        }

    } catch (PDOException $e) {      
        echo $e->getMessage();
   }

  }


  
   if ($acao  == 6) {

    try {     
        if($_parametros["_idag"] != "" ) {           
            $_SQL = "SELECT *
            FROM ".$_SESSION['BASE'].".agenda  WHERE   ag_empresa = '" . $_SESSION['BASE_ID'] . "' and  ag_id = '".$_parametros["_idag"]."' ";
            $statement = $pdo->query($_SQL);
            $retorno = $statement->fetchAll();
      
            if($retorno > 0){
                        foreach ($retorno as $row) {
                        $_nomeagenda = $row['ag_nome'];
                    }
                }               

          }
    

    ?>

<div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Agenda Grupo </h4>
                    </div>
                    <div class="modal-body">                         
                        <div class="row">        
                                <div class="col-sm-8">            
                                    <label >Nome Agenda</label>
                                   <?=$_nomeagenda;?>
                                     <input type="hidden" class="form-control" name="_idag" id="_idag" value="<?=$_parametros["_idag"];?>"> 
                                </div> 
                    
                        </div>
                        <div class="row">        
                          <div class="col-sm-11 ">
                          <div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
                                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                  ×
                                              </button>
                                              <span style="color:#6b1010"><?=nl2br($_errorlog) ?></span>
                                          </div>
                          </div>
                        </div>
                        <div class="row">        
                                <div class="col-sm-8"><p></p>                                       
                                </div> 
                    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" onclick="_AGE()">Fechar</button>
                        <button id="cadastrar" type="button" class="btn btn-success waves-effect waves-light mb-auto"  onclick="_gravar()">  Salvar</button>      
                        </div>
                    </div>
    <?php
     } catch (PDOException $e) {      
        echo $e->getMessage();
   }
   
}

//gravar novo evento agenda 
if ($acao  == 7) {
      
        try { 

            //validar informações preenchida

            $_idevento = $_parametros['_ideventoedit'];    
            $titulo = $_parametros['_titulo'];    
            $descricao = $_parametros['_obs'];    
            $situacao = $_parametros['situacao'];    
            $_tipoaval  = $_parametros['_tipoaval'];
            $id_usuario = $_parametros['_userAgenda'];
            $id_agenda = $_parametros['_idAgenda'];     

           
            if( $_parametros['_colaborador'] != "") {
               $id_userAgenda = $_parametros['_colaborador'];
            }else{
               $id_userAgenda = $_parametros['_userAgenda'];
            }
            if( $_parametros['_idpet'] != "") {
               $id_pet = $_parametros['_idpet']; //id pet novo cadastro
            }else{
               $id_pet = $_parametros['_idConsumidor']; //id pet selecionado
            }
           
            //$convidado = $_parametros['convidado'];
   
         
            
            $_cliente = $_SESSION['BASE_ID'];

            //fim validações
                    if($_idevento == "") {
                        $data  = explode("/",$_parametros['dataregI']);
                        $data =  $data[2]."-".$data[1]."-".$data[0];
               
                        $inicio = $data." ".$_parametros['horainiI'];
                        $termino = $data." ".$_parametros['horafimI'];
                            //adicionar novo evento
                            $_labeltitulo = "Agendado";
                             //buscar pet
                             /*
                            $SQL = "SELECT pets_nome,pets_idcliente ,Nome_Consumidor,CODIGO_CONSUMIDOR            
                            FROM   ".$_SESSION['BASE'].".pets
                            LEFT JOIN ".$_SESSION['BASE'].".consumidor ON pets_idcliente = CODIGO_CONSUMIDOR
                            WHERE pets_id = ? ";			
                            $stm = $pdo->prepare("$SQL");           
                            $stm->bindParam(1,$id_pet, \PDO::PARAM_INT);	
                            $stm->execute();	
                        
                            if ( $stm->rowCount() > 0 ){
                                $ret =  $stm->fetchAll(\PDO::FETCH_OBJ);
                            }
                            
                                $i = 0;      
                                foreach($ret as $key=>$val){
                                        $i++ ;
                                    
                                        $id_consumidor = $val->pets_idcliente;
                                        $titulo = "(".$val->CODIGO_CONSUMIDOR.")".$val->Nome_Consumidor;
                                        $titulo =$val->pets_nome."-".$titulo;
                                }
*/
                                //Verificar se é agenda compartilhada
                                    if($_parametros['_filcol'] != "") {
                                
                                        $_filvariavel = explode(";",$_parametros['_filcol']); //0-data 1-hora maior que 2 colabaoradores
                                   
                                        //buscar sequencial para id agenda compartilhda
                                        $_sql = "SELECT  p_seqEventoCompart  FROM ".$_SESSION['BASE'].".parametro  ";                    
                                        $consulta = $pdo->query($_sql);
                                        $retparametro = $consulta->fetchAll(\PDO::FETCH_OBJ);                                            
                                        foreach ($retparametro as $row){ 
                                            $_idevcompart  =  $row->p_seqEventoCompart;
                                        }
                                      
                                        $sql = "UPDATE  ".$_SESSION['BASE'].".parametro SET p_seqEventoCompart = '".($_idevcompart+1)."' ";                                    
                                        $query = $pdo->prepare( $sql );
                                        $sth = $query->execute();
                                        //listar nomes colaboradores
                                        foreach ($_filvariavel as $_col) {    
                                            $i++;
                                            if($i > 3) {
                                                     $sql = "INSERT INTO ".$_SESSION['BASE'].".eventos(fk_id_usuario, titulo, descricao, inicio, termino, cor,id_cliente,id_agenda,id_pet,id_userAgenda,ev_status,ev_sitagenda,id_emp,ev_tipoatend,ev_compartilhado) 
                                                        values ('$id_usuario', '$titulo','$descricao', '$inicio', '$termino', '$cor','$id_consumidor','$id_agenda','$id_pet','$_col','$situacao','1','$_cliente','$_tipoaval','$_idevcompart')";
                                                        $query = $pdo->prepare( $sql );
                                                        $sth = $query->execute();

                                                        $sql = "INSERT INTO ".$_SESSION['BASE'].".eventos_compart(ec_id,ec_idusario) values ('$_idevcompart','$_col') ";
                                                        
                                                        $query = $pdo->prepare( $sql );
                                                        $sth = $query->execute();
                                      
                                            }
                                      
                                        
                                        }
                                    }else{
                                        $sql = "INSERT INTO ".$_SESSION['BASE'].".eventos(fk_id_usuario, titulo, descricao, inicio, termino, cor,id_cliente,id_agenda,id_pet,id_userAgenda,ev_status,ev_sitagenda,id_emp,ev_tipoatend) 
                                        values ('$id_usuario', '$titulo','$descricao', '$inicio', '$termino', '$cor','$id_consumidor','$id_agenda','$id_pet','$id_userAgenda','$situacao','1','$_cliente','$_tipoaval')";
                                    
                                        $query = $pdo->prepare( $sql );
                                        $sth = $query->execute();

                                        $_atividades = array(
                                            '_bd' => $_SESSION['BASE'],	
                                            'at_datahora' => date("Y-m-d H:i:s"),
                                            'at_iduser' => $_SESSION['IDUSER'],	
                                            'at_userlogin' => $_SESSION['USERAPELIDO'],	
                                            'at_tipo'=> 1,	
                                            'at_icliente'=> $id_consumidor,	
                                            'at_idpet'=> $id_pet,		
                                            'at_documento'=> '',			
                                            'at_livro'=> '',		
                                            'at_assunto'=> 'Novo Agendamento',	
                                            'at_descricao'=> $titulo	
                                         );      
                                     //  Atividade::incluir($_atividades);

                                    }
         
                
                           
                        
                        
                            
                            //Seleciona ultimo evento e incrementa a tabela 'convites' se necessario
                            $ultimoEvento = "SELECT * FROM eventos ORDER BY id_evento DESC LIMIT 1";	
                            $req = $pdo->prepare($ultimoEvento);
                            $req->execute();
                            $linhas = $req->rowCount();
                            if ($linhas == 1) {
                                while ($dados = $req->fetch(PDO::FETCH_ASSOC)) {
                                    $id_evento = $dados['id_evento'];
                                }
                            }
                        // $sql2 = "INSERT INTO convites(fk_id_destinatario, fk_id_remetente, fk_id_evento, status) values ('$convidado', '$id_usuario', '$id_evento', null)";
                        // $query2 = $pdo->prepare( $sql2 );
                        //  $query2->execute();
                    }else{
                        //alterar registro evento
                        $data  = explode("/",$_parametros['datareg']);
                        $data =  $data[2]."-".$data[1]."-".$data[0];
               
                        $inicio = $data." ".$_parametros['horaini'];
                        $termino = $data." ".$_parametros['horafim'];
                        $_labeltitulo = "Alterado Agendamento";
                        $sql = "UPDATE ".$_SESSION['BASE'].".eventos SET
                            titulo = '$titulo',
                            descricao = '$descricao',
                            inicio = '$inicio',
                            termino = '$termino',
                            id_userAgenda  = '$id_userAgenda',
                            cor = '$cor',
                            ev_tipoatend = '$_tipoaval',
                            ev_status = '$situacao'
                            WHERE id_evento = '$_idevento' AND id_emp = '$_cliente'";    
                                 
                        $query = $pdo->prepare( $sql );

                        
                        $_atividades = array(
                            '_bd' => $_SESSION['BASE'],	
                            'at_datahora' => date("Y-m-d H:i:s"),
                            'at_iduser' => $_SESSION['IDUSER'],	
                            'at_userlogin' => $_SESSION['USERAPELIDO'],	
                            'at_tipo'=> 2,	
                            'at_icliente'=> $id_consumidor,	
                            'at_idpet'=> $id_pet,		
                            'at_documento'=> '',			
                            'at_livro'=> '',		
                            'at_assunto'=> 'Alteração Agenda',	
                            'at_descricao'=> $titulo	
                         );      
                   //    Atividade::incluir($_atividades);
                    
                        if ($query == false) {
                            print_r($pdo->errorInfo());
                            die ('Erro ao carregar');
                        }
                        $sth = $query->execute();


                    }
        } catch (PDOException $e) {      
            echo $e->getMessage();
       }
   
       ?>
       
                       <div class="row">
                       <div class="col-sm-12" align="center">			
                               <p><strong><?=$_labeltitulo;?></p>
                               <p></p>
                           </div>
                           </div>
                       <div class="row">
                           <div class="col-sm-12" align="center">
                               <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive " width="200"/>
                                                   
                           </div>
                           </div>  
                           
                       <div style="padding: 17px;" align="center">                   
                           <button type="button" class="btn btn-default waves-effect"  data-dismiss="modal" onclick="_pDet('<?=$id_usuario;?>','<?=$id_agenda;?>')" >Fechar</button>
                          <!-- <button type="button" class="btn btn waves-effect"  onclick="_ImprimirAtendimento()" >Imprimir </button> -->
                       </div>                
                   
                   <?php
    
       
    } 

//validar informações evento agenda 
if ($acao  == 77 or $acao  == 78) {
 
        try {
            $_idevento = $_parametros['_ideventoedit'];    
            $titulo = $_parametros['_titulo'];    
            $descricao = $_parametros['_obs'];    
            $situacao = $_parametros['situacao'];    
            $_tipoaval  = $_parametros['_tipoaval'];
            $id_usuario = $_parametros['_userAgenda'];
            $id_agenda = $_parametros['_idAgenda']; 
            if ($acao  == 77 ) { 
                
                $inicio = $_parametros['horaini'];
                $termino = $_parametros['horafim']; 
            } else {
                
                $inicio = $_parametros['horainiI'];
                $termino = $_parametros['horafimI']; 
            }  
            

            if(strlen($titulo) <= 5 ) {  
               // $_errorlog =   $_errorlog."Titulo não pode ser inferior a 5 caracteres \n";               
            }

            $_ret = validar_hora($inicio); // true
            if( $_ret == false) { 
                $_errorlog =   $_errorlog."Verifique a hora inicial informada \n";
            }; 
            $_ret = validar_hora($termino); // true
            if( $_ret == false) { 
                $_errorlog =   $_errorlog."Verifique a hora final informada \n";
            };  
            
            //validar horario livre

          
            if($_errorlog != "" ) {  
              
                ?>
                 <div class="row">        
                    <div class="col-sm-12 ">
                    <div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                    ×
                                </button>
                                <span style="color:#6b1010"><?=nl2br($_errorlog) ?></span>
                            </div>
                    </div>
                </div> 
                <?php
                exit();
            }
           
            
        } catch (PDOException $e) {      
            echo $e->getMessage();
       }
    }

    
//validar informações pre cadastro cliente
if ($acao  == 79 ) {
    if($_parametros['_idConsumidor'] != "") { 
      exit();
    }else{
      
        
    }
    
    try {
      
        $_nome = $_parametros['_nome'];    
        $_fone = $_parametros['_fone'];    
        $_pet = $_parametros['_pet'];    
        $_especie = $_parametros['_especie'];    
        $_raca  = $_parametros['_raca'];
        

        if(strlen($_nome) == "" ) {  
            $_errorlog =   $_errorlog."Preencha campo Nome \n";               
        }
        if(strlen($_fone) == "" ) {  
            $_errorlog =   $_errorlog."Preencha campo Telefone \n";               
        }
        if(strlen($_pet) == "" ) {  
            $_errorlog =   $_errorlog."Preencha  nome do PET \n";               
        }     
        if($_especie == "0" ) {  
            $_errorlog =   $_errorlog."Selecione a Espécie\n";               
        }
        

       

      
        if($_errorlog != "" ) {  
          
            ?>
             <div class="row">        
                <div class="col-sm-12 ">
                <div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                ×
                            </button>
                            <span style="color:#6b1010"><?=nl2br($_errorlog) ?></span>
                        </div>
                </div>
            </div> 
            <?php
            exit();
        }
       
        
    } catch (PDOException $e) {      
        echo $e->getMessage();
   }
}


//atualizar modal  - pesquisar agenda
if ($acao  == 8) {
 //   print_r($_parametros);    
    
        try { 
       //30-12-2021 09:00:00
         //verificar se venho da agenda compartilhada
         if($_parametros['_filcol'] != "") {
           
            $_filvariavel = explode(";",$_parametros['_filcol']); //0-data 1-hora maior que 2 colabaoradores
           
            $dtinicio = substr($_filvariavel[0],0,10);  
            $horainicio = $_filvariavel[1];  
            $_dt = explode("/",$dtinicio);
            $dtinicio =  $_dt[2]."-".$_dt[1]."-".$_dt[0]." ".$horainicio;
           
           // $now = new DateTime($dtinicio." ".$horainicio); // data/hora atual
           $now = new DateTime($dtinicio); // data/hora atual
      
            $now->add(new DateInterval('PT1H')); // somar 1 hora
            $horatermino = $now->format('H:i');
            $dtinicio = substr($_filvariavel[0],0,10); 
            //listar nomes colaboradores
            foreach ($_filvariavel as $_col) {    
                $i++;
                if($i > 2 ) {
                    //buscar nome colaborador          
                 
                    $_sql = "SELECT usuario_CODIGOUSUARIO,usuario_APELIDO,usuario_background,usuario_avatar 
                    FROM ".$_SESSION['BASE'].".usuario u    
                    INNER JOIN ".$_SESSION['BASE'].".colaborador e ON u.usuario_CODIGOUSUARIO = e.colaborador_usuario                                                              
                    WHERE usuario_ATIVO = '-1' and e.colaborador_empresa = '".$_SESSION['BASE_ID']."' AND  u.usuario_CODIGOUSUARIO = '". $_col."'";                    
                    $consulta = $pdo->query($_sql);                   
                    
                    $retornoUsuario = $consulta->fetchAll(\PDO::FETCH_OBJ);
                        
                    foreach ($retornoUsuario as $row){ 
                            $_colaboradorname = $_colaboradorname." ".$row->usuario_APELIDO."/";
                    }
                }
               // echo "if($i > 3 AND $_agenda_ocupada == 0) {  <br> ";
               
            }

         }else{
            $dtinicio = substr($_parametros['inicio'],0,10);       
            $horainicio = substr($_parametros['inicio'],11,5);
            $horatermino = substr($_parametros['termino'],11,5);
         }

           
          
           
          if($_parametros['_userAgenda'] != "") { //BUSCAR NOME USUARIO DA AGENDA
               $_SQL = "SELECT usuario_APELIDO as nome
               FROM  ".$_SESSION['BASE'].".usuario u 
               INNER JOIN ".$_SESSION['BASE'].".colaborador e ON u.usuario_CODIGOUSUARIO = e.colaborador_usuario 
               WHERE usuario_ATIVO = '-1' and  e.colaborador_empresa = '".$_SESSION['BASE_ID']."'
                and  usuario_CODIGOUSUARIO = '".$_parametros["_userAgenda"]."' ";
               $statement = $pdo->query($_SQL);
               $retorno = $statement->fetchAll();
          }else{
               //BUSCAR NOME DA AGENDA
               $_SQL = "SELECT ag_nome as nome
               FROM ".$_SESSION['BASE'].".agenda  WHERE   ag_empresa = '" . $_SESSION['BASE_ID'] . "' and  ag_id = '".$_parametros["_idAgenda"]."' ";
               $statement = $pdo->query($_SQL);
               $retorno = $statement->fetchAll();
          }
          
     
           if($retorno > 0){
               
                   foreach ($retorno as $row) {
                       $_nomeAGENDA = $row['nome'];                  
                   }
               } 
            ?>
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Agenda:<strong><?=$_nomeAGENDA;?></strong></h4>
                        <?php if($_colaboradorname != "") { ?>
                            <h5 class="modal-title">Colaboradores:(<?=$_colaboradorname;?>) </h5>
                         <?php } ?>   
                   <h5 class="modal-title" id="myModalLabel">Data/Hora: <?=$dtinicio;?> das <?=$horainicio;?>  até <?=$horatermino;?> </h5>
                 </div>
   
                 <?php if ($_parametros['id_cliente'] == "") {  //buscar cadastro?>
                   <div  id="_resultAG">
                                       <div class="row" style="padding: 5px;">   
                                              
                                               <div class="col-sm-9" >                                        
                                                   <div class="input-group">                                      
                                                     
                                                       <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                       </span>
                                                       <input type="text" id="_desc" name="_desc" class="form-control" onchange="_clisearch(this.value,1)"   placeholder="Nome, Telefone, Email">
                                                   </div>
                                               </div>
                                                   <div class="col-sm-3" >  
                                                   <div class="input-group">  
                                                       <span class="input-group-addon"><i class="fa fa-paw"></i></span>
                                                       <input type="text" id="_descPET" name="_descPET" onchange="_clisearchPet(this.value,2)" class="form-control" placeholder="Pet"> 
                                                   </div>
                                                   </div>
                                              
                                       </div>
                                       
                                       <div class="col-lg-12"  id="_resultcli">
                                                
                                                <table class="table table table-hover m-0">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Nome</th>                                                         
                                                            <th>Telefone</th>
                                                            <th>Email</th>
                                                            <th>PET</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5">
                                                                <div class="text-center">
                                                                Para localizar informe no campo pesquisa acima
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
          
                                       
   
                                        </div>    
   
                                       <div class="modal-footer">
                                           <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                           <button type="button" class="btn btn-success waves-effect waves-lightm-l-5" onclick="_cliInclui()" > <i class="fa fa-plus" ></i> Incluir</button>
                                       </div>
                   </div>
   
                 <?php
                 }else{
   
                 
                 ?>
                 <div class="modal-body">
                   <input type="text" name="titulo" class="form-control" id="titulo"  >
                     <div class="form-group">
                       <label for="titulo" class="col-sm-2 control-label">Cliente</label>
                       <div class="col-sm-10">
                            <input type="text"  name="titulo" class="form-control" id="titulo"  value = "ROBSON - RAMBO" readonly >
                       </div>
                     </div>
   
                     <div class="form-group">
                       <label for="descricao" class="col-sm-2 control-label">Descrição</label>
                       <div class="col-sm-10">
                         <textarea type="text" name="descricao" class="form-control" id="descricao" placeholder="Descrição"></textarea>
                       </div>
                     </div>
                     
                     <div class="form-group">
                       <label for="cor" class="col-sm-2 control-label">Situação</label>
                       <div class="col-sm-10">
                         <select name="situacao" class="form-control" id="situacao">                          
                         
                         <?php
                               $_SQL = "SELECT *
                               FROM ".$_SESSION['BASE'].".situacao_agenda  where sit_visualiza = 0 ";
                               $statement = $pdo->query($_SQL);
                               $retorno = $statement->fetchAll();                     
                     
                           if($retorno > 0){
                               
                                   foreach ($retorno as $row) {
                                       ?>  
                                       < <option style="color:<?=$row['sit_cor'];?>" value="<?=$row['sit_agendaID'];?>"> <?=$row['sit_agendaDescricao'];?></option>                            
                                       <?php
                                   }
                               }
                               ?>
                             
                           
                           </select>
                       </div>
                     </div>
   
                     <div class="form-group">
                       <label for="convidado" class="col-sm-2 control-label">Convidar</label>
                       <div class="col-sm-10">
                         <select name="convidado" class="form-control" id="convidado">
                         <option value="">Ninguém</option>
                         <?php
                         /*
                               $sql2 = "SELECT * FROM usuarios WHERE id_usuario!=$id_user";
                               $req = $db->prepare($sql2);
                               $req->execute();
                               $linhas = $req->rowCount();
                               while ($dados = $req->fetch(PDO::FETCH_ASSOC)) {
                                   $id_usuario = $dados['id_usuario'];
                                   $nome_usuario = $dados['nome'];
                                   echo " <option value=\"$id_usuario\">$nome_usuario</option>";
                               }
                               */
                           ?>				  
                           </select>
                       </div>
                     </div>
   
   
                     <div class="form-group">
                       <label for="inicio" class="col-sm-2 control-label">Inicio</label>
                       <div class="col-sm-10">
                         <input type="text" name="inicio" class="form-control" id="inicio" required>
                       </div>
                     </div>
                     <div class="form-group">
                       <label for="termino" class="col-sm-2 control-label">Termino</label>
                       <div class="col-sm-10">
                         <input type="text" name="termino" class="form-control" id="termino" required>
                       </div>
                     </div>
                   
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                   <button type="button" onclick="_evadd()" class="btn btn-success">Adicionar</button>
                 </div>
   
                 <?php } ?>
   
               
           </div>
            <?php
   
       } catch (PDOException $e) {      
           echo $e->getMessage();
      }
   
      
   } 

//EXCLUIR
if ($acao  == 9) {
    $_cliente = $_SESSION['BASE_ID'];
 
     try { 
        $_sql = "UPDATE  ".$_SESSION['BASE'].".agenda 
                SET ag_ativo = '0' WHERE   ag_empresa = ? AND  ag_id = ?     ";            
                $statement = $pdo->prepare("$_sql");
                $statement->bindParam(1, $_cliente); 
                $statement->bindParam(2, $_parametros["_idag"]);                                              
                $statement->execute();

                $_msg = "<p> Excluído com <strong>Sucesso !!!</strong></p>";
          
            ?>
                    <div class="modal-body">
                         <div class="col-sm-12" align="center">	<?=$_msg;?> <p></p>
                                  </div>                     
                                  <div class="row">
                                     <div class="col-sm-12" align="center">
                                           <img src="assets/images/small/img_0003.jpg" alt="image" class="img-responsive " width="200"/>                                                       
                                   </div>
                                  </div>                                                              
                             </div>                                 
                             <div class="modal-footer">                                 
                                 <button type="button" class="btn btn-default waves-effect" onclick="_AGE()">Fechar</button>
                             </div> 
            <?php
       
    } catch (PDOException $e) {      
        echo $e->getMessage();
   }

   
}

//COLABORADOR DA LISTA
if ($acao == '10') {
    
    $_cliente = $_SESSION['BASE_ID'];   
 
     try { 
         
              //verificar se é exclusao
            if($_parametros["_idagF"] == "e"){  
                $_varid = explode("|",$_parametros["_idag"]) ;
                $_parametros["_idag"] = $_varid[0];   
               
                $_sql = "DELETE FROM  ".$_SESSION['BASE'].".agenda_user WHERE agusr_empresa = ? AND agusr_id = ?  ";            
                 $statement = $pdo->prepare("$_sql");
                 $statement->bindParam(1,  $_SESSION['BASE_ID']); 
                 $statement->bindParam(2, $_varid[1]); 
                                                           
                 $statement->execute();
                }
         
        if($_parametros["_idag"] != "" ) {           
            $_SQL = "SELECT *
            FROM ".$_SESSION['BASE'].".agenda  WHERE   ag_empresa = '" . $_SESSION['BASE_ID'] . "' and  ag_id = '".$_parametros["_idag"]."' ";
            $statement = $pdo->query($_SQL);
            $retorno = $statement->fetchAll();
      
            if($retorno > 0){
                
                    foreach ($retorno as $row) {
                        $_nome = $row['ag_nome'];
                        $titulo = "$_nome";   
                    }
                } 
                
                
          //verificar se é inclusao
          if($_parametros["_idagF"] == "i"){         
            $_sql = "INSERT INTO  ".$_SESSION['BASE'].".agenda_user (agusr_empresa,agusr_idagenda,agusr_usuario) VALUES 
            (?,?,?)";            
                $statement = $pdo->prepare("$_sql");
                $statement->bindParam(1,  $_SESSION['BASE_ID']); 
                $statement->bindParam(2, $_parametros["_idag"]);  
                $statement->bindParam(3, $_parametros["_colaborador"]);                                                   
                $statement->execute();
            }

        

          }
        ?>

        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Agenda <?=$titulo;?> </h4>
        </div>
        <div class="modal-body">                         
             
               <div class="row">        
                       <div class="col-sm-6">                      
                           <input type="hidden" class="form-control" name="_idag" id="_idag" value="<?=$_parametros["_idag"];?>">     
                           <input type="hidden" class="form-control" name="_idagF" id="_idagF" value=""> 
                             <label for="servico-colaborador">Colaborador:</label>
                                    <?php 
                                    $_sql = "SELECT usuario_CODIGOUSUARIO, usuario_APELIDO 
                                    FROM ".$_SESSION['BASE'].".usuario 
                                    where usuario_CODIGOUSUARIO not in 
                                    (SELECT agusr_usuario from agenda_user 
                                    WHERE  agusr_empresa = '".$_SESSION['BASE_ID']."' AND agusr_idagenda = '".$_parametros["_idag"]."'
                                    )
                                    ORDER BY usuario_APELIDO";                                 
                                        $consulta = $pdo->query($_sql);
                                        
                                     
                                        $retornoUsuario = $consulta->fetchAll(\PDO::FETCH_OBJ);
                                        
                                    ?>
                                    <select class="form-control" name="_colaborador" id="_colaborador">
                                        <option value="0">Selecione</option>
                                    <?php foreach ($retornoUsuario as $row): ?>
                                        <option value="<?=$row->usuario_CODIGOUSUARIO?>"><?=$row->usuario_APELIDO?></option>
                                    <?php endforeach ?>
                                    </select>
                                   
                       </div>
                       <div class="col-sm-2">     
                       <button type="button"  style="margin-top: 25px;" class="btn btn-success waves-effect waves-light"  onclick="_incluirColaborador('i')">Incluir<span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button>
                       </div>
               </div>
               <div class="row">        
                       <div class="col-sm-8" style="padding-top: 5px;"> 
                           <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                <thead>
                                    <tr>                   
                                        <th>Nome </th>                        
                                        <th>Ação </th> 
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $_SQL = "SELECT *
                                        FROM ".$_SESSION['BASE'].".agenda_user  
                                        inner join  ".$_SESSION['BASE'].".usuario ON agusr_usuario = usuario_CODIGOUSUARIO
                                        WHERE   agusr_empresa = '" . $_SESSION['BASE_ID'] . "' 
                                        AND  agusr_idagenda = '".$_parametros["_idag"]."' ";
                                        $statement = $pdo->query($_SQL);
                                        $retorno = $statement->fetchAll();
                                
                                        if($retorno > 0){
                                        foreach ($retorno as $row) {
                                            $_varid = $_parametros["_idag"]."|".$row['agusr_id'];
                                            ?>
                                            <tr class="gradeX">
                                                <td class="text-left"><?=$row["usuario_APELIDO"]?></td>                              
                                            
                                            <td class="text-center">                                               
                                                <a href="javascript:void(0);" class="on-default " onclick="_ExCol('<?=$_varid;?>','e')"><i class="fa fa-trash fa-1x"></i></a>
                                            </td>
                                            </tr>
                                            <?php                           
                                        }
                                    } 
                                    
                                        ?>
                                    </tbody>
                </table>                                      
                       </div> 
           
               </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" onclick="_AGE()">Fechar</button>
              
            </div>
        </div>

<?php
       
    } catch (PDOException $e) {      
        echo $e->getMessage();
   }

   
}
   //localizar cliente 
    if ($acao == '11') {
    
             $_cliente = $_SESSION['BASE_ID'];              
        
            try { 
             
                $SQL = "SELECT fone_idcliente,Nome_Consumidor,CODIGO_CONSUMIDOR              
                FROM   ".$_SESSION['BASE'].".consumidor	 
                LEFT JOIN ".$_SESSION['BASE'].".telemail ON fone_idcliente = CODIGO_CONSUMIDOR
                WHERE Nome_Consumidor like '%".$_parametros['_desc']."%' OR
                fone_telefone like '%".$_parametros['_desc']."%'  group by fone_idcliente,Nome_Consumidor ";			
                $stm = $pdo->prepare("$SQL");                  
       
                //$stm->bindParam(1,$id_buscar, \PDO::PARAM_INT);	
                $stm->execute();	
             
                if ( $stm->rowCount() > 0 ){
                    $ret =  $stm->fetchAll(\PDO::FETCH_OBJ);
                }
                  ?>                                      
                  <table class="table table-hover mails m-0 table table-actions-bar">
                                    <thead>
                                        <tr>                                          
                                            <th style="width: 120px;">Nome</th>                                          
                                            <th><span style="margin-right: 120px;">Telefone/Email </span>PET</th>
                                           
                                        </tr>
                                    </thead>

                                    <tbody>

                    <tbody> <?php   
                    $i = 0;      
                    foreach($ret as $key=>$val){
                            $i++ ;
                        ?> 
                        <tr>                      
                            <td><?=$val->Nome_Consumidor;?></td> 
                            <td>
                        <?php
                            $_idcliente = $val->CODIGO_CONSUMIDOR;
                            //BUSCAR TODOS PET E FONE/EMAIL
                            $SQL = "SELECT *             
                            FROM   ".$_SESSION['BASE'].".consumidor	 
                            LEFT JOIN ".$_SESSION['BASE'].".telemail ON fone_idcliente = CODIGO_CONSUMIDOR
                            WHERE CODIGO_CONSUMIDOR = '$_idcliente'  ";			
                            $stm2 = $pdo->prepare("$SQL");                   
                  
                            //$stm->bindParam(1,$id_buscar, \PDO::PARAM_INT);	
                            $stm2->execute();	
                         
                            if ( $stm2->rowCount() > 0 ){
                                $response =  $stm2->fetchAll(\PDO::FETCH_OBJ);
                         

                                ?>
                                
                                <div class="row" >                                           
                                          <div class="col-sm-8" >  
                                              <?php
                         
                            foreach($response as $key=>$value){
                                if($row->usuario_avatar == ""){
                                    $usuario_avatar = "0048.png";
                                }else{
                                    $usuario_avatar = $row->usuario_avatar;
                                }
                               
                                                    if($value->fone_tipo == 3) {
                                                           echo $value->fone_telefone."<br>";                
                                                      }else { 
                                                          echo $value->fone_ddd;?>-<?=$value->fone_telefone."<br>";
                                                            } 
                                          
                             }
                            }?></div>

                                <?php
                                    $SQL = "SELECT *             
                                    FROM   ".$_SESSION['BASE'].".pets  
                                    LEFT JOIN  ".$_SESSION['BASE'].".especie ON   especie_id    =    pets_especie                                                     
                                    WHERE pets_idcliente = '$_idcliente' and pets_obito <> '1' ";	
                                   
                                    $stm2 = $pdo->prepare("$SQL");                  
                                    $stm2->execute();	
                                    if ( $stm2->rowCount() > 0 ){
                                        $response =  $stm2->fetchAll(\PDO::FETCH_OBJ);
                                        foreach($response as $key=>$value){
                                            if($value->pets_background != ""){
                                                $pets_background = $value->pets_background;
                                                
                                            }else{
                                                $pets_background = "#EEE";
                                            }
    
    
                                            if($value->pets_img != "" ){
    
                                                $avatar = $value->pets_img;
                                                if($value->pets_background == ""){
                                                    $caminho = "";
                                                }else{
                                                    $caminho = "/app/assets/images/avatarPets/";
                                                }
    
                                            }else{
                                                $avatar = $value->especie_avatar;
                                                $caminho = "/app/assets/images/avatarPets/";
                                            }
                               ?>
                                     <div class="col-sm-2" > 
                                            <div class="chat-avatar"  style="cursor: pointer;" onclick="_evadd('<?=$value->pets_id;?>')">
                                            <img src="<?=$caminho .$avatar;?>" alt="user-img" class="img-circle thumb-sm" style="background-color:<?=$pets_background;?>">
                                            
                                            </div>
                                      </div>
                                      <?php }
                                      } ?>
                                      
                              </div>
                            <?php
                               }?>
                        </td>
                    </tr>       
                    <?php
                      
                 


                    if($i == 0){//
                    ?>
                    </tbody>

                    </table>
                    
                    <?php
                    
                    }else{
                    ?>

                    </tbody>
                    </table>

                    <?php
                    } 
                        
            } catch (PDOException $e) {      
                echo $e->getMessage();
            }
        }
        //incluir Novo Cliente  

if ($acao == '12') {    
        $_cliente = $_SESSION['BASE_ID'];        
          try { 
           ?>
      
              
                <div class="row">
                    <div class="col-xs-6">
                        <label class="control-label">Nome Cliente</label>    
                            <input type="text" class="form-control" name="_nome" id="_nome" >
                    </div>
                    <div class="col-xs-6">
                        <label class="control-label">Telefone</label>    
                            <input type="text" class="form-control" name="_fone" id="_fone" maxlength="14" onKeyUp="mascaraTexto(event,'3')">
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-xs-12">
                        <label class="control-label">PET</label>
                        <input type="text" class="form-control" name="_pet" id="_pet">
                    </div>
                </div>

                <div class="row" style="padding: 5px;">
                    <div class="col-xs-6">
                        <div class="input-group">
                            <label class="control-label">Espécie</label>
                            <?php
                                            $statement = $pdo->query("SELECT *  FROM  ".$_SESSION['BASE'].".especie  order by especie_nome");
                                            $retorno = $statement->fetchAll();
                                            ?>
                            <select name="_especie" id="_especie" class="form-control"
                                onchange="_buscaraca(this.value)">
                                <option value="0">Selecione</option>
                                <?php
                                                foreach ($retorno as $row) {
                                                    ?>
                                <option value="<?=$row["especie_id"]?>"><?=$row["especie_nome"];?></option>
                                <?php
                                                }
                                                ?>
                            </select>
                        </div>
                    </div>

                 <div class="col-xs-6" >                                        
                   <div class="input-group">                                      
                   <label >Raça</label>
                                     <span id="_busca_raca">
                                            <select name="_raca" id="_raca" class="form-control" >
                                                <option value="">Selecione Especie</option>                                              
                                            </select> 
                                     </span>
                    </div>
                 </div>

                 <div class="row">
                    <div class="col-xs-12" id=>
                         <div id="returnAG_aviso">
						
                        </div>	
                    </div>
                </div>
                </div>

              
                <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				<button type="button" onclick="_evadd('')" class="btn btn-success">Salvar</button>
			  </div>
           <?php
        } catch (PDOException $e) {      
            echo $e->getMessage();
        }
    }


  //adicionar evento - nova agenda
  if ($acao == '13') {
 //print_r($_parametros);
    try {  
           $_cliente = $_SESSION['BASE_ID'];  
           $_userAgenda = $_parametros['_userAgenda'];
           $_idAgenda = $_parametros['_idAgenda'];
           $id = $_parametros['_idConsumidor']; //id do pet selecionado           
           $dtinicio = substr($_parametros['inicio'],0,10);       
           $horainicio = substr($_parametros['inicio'],11,5);
           $horatermino = substr($_parametros['termino'],11,5);
            //buscar nome da agenda
            if($_idAgenda > 0) {
                $sql = "SELECT ag_id  as usuario_CODIGOUSUARIO,
                ag_nome as usuario_APELIDO,
                ag_avatar as usuario_background,
                ag_avatar as usuario_avatar
                 FROM ".$_SESSION['BASE'].".agenda u 
                LEFT JOIN ".$_SESSION['BASE'].".empresa_cadastro e ON u.ag_empresa = e.id 
                WHERE e.base = '".$_SESSION['BASE']."' and ag_id = '$_idAgenda'";
                 $statement = $pdo->query("$sql");   
            }else{
                     
                 
                 $statement = $pdo->query("SELECT usuario_CODIGOUSUARIO,usuario_APELIDO,usuario_background,usuario_avatar FROM ".$_SESSION['BASE'].".usuario u 
                 LEFT JOIN ".$_SESSION['BASE'].".empresa_cadastro e ON u.usuario_base = e.id 
                 WHERE e.base = '".$_SESSION['BASE']."'AND  usuario_CODIGOUSUARIO = '".$_userAgenda."'");
                   
              
            }
            $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
            foreach ($retorno as $row) {
              
                $nomeAgenda = 	$row->usuario_APELIDO;	
            }

            //Verificar se é agenda compartilhada
            if($_parametros['_filcol'] != "") {
           
                $_filvariavel = explode(";",$_parametros['_filcol']); //0-data 1-hora maior que 2 colabaoradores
                $dtinicio = substr($_filvariavel[0],0,10);       
                $horainicio = $_filvariavel[1];
                $_dt = explode("/",$dtinicio);
                $dtinicio =  $_dt[2]."-".$_dt[1]."-".$_dt[0]." ".$horainicio;
               
               // $now = new DateTime($dtinicio." ".$horainicio); // data/hora atual
               $now = new DateTime($dtinicio); // data/hora atual
          
                $now->add(new DateInterval('PT1H')); // somar 1 hora
                $horatermino = $now->format('H:i');
                $dtinicio = substr($_filvariavel[0],0,10); 
                //listar nomes colaboradores
                foreach ($_filvariavel as $_col) {    
                    $i++;
                    if($i > 2 ) {
                        //buscar nome colaborador          
                     
                        $_sql = "SELECT usuario_CODIGOUSUARIO,usuario_APELIDO,usuario_background,usuario_avatar 
                        FROM ".$_SESSION['BASE'].".usuario u                                                                            
                        WHERE u.usuario_empresa = '".$_SESSION['BASE_ID']."' AND  u.usuario_CODIGOUSUARIO = '". $_col."'";                    
                        $consulta = $pdo->query($_sql);                   
                        
                        $retornoUsuario = $consulta->fetchAll(\PDO::FETCH_OBJ);
                            
                        foreach ($retornoUsuario as $row){ 
                                $_colaboradorname = $_colaboradorname." ".$row->usuario_APELIDO."/";
                        }
                    }
                   // echo "if($i > 3 AND $_agenda_ocupada == 0) {  <br> ";
                   
                }
            }
         

           //verificar se cliente  já cadastrado

           if($_parametros['_idConsumidor'] == "") { 
               $_nome = $_parametros['_nome'];
               $_nomepet = $_parametros['_pet'];
               $_telefone = $_parametros['_fone'];
           
               $_telefone = str_replace("-","",$_telefone);
           
               $_fone = explode(")",$_telefone);
               $dd = substr($_telefone,1,2);

             
               $_especie = $_parametros['_especie'];
               $_raca= $_parametros['_raca'];

               //grava consumidor
               $_sql = "INSERT INTO  ".$_SESSION['BASE'].".consumidor 
               (Nome_Consumidor,DDD_CELULAR,FONE_CELULAR,Data_Cadastro) VALUES 
               (?,?,?,CURRENT_DATE())";            
                   $statement = $pdo->prepare("$_sql");
                   $statement->bindParam(1, $_nome); 
                   $statement->bindParam(2, $dd);  
                   $statement->bindParam(3, $_fone['1']);                                                   
                   $statement->execute();
                   $idconsumidor = $pdo->lastInsertId();

                //grava telefone
                $_sql = "INSERT INTO  ".$_SESSION['BASE'].".telemail 
                (fone_idcliente	,fone_tipo,fone_telefone,fone_ddd) VALUES 
                (?,1,?,?)";            
                    $statement = $pdo->prepare("$_sql");
                    $statement->bindParam(1, $idconsumidor); 
                    $statement->bindParam(2, $_fone['1']);                    
                    $statement->bindParam(3, $dd);                                                 
                    $statement->execute();
                    


           }

         
                $SQL = "SELECT *             
                FROM   ".$_SESSION['BASE'].".pets  
                LEFT JOIN  ".$_SESSION['BASE'].".especie ON   especie_id    =    pets_especie                                                     
                WHERE pets_id = '$id'  ";	
              
                $stm2 = $pdo->prepare("$SQL");                  
                $stm2->execute();	
                if ( $stm2->rowCount() > 0 ){
                    $response =  $stm2->fetchAll(\PDO::FETCH_OBJ);
                    foreach($response as $key=>$value){
                        if($value->pets_background != ""){
                            $pets_background = $value->pets_background;
                            
                        }else{
                            $pets_background = "#EEE";
                        }


                        if($value->pets_img != "" ){

                            $avatar = $value->pets_img;
                            if($value->pets_background == ""){
                                $caminho = "";
                            }else{
                                $caminho = "/app/assets/images/avatarPets/";
                            }

                        }else{
                            $avatar = $value->especie_avatar;
                            $caminho = "/app/assets/images/avatarPets/";
                        }
                    }
                }

                //busca contatos
                $sql3="SELECT *             
                FROM   ".$_SESSION['BASE'].".telemail                                                   
                WHERE fone_idcliente = '$id_tutor'";
                $stm3 = $pdo->prepare($sql3);                  
                $stm3->execute();
                foreach($stm3->fetchAll(\PDO::FETCH_OBJ) as $value3){
                    if($value3->fone_ddd != 0){
                        $ddd = $value3->fone_ddd;
                    }else{
                        $ddd = "";
                    }
                    $contato = $contato.$ddd." ".$value3->fone_telefone." |";
                }
       
          
            ?>
            <input type="hidden" id="_idpet" name="_idpet"  value="<?=$id;?>">
            <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agendamento</strong></h4>                
			  </div>
            
              <div class="card-box">
                            <div class="contact-card">  
                            <div class="row">
                              <div class="col-xs-2">                            
                                <img src="<?=$caminho.$avatar;?>" alt="user-img" class="img-circle thumb-sm" style="background-color:<?=$pets_background;?>">
                                </div>
                                <div class="col-xs-7">  
                                    <h4 class="m-t-0 m-b-5 header-title"><b><?=$_nomepet;?></b></h4>
                                    <p class="text-muted"><?=$_nome;?> <br><?=$contato;?></p> 
                                    
                                </div>  
                                <div class="col-xs-3">     
                                    <label class="control-label">Agenda</label> 
                                      <h4 class="m-t-0 m-b-5 header-title"><b><?=$nomeAgenda;?></b></h4>
                                </div>    
                            </div>
                             
                            </div>
                           
             </div>
             <div class="card-box">
                 <?php
                    if($_colaboradorname != "") { ?>
                    
                    <div class="row">
                        <div class="col-xs-12" style="text-align: right;">                     
                                <i class="fa fa-share-alt-square text-warning"></i>
                                <label class="control-label text-warning">Agenda compartilhada</label> 
                                <p>  <?=$_colaboradorname ;?></p>

                      
                                                             
                       </div>

                     <?php } ?>
                   <div class="row">
                        <?php if($_idAgenda != "" and $_colaboradorname == "") { ?>
                    
                        <div class="col-xs-5">
                            <label class="control-label">Colaborador</label> 
                                        <?php 
                                        $_sql = "SELECT usuario_CODIGOUSUARIO, usuario_APELIDO 
                                        FROM ".$_SESSION['BASE'].".usuario 
                                        INNER JOIN agenda_user ON usuario_CODIGOUSUARIO = agusr_usuario                                   
                                        WHERE  agusr_empresa = '".$_SESSION['BASE_ID']."' AND agusr_idagenda = '".$_idAgenda."'                                   
                                        ORDER BY usuario_APELIDO";  
                                                                
                                            $consulta = $pdo->query($_sql);                                        
                                        
                                            $retornoUsuario = $consulta->fetchAll(\PDO::FETCH_OBJ);
                                                                                    ?>
                                        <select class="form-control" name="_colaborador" id="_colaborador">
                                            <option value="0">Selecione Colaborador</option>
                                              <?php foreach ($retornoUsuario as $row): ?>
                                                  <option value="<?=$row->usuario_CODIGOUSUARIO?>"><?=$row->usuario_APELIDO?></option>
                                             <?php endforeach ?>
                                        </select>
                        </div>      
                
                     <?php } ?>
                            <div class="col-xs-7">
                                    <label class="control-label">Tipo Avaliação</label> 
                                    <Select  class="form-control" id="_tipoaval"  name="_tipoaval" >   
                                    <option value="0">selecione</option>                                                                                                       
                                       <?php                                                             
                                           $consulta = $pdo->query("SELECT *
                                                       FROM ".$_SESSION['BASE'].".tipo_avaliacao
                                                       WHERE tipaval_ativo = '0'
                                                       ORDER BY tipaval_descricao");
                                           $result = $consulta->fetchAll();
                                           
                                           foreach ($result as $row) {
                                                   ?><option value="<?=$row["tipaval_id"];?>" <?=$row["tipaval_id"] == $_tipoaval ? "selected" : ""?>><?=$row["tipaval_descricao"];?></option><?php
                                               }                                                                  
                                       ?>  
                                   </Select>
                            </div>
                   </div>
                   
                <div class="row">
                    <div class="col-xs-3">
                        <div class="input-group">  
                        <label class="control-label">Data</label> 
                           <input type="text" id="dataregI" name="dataregI"  class="form-control" value="<?=$dtinicio;?>" placeholder="DT AGENDA"> 
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <label class="control-label">Hora</label> 
                        <input type="text" id="horainiI" name="horainiI"  class="form-control" placeholder="HORA INICIO" value="<?=$horainicio;?>"  onkeyup="hora('horainiI')">  
                        
                    </div>
                    <div class="col-xs-2">
                        <label class="control-label">&nbsp;</label> 
                        
                        <input type="text" id="horafimI" name="horafimI"  class="form-control" placeholder="HORA FIM" value="<?=$horatermino;?>" onkeyup="hora('horafimI')"> 
                    </div>
                    <div class="col-xs-5">
                        <label class="control-label">Tempo</label> 
                        <select name="_tempo" id="_tempo" class="form-control"  onchange="_buscaTempoI(this.value)" >
                                     <option value="30">30 minutos</option>
                                     <option value="60">1 hora</option>
                                     <option value="90">1:30 hora</option>
                                     <option value="120">2:00 hora</option>
                               </select>
                    </div>      
                </div>
                <div class="row">
                <label class="control-label">Situação</label> 
                <select name="situacao" class="form-control" id="situacao">
					 
					  <?php
                            $_SQL = "SELECT *
                            FROM ".$_SESSION['BASE'].".situacao_agenda where sit_visualiza = 0";
                            $statement = $pdo->query($_SQL);
                            $retorno = $statement->fetchAll();                     
                  
                        if($retorno > 0){
                            
                                foreach ($retorno as $row) {
                                    ?>  
                                    <option style="color:<?=$row['sit_cor'];?>" value="<?=$row['sit_agendaID'];?>"> <?=$row['sit_agendaDescricao'];?></option>                
                                    <?php
                                }
                            }
                            ?>
						</select>
                    </div>                  
                  </div>

                <div class="row">
                    <div class="col-xs-12">
                  
                        <label class="control-label">Observação</label> 
                           <textarea type="text" id="_obs" name="_obs"  class="form-control" placeholder="Observação"> </textarea>
                        </div>                  
                </div>
                <div id="return_aviso">
				
                        </div>
               

              
                <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				<button type="button" onclick="_evsalvar()" class="btn btn-success">Agendar</button>
			  </div>
           <?php
            

        } catch (PDOException $e) {      
            echo $e->getMessage();
        }
        }


       
//EVENTOS-------------------------------------------------------------------------------------------------
//buscar agednda compartilhada
if ($acao  == "14") {

   // print_r($_parametros); 
    $i = 0;
    $_filtro_user = "";
    $_agenda_ocupada = 0;
    $_filtrofinal = "";

    $horaini = '08:00';
    $horafim = '18:00';

    $_idagenda = $_parametros['_idagcomp'];

    $dataInicial = $_parametros['_dataIni'];
    $dataFinal = $_parametros['_dataFim'];
    $start = new DateTime($dataInicial);
    $end = new DateTime($dataFinal);
  
    $periodArr = new DatePeriod($start , new DateInterval('P1D') , $end);

    $_datafiltro = array();
    
    foreach($periodArr as $period) {
        //$end = $period->format('d/m/Y');
        $end = $period->format('Y-m-d');
        array_push($_datafiltro,$end);
    }
         array_push($_datafiltro,$dataFinal);

    //validar  
    $data = explode("-","$dataInicial"); // fatia a string $dat em pedados, usando / como referência
    $data2 = explode("-","$dataFinal"); // fatia a string $dat em pedados, usando / como referência
    
   if( count($data) == 0) {
        $_errorlog =   $_errorlog."Selecione Data Correta \n";
    }else{

    
	$d = $data[2];
	$m = $data[1];
	$y = $data[0];
	$res = checkdate($m,$d,$y);

    $d = $data2[2];
	$m = $data2[1];
	$y = $data2[0];
	$res2 = checkdate($m,$d,$y);
   
    if ($res == 1){ 
        if ($res2 == 1){   }else {
           $_errorlog =   $_errorlog."Data inválida \n";
       }

     }else {
        $_errorlog =   $_errorlog."Data inválida \n";
    }
}
   


    //validar usuario selecionado
    foreach ($_parametros as $item) {    
        $i++;
        if($i > 3 ) {          
            $_filtro_usersel =  $_filtro_usersel.";".$item;  
        }
       // echo "if($i > 3 AND $_agenda_ocupada == 0) {  <br> ";
       
    }
    if($i <= 3 ) {  
        $_errorlog =   $_errorlog."Selecione um colaborador \n";
       
    }

    if($_errorlog != "" ) {  
      
        ?>
         <div class="row">        
            <div class="col-sm-12 ">
            <div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            ×
                        </button>
                        <span style="color:#6b1010"><?=nl2br($_errorlog) ?></span>
                    </div>
            </div>
        </div> 
        <?php
        exit();
    }
       
  
        try {  ?>
            <div class="row">
                <div>
                        <!-- Table -->
                        <table class="table" >
                            <thead>
                                <tr>
                                     <th><div align="center">Data</div></th>
                                    <th><div align="center">Hora</div></th>
                                    <th><div align="center">Ação</div></th>
                                   
                                </tr>
                            </thead>
                          <tbody >
                            
                            <?php
                            $_valida = 1;   
                           foreach($_datafiltro as $_dtfiltro) {
                            $_dt = explode("-",$_dtfiltro);
                            $_dtviewer = $_dt[2]."/".$_dt[1]."/".$_dt[0];
                           // echo "$_dtfiltro <br>";
                        
                        
                                $_SQL = "SELECT DATE_FORMAT(h_hora,'%H:%i') as h_hora 
                                FROM ".$_SESSION['BASE'].".horario WHERE h_hora >= '$horaini' 
                                AND h_hora <= '$horafim' order by h_hora  " ;
                                $statement = $pdo->query($_SQL);                             
                                $result = $statement->fetchAll();
                               
                                foreach ($result as $row) {
                                    
                                 
                                    $_agenda_ocupada = 0;
                                    $i = 0;
                                //VERIFICAR POR USUARIO SELECIONADO
                            //  echo $row['h_hora'] ;
                                    foreach ($_parametros as $item) {   
                                      
                                        $i++;
                                     //   echo "col  $item | <br>" ;
                                       // echo "if($i > 3 AND $_agenda_ocupada == 0) {  <br> ";
                                        if($i > 3 AND $_agenda_ocupada == 0) {          
                                            $_filtro_user =  $item;   
                                                             
                                          
                                  //  echo "colaborador  $_filtro_user |" ;
                                 

                                        
                                           $_hora = $row['h_hora'];
                                          
                                          
                                           if($_horafinal != ""){
                                            $data_hora= strtotime($_dtfiltro." ".$_hora);
                                            $horaultima= strtotime($_horafinal);
                                            if($data_hora < $horaultima) { 
                                           //  echo "Ainda está dentro do prazo"; 
                                                 $_valida = 0;
                                                 $_agenda_ocupada = 1;
                                                
                                              } else {
                                            //  echo "fora do ultimo agendamento  nova validação $_horafinal";
                                                 $_valida = 1;
                                                
                                              }
                                            
                                           }                                         
                                          
                                         
                                    if( $_valida == 1) {                                         
                                           //verificar se existe agendamento a partir primeiro horario  
                                           $_SQLh =  "SELECT DATE_FORMAT(w.termino,'%H:%i') AS dttermino FROM ".$_SESSION['BASE'].".eventos w
                                                        WHERE  w.inicio >= '$_dtfiltro  $_hora'  
                                                        AND    w.inicio < DATE_ADD('$_dtfiltro  $_hora', INTERVAL 30 MINUTE)                                          
                                                        AND    w.id_userAgenda = '$_filtro_user'    
                                                                                                                                                
                                             "; 
                                             
                                             //OR     w.termino <= '$_dtfiltro  23:59:59'   
                                             //AND    w.id_userAgenda = '$_filtro_user'   
                                               //AND    w.id_agenda = '$_idagenda'  
                                         //   echo "$_SQLh \ <BR><BR>"                    ;
                                             $_reth = $pdo->query($_SQLh);                                      

                                            //se existir agendamento buscar ultimo horario.
                                            $resulth = $_reth->fetchAll();
                                            if($_reth->rowCount() > 0) {  
                                                foreach ($resulth as $rowh) {
                                                    $_horafinal = $rowh['dttermino'];
                                                }
                                                
                                                        $_SQLn =  "SELECT w.inicio FROM ".$_SESSION['BASE'].".eventos w
                                                                   WHERE  w.inicio >= '$_dtfiltro  $_hora'                                                                    
                                                                   AND    w.termino <= '$_dtfiltro  $_horafinal'                                                                   
                                                                    AND    w.id_userAgenda = '$_filtro_user'  ";

                                            }else{
                                                        $_SQLn =  "SELECT w.inicio FROM ".$_SESSION['BASE'].".eventos w
                                                                    WHERE  w.inicio >= '$_dtfiltro  $_hora'   
                                                                    and  w.termino < DATE_ADD('$_dtfiltro  $_hora', INTERVAL 30 MINUTE)  
                                                                    AND    w.id_userAgenda = '$_filtro_user'  ";
                                            }
                                            // AND    w.id_agenda = '$_idagenda'
                                            //  AND    w.termino < DATE_ADD('$_dtfiltro  $_horafinal', INTERVAL 1 HOUR) 
                                             // foreach ($result as $row) {
                                                 
                                         // echo "$_SQLn";
                                            $_ret = $pdo->query($_SQLn); 
                                                if($_ret->rowCount() == 0 and $_valida == 1) {
                                                    $_agenda_ocupada = 0; 
                                                }else{
                                                    $_agenda_ocupada = 1; 
                                                }

                                           
                                             
                                           //   echo "fim $_agenda_ocupada|$_valida <br>";
                                        }                                         
                                      
                                     } 
                                    
                                    // echo "aqui $_agenda_ocupada <br>";

                                     

                                    }///VERIFICAR POR USUARIO SELECIONADO  
                                    if($_agenda_ocupada == 0) {                                       
                                        $_variavel = "$_dtviewer;$_hora"."$_filtro_usersel"; 
                                        ?>
                                            <tr>
                                                <td><div align="center"><?=$_dtviewer;?></div></td>
                                                <td><div align="center"><?=$_hora;?></div></td>
                                                <td><button type="button" class="btn btn-default waves-effect waves-light mb-auto" onclick="_pDetComp('<?=$_variavel;?>')"> <i class="fa fa-calendar-plus-o"></i></button>
                                                </td>                                 
                                            </tr>
                                        <?php
                                     }

                                    }   
                                
                                } // fim array $_datafiltro
                                
                               ?>
                                
                            </tbody>	
                        </table>
                        </div>
                    </div>
<?php
        } catch (PDOException $e) {      
            echo $e->getMessage();
       }
    }

    
  //editar evento -  agenda editar
  if ($acao == '15') {
   
       try {  
            
              $_idevento = $_parametros['_idevento'];
              //buscar evento
              $sql = "SELECT 
                      id_agenda,
                      id_userAgenda,
                      id_pet,
                      id_controle,
                      ev_status,
                      ev_sitagenda,
                      ev_tipoatend,
                      titulo,
                      descricao,
                      ev_compartilhado,
                      date_format(inicio,'%d/%m/%Y') as dtinicio,
                      date_format(inicio,'%H:%i') as dthoraini ,
                      date_format(termino,'%H:%i') as dthoratermino                     
                      FROM ".$_SESSION['BASE'].".eventos                      
                      WHERE id_emp = '".$_SESSION['BASE_ID']."' and id_evento = '$_idevento'";
                
               $statement = $pdo->query("$sql"); 
               $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
          
                foreach ($retorno as $row) {
                                         
                         $_idAgenda = $row->id_agenda;
                         $_userAgenda = $row->id_userAgenda;
                         $_idpet = $row->id_pet;
                         $_idcontrole = $row->id_controle;
                         $_sitstatus  = $row->ev_status;   
                         $_sitfase  = $row->ev_sitagenda;
                         $_tipoaval   = $row->ev_tipoatend;   
                         $_titulo     = $row->titulo;                
                         $dtinicio =   $row->dtinicio;       
                         $horainicio =   $row->dthoraini;
                         $horatermino = $row->dthoratermino;
                         $_obs = $row->descricao;
                         $_agendacompartilhada = $row->ev_compartilhado;


                }
             

                if($_id_controle == '0')     {
                    $_labelatendimento = "Iniciar Atendimento";
                }else {
                    $_labelatendimento = "Atendimento";
                }
                

              
               //buscar nome da agenda
               if($_idAgenda > 0) {
                $_idAgenda2 = $_idAgenda;
                   $sql = "SELECT ag_id  as usuario_CODIGOUSUARIO,
                   ag_nome as usuario_APELIDO,
                   ag_avatar as usuario_background,
                   ag_avatar as usuario_avatar
                    FROM ".$_SESSION['BASE'].".agenda u 
                   LEFT JOIN ".$_SESSION['BASE'].".empresa_cadastro e ON u.ag_empresa = e.id 
                   WHERE e.base = '".$_SESSION['BASE']."' and ag_id = '$_idAgenda'";
                    $statement = $pdo->query("$sql");   
               }else{
                        
                $_userAgenda2 = $_userAgenda;
                    $statement = $pdo->query("SELECT usuario_CODIGOUSUARIO,usuario_APELIDO,usuario_background,usuario_avatar FROM ".$_SESSION['BASE'].".usuario u 
                    LEFT JOIN ".$_SESSION['BASE'].".empresa_cadastro e ON u.usuario_base = e.id 
                    WHERE e.base = '".$_SESSION['BASE']."'AND  usuario_CODIGOUSUARIO = '".$_userAgenda."'");
                      
                 
               }
               $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
               foreach ($retorno as $row) {
                 
                   $nomeAgenda = 	$row->usuario_APELIDO;	
               }
   
            
   
               $_SQL = "SELECT *
               FROM ".$_SESSION['BASE'].".pets 
               LEFT JOIN   ".$_SESSION['BASE'].".consumidor  ON pets_idcliente = CODIGO_CONSUMIDOR 
               WHERE   pets_id = '$_idpet' ";
             
               $statement = $pdo->query($_SQL);
               $retorno = $statement->fetchAll();
    
               if($retorno > 0){
                   
                       foreach ($retorno as $row) {
                           $_nome = $row['Nome_Consumidor'];
                           $_fone = "(".$row['DDD_CELULAR'].")".$row['FONE_CELULAR'];
                        
                          
                       }
                       
                   } 
          
             
                   
               ?>
                <input type="hidden" id="_ideventoedit" name="_ideventoedit"  value="<?=$_idevento;?>">
               <input type="hidden" id="_idpet" name="_idpet"  value="<?=$_idpet;?>">
               <input type="hidden" id="_userAgenda" name="_userAgenda"  value="<?=$_userAgenda2;?>">
               <input type="hidden" id="_idAgenda" name="_idAgenda"  value="<?=$_idAgenda2;?>">
             
              
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Agendamento</strong></h4>                
                 </div>
               
                 <div class="card-box">
                               <div class="contact-card">  
                               <div class="row">
                                 <div class="col-xs-2">                         
                                   <img src="/app/assets/images/avatarPets/0048.png" alt="user-img" class="img-circle thumb-sm" style="background-color:<?=$usuario_background;?>">
                                   </div>
                                   <div class="col-xs-6">  
                                       <h4 class="m-t-0 m-b-5 header-title"><b><?=$_nomepet;?></b></h4>
                                       <p class="text-muted"><?=$_nome;?> <br><?=$_fone;?></p> 
                                       
                                   </div>  
                                   <div class="col-xs-4">     
                                       <label class="control-label">Agenda</label> 
                                         <h4 class="m-t-0 m-b-5 header-title"><b><?=$nomeAgenda;?></b></h4>
                                         <button type="button" onclick="_atend(';;;<?=$_idevento;?>')" class="btn btn-success"><?=$_labelatendimento;?></button>
                                   </div>    
                                   
                               </div>
                                
                               </div>
                              
                </div>
                <div class="card-box">
                <?php
                if($_agendacompartilhada > 0) { ?>
                    <div class="row">
                            <div class="col-xs-12" style="text-align: right;">                     
                            <i class="fa fa-share-alt-square text-warning"></i>
                            <label class="control-label text-warning">Agenda compartilhada</label> 
                            <p><?php 
                             $_sql = "SELECT usuario_CODIGOUSUARIO, usuario_APELIDO 
                                      FROM ".$_SESSION['BASE'].".eventos
                                      INNER JOIN  eventos_compart ON ec_id = ev_compartilhado     
                                      INNER JOIN ".$_SESSION['BASE'].".usuario  ON usuario_CODIGOUSUARIO = ec_idusario
                                                                             
                                      WHERE  id_emp = '".$_SESSION['BASE_ID']."'  
                                        AND id_evento = '$_idevento'                                    
                                        AND ev_compartilhado = '$_agendacompartilhada'                                 
                                        AND ec_idusario <> '$_userAgenda'
                                        ORDER BY usuario_APELIDO";  
                                     
                                        $consulta = $pdo->query($_sql);                                        
                                        $result = $consulta->fetchAll(\PDO::FETCH_OBJ);
                                        foreach ($result as $row) {
                                                echo $row->usuario_APELIDO." / ";
                                        }

                             ?>
                             </p>
                                
                                </div>
                            
                        </div>
                <?php }
                ?>
                        <div class="row">
                            <div class="col-xs-12">                     
                                <label class="control-label">Titulo</label> 
                                <input type="text" id="_titulo" name="_titulo"  class="form-control" value="<?=$_titulo;?>" placeholder="Titulo Agenda"> 
                                </div>
                            
                        </div>
                   
                       
                       <div class="row">
                              <?php if($_idAgenda != "0") { ?>                    
                                <div class="col-xs-5">
                                    <label class="control-label">Colaborador</label> 
                                                <?php 
                                                 if($_agendacompartilhada > 0) {
                                                    $_sql = "SELECT usuario_CODIGOUSUARIO, usuario_APELIDO 
                                                    FROM ".$_SESSION['BASE'].".usuario 
                                                                                
                                                    WHERE  usuario_empresa = '".$_SESSION['BASE_ID']."' AND usuario_CODIGOUSUARIO = '".$_userAgenda."'                                   
                                                    ORDER BY usuario_APELIDO";  
                                               
                                                 }else{
                                                    $_sql = "SELECT usuario_CODIGOUSUARIO, usuario_APELIDO 
                                                    FROM ".$_SESSION['BASE'].".usuario 
                                                    INNER JOIN agenda_user ON usuario_CODIGOUSUARIO = agusr_usuario                                   
                                                    WHERE  agusr_empresa = '".$_SESSION['BASE_ID']."' AND agusr_idagenda = '".$_idAgenda."'                                   
                                                    ORDER BY usuario_APELIDO";  
                                                 }
                                                
                                                                        
                                                    $consulta = $pdo->query($_sql);                                        
                                                
                                                    $retornoUsuario = $consulta->fetchAll(\PDO::FETCH_OBJ);
                                                    
                                                   
                                                ?>
                                                <select class="form-control" name="_colaborador" id="_colaborador">
                                                <?php if($_agendacompartilhada == 0) { ?>
                                                    <option value="0">Selecione Colaborador</option>
                                                    <?php } ?>
                                                    <?php foreach ($retornoUsuario as $row): ?>
                                                        <option value="<?=$row->usuario_CODIGOUSUARIO?>"<?=$row->usuario_CODIGOUSUARIO == $_userAgenda ? "selected" : ""?>><?=$row->usuario_APELIDO?></option>
                                                    <?php endforeach ?>
                                                </select>
                                    </div> 
                                <?php }else{ ?>
                                    
                                    <input type="hidden" id="_colaborador" name="_colaborador"   value="<?=$_userAgenda;?>" > 
                                    <?php
                                } ?>
                            <div class="col-xs-7">
                                    <label class="control-label">Tipo Avaliação</label> 
                                    <Select  class="form-control" id="_tipoaval"  name="_tipoaval" >    
                                                                                                                                          
                                       <?php                                                             
                                           $consulta = $pdo->query("SELECT *
                                                       FROM ".$_SESSION['BASE'].".tipo_avaliacao
                                                       WHERE tipaval_ativo = '0'
                                                       ORDER BY tipaval_descricao");
                                           $result = $consulta->fetchAll();
                                           
                                           foreach ($result as $row) {
                                                   ?><option value="<?=$row["tipaval_id"];?>" <?=$row["tipaval_id"] == $_tipoaval ? "selected" : ""?>><?=$row["tipaval_descricao"];?></option><?php
                                               }                                                                  
                                       ?>  
                                   </Select>
                            </div>
                   </div>
                   <div class="row">
                       <div class="col-xs-3">
                           <div class="input-group">  
                           <label class="control-label">Data</label> 
                              <input type="text" id="datareg" name="datareg"  class="form-control" value="<?=$dtinicio;?>" placeholder="DT AGENDA" readonly> 
                           </div>
                       </div>
                       <div class="col-xs-2">
                           <label class="control-label">Hora</label> 
                           <input type="text" id="horaini"  name="horaini"  class="form-control" placeholder="HORA INICIO" value="<?=$horainicio;?>" maxlength="5">  
                           
                       </div>
                       <div class="col-xs-2">
                           <label class="control-label">&nbsp;</label> 
                           
                           <input type="text" id="horafim" name="horafim"  class="form-control" placeholder="HORA FIM" value="<?=$horatermino;?>"  maxlength="5">   
                       </div>
                       <div class="col-xs-5">
                           <label class="control-label">Tempo</label> 
                           <?php 
                           //7 numbers specify year, month, day, hour, minute, second, and millisecond (in that order):
                           //2018, 11, 24, 10, 33, 30, 0 ; ?>
                               <select name="_tempo" id="_tempo" class="form-control"  onchange="_buscaTempo(this.value)" >
                                     <option value="30">30 minutos</option>
                                     <option value="60">1 hora</option>
                                     <option value="90">1:30 hora</option>
                                     <option value="120">2:00 hora</option>
                               </select>
                       </div>      
                   </div>
                   <div class="row">
                        <label class="control-label">Situação</label> 
                        <?php 
                      
                         if( $_sitfase == '1')     { ?>
                                        <select name="situacao" class="form-control" id="situacao" read>
                                        
                                        <?php
                                            $_SQL = "SELECT *
                                            FROM ".$_SESSION['BASE'].".situacao_agenda where sit_visualiza = 0";
                                            $statement = $pdo->query($_SQL);
                                            $retorno = $statement->fetchAll();                     
                                    
                                        if($retorno > 0){
                                            
                                                foreach ($retorno as $row) {
                                                    ?>  
                                                    <option style="color:<?=$row['sit_cor'];?>" value="<?=$row['sit_agendaID'];?>" <?php if($_sitstatus == $row['sit_agendaID']){ echo 'selected'; }?> > <?=$row['sit_agendaDescricao'];?></option>                
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>

                         <?php

                         }else{
                            //ja iniciado atendimento não pode alterar status
                            
                                            $_SQL = "SELECT *
                                            FROM ".$_SESSION['BASE'].".situacao_agenda where sit_agendaID = '$_sitstatus'";
                                            $statement = $pdo->query($_SQL);
                                            $retorno = $statement->fetchAll();                     
                                    
                                        if($retorno > 0){                                            
                                                foreach ($retorno as $row) { ?>
                                                  <p> <label style="color:<?=$row['sit_cor'];?>">
                                                   <?=$row['sit_agendaDescricao'];?></label></p>
                                                <?php }
                                            }
                            ?>
                                <input type="hidden" id="situacao" name="situacao"   value="<?=$_sitstatus;?>" > 
                            <?php
                         }
                         ?>
                        
                       </div>    
                       <div class="row">
                       <div class="col-xs-12">                     
                           <label class="control-label">Observação</label> 
                              <textarea type="text" id="_obs" name="_obs"  class="form-control" placeholder="Observação"><?=trim($_obs);?></textarea>
                           </div>
                     
                   </div>              
                     </div>
                  
                  
                   <div id="return_avisoEdit">
				
                        </div>
   
                 
                   <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                   <?php 
                     if( $_sitfase == '1')     {  ?>
                   <button type="button" onclick="_editsalvar()" class="btn btn-success">salvar</button>
                   <?php } ?>
                   
                 </div>
              <?php
               
   
           } catch (PDOException $e) {      
               echo $e->getMessage();
           }
           }
   

/*
     //BUSCAR NOME DA AGENDA
        $_SQL = "SELECT *
        FROM ".$_SESSION['BASE'].".agenda  WHERE   ag_empresa = '" . $_SESSION['BASE_ID'] . "' and  ag_id = '".$_parametros["_idAgenda"]."' ";
        $statement = $pdo->query($_SQL);
        $retorno = $statement->fetchAll();
  
        if($retorno > 0){            
                foreach ($retorno as $row) {
                    $_nomeAGENDA = $row['ag_nome'];                  
                }
            }
 