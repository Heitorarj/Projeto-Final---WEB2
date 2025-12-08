<?php
    include "headerAdmin.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Categoria</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6f9; }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            margin-top: 120px;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="col-md-6 offset-md-3 form-box">

        <h3 class="text-center mb-4">Cadastrar Categoria</h3>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
            </div>
        <?php endif; ?>

        <form action="../../actions/categoria/salvarCategoria.php" method="POST">

            <label class="fw-bold">Nome da Categoria:</label>
            <input type="text" name="nome" class="form-control mb-3" required>

            <button type="submit" name="acao" value="cadastrar" class="btn btn-primary w-100 mb-2">
                Registrar Categoria
            </button>

        </form>

        <a href="listarCategorias.php" class="btn btn-secondary w-100 mt-3">
            Voltar para Lista
        </a>

    </div>
</div>

</body>
</html>
