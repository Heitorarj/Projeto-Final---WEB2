CREATE DATABASE db_loja CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_loja;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  tipo TINYINT NOT NULL DEFAULT 0, 
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL
);

CREATE TABLE fabricantes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150),
  site VARCHAR(255)
);

CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(200) NOT NULL,
  descricao TEXT,
  imagem VARCHAR(255),
  estoque INT DEFAULT 0,
  preco_custo DECIMAL(10,2) DEFAULT 0,
  preco_venda DECIMAL(10,2) NOT NULL,
  fabricante_id INT,
  categoria_id INT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (fabricante_id) REFERENCES fabricantes(id) ON DELETE SET NULL,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE vendas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
  valor_total DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE itens_venda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venda_id INT NOT NULL,
  produto_id INT NOT NULL,
  quantidade INT NOT NULL,
  preco_unit DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (venda_id) REFERENCES vendas(id) ON DELETE CASCADE,
  FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE caracteristicas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produto_id INT NOT NULL,
  nome VARCHAR(100),
  valor VARCHAR(255),
  FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);


-- Inserir categorias
INSERT INTO categorias (nome) VALUES
('Camisetas Básicas'),
('Camisas Polo'),
('Camisas Social'),
('Regatas'),
('Moletom'),
('Camisas Esportivas');

-- Inserir fabricantes
INSERT INTO fabricantes (nome, site) VALUES
('Nike', 'https://www.nike.com'),
('Adidas', 'https://www.adidas.com'),
('Lacoste', 'https://www.lacoste.com'),
('Ralph Lauren', 'https://www.ralphlauren.com'),
('Hering', 'https://www.hering.com.br'),
('Reserva', 'https://www.usereserva.com'),
('Oakley', 'https://www.oakley.com'),
('Under Armour', 'https://www.underarmour.com');

-- Inserir produtos
INSERT INTO produtos (nome, descricao, imagem, estoque, preco_custo, preco_venda, fabricante_id, categoria_id) VALUES
('Camiseta Nike Dry Fit', 'Camiseta esportiva com tecnologia Dry Fit, ideal para atividades físicas','https://external-preview.redd.it/finally-finished-a-frog-fighter-i-sketched-over-a-year-ago-v0-OO9EXShGBxkBQ09BAmYy-j4zJTXHQMcNR7hy6MLIPks.jpg?auto=webp&s=73839b0a7655b0f857302468c0fa5c21f5ae3f42', 50, 29.90, 89.90, 1, 6),
('Polo Lacoste Classic', 'Camisa polo clássica com detalhe do crocodilo, 100% algodão','https://external-preview.redd.it/finally-finished-a-frog-fighter-i-sketched-over-a-year-ago-v0-OO9EXShGBxkBQ09BAmYy-j4zJTXHQMcNR7hy6MLIPks.jpg?auto=webp&s=73839b0a7655b0f857302468c0fa5c21f5ae3f42', 30, 89.90, 299.90, 3, 2),
('Camisa Social Ralph Lauren', 'Camisa social slim fit, tecido oxford, ideal para trabalho','https://external-preview.redd.it/finally-finished-a-frog-fighter-i-sketched-over-a-year-ago-v0-OO9EXShGBxkBQ09BAmYy-j4zJTXHQMcNR7hy6MLIPks.jpg?auto=webp&s=73839b0a7655b0f857302468c0fa5c21f5ae3f42', 25, 129.90, 399.90, 4, 3),
('Regata Adidas Training', 'Regata esportiva para treinos, tecido respirável','https://external-preview.redd.it/finally-finished-a-frog-fighter-i-sketched-over-a-year-ago-v0-OO9EXShGBxkBQ09BAmYy-j4zJTXHQMcNR7hy6MLIPks.jpg?auto=webp&s=73839b0a7655b0f857302468c0fa5c21f5ae3f42', 40, 19.90, 69.90, 2, 4),
('Moletom Nike Club', 'Moletom fleece, corte regular, quente e confortável','https://external-preview.redd.it/finally-finished-a-frog-fighter-i-sketched-over-a-year-ago-v0-OO9EXShGBxkBQ09BAmYy-j4zJTXHQMcNR7hy6MLIPks.jpg?auto=webp&s=73839b0a7655b0f857302468c0fa5c21f5ae3f42', 35, 49.90, 159.90, 1, 5),
('Camiseta Hering Básica', 'Camiseta básica 100% algodão, várias cores disponíveis','https://external-preview.redd.it/finally-finished-a-frog-fighter-i-sketched-over-a-year-ago-v0-OO9EXShGBxkBQ09BAmYy-j4zJTXHQMcNR7hy6MLIPks.jpg?auto=webp&s=73839b0a7655b0f857302468c0fa5c21f5ae3f42', 100, 12.90, 39.90, 5, 1),
('Polo Reserva Logo', 'Camisa polo com logo bordado, malha piquet premium','https://external-preview.redd.it/finally-finished-a-frog-fighter-i-sketched-over-a-year-ago-v0-OO9EXShGBxkBQ09BAmYy-j4zJTXHQMcNR7hy6MLIPks.jpg?auto=webp&s=73839b0a7655b0f857302468c0fa5c21f5ae3f42', 45, 59.90, 199.90, 6, 2),
('Camisa Oakley Performance', 'Camisa esportiva com proteção UV, ideal para corrida','https://external-preview.redd.it/finally-finished-a-frog-fighter-i-sketched-over-a-year-ago-v0-OO9EXShGBxkBQ09BAmYy-j4zJTXHQMcNR7hy6MLIPks.jpg?auto=webp&s=73839b0a7655b0f857302468c0fa5c21f5ae3f42', 28, 39.90, 129.90, 7, 6);

