<?php require_once('validarlogin.php') ?>
<!DOCTYPE html>
<html>
	<?php require_once('header.php') ?>
    <body>

    <?php require_once('navigatorbar.php');
	use Database\MySQL;
	$pdo = MySQL::acessabd();	
	

	$id = $_POST['idA'];
	
	if($id  == ""){
		$sql="select * from " . $_SESSION['BASE'] . ".salavirtual order by id_sv DESC LIMIT 1";
		$stm = $pdo->prepare("$sql");
		$stm->execute();
		while($value = $stm->fetch(PDO::FETCH_OBJ)){
			$id = $value->id_sv + 1;
			$cor = "#5D9CEC";
			$icon = "fa fa-certificate";
		}
	}else{
		$sql="select * from " . $_SESSION['BASE'] . ".salavirtual where id_sv = '$id'";		
		$stm = $pdo->prepare("$sql");
		$stm->execute();
		while($value = $stm->fetch(PDO::FETCH_OBJ)){
			$titulo = $value->titulo_sv;
			$subtitulo = $value->subtitulo_sv;
			$cor = $value->cor_sv;
			$icon = $value->icon_sv;
			$texto_sv = $value->texto_sv;
			$tipo = $value->tipo_sv;
		}
	}
	
?>
	
	<body id="tamanho">
	<style>
.circleBase {
    padding: 15px;
    border-radius: 50%;
    color: #FFF;
    text-align: center;
    width: 61px;
    font-size: 21px;
    margin-top: 8px;
    margin-left: 29px;
}	
	</style>
		<div class="container-fluid" style="padding:1%; width:98%;">
			<legend><b><i class="fa fa-plus"></i> Novo Tópico - ID[<?=$id;?>]</b></legend>
			<input type="hidden" id="id" name="id" value='<?=$id;?>'>
			<div class="row">
				<div class="col-sm-2">
					<label>Título</label>
					<input type="text" class="form-control input-sm" name="titulo" id="titulo" value="<?=$titulo;?>">
				</div>
				<div class="col-sm-2">
					<label>Sub Título</label>
					<input type="text" class="form-control input-sm" name="subtitulo" id="subtitulo" value="<?=$subtitulo;?>">
				</div>
				<div class="col-sm-2">
					<label>Tipo</label>
					<select class="form-control input-sm" id="tipo" name="tipo">
						<option value="0" <?php if($tipo == 0){ echo 'selected="selected"'; } ?>>Operacional</option>
						<option value="1" <?php if($tipo == 1){ echo 'selected="selected"'; } ?>>Gerencial</option>
					</select>
				</div>
				<div class="col-sm-1">
					<div class="circleBase" id="circle" style="background-color:<?=$cor;?>"><i class="<?=$icon;?>"></i></div>
				</div>
				<div class="col-sm-2" style="padding-top:12px;">
					<?php 
						$sql="select * from " . $_SESSION['BASE'] . ".categoria_cor";
						$stm = $pdo->prepare("$sql");
						$stm->execute();
						while ($linha = $stm->fetch(PDO::FETCH_OBJ)){
							?><div style="width:25px; height:25px; background-color:<?=$linha->codigo_cor;?>; margin:1px; float:left; cursor:pointer" onclick="cor('<?=$linha->codigo_cor;?>');">&nbsp;</div><?php
						}
					?>
					<input type="hidden" id="cor" name="cor" value="<?=$cor;?>">
					
				</div>
				<div class="col-sm-3"  style="overflow-y:scroll; height:82px; min-width:300px; padding-top:12px">
					<?php 
						$sql="select * from " . $_SESSION['BASE'] . ".categoria_icon";
						$stm = $pdo->prepare("$sql");
						$stm->execute();
						while ($linha = $stm->fetch(PDO::FETCH_OBJ)){
							?><i class="<?=$linha->icon;?>" style="font-size:20px; margin-left:10px; margin-top:5px; cursor:pointer; float:left;" onclick="icon('<?=$linha->icon;?>');"></i><?php
						}
					?>
					<input type="hidden" id="icon" name="icon" value="<?=$icon;?>">
				</div>
			</div>
			<br>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal"><i class="fa fa-folder-open"></i> Vídeos</button>
			<hr>
                        <div class="row">
                            <div class="col-lg-12">                         
                                <div class="row">
                                	<div class="col-sm-12">
                                		<div class="card-box m-t-20">
                                			<div class="p-20">
                                				<form role="form">
		                                            <div class="form-group">
		                                                <div class="summernote" id="summernote">
		                                                	<?php if($id == ""){ ?><h6>Digite Aqui...</h6>
															
															<?php }else{ echo $texto_sv; } ?>
		                                                </div>
		                                            </div>
		                                            
		                                            <div class="btn-toolbar form-group m-b-0">
			                                            <div class="pull-right">
			                                               <button type="button" class="btn btn-danger waves-effect waves-light m-r-5" style="margin:2px;" onclick="voltar()"><i class="fa fa-times"></i> Voltar</button>
			                                                <button type="button" class="btn btn-warning waves-effect waves-light m-r-5" style="margin:2px;"   onclick="preview()"><i class="fa fa-eye"></i> Pré-visualização</button>
			                                                <button class="btn btn-success waves-effect waves-light" style="margin:2px;" type="button" onclick="salvar()"> <span>Salvar</span> <i class="fa fa-floppy-o"></i> </button>
			                                            </div>
			                                        </div>
	
		                                        </form>
                                			</div>
                                		</div>

                                	</div>
                                </div>
                               
		                        <!-- End row -->
                                
                                
                            </div> <!-- end Col-9 -->
                        
                        </div><!-- End row -->
		</div>
		
		
