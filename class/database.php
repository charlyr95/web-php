<?php

class Database {
    private $host;
    private $database;
    private $username;
    private $password;
    private $connection;
    
    public function __construct($host = 'localhost', $database = 'MIPROYECTO', $username = 'root', $password = '') {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->connect();
    }
    
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function insert($table, $data) {
        try {
            $columns = implode(',', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
            $stmt = $this->connection->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            
            $stmt->execute();
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error al insertar: " . $e->getMessage());
        }
    }
    
    public function update($table, $data, $conditions) {
        try {
            $setClause = '';
            foreach ($data as $key => $value) {
                $setClause .= "{$key} = :{$key}, ";
            }
            $setClause = rtrim($setClause, ', ');
            
            $whereClause = '';
            foreach ($conditions as $key => $value) {
                $whereClause .= "{$key} = :where_{$key} AND ";
            }
            $whereClause = rtrim($whereClause, ' AND ');
            
            $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
            $stmt = $this->connection->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            
            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":where_{$key}", $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }
    
    public function delete($table, $conditions) {
        try {
            $whereClause = '';
            foreach ($conditions as $key => $value) {
                $whereClause .= "{$key} = :{$key} AND ";
            }
            $whereClause = rtrim($whereClause, ' AND ');
            
            $sql = "DELETE FROM {$table} WHERE {$whereClause}";
            $stmt = $this->connection->prepare($sql);
            
            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
    
    public function select($table, $columns = '*', $conditions = [], $orderBy = '', $limit = '') {
        try {
            $sql = "SELECT {$columns} FROM {$table}";
            
            if (!empty($conditions)) {
                $whereClause = '';
                foreach ($conditions as $key => $value) {
                    $whereClause .= "{$key} = :{$key} AND ";
                }
                $whereClause = rtrim($whereClause, ' AND ');
                $sql .= " WHERE {$whereClause}";
            }
            
            if (!empty($orderBy)) {
                $sql .= " ORDER BY {$orderBy}";
            }
            
            if (!empty($limit)) {
                $sql .= " LIMIT {$limit}";
            }
            
            $stmt = $this->connection->prepare($sql);
            
            if (!empty($conditions)) {
                foreach ($conditions as $key => $value) {
                    $stmt->bindValue(":{$key}", $value);
                }
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al seleccionar: " . $e->getMessage());
        }
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindValue(":{$key}", $value);
                }
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }
    
    public function __destruct() {
        $this->connection = null;
    }
}

?>