<?php

require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/VendaController.php';

AuthController::requireLogin();

$produto_id = $_POST['produto_id'] ?? 0;
$quantidade = $_POST['quantidade'] ?? 1;

VendaController::atualizarQuantidadeCarrinho((int)$produto_id, (int)$quantidade);

header('Location: ../../views/Cliente/clienteCarrinho.php');
exit();