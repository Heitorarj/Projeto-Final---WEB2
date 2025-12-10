<?php
session_start();
include "headerAdmin.php";

require_once __DIR__ . '/../../controllers/VendaController.php';

$dataInicial = $_GET['data_inicial'] ?? null;
$dataFinal   = $_GET['data_final'] ?? null;

if (($dataInicial && !$dataFinal) || (!$dataInicial && $dataFinal)) {
    $_SESSION['erro'] = "Informe ambas as datas para filtrar";
    $dataInicial = $dataFinal = null;
}

$vendas = VendaController::listar($dataInicial, $dataFinal);
$estatisticas = VendaController::obterEstatisticas($dataInicial, $dataFinal);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Vendas Realizadas - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f4f4;
            padding: 40px;
        }

        .filtro-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2rem;
            color: #0a0a23;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container mt-4">

        <h1 class="fw-bold mb-4">Vendas Realizadas</h1>

        <!-- Filtro de datas -->
        <div class="filtro-box shadow mb-4">
            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Data Inicial:</label>
                    <input type="date" name="data_inicial"
                        value="<?php echo $dataInicial; ?>"
                        class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Data Final:</label>
                    <input type="date" name="data_final"
                        value="<?php echo $dataFinal; ?>"
                        class="form-control">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Filtrar</button>
                </div>

            </form>
        </div>

        <!-- Estatísticas -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card shadow">
                    <p class="text-muted mb-2">Total de Vendas</p>
                    <h3><?php echo $estatisticas['total_vendas'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card shadow">
                    <p class="text-muted mb-2">Valor Total</p>
                    <h3>R$ <?php echo number_format($estatisticas['valor_total'] ?? 0, 2, ',', '.'); ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card shadow">
                    <p class="text-muted mb-2">Ticket Médio</p>
                    <h3>R$ <?php echo number_format($estatisticas['ticket_medio'] ?? 0, 2, ',', '.'); ?></h3>
                </div>
            </div>
        </div>

        <!-- Tabela de vendas -->
        <?php if (empty($vendas)): ?>
            <div class="alert alert-info">
                Nenhuma venda encontrada para o período selecionado.
            </div>
        <?php else: ?>
            <div class="table-responsive shadow">
                <table class="table table-striped table-bordered bg-white">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Valor Total</th>
                            <th>Itens</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($vendas as $venda): ?>
                            <tr>
                                <td><?php echo $venda['id']; ?></td>
                                <td><?php echo htmlspecialchars($venda['usuario_nome']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($venda['data_venda'])); ?></td>
                                <td>R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></td>
                                <td>
                                    <?php if (!empty($venda['itens'])): ?>
                                        <ul class="mb-0">
                                            <?php foreach ($venda['itens'] as $item): ?>
                                                <li>
                                                    <?php echo htmlspecialchars($item['produto_nome']); ?>
                                                    (<?php echo $item['quantidade']; ?>x)
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>