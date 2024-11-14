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

        if ($stmt->execute()) {
            $lastInsertId = $conectar->lastInsertId();
            error_log("ID insertado: " . $lastInsertId);
            return $lastInsertId;
        } else {
            error_log("Error en la inserción: " . implode(" ", $stmt->errorInfo())); 
            return false;
        }
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
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_cuadrilla";
            // Preparar y ejecutar la consulta
            $stmt = $conectar->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar una cuadrilla
    public function delete_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM tm_cuadrilla WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $result = $stmt->execute();
        if ($result) {
            return true; 
        } else {
            return false;
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

    public function update_cuadrilla_asignacion($cua_id, $col_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_cuadrilla_colaborador 
                SET col_id = ? 
                WHERE cua_id = ?";

        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $col_id);
        $sql->bindValue(2, $cua_id);

        // Verificar si la ejecución fue exitosa
        if ($sql->execute()) {
            return true;
        } else {
            return false;
        }
    }



    //     $conectar = parent::conexion();
    //     parent::set_names();

    //     // Primero verificamos si ya existe una asignación para esa cuadrilla
    //     $sql = "SELECT * FROM tm_cuadrilla_colaborador WHERE cua_id = ? AND col_id = ?";
    //     $sql = $conectar->prepare($sql);
    //     $sql->bindValue(1, $cua_id);
    //     $sql->bindValue(2, $col_id);
    //     $sql->execute();

    //     // Si no existe una asignación, realizamos un INSERT
    //     if ($sql->rowCount() == 0) {
    //         $sql = "INSERT INTO tm_cuadrilla_colaborador (cua_id, col_id) VALUES (?, ?)";
    //         $sql = $conectar->prepare($sql);
    //         $sql->bindValue(1, $cua_id);
    //         $sql->bindValue(2, $col_id);
    //         $sql->execute();
    //     } else {
    //         // Si ya existe, simplemente actualizamos el registro
    //         $sql = "UPDATE tm_cuadrilla_colaborador SET col_id = ? WHERE cua_id = ?";
    //         $sql = $conectar->prepare($sql);
    //         $sql->bindValue(1, $col_id);
    //         $sql->bindValue(2, $cua_id);
    //         $sql->execute();
    //     }

    //     return true;



    // public function get_colaboradores(){
    //     $conectar= parent::conexion();
    //     parent::set_names();
    //     $sql="SELECT * FROM tm_colaborador";
    //     $sql=$conectar->prepare($sql);
    //     $sql->execute();
    //     return $resultado=$sql->fetchAll();
    // }



    //Metodo usado para mostar los colaboradores por cuadrilla en el DataTable
    public function get_colaboradores_por_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Consulta para obtener los colaboradores de una cuadrilla
        $sql = "SELECT col.col_nombre
        FROM tm_cuadrilla_colaborador
        INNER JOIN tm_colaborador col ON tm_cuadrilla_colaborador.col_id = col.col_id
        WHERE tm_cuadrilla_colaborador.cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
