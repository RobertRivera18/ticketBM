<?php
    class Cuadrilla extends Conectar{

        public function insert_cuadrilla($cua_nombre) {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_cuadrilla (cua_nombre) VALUES (?)";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $cua_nombre);
            $stmt->execute();
            return $resultado = $stmt->fetchAll();
        }
    
        // Método para actualizar el nombre de una cuadrilla
        public function update_cuadrilla($cua_id, $cua_nombre) {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "UPDATE tm_cuadrilla SET cua_nombre = ? WHERE cua_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $cua_nombre);
            $stmt->bindValue(2, $cua_id);
            $stmt->execute();
            return $resultado = $stmt->fetchAll();
        }

        public function get_cuadrilla(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_cuadrilla";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

         // Método para eliminar una cuadrilla
    public function delete_cuadrilla($cua_id) {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM tm_cuadrilla WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $stmt->execute();
        return $resultado = $stmt->fetchAll();
    }

    public function get_cuadrilla_x_id($cua_id){
        $conectar= parent::conexion();
        parent::set_names();
        $sql="SELECT * FROM tm_cuadrilla WHERE cua_id = ?";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $cua_id);
        $sql->execute();
        return $resultado=$sql->fetchAll();
    }

    }
?>