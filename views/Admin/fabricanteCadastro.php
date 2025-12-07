<?php
    include "headerAdmin.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Fabricante</title>

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

        <h3 class="text-center mb-4">Cadastrar Fabricante</h3>

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

        <form action="../../actions/fabricante/salvarFabricante.php" method="POST">

            <label class="fw-bold">Nome do Fabricante:</label>
            <input type="text" name="nome" class="form-control mb-3" required>

            <button type="submit" class="btn btn-success w-100">Registrar Fabricante</button>
        </form>

    </div>
</div>

</body>
</html>
