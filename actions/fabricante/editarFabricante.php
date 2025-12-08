<?php

require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/FabricanteController.php';

AuthController::requireAdmin();
FabricanteController::atualizar();
