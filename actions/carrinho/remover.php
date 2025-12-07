<?php

require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/VendaController.php';

AuthController::requireLogin();

$produto_id = $_GET['id'] ?? 0;

VendaController::removerDoCarrinho((int)$produto_id);

header('Location: ../../views/Cliente/clienteCarrinho.php');
exit();