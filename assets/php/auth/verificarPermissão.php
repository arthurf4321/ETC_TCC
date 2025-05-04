<?php 
session_start();

function verificarAcesso($cargosPermitidos) {
    if (!isset($_SESSION['funcionario_cargo']) || !in_array($_SESSION["funcionario_cargo"], $cargosPermitidos)) {
        header("Location: ../pages/pagSemPermissao.php");
        exit;
    }
}
?>