-- Inserir características dos produtos
INSERT INTO caracteristicas (produto_id, nome, valor) VALUES
(1, 'Material', 'Poliéster'),
(1, 'Tecnologia', 'Dry Fit'),
(1, 'Gênero', 'Unissex'),
(1, 'Manga', 'Curta'),
(2, 'Material', '100% Algodão'),
(2, 'Gola', 'Polo'),
(2, 'Estampa', 'Crocodilo bordado'),
(2, 'Origem', 'Importada'),
(3, 'Material', 'Oxford 100% Algodão'),
(3, 'Corte', 'Slim Fit'),
(3, 'Gola', 'Social'),
(3, 'Ocasião', 'Trabalho/Formal'),
(4, 'Material', 'Poliéster'),
(4, 'Uso', 'Esportivo'),
(4, 'Manga', 'Regata'),
(4, 'Respirabilidade', 'Alta'),
(5, 'Material', 'Fleece'),
(5, 'Tipo', 'Moletom'),
(5, 'Capuz', 'Sim'),
(5, 'Bolso', 'Canguru'),
(6, 'Material', '100% Algodão'),
(6, 'Tipo', 'Básica'),
(6, 'Corte', 'Regular'),
(6, 'Variedade', 'Multi-cores');

-- Inserir vendas
INSERT INTO vendas (usuario_id, data_venda, valor_total) VALUES
(2, '2024-01-15 10:30:00', 689.70),
(3, '2024-01-16 14:45:00', 499.80),
(2, '2024-01-17 16:20:00', 239.80),
(4, '2024-01-18 11:15:00', 129.90);

-- Inserir itens das vendas
INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unit) VALUES
-- Venda 1
(1, 2, 1, 299.90),  -- 1 Polo Lacoste
(1, 1, 2, 89.90),   -- 2 Camisetas Nike
(1, 6, 3, 39.90),   -- 3 Camisetas Hering
-- Venda 2
(2, 3, 1, 399.90),  -- 1 Camisa Social
(2, 7, 1, 199.90),  -- 1 Polo Reserva
-- Venda 3
(3, 1, 1, 89.90),   -- 1 Camiseta Nike
(3, 4, 1, 69.90),   -- 1 Regata Adidas
(3, 6, 2, 39.90),   -- 2 Camisetas Hering
-- Venda 4
(4, 8, 1, 129.90);  -- 1 Camisa Oakley

-- Atualizar estoque após vendas
UPDATE produtos SET estoque = estoque - 2 WHERE id = 1;  -- Vendidas 2 unidades
UPDATE produtos SET estoque = estoque - 1 WHERE id = 2;  -- Vendida 1 unidade
UPDATE produtos SET estoque = estoque - 1 WHERE id = 3;  -- Vendida 1 unidade
UPDATE produtos SET estoque = estoque - 1 WHERE id = 4;  -- Vendida 1 unidade
UPDATE produtos SET estoque = estoque - 5 WHERE id = 6;  -- Vendidas 5 unidades (3+2)
UPDATE produtos SET estoque = estoque - 1 WHERE id = 7;  -- Vendida 1 unidade
UPDATE produtos SET estoque = estoque - 1 WHERE id = 8;  -- Vendida 1 unidade

-- Inserir mais produtos para garantir variedade
INSERT INTO produtos (nome, descricao, estoque, preco_custo, preco_venda, fabricante_id, categoria_id) VALUES
('Camiseta Under Armour HeatGear', 'Camiseta com tecnologia HeatGear para atividades intensas', 60, 34.90, 119.90, 8, 6),
('Polo Adidas Originals', 'Polo clássico com as três listras, estilo casual', 42, 45.90, 149.90, 2, 2),
('Camisa Social Slim Fit', 'Camisa social moderna, corte slim, várias cores', 38, 79.90, 249.90, 6, 3);

-- Inserir características para os novos produtos
INSERT INTO caracteristicas (produto_id, nome, valor) VALUES
(9, 'Tecnologia', 'HeatGear'),
(9, 'Proteção', 'UV 30+'),
(9, 'Material', 'Poliéster elastano'),
(10, 'Estilo', 'Casual'),
(10, 'Material', 'Algodão piquet'),
(10, 'Detalhe', 'Listras laterais'),
(11, 'Corte', 'Slim Fit'),
(11, 'Material', 'Popeline'),
(11, 'Ocasião', 'Escritório');

-- Mostrar resumo do que foi inserido
SELECT 
    (SELECT COUNT(*) FROM usuarios) AS total_usuarios,
    (SELECT COUNT(*) FROM categorias) AS total_categorias,
    (SELECT COUNT(*) FROM fabricantes) AS total_fabricantes,
    (SELECT COUNT(*) FROM produtos) AS total_produtos,
    (SELECT COUNT(*) FROM vendas) AS total_vendas,
    (SELECT SUM(valor_total) FROM vendas) AS faturamento_total,
    (SELECT SUM(estoque) FROM produtos) AS estoque_total;
    