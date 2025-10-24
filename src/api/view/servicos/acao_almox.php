<?php

include("../../api/config/iconexao.php");   


$_idtecnico = $_parametros['idtecnico'];
$_idtecnicoOficina = $_parametros['idtecnicoOficina'];

$query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox  FROM usuario  where usuario_CODIGOUSUARIO = '$_idtecnico' ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while($pegar = mysqli_fetch_array($result)){
        $_almox = $pegar["usuario_almox"] ;  
        $_almoxsel =   $_almox;   
}

$query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox FROM usuario  where usuario_perfil2 = 9 and usuario_CODIGOUSUARIO = '$_idtecnicoOficina'");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while($pegar = mysqli_fetch_array($result)){
      
        if($_almox != "") {
                $_almoxoficina =  " or Codigo_Almox = '".$pegar["usuario_almox"] ."'";
                $_almoxsel =   $pegar["usuario_almox"];   
        }else{
                $_almoxoficina =  "";
                $_almox = $pegar["usuario_almox"] ;
        }
               
      
       
}
if($_SESSION['per226'] == '226') { //liberada todos almoxarifado seleção
        $querySit = ("SELECT * FROM almoxarifado  order by Descricao");
}else{
        $querySit = ("SELECT * FROM almoxarifado where Codigo_Almox = '$_almox'  $_almoxoficina order by Descricao");
}


$resultSit = mysqli_query($mysqli,$querySit)  or die(mysqli_error($mysqli));
while($pegar = mysqli_fetch_array($resultSit)){
            $codigoSit = $pegar["Codigo_Almox"];
            $descricaoSit =  $pegar["Descricao"];    ?>
            <option value="<?php echo "$codigoSit"; ?>" <?php if ($codigoSit ==  $_almoxsel) { ?>selected="selected" <?php } ?>> <?php echo "$descricaoSit"; ?></option>
    <?php
    }
    ?>