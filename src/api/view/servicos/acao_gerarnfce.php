<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  

use Database\MySQL;
use Functions\NFeService;
use NFePHP\NFe\Common\Standardize;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

function validaCPF($cpf) {
 
    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}


function validar_cnpj($cnpj)
{
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	
	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;

	// Verifica se todos os digitos são iguais
	if (preg_match('/(\d)\1{13}/', $cnpj))
		return false;	

	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
		return false;

	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
}


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia ;


$_acao = $_POST["acao"];


if($_acao == 2 ) {   //confirmar nfce

	//validar produtos da O.S
	$_OS = $_parametros['osfinan'];
	$cpfcnpjfim  = $_parametros['DOCfinan'];
	?>
<div class="alert alert-info  alert-dismissable" style="text-align:right"> Deseja realmente gerar a NFC-e ?   <br>
Obs.:<input type="text" id="obsnfcefim" name="obsnfcefim" value="" style="width: 350px;">        
<input type="text" id="cpfcnpjfim" name="cpfcnpjfim" value="<?=$cpfcnpjfim;?>">
               
		<button type="button" class="btn  btn-default waves-effect waves-light" onclick="_gerarNFCe(3)"><i class="ion-checkmark-circled"></i> Sim </button> </div>
			</div>
	<?php
	
	}

