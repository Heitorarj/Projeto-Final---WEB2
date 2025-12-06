<?php
session_start();
include "headerCliente.php";

?>

<!-- minhasCompras.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minhas Compras - FUT STORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f4f4;
            padding-top: 70px;
        }
        nav {
            background: #0a0a23 !important;
        }
        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: bold;
        }
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
        }
    </style>
</head>

<body>

<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Minhas Compras</h2>

    <div class="table-card shadow">

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID da Compra</th>
                    <th>Data</th>
                    <th>Valor Total</th>
                </tr>
            </thead>

            <tbody>
                <!-- EXEMPLOS VISUAIS APENAS -->
                <tr>
                    <td>#1023</td>
                    <td>03/12/2025</td>
                    <td>R$ 879,70</td>
                </tr>

                <tr>
                    <td>#1001</td>
                    <td>15/11/2025</td>
                    <td>R$ 299,90</td>
                </tr>

                <tr>
                    <td>#998</td>
                    <td>09/10/2025</td>
                    <td>R$ 559,80</td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

</body>
</html>
