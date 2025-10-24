<?php
include('conecta.php');
if($_GET['code'] <> ''){
    include('auth.php');
} else {
    include('refresh.php');
}
?>