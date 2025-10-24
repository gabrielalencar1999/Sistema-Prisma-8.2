	<?php
	use Functions\Financeiro;

	if($variable != ""){	
			$var= array(
				'_var' =>$variable    
			);
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros2 =  array_merge($var, $_bd);
			
			$_retorno = Financeiro::select_categoria($_parametros2);
			$_retorno2 = Financeiro::select_subCategoria($_parametros2);
			
			
			foreach($_retorno as $value){
				$icon = $value->icon_categoria;
				$cor = $value->cor_categoria;
				$titulo = $value->descricao_categoria;
				
				if($value->tipo_categoria == '0'){
					$descTipo = "Receita";
				}else{
					if($value->tipo_categoria == '1'){
						$descTipo = "Despesa";
					}else{
						$descTipo = "Ambos";
					}
				}
			}

			
	}
	?>
	<div class="row">
		<div class="col-xs-3" align="center">
			<div class="bar-widget">
				<div class="iconbox" style="margin-right:0px; background-color:<?=$cor;?>;">
					<i class="<?=$icon;?>"></i>
				</div>
			</div>
			<p class="categoria_title"><b style="font-size:14px;"><?=$titulo;?></b><br><?=$descTipo;?></p>
		</div>
		<div class="col-xs-6">
			<select class="form-control" id="subcategoria" name="subcategoria" onchange="selecionarSubcategoria2(this.value)">
				<option value="">Nenhum</option>
				<?php
					if(count($_retorno2) > 0){
						foreach($_retorno2 as $value2){?>
							<option <?php if($value2->id_subcategoria == $financeiro_subgrupo){ echo'selected="selected"';}?> value="<?=$value2->id_subcategoria;?>"><?=$value2->descricao_subcategoria;?></option>
						<?php
						}
					}
				?>
			</select>
		</div>
		<div class="col-xs-3">
			<button type="button" class="btn btn-default btn-block" onclick="volt()" style="height: 37px;">Voltar</button>
		</div>
	</div>

	