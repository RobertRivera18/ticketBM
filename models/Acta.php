<?php
class Acta extends Conectar
{

    public function insert_acta($tipo_acta, $col_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO acta (tipo_acta,col_id,fecha_entrega) VALUES (?,?,NOW())";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tipo_acta);
        $stmt->bindValue(2, $col_id);

        if ($stmt->execute()) {
            $lastInsertId = $conectar->lastInsertId();
            error_log("ID insertado: " . $lastInsertId);
            return $lastInsertId;
        } else {
            error_log("Error en la inserción: " . implode(" ", $stmt->errorInfo()));
            return false;
        }
    }

    public function insert_actaEquipos($tipo_acta, $col_id, $equipo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO acta (tipo_acta,col_id,equipo_id,fecha_entrega) VALUES (?,?,?,NOW())";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tipo_acta);
        $stmt->bindValue(2, $col_id);
        $stmt->bindValue(3, $equipo_id);

        if ($stmt->execute()) {
            $lastInsertId = $conectar->lastInsertId();
            error_log("ID insertado: " . $lastInsertId);
            return $lastInsertId;
        } else {
            error_log("Error en la inserción: " . implode(" ", $stmt->errorInfo()));
            return false;
        }
    }


    public function get_acta()
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT a.id_acta, a.tipo_acta, a.col_id, c.col_nombre, c.col_cedula, a.fecha_entrega
        FROM acta a
        LEFT JOIN tm_colaborador c ON a.col_id = c.col_id
        ORDER BY a.id_acta DESC
        ";
            $stmt = $conectar->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }


    public function get_acta_by_id($id_acta)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT 
    a.id_acta,
    a.tipo_acta,
    c.col_nombre,
    c.col_cedula,
    CASE 
        WHEN a.tipo_acta = 3 THEN e.nombre_equipo
        ELSE NULL
    END AS nombre_equipo,
    CASE 
        WHEN a.tipo_acta = 3 THEN e.marca
        ELSE NULL
    END AS marca,
    CASE 
        WHEN a.tipo_acta = 3 THEN e.modelo
        ELSE NULL
    END AS modelo,
     CASE 
        WHEN a.tipo_acta = 3 THEN e.serie
        ELSE NULL
    END AS serie
FROM acta a
LEFT JOIN tm_colaborador c ON a.col_id = c.col_id
LEFT JOIN tm_equipos e ON a.equipo_id = e.equipo_id
WHERE a.id_acta = ?
";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $id_acta, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }


    public function get_acta_by_equipo_colaborador($id_acta)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT a.id_acta, a.tipo_acta, a.col_id, c.col_nombre, c.col_id ,c.col_cedula,e.equipo_nombre, e.marca, e.serie
                FROM acta a
                LEFT JOIN tm_colaborador c ON a.col_id = c.col_id
                      LEFT JOIN tm_equipos e ON e.equipo_id = a.equipo_id
                WHERE a.id_acta = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $id_acta, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }


    public function get_acta_by_equipos($id_acta)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT a.id_acta, a.tipo_acta, a.cua_id, c.cua_nombre, c.cua_id
                FROM acta a
                LEFT JOIN tm_cuadrilla c ON a.cua_id = c.cua_id
                WHERE a.id_acta = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $id_acta, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }
    public function get_equipos_asignados($id_acta)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();

            // Consulta ajustada para traer nombre y serie del equipo
            $sql = "SELECT e.nombre_equipo, e.marca ,e.serie 
                    FROM acta a
                    LEFT JOIN tm_equipos e ON a.equipo_id = e.equipo_id
                    WHERE a.id_acta = ?";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $id_acta, PDO::PARAM_INT);
            $stmt->execute();

            // Retorna todos los equipos asignados como un arreglo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error en la consulta: " . $e->getMessage();
            return [];
        }
    }


    public function guardarRutaArchivo($id_acta, $ruta)
    {
        try {
            $conectar = parent::conexion();
            if (!$conectar) {
                throw new Exception("No se pudo establecer la conexión a la base de datos");
            }
            parent::set_names();
            $sql = "UPDATE acta SET ruta_firma = ? WHERE id_acta = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $ruta, PDO::PARAM_STR);
            $stmt->bindValue(2, $id_acta, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                error_log("Error al ejecutar la consulta: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
            return true;
        } catch (PDOException $e) {
            error_log("Error en guardarRutaArchivo: " . $e->getMessage());
            return false;
        }
    }


    public function delete_acta($id_acta)
    {
        $conectar = parent::conexion();
        parent::set_names();
        // Preparamos la sentencia DELETE
        $sql = "DELETE FROM acta WHERE id_acta = ?";
        $stmt = $conectar->prepare($sql);
        // Enlazamos el valor del parámetro
        $stmt->bindValue(1, $id_acta);
        $result = $stmt->execute();
        // Retornamos un valor booleano que indica si la eliminación fue exitosa
        if ($result) {
            return true;  // Eliminación exitosa
        } else {
            return false;  // Hubo un error al intentar eliminar
        }
    }



    //Para descargar el comprobante
    public function obtenerRutaArchivo($id_acta)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT ruta_firma FROM acta WHERE id_acta = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $id_acta, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['ruta_firma'] : '';
        } catch (Exception $e) {
            return '';
        }
    }
}
