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

    public function insert_actaEquipos($tipo_acta, $equipo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO acta (tipo_acta,equipo_id) VALUES (?,?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tipo_acta);
        $stmt->bindValue(2, $equipo_id);

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
        LEFT JOIN tm_colaborador c ON a.col_id = c.col_id";
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
            $sql = "SELECT a.id_acta, a.tipo_acta, a.col_id, c.col_nombre, c.col_id ,c.col_cedula
                FROM acta a
                LEFT JOIN tm_colaborador c ON a.col_id = c.col_id
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
}
