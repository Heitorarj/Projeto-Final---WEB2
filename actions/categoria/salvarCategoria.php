<?php

require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/CategoriaController.php';

AuthController::requireAdmin();
CategoriaController::criar();
