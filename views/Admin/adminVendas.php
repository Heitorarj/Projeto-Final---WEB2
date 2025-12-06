<?php

    include "headerAdmin.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Vendas Realizadas - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f4f4f4; padding:40px; }
        .filtro-box { background:white; padding:20px; border-radius:10px; }
    </style>
</head>

<body>

<div class="container mt-4">
    
    <h1 class="fw-bold mb-4">Vendas Realizadas</h1>

    <!-- Filtro por data -->
    <div class="filtro-box shadow mb-4">
        <h5 class="fw-bold">Filtrar por Período</h5>

        <div class="row mt-3">
            <div class="col-md-4">
                <label class="fw-bold">Data Inicial</label>
                <input type="date" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="fw-bold">Data Final</label>
                <input type="date" class="form-control">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-dark w-100 fw-bold">Filtrar</button>
            </div>
        </div>
    </div>

    <!-- Tabela de vendas -->
    <div class="table-responsive shadow">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID da Compra</th>
                    <th>Data</th>
                    <th>Valor Total</th>
                    <th>ID do Cliente</th>
                </tr>
            </thead>

            <tbody>

                <!-- Exemplo -->
                <tr>
                    <td>1023</td>
                    <td>15/02/2025</td>
                    <td>R$ 399,80</td>
                    <td>48</td>
                </tr>

                <!-- Copiar linhas para mais vendas -->

            </tbody>
        </table>
    </div>

</div>

</body>
</html>