if($_acao == 3 ) {   //gerar nfce

		//validar produtos da O.S
		$_OS = $_parametros['osfinan'];
		$_CPFCNPJ = $_parametros['DOCfinan'];
		$_OBS = $_parametros['DOCfinanobs'];
		//$_NOME  = $_parametros['DOCnome'];
		$_IDCLIENTE = $_parametros['DOCidcliente'];
		$_msg = "";
		$empresa  = $_parametros['DOCempresa'];
		try {

			if(trim($_CPFCNPJ != "")) {

				$cpfcnpj = preg_replace('/[^0-9]/', '', (string) $_CPFCNPJ);
			

				if(strlen($cpfcnpj) > 11) {
					$ret  = validar_cnpj($cpfcnpj);
					if($ret == false){
						$_msg = $_msg ."CNPJ $_CPFCNPJ INVALIDO, Verifique !!! ";									

					}

				}else{
					$ret  = validaCPF($cpfcnpj);
					if($ret == false){
						$_msg = $_msg ."CPF $_CPFCNPJ INVALIDO, Verifique !!! ";									

					}

				}
			
			}

			//busca dados consumidor
			if($_IDCLIENTE > 0) {
			$stm =  $pdo->query("SELECT Nome_Consumidor,CIDADE,BAIRRO,Nome_Rua,CEP,UF,Num_Rua	 FROM ".$_SESSION['BASE'].".consumidor WHERE CODIGO_CONSUMIDOR = '$_IDCLIENTE'  ");
			$consumidor = $stm->fetch(PDO::FETCH_OBJ);
					$c_nomecliente = trim($consumidor->Nome_Consumidor);
					$c_endereco = trim($consumidor->Nome_Rua);
					$c_numrua= trim($consumidor->Num_Rua);
					$c_bairro = trim($consumidor->BAIRRO);
					$c_cidade = trim($consumidor->CIDADE);      
					$c_cep = trim($consumidor->CEP);       
					$c_uf = trim($consumidor->UF);    
					
					//buscar codigo municipio 
					$_s = "Select cod_cidade,cod_uf  from minhaos_cep.cidade  where cidade  = '".$c_cidade."' and estado  = '". $c_uf."'";
					$stm =  $pdo->query("$_s");         
					$codm = $stm->fetch(PDO::FETCH_OBJ);
					$codigo_municipio =  $codm->cod_cidade;
			}
			if($c_endereco != ""){
					if($c_numrua == "") { 
						$_msg = $_msg . "Numero do Endereço não informado<br>";
					}
					if($c_bairro == "") { 
						$_msg = $_msg ."Bairro não informado<br>";
					}
					if($c_cidade == "") { 
						$_msg = $_msg ."Cidade não informado<br>";
					}
					if($c_cep == "") { 
						$_msg = $_msg ."Cep não informado<br>";
					}
					if($c_uf == "") { 
						$_msg = $_msg ."Cep não informado<br>";
					}
					if($codigo_municipio == "") { 
						$_msg = $_msg ."Não foi encontrado Cod.Municipo <br>";
					}

			}
			if($_msg != "") { ?>
				<div class="alert alert-danger  alert-dismissable" style="text-align:right"><?=$_msg;?><br>					 
				<?php exit();

			}

			$descontopecas = 0;
			$sql="Select DESC_PECA	from chamada where CODIGO_CHAMADA  = '$_OS' and DESC_PECA > 0";		
			$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
				while($row = mysqli_fetch_array($resultado)){
						$descontopecas = $row['DESC_PECA'];
					}

					
			
			$sq = "SELECT  nfed_pedido,nfed_numeronf FROM ". $_SESSION['BASE'] . ".NFE_DADOS  WHERE  nfed_chamada = '$_OS' and 	nfed_data = '$data'";	//nfed_chave <> '' and nfed_cancelada = '0' and	
		
			//verificar se existe nota emitida mesmo valor
				$consulta = $pdo->query("$sq");
				$ret = $consulta->fetch(PDO::FETCH_OBJ);
				if($consulta->rowCount() > 0) {
					$numeroNFCe = $ret->nfed_numeronf;
					if( $ret->nfed_chave != "" and $ret->nfed_cancelada != "0" ){				
					?>
					<div class="alert alert-danger  alert-dismissable" style="text-align:right"> Já existe Nota gerada com data hoje !!!  <br>					 
				<?php exit();
					}
				 }else{
					$numeroNFCe = 0;
				 }

				
				 $stm = $pdo->query("SELECT sum(Valor_Peca*Qtde_peca) as pecas	 FROM ".$_SESSION['BASE'].".chamadapeca WHERE Numero_OS = '$_OS' and TIPO_LANCAMENTO = 0 ");
				 $pedido = $stm->fetch(PDO::FETCH_OBJ);
				 $totalnf =  $pedido->pecas-$descontopecas;
		
			if($empresa  > '1'){
				//$empresa = '1';
			}else{
				$empresa = '1';
			}
		
			// Instância NFeService
			$nfe = new NFeService($empresa, 65);
			$numero_pedido= $_OS;
			$livro = 0;
			//Gera e assina XML
		
			$xml = $nfe->gerarNFCeOS($numeroNFCe,$_OS,$_CPFCNPJ,$descontopecas,$_IDCLIENTE,$_OBS);
			

			$signedXML = $nfe->assinaNFe($xml);

			//Grava XML no banco e incrementa número de NF
			$consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_chamada = '$numero_pedido' AND nfed_numlivro = '$livro'");
			$xml = $consulta->fetch(PDO::FETCH_OBJ);

			if (!$xml) {
				$dataNFC = date('Y-m-d H:m:s');							

				$consulta = $pdo->query("SELECT  proximo_numero_nfce_producao,serie_nfce_producao FROM ". $_SESSION['BASE'] . ".empresa  WHERE empresa_id = '$empresa'");
				$ret = $consulta->fetch(PDO::FETCH_OBJ);
				$numeroNFCe = $ret->proximo_numero_nfce_producao;
				$serie = $ret->serie_nfce_producao;				

				$insert = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_chamada, nfed_numlivro, nfed_xml, nfed_hora,nfed_numeronf,nfed_empresa,nfed_serie,nfed_modelo,nfed_data,nfed_totalnota,nfed_informacaoAdicionais) VALUES(?, ?, ?, ?,?,?, ?, '0',?, ?,?)");
				$insert->bindParam(1, $numero_pedido);
				$insert->bindParam(2, $livro);
				$insert->bindParam(3, $signedXML);
				$insert->bindParam(4, $dataNFC);
				$insert->bindParam(5, $numeroNFCe);
				$insert->bindParam(6, $empresa);
				$insert->bindParam(7, $serie);
				$insert->bindParam(8, $data);
				$insert->bindParam(9, $totalnf);
				$insert->bindParam(10, $_OBS);				
				$insert->execute();

		
				$update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".empresa SET proximo_numero_nfce_producao = proximo_numero_nfce_producao + 1 WHERE empresa_id = ?");
				$update->bindParam(1, $empresa);
				$update->execute();
			
				
			} else {
				$dataNFC = date('Y-m-d H:m:s');
				$update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ?, nfed_hora = ? WHERE nfed_chamada = ? AND nfed_numlivro = ?");
				$update->bindParam(1, $signedXML);
				$update->bindParam(2, $dataNFC);
				$update->bindParam(3, $numero_pedido);
				$update->bindParam(4, $livro);
				$update->execute();
			}

		
			$consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_chamada = '$numero_pedido' AND nfed_numlivro = '$livro'");
			$xml = $consulta->fetch(PDO::FETCH_OBJ);


			//Transmite XML
				$recibo = $nfe->transmitir($xml->nfed_xml);					
								
				$st = new Standardize();
				  $stResponse = $st->toStd($recibo);
			
	
			//Grava 
			$update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET nfed_recibo = ? , nfed_chave = ? WHERE nfed_chamada = ? AND nfed_numlivro = ?");
			$update->bindParam(1, $recibo);
			$update->bindParam(2, $_CHAVE);
			$update->bindParam(3, $numero_pedido);
			$update->bindParam(4, $livro);
			$update->execute();

			$verificaProtocolo = new Standardize();
			//$verificaProtocolo = $verificaProtocolo->toStd($protocolo);
			$verificaProtocolo = $verificaProtocolo->toStd($recibo);

			$_retmotivo = $verificaProtocolo->protNFe->infProt->xMotivo;
			$_retprotocolo = $verificaProtocolo->protNFe->infProt->nProt;
			$_CHAVE  = $verificaProtocolo->protNFe->infProt->chNFe;

			if ($verificaProtocolo->cStat != '104') {
				$update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET  nfed_motivo = ? WHERE nfed_chamada = ? AND nfed_numlivro = ?");
				$update->bindParam(1, $verificaProtocolo->protNFe->infProt->xMotivo);
				$update->bindParam(2, $numero_pedido);
				$update->bindParam(3, $livro);
				$update->execute();
			} else {
			
				$dataProtocolo = date('Y-m-d H:m:s');
//echo "autoriza";
				$xmlProtocolado = $nfe->autorizaXml($xml->nfed_xml,$recibo);
		//	print_r($xmlProtocolado);
				$update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
				nfed_dataautorizacao = ?, 
				nfed_xml_protocolado = ?, 
				nfed_motivo = ?,											
				nfed_protocolo =  ?,
				nfed_chave = ?
				WHERE nfed_chamada = ? AND nfed_numlivro = ?");
				$update->bindParam(1, $dataProtocolo);
				$update->bindParam(2, $xmlProtocolado);
				$update->bindParam(3, $_retmotivo);				
				$update->bindParam(4, $_retprotocolo);	
				$update->bindParam(5, $_CHAVE);   								
				$update->bindParam(6, $numero_pedido);
				$update->bindParam(7, $livro);
				$update->execute();
				
				?>
				<div class="alert alert-success  alert-dismissable" style="text-align:right"> Nota gerada com sucesso !!!  <?=$_CHAVE;?>  <Br>
					<button type="button" class="btn btn-primary btn-block"  onclick="_ImprimirNF(65,'<?=$numeroNFCe;?>')" >Imprimir NFCe</button>    </div>                
					
					<?php
				
			}


		} catch (\Exception $e) {
			//echo $e;
			
			?>
			<div class="row">
								<div class="col-sm-12" align="center">			
									<p><strong> Ops Ocorreu Erro</strong>, Envio Receita !!!<?php echo $x;?></p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12" align="center">			
									<?php 
									
									echo $e->getmessage();
									?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12" align="center">			
								<button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
								</div>
							</div>
							
					<?php
			exit();
		}
	
		
}


