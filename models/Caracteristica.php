<?php
require_once __DIR__ . '/../interfaces/iDao.php';
class Caracteristica implements iDao
{
    private int $id;
    private string $nome;
    private string $valor;


    /**
     * Getters
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getValor(): string
    {
        return $this->valor;
    }

    /**
     * Setters
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setValor(string $valor): void
    {
        $this->valor = $valor;
    }

    /**
     * Database Connection
     */

    private static function getPDO(): PDO
    {
        return Database::getInstance();
    }

    /**
     * Dao Methods
     */

    public static function create(array $data): int
    {
        // Validações
        if (empty($data['nome']) || empty($data['valor'])) {
            throw new Exception("Nome e valor são obrigatórios");
        }

        $sql = "INSERT INTO caracteristicas (nome, valor) 
                VALUES (:nome, :valor)";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nome' => $data['nome'],
                ':valor' => $data['valor']
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar característica: " . $e->getMessage());
        }
    }

    public static function read(int $id): ?array
    {
        $sql = "SELECT id, nome, valor 
                FROM caracteristicas 
                WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar característica: " . $e->getMessage());
        }
    }

    public static function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['nome'])) {
            $fields[] = "nome = :nome";
            $params[':nome'] = $data['nome'];
        }

        if (isset($data['valor'])) {
            $fields[] = "valor = :valor";
            $params[':valor'] = $data['valor'];
        }

        if (empty($fields)) {
            return false; // Nada para atualizar
        }

        $sql = "UPDATE caracteristicas SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar característica: " . $e->getMessage());
        }
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM caracteristicas WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar característica: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT id, nome, valor 
                FROM caracteristicas 
                ORDER BY nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar todas as características: " . $e->getMessage());
        }
    }

    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM caracteristicas";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar características: " . $e->getMessage());
        }
    }

    /**
     * Additional Methods
     */

    public static function findByNome(string $nome): ?array
    {
        $sql = "SELECT id, nome, valor 
                FROM caracteristicas 
                WHERE nome = :nome 
                LIMIT 1";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => $nome]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar característica por nome: " . $e->getMessage());
        }
    }

    public static function findByValor(string $valor): array
    {
        $sql = "SELECT id, nome, valor 
                FROM caracteristicas 
                WHERE valor LIKE :valor 
                ORDER BY nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':valor' => "%$valor%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar características por valor: " . $e->getMessage());
        }
    }
}
