<?php
require_once __DIR__ . '/../interfaces/iDao.php';

class Usuario implements iDao
{
    private int $id;
    private int $tipo;
    private string $nome;
    private string $email;
    private string $senha;


    /** 
     * Getters 
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getTipo(): int
    {
        return $this->tipo;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    /** 
     * Setters 
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTipo(int $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    /** 
     * Database Connection
     */

    private static function getPDO(): PDO
    {
        return Database::getInstance();
    }

    /**
     * DAO Methods
     */
    public static function create(array $data): int
    {
        if (empty($data['email']) || empty($data['senha'])) {
            throw new Exception("Email e senha são obrigatórios");
        }

        $sql = "INSERT INTO usuarios (nome, email, senha_hash, tipo) 
                VALUES (:nome, :email, :senha_hash, :tipo)";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nome' => $data['nome'] ?? '',
                ':email' => $data['email'],
                ':senha_hash' => password_hash($data['senha'], PASSWORD_DEFAULT),
                ':tipo' => $data['tipo'] ?? 0
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Email já cadastrado");
            }
            throw new Exception("Erro ao criar usuário: " . $e->getMessage());
        }
    }

    public static function read(int $id): ?array
    {
        $sql = "SELECT id, nome, email, tipo, criado_em 
                FROM usuarios 
                WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário: " . $e->getMessage());
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

        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }

        if (isset($data['senha'])) {
            $fields[] = "senha_hash = :senha_hash";
            $params[':senha_hash'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        if (isset($data['tipo'])) {
            $fields[] = "tipo = :tipo";
            $params[':tipo'] = $data['tipo'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE usuarios SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar usuário: " . $e->getMessage());
        }
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar usuário: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT id, nome, email, tipo, criado_em 
                FROM usuarios 
                ORDER BY criado_em DESC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar todos os usuários: " . $e->getMessage());
        }
    }

    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM usuarios";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar usuários: " . $e->getMessage());
        }
    }

    public static function login(string $email, string $senha): ?array
    {
        $sql = "SELECT id, nome, email, senha_hash, tipo, criado_em 
                FROM usuarios 
                WHERE email = :email";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
                return null;
            }

            unset($usuario['senha_hash']);
            return $usuario;
        } catch (PDOException $e) {
            throw new Exception("Erro ao fazer login: " . $e->getMessage());
        }
    }
}
