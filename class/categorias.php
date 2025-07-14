<?php

class Categorias {
    private $id;
    private $nombre;
    private $database;
    
    public function __construct() {
        $this->database = new Database();
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    // Métodos principales
    public function guardar() {
        try {
            $data = [
                'nombre' => $this->nombre
            ];
            
            if ($this->id) {
                // Actualizar categoría existente
                $conditions = ['id' => $this->id];
                return $this->database->update('categorias', $data, $conditions);
            } else {
                // Insertar nueva categoría
                $this->id = $this->database->insert('categorias', $data);
                return $this->id;
            }
        } catch (Exception $e) {
            throw new Exception("Error al guardar la categoría: " . $e->getMessage());
        }
    }
    
    public function eliminar() {
        try {
            if (!$this->id) {
                throw new Exception("No se puede eliminar una categoría sin ID");
            }
            
            $conditions = ['id' => $this->id];
            return $this->database->delete('categorias', $conditions);
        } catch (Exception $e) {
            throw new Exception("Error al eliminar la categoría: " . $e->getMessage());
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $result = $this->database->select('categorias', '*', ['id' => $id]);
            
            if (!empty($result)) {
                $categoria = $result[0];
                $this->id = $categoria['id'];
                $this->nombre = $categoria['nombre'];
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            throw new Exception("Error al obtener la categoría: " . $e->getMessage());
        }
    }
    
    public function listarTodas() {
        try {
            return $this->database->select('categorias', '*', [], 'nombre ASC');
        } catch (Exception $e) {
            throw new Exception("Error al listar las categorías: " . $e->getMessage());
        }
    }
    
    public function buscarPorNombre($nombre) {
        try {
            $sql = "SELECT * FROM categorias WHERE nombre LIKE :nombre ORDER BY nombre ASC";
            $params = ['nombre' => "%{$nombre}%"];
            return $this->database->query($sql, $params);
        } catch (Exception $e) {
            throw new Exception("Error al buscar categorías: " . $e->getMessage());
        }
    }
    
    public function validarNombreUnico($nombre, $id_excluir = null) {
        try {
            $conditions = ['nombre' => $nombre];
            $categorias = $this->database->select('categorias', 'id', $conditions);
            
            if (!empty($categorias)) {
                // Si hay un ID a excluir (para edición), verificar que no sea el mismo
                if ($id_excluir && $categorias[0]['id'] == $id_excluir) {
                    return true; // Es la misma categoría, es válido
                }
                return false; // Ya existe otra categoría con ese nombre
            }
            
            return true; // No existe, es válido
        } catch (Exception $e) {
            throw new Exception("Error al validar el nombre: " . $e->getMessage());
        }
    }
}

?>