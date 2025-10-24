<?php

include("../../api/config/iconexao.php");   


$_idtecnico = $_parametros['idtecnico'];
$_idtecnicoOficina = $_parametros['idtecnicoOficina'];


$query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO 
FROM usuario  where usuario_CODIGOUSUARIO = '$_idtecnico' or  usuario_CODIGOUSUARIO = '$_idtecnicoOficina'");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while($pegar = mysqli_fetch_array($result)){
      
        if($_idtecnicoOficina != "") {              
                $_sel =   $pegar["usuario_CODIGOUSUARIO"];   
        }else{
            
                $_sel =   $pegar["usuario_CODIGOUSUARIO"] ;
        }
        $codigoSit = $pegar["usuario_CODIGOUSUARIO"];
        $descricaoSit =  $pegar["usuario_APELIDO"];    ?>
        <option value="<?php echo "$codigoSit"; ?>" <?php if ($codigoSit ==  $_sel) { ?>selected="selected" <?php } ?>> <?php echo "$descricaoSit"; ?></option>
 <?php
               
      
       
}


    ?>