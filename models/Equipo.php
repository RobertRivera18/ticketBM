<?php
class Equipo extends Conectar
{

    public function insert_equipo($nombre_equipo, $marca, $modelo, $serie, $datos)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_equipos (nombre_equipo,marca,modelo,serie,datos) VALUES (?,?,?,?,?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $nombre_equipo);
        $stmt->bindValue(2, $marca);
        $stmt->bindValue(3, $modelo);
        $stmt->bindValue(4, $serie);
        $stmt->bindValue(5, $datos);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $conectar->lastInsertId();
        } else {
            return false;
        }
    }
    public function update_equipo($equipo_id, $nombre_equipo, $marca, $modelo, $serie, $datos)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_equipos set 
             nombre_equipo = ?,
              marca = ?, 
              modelo = ?,
              serie = ?,
              datos = ?
               WHERE 
              equipo_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $nombre_equipo);
        $stmt->bindValue(2, $marca);
        $stmt->bindValue(3, $modelo);
        $stmt->bindValue(4, $serie);
        $stmt->bindValue(5, $datos);
        $stmt->bindValue(6, $equipo_id);
        $stmt->execute();
        return $resultado = $stmt->fetchAll();
    }

    public function get_equipo_con_asignacion()
    {
        try {
            // Conexión a la base de datos
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "
              SELECT 
    e.equipo_id,
    e.nombre_equipo,
    e.marca,
    e.modelo,
    e.serie,
    e.datos,
  
    CASE 
        WHEN e.datos = 2 THEN CONCAT(u.usu_nom, ' ', u.usu_ape)
        ELSE NULL 
    END AS nombre_usuario,
   
    CASE 
        WHEN e.datos = 1 THEN cua.cua_nombre
        ELSE NULL 
    END AS cuadrilla_asignada
FROM 
    tm_equipos e

LEFT JOIN 
    tm_usuario_equipo ue ON e.equipo_id = ue.equipo_id
LEFT JOIN 
    tm_usuario u ON ue.usu_id = u.usu_id

LEFT JOIN 
    tm_cuadrilla_equipo cue ON e.equipo_id = cue.equipo_id
LEFT JOIN 
    tm_cuadrilla cua ON cue.cua_id = cua.cua_id
WHERE 
    e.datos IN (1, 2) 
ORDER BY 
    e.equipo_id DESC;
;

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
             WHERE cc.equipo_id IS NULL AND e.datos=2";
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
             WHERE ue.equipo_id IS NULL AND e.datos=2";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

   
}
