<?php

session_start();
require_once __DIR__ . '/../controllers/VendaController.php';

$data_inicial = $_GET['data_inicial'] ?? null;
$data_final = $_GET['data_final'] ?? null;

if ($data_inicial && $data_final && strtotime($data_inicial) > strtotime($data_final)) {
    $_SESSION['erro'] = "Data inicial não pode ser maior que data final";
    header('Location: ../../views/Admin/adminVendas.php');
    exit();
}

$queryParams = [];
if ($data_inicial) $queryParams['data_inicial'] = $data_inicial;
if ($data_final) $queryParams['data_final'] = $data_final;

$queryString = http_build_query($queryParams);
header("Location: ../../views/Admin/adminVendas.php?$queryString");
exit();
