<?php
class Cuadrilla extends Conectar
{

    public function insert_cuadrilla($cua_nombre)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_cuadrilla (cua_nombre) VALUES (?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_nombre);
        $stmt->execute();
        return $conectar->lastInsertId();
    }


    // Método para actualizar el nombre de una cuadrilla
    public function update_cuadrilla($cua_id, $cua_nombre)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_cuadrilla SET cua_nombre = ? WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_nombre);
        $stmt->bindValue(2, $cua_id);
        $stmt->execute();
        return $resultado = $stmt->fetchAll();
    }

    public function get_cuadrilla()
    {
        try {
            // Conexión a la base de datos
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_cuadrilla";
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


    // Método para eliminar una cuadrilla
    public function delete_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Preparamos la sentencia DELETE
        $sql = "DELETE FROM tm_cuadrilla WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);
        // Enlazamos el valor del parámetro
        $stmt->bindValue(1, $cua_id);
        // Ejecutamos la consulta
        $result = $stmt->execute();
        // Retornamos un valor booleano que indica si la eliminación fue exitosa
        if ($result) {
            return true;  // Eliminación exitosa
        } else {
            return false;  // Hubo un error al intentar eliminar
        }
    }

    public function get_cuadrilla_x_id($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_cuadrilla WHERE cua_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cua_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    // public function get_colaboradores(){
    //     $conectar= parent::conexion();
    //     parent::set_names();
    //     $sql="SELECT * FROM tm_colaborador";
    //     $sql=$conectar->prepare($sql);
    //     $sql->execute();
    //     return $resultado=$sql->fetchAll();
    // }

    public function get_colaboradores_por_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Consulta para obtener los colaboradores de una cuadrilla
        $sql = "SELECT 
                    col.col_nombre, 
                    col.col_apellido 
                FROM 
                    tm_cuadrilla_colaborador c_cuadrilla_colaborador
                INNER JOIN 
                    tm_colaborador col ON c_cuadrilla_colaborador.col_id = col.col_id
                WHERE 
                    c_cuadrilla_colaborador.cua_id = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
