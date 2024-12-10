<?php
class Usuario_Equipo extends Conectar
{
    public function insert_usuario_equipos($usu_id, $equipo_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_usuario_equipo (usu_id, equipo_id) VALUES (?, ?)";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $equipo_id, PDO::PARAM_INT);
            return $stmt->execute(); // Retorna true o false directamente
        } catch (PDOException $e) {
            error_log("Error en insert_usuario_equipos: " . $e->getMessage());
            return false;
        }
    }


    public function get_equipos_por_usuario($usu_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT eq.equipo_id, eq.nombre_equipo, eq.marca, eq.serie
                    FROM tm_usuario_equipo
                    INNER JOIN tm_equipos eq ON tm_usuario_equipo.equipo_id = eq.equipo_id
                    WHERE tm_usuario_equipo.usu_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en get_equipos_por_usuario: " . $e->getMessage());
            return [];
        }
    }

    public function delete_usuario_equipo($usu_id, $equipo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM tm_usuario_equipo WHERE usu_id = ? AND equipo_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $equipo_id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function create_word($cua_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT 
    GROUP_CONCAT(DISTINCT c.col_nombre SEPARATOR ',') AS nombres_colaboradores,
    GROUP_CONCAT(DISTINCT c.col_cedula SEPARATOR ',') AS cedulas_colaboradores,
    GROUP_CONCAT(DISTINCT e.serie SEPARATOR ',') AS equipos_asignados,
    cu.cua_nombre AS nombre_cuadrilla
FROM
    tm_cuadrilla_colaborador cc
INNER JOIN
    tm_colaborador c ON cc.col_id = c.col_id
LEFT JOIN
    tm_cuadrilla_equipo ce ON ce.cua_id = cc.cua_id
LEFT JOIN
    tm_equipos e ON ce.equipo_id = e.equipo_id
INNER JOIN
    tm_cuadrilla cu ON cu.cua_id = cc.cua_id
WHERE
    cc.cua_id = ?
";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $cua_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return false;
            }

            return $result;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }
}
