<?php
require_once __DIR__ . '/../../controllers/AuthController.php';

AuthController::requireAdmin();
$usuario = AuthController::getUsuarioLogado();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<nav class="navbar navbar-expand-lg fixed-top" style="background:#0a0a23;">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold text-light" href="adminDashboard.php">
            FUT STORE
        </a>

        <a href="adminDashboard.php" class="ms-2">
            <img src="https://img.icons8.com/?size=100&id=2797&format=png&color=ffffff"
                 alt="Início" style="width:28px; height:28px;">
        </a>

        <div id="navbarNav" class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <form method="POST" action="produtosCadastrados.php" class="m-0 p-0">
                        <button type="submit" class="nav-link btn btn-link text-light">Produtos Cadastrados</button>
                    </form>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link text-light" href="adminVendas.php">
                        Vendas
                    </a>
                </li>

                <li class="nav-item">
                    <span class="nav-link text-light">Olá, <?php echo htmlspecialchars($usuario['nome']); ?></span>
                </li>

                <li class="nav-item">
                    <a href="../../actions/auth/logout.php" class="nav-link text-light">Sair</a>
                </li>

            </ul>
        </div>

    </div>
</nav>