<!-- Small modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
       <div class="modal-header">
       	 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       	 <h4 class="modal-title" id="mySmallModalLabel">Vídeos</h4>
      </div>
      <div class="modal-body">
			<?php /*
			$path = 'documentos/Manual/videos';
			foreach (new DirectoryIterator($path) as $fileInfo) {
				if($fileInfo->isDot()) continue;
				$arquivo = $fileInfo->getFilename();
				$path_arquivo = $path.$fileInfo->getFilename();

				$data = date ("d/m/Y H:i:s",$fileInfo->getMTime());
				?>
				<a href="<?=$path."/".$arquivo;?>" target="_blank"><i class="fa fa-file-video-o"></i></a> <b></b>&nbsp;&nbsp;|&nbsp;&nbsp;<?=$data;?><br>
				<?php
			}*/
			  ?>			
			
      </div>
	</div>
  </div>
</div>			
        <form  id="form1" name="form1" method="post" action="">
            <input type="hidden" id="_keyform" name="_keyform"  value="">
        </form>

		
        <script src="../app/assets/js/jquery.min.js"></script>
        <script src="../app/assets/js/bootstrap.min.js"></script>
        <script src="../app/assets/js/detect.js"></script>
        <script src="../app/assets/js/fastclick.js"></script>
        <script src="../app/assets/js/jquery.slimscroll.js"></script>
        <script src="../app/assets/js/jquery.blockUI.js"></script>
        <script src="../app/assets/js/waves.js"></script>
        <script src="../app/assets/js/wow.min.js"></script>
        <script src="../app/assets/js/jquery.nicescroll.js"></script>
        <script src="../app/assets/js/jquery.scrollTo.min.js"></script>
		<script src="../app/assets/js/routesGerencial.js"></script>


        <script src="../app/assets/js/jquery.core.js"></script>
        <script src="../app/assets/js/jquery.app.js"></script>
        <script src="../app/assets/plugins/summernote/summernote.min.js"></script>
		
	
        <script>

            jQuery(document).ready(function(){

                $('.summernote').summernote({
                    height: 500,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                    focus: false,                 // set focus to editable area after initializing summernote 
					callbacks: {
						onImageUpload: function(image, editor, welEditable) {
							uploadImage(image[0]);
						}
					}
                });
            });
			function uploadImage(image) {
				var data = new FormData();
				data.append("image", image);
				$.ajax({
					url: 'documentos/Manual/manual_imagem.php',
					cache: false,
					contentType: false,
					processData: false,
					data: data,
					type: "post",
					success: function(url) {
						//console.log(url);
						alert(url);
						var image = $('<img>').attr('src', 'https://dvet.com.br/gerencial/documentos/Manual/imagens/' + url);
						$('#summernote').summernote("insertNode", image[0]);
					},
					error: function(data) {
						console.log(data);
					}
					
				});
			}
			function salvar(){
				
				var id = $("#id").val();
				var titulo = $("#titulo").val();
				var subtitulo = $("#subtitulo").val();
				var cor = $("#cor").val();
				var icon = $("#icon").val();
				var tipo = $("#tipo").val();
				
				if(titulo == ""){
					alert("titulo nao Pode ser vazio");
				}else{
					if(subtitulo == ""){
						alert("Subtitulo nao Pode ser vazio");
					}else{
						if(cor == ""){
							alert("Escolha a cor do Icone");
						}else{
							if(icon == ""){
								alert("Escolha o desenho do Icone");
							}else{
								if(tipo == ""){
									alert("Selecione o tipo");
								}else{
									var codigo = $('#summernote').summernote('code');
									 var $_keyid = "especie_00002";
									 $.post("page_return.php", {_keyform:$_keyid, _var:id, acao:5},
										function(result){
											$("#divAlt").html(result);
									  });
									$.post('documentos/Manual/manual_acao.php',{codigo:codigo, id:id, titulo:titulo, subtitulo:subtitulo, cor:cor, icon:icon, tipo:tipo,  acao:"salvarCodigo"}, function(resp){
										voltar();
									});
								}
							}
						}
					}
				}
			}

			function preview(){
				
				var id = $("#id").val();
				var titulo = $("#titulo").val();
				var subtitulo = $("#subtitulo").val();
				var cor = $("#cor").val();
				var icon = $("#icon").val();
				var tipo = $("#tipo").val();
				
				var codigo = $('#summernote').summernote('code');
				$.post('documentos/Manual/manual_acao.php',{codigo:codigo, titulo:titulo, subtitulo:subtitulo , cor:cor , icon:icon, tipo:tipo, id:id, acao:"salvarCodigoPreview"}, function(resp){
					window.open('https://dvet.com.br/gerencial/previewManual.php?id='+id, '_blank');
				});
			}
        </script>		
		<script>
			function cor(codigo){
				$("#cor").val(codigo);
				$("#circle").css("background-color",codigo);
			}
			function icon(codigo){
				$("#icon").val(codigo);
				$("#circle").html('<i class="'+codigo+'"></i>');
			}
			function voltar(){
				//VOLTA PARA AREA TREINAMENTOS!
				var $_keyid =   "_G001"; 
				$('#_keyform').val($_keyid);     
				$('#idA').val(id);
				$('#acao').val("");				
				$("#form1").submit(); 				
			}
		</script>
	</body>
</html>