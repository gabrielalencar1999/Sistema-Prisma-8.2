<?php
namespace Functions;

use stdClass;
use Database\MySQL;

class Financeiro {

    public static function consultarFinanceiro(array $params){
	
		//$response = new \stdClass;
		$response = "";
	//	$quiz_ret = array();

        try{
			if($params['dataIni'] == ""){				
				$params['dataIni'] = date('Y-m-d');
			}
			if($params['dataIni'] == ""){
				$params['dataFim'] = date('Y-m-d');
			}
			
			$filtroCancelada = "and financeiro_situacaoID = '".$params['canceladas']."'";

			
			
			//TIPO FILTRAR POR DATA LANCAMENTO
			if($params['tipoData'] == "financeiro_emissao" or $params['tipoData'] == ""){
				$filtroData = "financeiro_emissao BETWEEN  '".$params['dataIni']." 00:00:00' and '".$params['dataFim']." 23:59:59'";
			}	
			//TIPO FILTRAR POR DATA DE VENCIMENTO
			if($params['tipoData'] == "financeiro_vencimento"){
				$filtroData = "financeiro_vencimento BETWEEN  '".$params['dataIni']." 00:00:00' and '".$params['dataFim']." 23:59:59'";
			}
			//TIPO FILTRAR POR DATA DE PAGAMENTO
			if($params['tipoData'] == "financeiro_dataFim"){
				$filtroData = "financeiro_dataFim BETWEEN  '".$params['dataIni']." 00:00:00' and '".$params['dataFim']." 23:59:59'";
			}				

			if($params['financeiro_tipoQuem2'] != "" and $params['financeiro_codigoCliente2']){
				$filtroQuem = "and financeiro_codigoCliente = '".$params['financeiro_codigoCliente2']."'";
			}
			//TIPO RECEITA================================================================================
			if($params['financeiro_tipo'] != ""){
				$receita = "and financeiro_tipo = '".$params['financeiro_tipo']."'";
			}
			//TIPO PAGAMENTO================================================================================
			if($params['financeiro_tipoPagamento_filtro'] != ""){
				$tipoPagamento = "and financeiro_tipoPagamento = '".$params['financeiro_tipoPagamento_filtro']."'";
			}
			
			//CATEGORIA==================================================================================
			if($params['pesquisaCategoria'] != ""){
				if($params['pesquisaSubCategoria'] != ""){
					$categoria = "and financeiro_grupo = '".$params['pesquisaCategoria']."' and financeiro_subgrupo = '".$params['pesquisaSubCategoria']."'";
				}else{
					$categoria = "and financeiro_grupo = '".$params['pesquisaCategoria']."'";
				}
				
			}
			//SITUACAO DO REGISTRO==================================================================================
			if($params['sit_financeiro'] == 1){
				$sit_financeiro = "and financeiro_valorFim = '0'";
			}
			if($params['sit_financeiro'] == 2){
				$sit_financeiro = "and financeiro_valorFim  > '0'";
			}
				
			

			if($params['Descricao'] != ""){
				$filDescricao  = "
				and financeiro_historico like '%".$params['Descricao']."%'
				OR financeiro_documento = '".$params['Descricao']."' and financeiro_documento <> '0' $categoria $receita $filtroQuem $tipoPagamento and $filtroData $filtroCancelada $sit_financeiro";
			}
			

			
			
			//ordenar
			if($params['ordenar'] == ""){
				$ord = "DESC";
			}else{
				$ord = $params['ordenar'];
			}
			$order = "ORDER BY ".$params['tipoData']." $ord";

			
			$pdo = MySQL::acessabd();				
			
					$stm = $pdo->prepare("
                    SELECT *,".$params['_bd'].".tiporecebimpgto.nome as tipoPagto, DATE_FORMAT(financeiro_emissao,'%d/%m/%Y') AS data, DATE_FORMAT(financeiro_vencimento,'%d/%m/%Y') AS vencimento 
                    FROM  ".$params['_bd'].".financeiro
					LEFT JOIN ".$params['_bd'].".fabricante ON CODIGO_FABRICANTE=financeiro_codigoCliente
					LEFT JOIN ".$params['_bd'].".tiporecebimpgto ON id=financeiro_tipoPagamento
                    WHERE $filtroData $categoria $receita $filtroQuem $tipoPagamento $filtroCancelada $sit_financeiro $filDescricao $order");
					//echo("$filtroData $categoria $receita $filtroQuem $filtroCancelada $filDescricao $order");
                    $stm->execute();	
                 
			        if ( $stm->rowCount() > 0 ){
                        $response =  $stm->fetchAll(\PDO::FETCH_OBJ);
					}

        }
        catch (\Exception $fault){
            $response = $fault;
        }
        return $response;
    }  

	public static function categoria(array $params){
	
		//$response = new \stdClass;
		$response = "";
	//	$quiz_ret = array();

        try{			
		
			$pdo = MySQL::acessabd();	
			$dtip = $params['tipoCategoria'];
			if($dtip == '0' or $dtip == '1'){			
				$tipo = "where tipo_categoria = '2' or tipo_categoria = '$dtip'"; 
			}
			$stm = $pdo->prepare("SELECT * FROM  ".$params['_bd'].".categoria $tipo ");
			$stm->execute();	
		 
			if ( $stm->rowCount() > 0 ){
				$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
			}

        }
        catch (\Exception $fault){
                        $response = $fault;
        }
        return $response;
    }
	public static function categoriaCor(array $params){
	
		//$response = new \stdClass;
		$response = "";
	//	$quiz_ret = array();

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("SELECT * FROM  bd_gestorpet.categoria_cor");
				$stm->execute();	
			 
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}

        }
        catch (\Exception $fault){
                        $response = $fault;
        }
        return $response;
    }

	public static function categoriaIcon(array $params){
	
		//$response = new \stdClass;
		$response = "";
	//	$quiz_ret = array();

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("SELECT * FROM  bd_prisma.categoria_icon");
				$stm->execute();	
			 
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}

        }
        catch (\Exception $fault){
                        $response = $fault;
        }
        return $response;
    }

	public static function insert_categoria(array $params){

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("
				insert into  ".$params['_bd'].".categoria 
				(descricao_categoria,tipo_categoria,cor_categoria,icon_categoria,ativo_categoria) 
				values
				('".$params['newCategoria']."',
				'".$params['tipoCategoria']."',
				'".$params['cor']."',
				'".$params['icon']."',
				'0')");
				$stm->execute();	
			 
				 $response = "ok";

        }
        catch (\Exception $fault){
               $response = $fault;
        }
        return $response;
    }

		public static function select_subCategoria(array $params){

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("
				select * from ".$params['_bd'].".subcategoria
				where ref_subcategoria = ?
				");
				$stm->bindParam(1, $params['_var'], \PDO::PARAM_INT, 3);
				$stm->execute();	
			 
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}

        }
        catch (\Exception $fault){
               $response = $fault;
        }
        return $response;
    }
	public static function select_categoria(array $params){

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("
				select * from ".$params['_bd'].".categoria
				where id_categoria = ?
				");
				$stm->bindParam(1, $params['_var'], \PDO::PARAM_INT, 3);
				$stm->execute();	
			 
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}

        }
        catch (\Exception $fault){
               $response = $fault;
        }
        return $response;
    }
	public static function insert_subcategoria(array $params){

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("
				insert into ".$params['_bd'].".subcategoria
				(ref_subcategoria,descricao_subcategoria)
				values('".$params['idCategoria']."','".$params['nomeSubcategoria']."')
				");
				$stm->execute();	
			 
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}

        }
        catch (\Exception $fault){
               $response = $fault;
        }
        return $response;
    }
	public static function update_subcategoria(array $params){

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("
				update ".$params['_bd'].".subcategoria
				set descricao_subcategoria = '".$params['nomeSubcategoria']."'
				where id_subcategoria = '".$params['idsubCategoria']."'");
				$stm->execute();	
			 
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}

        }
        catch (\Exception $fault){
               $response = $fault;
        }
        return $response;
    }	
	public static function delete_subcategoria(array $params){

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("
				delete from ".$params['_bd'].".subcategoria where id_subcategoria = '".$params['idsubCategoria']."'");
				$stm->execute();	
			 
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}

        }
        catch (\Exception $fault){
               $response = $fault;
        }
        return $response;
    }

	public static function tipoPagamento(array $params){

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("select * from  ".$params['_bd'].".tiporecebimpgto");
				$stm->execute();	
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}

        }
        catch (\Exception $fault){
               $response = $fault;
        }
        return $response;
    }
	public static function buscaQuem(array $params){

        try{	
			if($params['tipo'] == 1){
				$pdo = MySQL::acessabd();	
				if($params['idOS'] != "" and $params['idconsumidor'] == '' ){
					$stm = $pdo->prepare("select O.CODIGO_CONSUMIDOR AS CODIGO_CONSUMIDOR, C.Nome_Consumidor AS Nome_Consumidor from  ".$params['_bd'].".chamada  as O
					LEFT JOIN  ".$params['_bd'].".consumidor as C ON C.CODIGO_CONSUMIDOR  = O.CODIGO_CONSUMIDOR 
					WHERE CODIGO_CHAMADA = '".$params['idOS']."'");					
				}	else{
					$stm = $pdo->prepare("select CODIGO_CONSUMIDOR,Nome_Consumidor from  ".$params['_bd'].".consumidor WHERE CODIGO_CONSUMIDOR = '".$params['idconsumidor']."'");
				}					
				
				$stm->execute();	
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}
			}		
			if($params['tipo'] == 2){
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("select CODIGO_FABRICANTE,NOME,CNPJ from  ".$params['_bd'].".fabricante order by NOME");
				$stm->execute();	
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}
			}

			
			if($params['tipo'] == 3){
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("SELECT usuario_CODIGOUSUARIO,usuario_APELIDO
				FROM  ".$params['_bd'].".usuario 
			
				WHERE usuario_ATIVO = 'Sim'			
				
				ORDER BY usuario_APELIDO");
				$stm->execute();	
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}
			}

        }
        catch (\Exception $fault){
               $response = $fault;
        }
        return $response;
    }
	public static function bancos(array $params){

        try{			
			
				$pdo = MySQL::acessabd();							
				$stm = $pdo->prepare("select * from  ".$params['_bd'].".bancos");
				$stm->execute();	
				if ( $stm->rowCount() > 0 ){
					$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				}

        }
        catch (\Exception $fault){
               $response = $fault;
        }
        return $response;
    }

	public static function insert_financeiro(array $params){
		
		

        try{	
				
				$financeiro_registro = $params['financeiro_registro'];                     // $financeiro_parcela = $params['financeiro_parcela'];
				$financeiro_totalParcela = $params['financeiro_totalParcela'];              $financeiro_codigoCliente = $params['financeiro_codigoCliente'];
				$financeiro_nome = $params['financeiro_nome'];                              $financeiro_portador = $params['financeiro_portador'];
				$financeiro_documento = $params['financeiro_documento'];                    $financeiro_historico = $params['financeiro_historico'];
				$financeiro_emissao = $params['financeiro_emissao'];                        $financeiro_vencimento = $params['financeiro_vencimento'];
				$financeiro_vencimentoOriginal = $params['financeiro_vencimentoOriginal'];  $financeiro_vencimentoOriginal = $params['financeiro_vencimentoOriginal'];
				$financeiro_valor = $params['financeiro_valor'];                            $financeiro_dataFim = $params['financeiro_dataFim'];
				$financeiro_valorFim = $params['financeiro_valorFim'];                      $financeiro_valorJuros = $params['financeiro_valorJuros'];
				$financeiro_valorDesconto = $params['financeiro_valorDesconto'];            $financeiro_impresso = $params['financeiro_impresso'];
				$financeiro_dias = $params['financeiro_dias'];                              $financeiro_referente = $params['financeiro_referente'];
				$financeiro_identificador = $params['financeiro_identificador'];            $financeiro_situacaoID = $params['financeiro_situacaoID'];
				$financeiro_obs = $params['financeiro_obs'];                                $financeiro_carencia = $params['financeiro_carencia'];
				$financeiro_comissaoPaga = $params['financeiro_comissaoPaga'];              $financeiro_valorEmpresa = $params['financeiro_valorEmpresa'];
				$financeiro_tipoPagamento = $params['financeiro_tipoPagamento'];            $financeiro_codBanco = $params['financeiro_codBanco'];
				$financeiro_agenciaBanco = $params['financeiro_agenciaBanco'];              $financeiro_contaBanco = $params['financeiro_contaBanco'];
				$financeiro_numTituloBanco = $params['financeiro_numTituloBanco'];          $financeiro_motivo = $params['financeiro_motivo'];
				$financeiro_valorSaldo = $params['financeiro_valorSaldo'];                  $financeiro_caixa = $params['financeiro_caixa'];
				$Documento = $params['Documento'];                                          $financeiro_lcLivroUsuarioLancamento = $params['financeiro_lcLivroUsuarioLancamento'];
				$financeiro_usuarioLancamento = $params['financeiro_usuarioLancamento'];    $financeiro_empresa = $params['financeiro_empresa'];
				$financeiro_pgtoParcial = $params['financeiro_pgtoParcial'];                $financeiro_grupo = $params['financeiro_grupo'];
				$INDENTIFICADOR = $params['INDENTIFICADOR'];                                $financeiro_lancamentoCaixa = $params['financeiro_lancamentoCaixa'];
				$financeiro_nossoNumero = $params['financeiro_nossoNumero'];                $financeiro_remessa = $params['financeiro_remessa'];
				$financeiro_dataRemessa = $params['financeiro_dataRemessa'];                $financeiro_descricaoBoleto = $params['financeiro_descricaoBoleto'];
				$financeiro_hora = $params['financeiro_hora'];                              $financeiro_tipo = $params['financeiro_tipo'];
				$financeiro_subgrupo = $params['financeiro_subgrupo'];                      $financeiro_tipoQuem = $params['financeiro_tipoQuem'];                              
				$financeiro_empresa = $params['financeiro_empresa'];						
				
				
			
				$financeiro_valor = str_replace(".","",$financeiro_valor);
				$financeiro_valor = str_replace(",",".",$financeiro_valor);

				//if($financeiro_valorFim != "" and $financeiro_valorFim > 0){
					$financeiro_valorFim = str_replace(".","",$financeiro_valorFim);
					$financeiro_valorFim = str_replace(",",".",$financeiro_valorFim);
				//}else{
				//	$financeiro_valorFim = "";
				//}

				$financeiro_valorJuros = str_replace(".","",$financeiro_valorJuros);
				$financeiro_valorJuros = str_replace(",",".",$financeiro_valorJuros);

				$financeiro_valorSaldo = str_replace(".","",$financeiro_valorSaldo);
				$financeiro_valorSaldo = str_replace(",",".",$financeiro_valorSaldo);

				$financeiro_pgtoParcial = str_replace(".","",$financeiro_pgtoParcial);
				$financeiro_pgtoParcial = str_replace(",",".",$financeiro_pgtoParcial);

				$financeiro_cad= date('Y-m-d H:i:s');
				
				if($financeiro_emissao == ""){
					$financeiro_emissao = date('Y-m-d');
					$financeiro_hora= date('Y-m-d H:i:s');
				}
				if($financeiro_vencimentoOriginal == ""){
					$financeiro_vencimentoOriginal = $financeiro_vencimento;
				}
				$i = 1;
				if($financeiro_totalParcela == ""){
					$financeiro_totalParcela = 1;
				}
				while($i <= $financeiro_totalParcela){
					
					//PARCELA
					$financeiro_parcela = $i;
					
					//SOMA DATA DE ACORDO COM NUMERO DE PARCELA
					if($i != 1){
						$financeiro_vencimento = date('Y-m-d', strtotime($financeiro_vencimento.' + 1 month'));
						$financeiro_vencimentoOriginal = date('Y-m-d', strtotime($financeiro_vencimentoOriginal.' + 1 month'));
					}
						
	
					//verificar se usuário atual é comissionando para lançamento contacorrente
					$pdo = MySQL::acessabd();	
					$sql = "SELECT * FROM ". $_SESSION['BASE'].".usuario WHERE usuario_CODIGOUSUARIO = '$financeiro_codigoCliente' and usuario_lancaCC = '-1'";
					$stm = $pdo->prepare($sql);
					$stm->execute();
					if($stm->rowCount() > 0){					
						$financeiro_lancamentoCC = 1;
					}else{
						$financeiro_lancamentoCC = 0;													
					}
					
											
					$stm = $pdo->prepare("
					insert into ".$params['_bd'].".financeiro
					(	financeiro_registro,
						financeiro_parcela,
						financeiro_totalParcela,
						financeiro_codigoCliente,
						financeiro_nome,
						financeiro_portador,
						financeiro_documento,
						financeiro_historico,
						financeiro_dtcad,
						financeiro_emissao,
						financeiro_vencimento,
						financeiro_vencimentoOriginal,
						financeiro_valor,
						financeiro_dataFim,
						financeiro_valorFim,
						financeiro_valorJuros,
						financeiro_valorDesconto,
						financeiro_impresso,
						financeiro_dias,
						financeiro_referente,
						Documento,
						financeiro_situacaoID,
						financeiro_obs,
						financeiro_carencia,
						financeiro_comissaoPaga,
						financeiro_valorEmpresa,
						financeiro_tipoPagamento,
						financeiro_codBanco,
						financeiro_agenciaBanco,
						financeiro_contaBanco,
						financeiro_numTituloBanco,
						financeiro_motivo,
						financeiro_valorSaldo,
						financeiro_caixa,
						financeiro_identificador,
						financeiro_lcLivroUsuarioLancamento,
						financeiro_usuarioLancamento,
						financeiro_empresa,
						financeiro_pgtoParcial,
						financeiro_grupo,
						INDENTIFICADOR,
						financeiro_lancamentoCaixa,
						financeiro_nossoNumero,
						financeiro_remessa,
						financeiro_dataRemessa,
						financeiro_descricaoBoleto,
						financeiro_hora,
						financeiro_tipo,
						financeiro_subgrupo,
						financeiro_tipoQuem,
						financeiro_lancamentoCC
					)
					values(
						'$financeiro_registro',
						'$financeiro_parcela',
						'$financeiro_totalParcela',
						'$financeiro_codigoCliente',
						'$financeiro_nome',
						'$financeiro_portador',
						'$financeiro_documento',
						'$financeiro_historico',
						'$financeiro_cad',
						'$financeiro_emissao',
						'$financeiro_vencimento',
						'$financeiro_vencimentoOriginal',
						'$financeiro_valor',
						'$financeiro_dataFim',
						'$financeiro_valorFim',
						'$financeiro_valorJuros',
						'$financeiro_valorDesconto',
						'$financeiro_impresso',
						'$financeiro_dias',
						'$financeiro_referente',
						'$Documento',
						'$financeiro_situacaoID',
						'$financeiro_obs',
						'$financeiro_carencia',
						'$financeiro_comissaoPaga',
						'$financeiro_valorEmpresa',
						'$financeiro_tipoPagamento',
						'$financeiro_codBanco',
						'$financeiro_agenciaBanco',
						'$financeiro_contaBanco',
						'$financeiro_numTituloBanco',
						'$financeiro_motivo',
						'$financeiro_valorSaldo',
						'$financeiro_caixa',
						'$financeiro_identificador',
						'$financeiro_lcLivroUsuarioLancamento',
						'$financeiro_usuarioLancamento',
						'$financeiro_empresa',
						'$financeiro_pgtoParcial',
						'$financeiro_grupo',
						'$INDENTIFICADOR',
						'$financeiro_lancamentoCaixa',
						'$financeiro_nossoNumero',
						'$financeiro_remessa',
						'$financeiro_dataRemessa',
						'$financeiro_descricaoBoleto',
						'$financeiro_hora',
						'$financeiro_tipo',
						'$financeiro_subgrupo',
						'$financeiro_tipoQuem',
						'$financeiro_lancamentoCC'
					)
					");
					$stm->execute();
					$financeiro_id = $pdo->lastInsertId();
					
					
					//---------------------LANCAMENTO CONTA CORRENTE ------------------------------------------------------------------------------------------
					if($financeiro_valorFim == "" and $financeiro_valorFim < 0){
						$financeiro_valorFim = "";
					}
					
					if($financeiro_dataFim == "0000-00-00"){
						$financeiro_dataFim = "";
					}
								
					//--------------------------------- FIM LANCAMENTO CONTA CORRENTE-------------------------------------------------------------------------
					$i++;
				}
        }		
        catch (\Exception $fault){
               $response = $fault;
        }
      //  echo $response;
    }

		public static function altera_financeiro(array $params){
		
		

        try{	
				
				$financeiro_id = $params['financeiro_id']; 
				$financeiro_registro = $params['financeiro_registro'];                    // $financeiro_parcela = $params['financeiro_parcela'];
				$financeiro_totalParcela = $params['financeiro_totalParcela'];              $financeiro_codigoCliente = $params['financeiro_codigoCliente'];
				$financeiro_nome = $params['financeiro_nome'];                              $financeiro_portador = $params['financeiro_portador'];
				$financeiro_documento = $params['financeiro_documento'];                    $financeiro_historico = $params['financeiro_historico'];
				$financeiro_emissao = $params['financeiro_emissao'];                        $financeiro_vencimento = $params['financeiro_vencimento'];
				$financeiro_vencimentoOriginal = $params['financeiro_vencimentoOriginal'];  $financeiro_vencimentoOriginal = $params['financeiro_vencimentoOriginal'];
				$financeiro_valor = $params['financeiro_valor'];                            $financeiro_dataFim = $params['financeiro_dataFim'];
				$financeiro_valorFim = $params['financeiro_valorFim'];                      $financeiro_valorJuros = $params['financeiro_valorJuros'];
				$financeiro_valorDesconto = $params['financeiro_valorDesconto'];            $financeiro_impresso = $params['financeiro_impresso'];
				$financeiro_dias = $params['financeiro_dias'];                              $financeiro_referente = $params['financeiro_referente'];
				$financeiro_identificador = $params['financeiro_identificador'];            $financeiro_situacaoID = $params['financeiro_situacaoID'];
				$financeiro_obs = $params['financeiro_obs'];                                $financeiro_carencia = $params['financeiro_carencia'];
				$financeiro_comissaoPaga = $params['financeiro_comissaoPaga'];              $financeiro_valorEmpresa = $params['financeiro_valorEmpresa'];
				$financeiro_tipoPagamento = $params['financeiro_tipoPagamento'];            $financeiro_codBanco = $params['financeiro_codBanco'];
				$financeiro_agenciaBanco = $params['financeiro_agenciaBanco'];              $financeiro_contaBanco = $params['financeiro_contaBanco'];
				$financeiro_numTituloBanco = $params['financeiro_numTituloBanco'];          $financeiro_motivo = $params['financeiro_motivo'];
				$financeiro_valorSaldo = $params['financeiro_valorSaldo'];                  $financeiro_caixa = $params['financeiro_caixa'];
				$Documento = $params['Documento'];                                          $financeiro_lcLivroUsuarioLancamento = $params['financeiro_lcLivroUsuarioLancamento'];
				$financeiro_usuarioLancamento = $params['financeiro_usuarioLancamento'];    $financeiro_empresa = $params['financeiro_empresa'];
				$financeiro_pgtoParcial = $params['financeiro_pgtoParcial'];                $financeiro_grupo = $params['financeiro_grupo'];
				$INDENTIFICADOR = $params['INDENTIFICADOR'];                                $financeiro_lancamentoCaixa = $params['financeiro_lancamentoCaixa'];
				$financeiro_nossoNumero = $params['financeiro_nossoNumero'];                $financeiro_remessa = $params['financeiro_remessa'];
				$financeiro_dataRemessa = $params['financeiro_dataRemessa'];                $financeiro_descricaoBoleto = $params['financeiro_descricaoBoleto'];
				$financeiro_hora = $params['financeiro_hora'];                              $financeiro_tipo = $params['financeiro_tipo'];
				$financeiro_subgrupo = $params['financeiro_subgrupo'];                      $financeiro_tipoQuem = $params['financeiro_tipoQuem'];  
				$financeiro_lancamentoCC = $params['financeiro_lancamentoCC'];       
			
				//verifica se está alterando o status do cancelado!!!!
				$verificaCancelado = $params['verificaCancelado'];
				
				//usuario anterior 
				$usuarioAnterior = $params['usuarioAnterior'];
			
				$financeiro_valor = str_replace(".","",$financeiro_valor);
				$financeiro_valor = str_replace(",",".",$financeiro_valor);
				
			//	if($financeiro_valorFim != "" and $financeiro_valorFim > 0){
					$financeiro_valorFim = str_replace(".","",$financeiro_valorFim);
					$financeiro_valorFim = str_replace(",",".",$financeiro_valorFim);
			//	}else{
				//	$financeiro_valorFim = "";
			//	}

				$financeiro_valorJuros = str_replace(".","",$financeiro_valorJuros);
				$financeiro_valorJuros = str_replace(",",".",$financeiro_valorJuros);

				$financeiro_valorSaldo = str_replace(".","",$financeiro_valorSaldo);
				$financeiro_valorSaldo = str_replace(",",".",$financeiro_valorSaldo);

				$financeiro_pgtoParcial = str_replace(".","",$financeiro_pgtoParcial);
				$financeiro_pgtoParcial = str_replace(",",".",$financeiro_pgtoParcial);
				
				if($financeiro_emissao == ""){
					$financeiro_emissao = date('Y-m-d');
				}
				if($financeiro_vencimentoOriginal == ""){
					$financeiro_vencimentoOriginal = $financeiro_vencimento;
				}
				$i = 1;
				if($financeiro_totalParcela == ""){
					$financeiro_totalParcela = 1;
				}
				while($i <= $financeiro_totalParcela){
					
					//PARCELA
					$financeiro_parcela = $i;
					
					//SOMA DATA DE ACORDO COM NUMERO DE PARCELA
					if($i != 1){
						$financeiro_vencimento = date('Y-m-d', strtotime($financeiro_vencimento.' + 1 month'));
						$financeiro_vencimentoOriginal = date('Y-m-d', strtotime($financeiro_vencimentoOriginal.' + 1 month'));
					}
						
					
					//date('d/m/Y', strtotime('2016-01-31 + 1 months'));
					$pdo = MySQL::acessabd();	
											
					$stm = $pdo->prepare("
					update ".$params['_bd'].".financeiro set 					
					financeiro_registro = '$financeiro_registro',
					financeiro_codigoCliente = '$financeiro_codigoCliente',
					financeiro_nome = '$financeiro_nome',
					financeiro_portador = '$financeiro_portador',
					financeiro_documento = '$financeiro_documento',
					financeiro_historico = '$financeiro_historico',
					financeiro_emissao = '$financeiro_emissao',
					financeiro_vencimento = '$financeiro_vencimento',
					financeiro_vencimentoOriginal = '$financeiro_vencimentoOriginal',
					financeiro_valor = '$financeiro_valor',
					financeiro_dataFim = '$financeiro_dataFim',
					financeiro_valorFim = '$financeiro_valorFim',
					financeiro_valorJuros = '$financeiro_valorJuros',
					financeiro_valorDesconto = '$financeiro_valorDesconto',
					financeiro_impresso = '$financeiro_impresso',
					financeiro_dias = '$financeiro_dias',
					financeiro_referente = '$financeiro_referente',
					Documento = '$Documento',
					financeiro_situacaoID = '$financeiro_situacaoID',
					financeiro_obs = '$financeiro_obs',
					financeiro_carencia = '$financeiro_carencia',
					financeiro_comissaoPaga = '$financeiro_comissaoPaga',
					financeiro_valorEmpresa = '$financeiro_valorEmpresa',
					financeiro_tipoPagamento = '$financeiro_tipoPagamento',
					financeiro_agenciaBanco = '$financeiro_agenciaBanco',
					financeiro_contaBanco = '$financeiro_contaBanco',
					financeiro_numTituloBanco = '$financeiro_numTituloBanco',
					financeiro_motivo = '$financeiro_motivo',
					financeiro_valorSaldo = '$financeiro_valorSaldo',
					financeiro_caixa = '$financeiro_caixa',
					financeiro_identificador = '$financeiro_identificador',
					financeiro_lcLivroUsuarioLancamento = '$financeiro_lcLivroUsuarioLancamento',
					financeiro_usuarioLancamento = '$financeiro_usuarioLancamento',
					financeiro_empresa = '$financeiro_empresa',
					financeiro_pgtoParcial = '$financeiro_pgtoParcial',
					financeiro_grupo = '$financeiro_grupo',
					INDENTIFICADOR = '$INDENTIFICADOR',
					financeiro_lancamentoCaixa = '$financeiro_lancamentoCaixa',
					financeiro_nossoNumero = '$financeiro_nossoNumero',
					financeiro_remessa = '$financeiro_remessa',
					financeiro_dataRemessa = '$financeiro_dataRemessa',
					financeiro_descricaoBoleto = '$financeiro_descricaoBoleto',
					financeiro_hora = '$financeiro_hora',
					financeiro_tipo = '$financeiro_tipo',
					financeiro_subgrupo = '$financeiro_subgrupo',
					financeiro_tipoQuem = '$financeiro_tipoQuem',
					financeiro_lancamentoCC = '$financeiro_lancamentoCC'
					where financeiro_id = '$financeiro_id'
					
					");
					$stm->execute();

					
					//--------------------- EXCLUI REGISTRO DA O.S ------------------------------------------------------------------------------------------
					if($financeiro_situacaoID == 1) { 
				
							$stm = $pdo->prepare("
								delete FROM ".$params['_bd'].".pagamentos 
								where pgto_id = '$Documento' limit 1					
								");
								$stm->execute();

								if($financeiro_grupo == 1 and $financeiro_subgrupo == 1 ) { //OS
									$_motivo = 6;
								}

								if($financeiro_grupo == 1 and $financeiro_subgrupo == 2 ) { //vendas
									$_motivo = 5;
								}

								$stm = $pdo->prepare("delete FROM ".$params['_bd'].".livro_caixa 	where Livro_caixa_motivo = '$_motivo' and  Livro_Num_Docto = '$financeiro_documento'");
								$stm->execute();
					}
					//EXCLUIR ENTRADA VENDAS
					if($financeiro_identificador == 2) { 					
						$_sq = "UPDATE  ".$params['_bd'].".saidaestoque  SET
						Valor_Entrada = '0',Tipo_Pagamento_Entrada = '0'
						Where NUMERO = '$Documento' limit 1";
						$stm = $pdo->prepare("$_sq");
							$stm->execute();
				}
				


					
					
					//---------------------LANCAMENTO CONTA CORRENTE ------------------------------------------------------------------------------------------
					if($financeiro_valorFim != "" and $financeiro_valorFim > 0){
						$financeiro_valorFim = "-$financeiro_valorFim";
					}else{
						$financeiro_valorFim = "";
					}
		
					//--------------------------------- FIM LANCAMENTO CONTA CORRENTE-------------------------------------------------------------------------
					$i++;
				}
        }
        catch (\Exception $fault){
               $response = $fault;
        }
      //  echo $response;
    }
    
}