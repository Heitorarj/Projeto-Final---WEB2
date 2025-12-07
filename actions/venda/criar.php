<?php

require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/VendaController.php';

AuthController::requireLogin();
VendaController::criar();