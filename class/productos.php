<?php

class Productos {
    private $id;
    private $nombre;
    private $precio;
    private $categoria_id;
    private $descripcion;
    private $imagen;
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
    
    public function getPrecio() {
        return $this->precio;
    }
    
    public function getCategoriaId() {
        return $this->categoria_id;
    }
    
    public function getDescripcion() {
        return $this->descripcion;
    }
    
    public function getImagen() {
        return $this->imagen;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function setPrecio($precio) {
        $this->precio = $precio;
    }
    
    public function setCategoriaId($categoria_id) {
        $this->categoria_id = $categoria_id;
    }
    
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    
    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }
    
    // Métodos principales
    public function guardar() {
        try {
            // Validar que la categoría existe
            if ($this->categoria_id) {
                $categoria = new Categorias();
                if (!$categoria->obtenerPorId($this->categoria_id)) {
                    throw new Exception("La categoría especificada no existe");
                }
            }
            
            $data = [
                'nombre' => $this->nombre,
                'precio' => $this->precio,
                'categoria_id' => $this->categoria_id,
                'descripcion' => $this->descripcion,
                'imagen' => $this->imagen
            ];
            
            if ($this->id) {
                // Actualizar producto existente
                $conditions = ['id' => $this->id];
                return $this->database->update('productos', $data, $conditions);
            } else {
                // Insertar nuevo producto
                $this->id = $this->database->insert('productos', $data);
                return $this->id;
            }
        } catch (Exception $e) {
            throw new Exception("Error al guardar el producto: " . $e->getMessage());
        }
    }
    
    public function eliminar() {
        try {
            if (!$this->id) {
                throw new Exception("No se puede eliminar un producto sin ID");
            }
            
            $conditions = ['id' => $this->id];
            return $this->database->delete('productos', $conditions);
        } catch (Exception $e) {
            throw new Exception("Error al eliminar el producto: " . $e->getMessage());
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $result = $this->database->select('productos', '*', ['id' => $id]);
            
            if (!empty($result)) {
                $producto = $result[0];
                $this->id = $producto['id'];
                $this->nombre = $producto['nombre'];
                $this->precio = $producto['precio'];
                $this->categoria_id = $producto['categoria_id'];
                $this->descripcion = $producto['descripcion'];
                $this->imagen = $producto['imagen'];
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            throw new Exception("Error al obtener el producto: " . $e->getMessage());
        }
    }
    
    public function listarTodos() {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id
                    ORDER BY p.nombre ASC";
            
            return $this->database->query($sql);
        } catch (Exception $e) {
            throw new Exception("Error al listar los productos: " . $e->getMessage());
        }
    }
    
    public function listarPorCategoria($categoria_id) {
        try {
            $conditions = ['categoria_id' => $categoria_id];
            return $this->database->select('productos', '*', $conditions, 'nombre ASC');
        } catch (Exception $e) {
            throw new Exception("Error al listar productos por categoría: " . $e->getMessage());
        }
    }
    
    public function buscarPorNombre($nombre) {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    WHERE p.nombre LIKE :nombre
                    ORDER BY p.nombre ASC";
            
            $params = ['nombre' => "%{$nombre}%"];
            return $this->database->query($sql, $params);
        } catch (Exception $e) {
            throw new Exception("Error al buscar productos: " . $e->getMessage());
        }
    }
    
    public function validarDatos() {
        $errores = [];
        
        if (empty($this->nombre)) {
            $errores[] = "El nombre del producto es obligatorio";
        }
        
        if ($this->precio <= 0) {
            $errores[] = "El precio debe ser mayor a 0";
        }
        
        if (empty($this->categoria_id)) {
            $errores[] = "La categoría es obligatoria";
        }
        
        return $errores;
    }
}

?>