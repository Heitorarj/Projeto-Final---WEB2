<?php
session_start();
include "headerAdmin.php";

require_once __DIR__ . '/../../controllers/ProdutoController.php';
require_once __DIR__ . '/../../controllers/CategoriaController.php';
require_once __DIR__ . '/../../controllers/FabricanteController.php';

// Buscar o produto pelo ID
$id = $_GET['id'] ?? 0;
$produto = ProdutoController::buscarPorId((int)$id);

// Se o produto não existir, redirecionar
if (!$produto) {
    $_SESSION['erro'] = "Produto não encontrado!";
    header('Location: produtosCadastrados.php');
    exit();
}

// Buscar categorias e fabricantes para os selects
$categorias = CategoriaController::listar();
$fabricantes = FabricanteController::listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Produto - Admin</title>

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

        .current-img {
            max-width: 200px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold">Editar Produto</h1>

            <div>
                <a href="produtosCadastrados.php" class="btn btn-secondary fw-bold px-4 me-2">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
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

            <form method="POST" action="../../actions/produto/atualizar.php" id="formProduto">
                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

                <!-- Informações Básicas do Produto -->
                <div class="mb-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">
                        <i class="bi bi-info-circle"></i> Informações Básicas
                    </h5>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="fw-bold">Nome do Produto *</label>
                            <input type="text" name="nome" class="form-control mb-3"
                                value="<?php echo htmlspecialchars($produto['nome']); ?>"
                                placeholder="Ex: Camisa Flamengo 2024" required>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">Estoque</label>
                            <input type="number" name="estoque" class="form-control mb-3"
                                value="<?php echo $produto['estoque']; ?>" min="0">
                        </div>
                    </div>

                    <label class="fw-bold">Descrição</label>
                    <textarea name="descricao" class="form-control mb-3" rows="3"
                        placeholder="Descrição detalhada do produto"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="fw-bold">Preço de Custo</label>
                            <input type="number" name="preco_custo" step="0.01"
                                class="form-control mb-3"
                                value="<?php echo $produto['preco_custo']; ?>"
                                placeholder="Ex: 100.00">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Preço de Venda *</label>
                            <input type="number" name="preco_venda" step="0.01"
                                class="form-control mb-3"
                                value="<?php echo $produto['preco_venda']; ?>"
                                placeholder="Ex: 199.90" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="fw-bold">Categoria *</label>
                            <select name="categoria_id" class="form-select mb-3" required>
                                <option value="">Selecione uma categoria</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo $categoria['id']; ?>"
                                        <?php echo $produto['categoria_id'] == $categoria['id'] ? 'selected' : ''; ?>>
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
                                    <option value="<?php echo $fabricante['id']; ?>"
                                        <?php echo $produto['fabricante_id'] == $fabricante['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($fabricante['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <label class="fw-bold">Link da Imagem do Produto</label>
                    <input type="url" name="imagem" class="form-control mb-3"
                        value="<?php echo htmlspecialchars($produto['imagem']); ?>"
                        placeholder="https://exemplo.com/imagem.jpg">
                    <small class="text-muted d-block mb-3">Cole o link (URL) da imagem do produto</small>

                    <?php if (!empty($produto['imagem'])): ?>
                        <div class="mb-3">
                            <p class="fw-bold mb-2">Imagem atual:</p>
                            <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                class="current-img"
                                alt="Imagem atual do produto"
                                onerror="this.style.display='none'">
                        </div>
                    <?php endif; ?>
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
                        <?php if (!empty($produto['caracteristicas'])): ?>
                            <?php foreach ($produto['caracteristicas'] as $index => $caracteristica): ?>
                                <div class="caracteristica-item" data-index="<?php echo $index; ?>">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="fw-bold">Nome da Característica</label>
                                            <input type="text" name="caracteristicas[<?php echo $index; ?>][nome]"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($caracteristica['nome']); ?>"
                                                placeholder="Ex: Material" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="fw-bold">Valor</label>
                                            <input type="text" name="caracteristicas[<?php echo $index; ?>][valor]"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($caracteristica['valor']); ?>"
                                                placeholder="Ex: Poliéster" required>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm w-100 btnRemoverCaracteristica">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Característica inicial se não houver características -->
                            <div class="caracteristica-item" data-index="0">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="fw-bold">Nome da Característica</label>
                                        <input type="text" name="caracteristicas[0][nome]"
                                            class="form-control" placeholder="Ex: Material" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="fw-bold">Valor</label>
                                        <input type="text" name="caracteristicas[0][valor]"
                                            class="form-control" placeholder="Ex: Poliéster" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm w-100 btnRemoverCaracteristica">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> Exemplo: Material → Poliéster, Tamanho → G, Cor → Vermelho
                    </small>
                </div>

                <!-- Botões de Ação -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold">
                        <i class="bi bi-save"></i> Atualizar Produto
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

            // Calcular o próximo índice baseado nas características existentes
            let caracteristicaCount = container.children.length;

            // Se não houver características, começa em 1 (já tem uma)
            if (caracteristicaCount === 0) {
                caracteristicaCount = 1;
            }

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

            // Inicializar índices corretamente
            atualizarIndices();
        });
    </script>

</body>

</html>