<?php
class Grupo extends Conectar
{

    public function insert_grupo($grupo_nombre)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_grupo (grupo_nombre) VALUES (?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $grupo_nombre);
        $stmt->execute();
        return $conectar->lastInsertId();
    }


    // Método para actualizar el nombre de una cuadrilla
    public function update_grupo($grupo_id, $grupo_nombre)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_grupo SET grupo_nombre = ? WHERE grupo_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $grupo_nombre);
        $stmt->bindValue(2, $grupo_id);
        $stmt->execute();
        return $resultado = $stmt->fetchAll();
    }

    public function get_grupo()
    {
        try {
            // Conexión a la base de datos
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_grupo";
            // Preparar y ejecutar la consulta
            $stmt = $conectar->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Manejo de errores si la consulta falla
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }

    // // Método para eliminar un grupo
    public function delete_grupo($grupo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM tm_grupo WHERE grupo_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $grupo_id);
        $result = $stmt->execute();
        if ($result) {
            return true;  
        } else {
            return false; 
        }
    }

    public function get_grupo_x_id($grupo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_grupo WHERE grupo_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $grupo_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }


    // //Muestra los colaboradores que no tienen asignado una cuadrilla 
    // public function get_colaboradores()
    // {
    //     $conectar = parent::conexion();
    //     parent::set_names();
    //     $sql = "SELECT c.*
    //          FROM tm_colaborador c
    //          LEFT JOIN tm_cuadrilla_colaborador cc ON c.col_id = cc.col_id
    //          WHERE cc.col_id IS NULL;";
    //     $sql = $conectar->prepare($sql);
    //     $sql->execute();
    //     return $resultado = $sql->fetchAll();
    // }
}
