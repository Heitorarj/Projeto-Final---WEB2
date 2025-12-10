<?php
session_start();
include "headerAdmin.php";

require_once __DIR__ . '/../../controllers/CategoriaController.php';
require_once __DIR__ . '/../../controllers/FabricanteController.php';

$categorias = CategoriaController::listar();
$fabricantes = FabricanteController::listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #f4f4f4;
            padding: 40px;
        }

        .card-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
        }

        .caracteristica-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #0d6efd;
        }

        .caracteristica-item:last-child {
            margin-bottom: 0;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold">Cadastro de Produto</h1>

            <!-- BOTÃO DE LISTAR PRODUTOS -->
            <a href="produtosCadastrados.php" class="btn btn-primary fw-bold px-4">
                <i class="bi bi-list-ul"></i> Ver Produtos Cadastrados
            </a>
        </div>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['sucesso'];
                unset($_SESSION['sucesso']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $_SESSION['erro'];
                unset($_SESSION['erro']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card-form shadow">

            <form method="POST" action="../../actions/produto/criar.php" id="formProduto">

                <!-- Informações Básicas do Produto -->
                <div class="mb-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">
                        <i class="bi bi-info-circle"></i> Informações Básicas
                    </h5>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="fw-bold">Nome do Produto *</label>
                            <input type="text" name="nome" class="form-control mb-3"
                                placeholder="Ex: Camisa Flamengo 2024" required>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">Estoque Inicial</label>
                            <input type="number" name="estoque" class="form-control mb-3"
                                value="0" min="0">
                        </div>
                    </div>

                    <label class="fw-bold">Descrição</label>
                    <textarea name="descricao" class="form-control mb-3" rows="3"
                        placeholder="Descrição detalhada do produto"></textarea>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="fw-bold">Preço de Custo</label>
                            <input type="number" name="preco_custo" step="0.01"
                                class="form-control mb-3" placeholder="Ex: 100.00">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Preço de Venda *</label>
                            <input type="number" name="preco_venda" step="0.01"
                                class="form-control mb-3" placeholder="Ex: 199.90" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="fw-bold">Categoria *</label>
                            <select name="categoria_id" class="form-select mb-3" required>
                                <option value="">Selecione uma categoria</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo $categoria['id']; ?>">
                                        <?php echo htmlspecialchars($categoria['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Fabricante *</label>
                            <select name="fabricante_id" class="form-select mb-3" required>
                                <option value="">Selecione um fabricante</option>
                                <?php foreach ($fabricantes as $fabricante): ?>
                                    <option value="<?php echo $fabricante['id']; ?>">
                                        <?php echo htmlspecialchars($fabricante['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <label class="fw-bold">Link da Imagem do Produto</label>
                    <input type="url" name="imagem" class="form-control mb-3"
                        placeholder="https://exemplo.com/imagem.jpg">
                    <small class="text-muted d-block mb-3">Cole o link (URL) da imagem do produto</small>
                </div>

                <!-- Características do Produto -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold border-bottom pb-2 mb-0">
                            <i class="bi bi-tags"></i> Características
                        </h5>
                        <button type="button" id="btnAdicionarCaracteristica" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Adicionar Característica
                        </button>
                    </div>

                    <div id="caracteristicasContainer">
                        <!-- Característica 1 -->
                        <div class="caracteristica-item" data-index="0">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="fw-bold">Nome da Característica</label>
                                    <input type="text" name="caracteristicas[0][nome]"
                                        class="form-control" placeholder="Ex: Material">
                                </div>
                                <div class="col-md-5">
                                    <label class="fw-bold">Valor</label>
                                    <input type="text" name="caracteristicas[0][valor]"
                                        class="form-control" placeholder="Ex: Poliéster">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm w-100 btnRemoverCaracteristica">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> Exemplo: Material → Poliéster, Tamanho → G, Cor → Vermelho
                    </small>
                </div>

                <!-- Botões de Ação -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold">
                        <i class="bi bi-save"></i> Cadastrar Produto
                    </button>
                    <a href="produtosCadastrados.php" class="btn btn-outline-secondary w-50">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>

        </div>
    </div>

    <!-- JavaScript para gerenciar características dinâmicas -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('caracteristicasContainer');
            const btnAdicionar = document.getElementById('btnAdicionarCaracteristica');
            let caracteristicaCount = 1; // Começa em 1 porque já temos a primeira (índice 0)

            // Função para adicionar nova característica
            btnAdicionar.addEventListener('click', function() {
                const template = `
            <div class="caracteristica-item" data-index="${caracteristicaCount}">
                <div class="row">
                    <div class="col-md-5">
                        <label class="fw-bold">Nome da Característica</label>
                        <input type="text" name="caracteristicas[${caracteristicaCount}][nome]" 
                               class="form-control" placeholder="Ex: Tamanho" required>
                    </div>
                    <div class="col-md-5">
                        <label class="fw-bold">Valor</label>
                        <input type="text" name="caracteristicas[${caracteristicaCount}][valor]" 
                               class="form-control" placeholder="Ex: G" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm w-100 btnRemoverCaracteristica">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

                container.insertAdjacentHTML('beforeend', template);
                caracteristicaCount++;
            });

            // Delegar evento para remover características
            container.addEventListener('click', function(e) {
                if (e.target.closest('.btnRemoverCaracteristica')) {
                    const item = e.target.closest('.caracteristica-item');
                    // Só remove se não for o único item
                    if (container.children.length > 1) {
                        item.remove();
                        // Atualizar índices dos inputs restantes
                        atualizarIndices();
                    }
                }
            });

            // Função para atualizar índices após remover uma característica
            function atualizarIndices() {
                const items = container.querySelectorAll('.caracteristica-item');
                caracteristicaCount = 0;

                items.forEach((item, index) => {
                    item.setAttribute('data-index', index);

                    // Atualizar nomes dos inputs
                    const nomeInput = item.querySelector('input[name*="[nome]"]');
                    const valorInput = item.querySelector('input[name*="[valor]"]');

                    nomeInput.name = `caracteristicas[${index}][nome]`;
                    valorInput.name = `caracteristicas[${index}][valor]`;

                    caracteristicaCount++;
                });
            }
        });
    </script>

</body>

</html>