if($_acao == 4 ) {   //confirmar nfe

	//validar produtos da O.S
	$_OS = $_parametros['osfinan'];
	$cpfcnpjfim  = $_parametros['DOCfinan'];
	?>
<div class="alert alert-info  alert-dismissable" style="text-align:right"> Deseja realmente gerar a NF-e ?   <br>
              
		<button type="button" class="btn  btn-default waves-effect waves-light" onclick="_gerarNFe(5)"><i class="ion-checkmark-circled"></i> Sim </button> </div>
			</div>
	<?php
	
	}


	if($_acao == 5 ) {   //cancelar nfce

		$_id = $_parametros['nfceID'];
		?>
		<div class="alert alert-danger  alert-dismissable" style="text-align:right"> Deseja realmente CANCELAR a NFC-e ?   <br>     
	
				   
			<button type="button" class="btn  btn-default waves-effect waves-light" onclick="_cancelarNFCeSim('<?=$_id;?>')"><i class="ion-checkmark-circled"></i> Sim </button> </div>
				</div>
		<?php
		
		}

		
	if($_acao == 6 ) {   //cancelar nfce

		$_idpedido= $_parametros['nfceID'];
		try{
			date_default_timezone_set('America/Sao_Paulo');      
	
			$idemp = 1;
			// Instância NFeService
			$nfe = new NFeService($idemp, 65);
	
			
		
			$consultaLinha = $pdo->query("Select nfed_chave,nfed_protocolo
			from ". $_SESSION['BASE'] .".NFE_DADOS 
			where  nfed_id = '".$_idpedido."' and nfed_modelo <> '55' ");							   
			$retornoLinha = $consultaLinha->fetchAll();
			foreach ($retornoLinha as $row_nf) { 
				$chave =  $row_nf['nfed_chave'];
				  $xJust =   trim("PEDIDO DE VENDA CANCELADO");
				$nProt =   $row_nf['nfed_protocolo'];
			}
		
		
			$livro = 0;
		 
			$retcancelamento = $nfe->CancelarNF($chave, $xJust, $nProt);
		  //  print_r($retcancelamento);
		//   echo "----------------------";
			$st = new Standardize();
			$stResponse = $st->toStd($retcancelamento);
			$cStat = $stResponse->retEvento->infEvento->cStat;
			$xMotivo = $stResponse->retEvento->infEvento->xMotivo;
			$xcancelada = 0;
		 //   echo "-- $cStat------ $xMotivo-------------";
			if( $cStat != "135") {
				$xcancelada = 0;
				?>
			
			<div class="alert alert-warning alert-dismissable"> 
								<p><strong>Nota não pode ser Cancelada</strong> </p>	
								<p><?=$chave;?>
								<p><?php echo $xMotivo ;?></p>
							</div>
													   
					
									 
								<?php
	
			}else{
				$xcancelada = 1;
				?>
			
			<div class="alert alert-success alert-dismissable"> 		
								<p><strong> Executado</strong> !!!<?php echo $xMotivo ;?></p>
							</div>
														 
							
									
								<?php
			}
			
		   $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
		   nfed_cancelada = ?,
		   nfed_motEcarta = ?, 
		   nfed_xmlcancelado = ?
		   WHERE nfed_cancelada = '0' and  nfed_id = ? ");
		   $update->bindParam(1, $xcancelada);
		   $update->bindParam(2, $xJust);
		   $update->bindParam(3, $retcancelamento);     					
		   $update->bindParam(4, $_idpedido);
		  
		   $update->execute();
		} catch (\Exception $e) {
		 echo $e;
		}
		
		
		}


		
if($_acao == 7 ) {   //confirmar nfse

	//validar produtos da O.S
	$_OS = $_parametros['osfinan'];
	$cpfcnpjfim  = $_parametros['DOCfinan'];
	?>
<div class="alert alert-info  alert-dismissable" style="text-align:right"> Deseja realmente gerar a NFS-e ?   <br>
              
		<button type="button" class="btn  btn-default waves-effect waves-light" onclick="_gerarNFse(5)"><i class="ion-checkmark-circled"></i> Sim </button> </div>
			</div>
	<?php
	
	}



?> 
