<?php

require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/ProdutoController.php';

AuthController::requireAdmin();

$id = $_GET['id'] ?? 0;
ProdutoController::deletar((int)$id);