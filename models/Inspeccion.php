<?php
class Inspeccion extends Conectar
{

    public function insert_inspeccion($trabajo, $ubicacion, $numero_orden, $solicitante_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_inspeccion (trabajo,ubicacion,numero_orden,fecha,solicitante_id) VALUES (?,?,?,NOW(),?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $trabajo);
        $stmt->bindValue(2, $ubicacion);
        $stmt->bindValue(3, $numero_orden);
        $stmt->bindValue(4, $solicitante_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $conectar->lastInsertId();
        } else {
            return false;
        }
    }

    public function get_inspecciones(){
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "
                SELECT 
                i.inspeccion_id,
                 i.trabajo,
                 i.ubicacion,
                 i.numero_orden,
                 i.fecha,
                 c.col_nombre
           FROM 
               tm_inspeccion AS i
           INNER JOIN 
               tm_colaborador AS c
           ON 
               i.solicitante_id = c.col_id
            ";

            $stmt = $conectar->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Manejo de errores si la consulta falla
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }


    // Método para eliminar un 
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

    public function get_equipos_disponibles()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT e.*
             FROM tm_equipos e
             LEFT JOIN tm_cuadrilla_equipo cc ON e.equipo_id = cc.equipo_id
             WHERE cc.equipo_id IS NULL AND e.datos=0";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }


    public function get_equipos_disponibles_usuarios()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT e.*
             FROM tm_equipos e
             LEFT JOIN tm_usuario_equipo ue ON e.equipo_id = ue.equipo_id
             WHERE ue.equipo_id IS NULL AND e.datos=0";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }
}
