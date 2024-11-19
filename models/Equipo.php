<?php
class Equipo extends Conectar
{

    public function insert_equipo($nombre_equipo, $marca, $serie)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_equipos (nombre_equipo,marca,serie) VALUES (?,?,?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $nombre_equipo);
        $stmt->bindValue(2, $marca);
        $stmt->bindValue(3, $serie);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $conectar->lastInsertId();
        } else {
            return false;
        }
    }
    public function update_equipo($equipo_id, $nombre_equipo, $marca, $serie)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_equipos set 
             nombre_equipo = ?,
              marca = ?, 
              serie = ? WHERE 
              equipo_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $nombre_equipo);
        $stmt->bindValue(2, $marca);
        $stmt->bindValue(3, $serie);
        $stmt->bindValue(4, $equipo_id);
        $stmt->execute();
        return $resultado=$stmt->fetchAll();
    }

    public function get_equipo()
    {
        try {
            // Conexión a la base de datos
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_equipos";
            $stmt = $conectar->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Manejo de errores si la consulta falla
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar un colaborador
    public function delete_equipo($equipo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Preparamos la sentencia DELETE
        $sql = "DELETE FROM tm_equipos WHERE equipo_id = ?";
        $stmt = $conectar->prepare($sql);
        // Enlazamos el valor del parámetro
        $stmt->bindValue(1, $equipo_id);
        $result = $stmt->execute();
        // Retornamos un valor booleano que indica si la eliminación fue exitosa
        if ($result) {
            return true;  // Eliminación exitosa
        } else {
            return false;  // Hubo un error al intentar eliminar
        }
    }

    public function get_equipo_x_id($equipo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_equipos WHERE equipo_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $equipo_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

}