<?php

require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/ProdutoController.php';

AuthController::requireAdmin();
ProdutoController::criar();