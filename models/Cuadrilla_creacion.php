<?php
class Cuadrilla_creacion extends Conectar
{

    public function insert_cuadrilla($cua_nombre, $cua_empresa, $cua_ciudad)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_cuadrilla (cua_nombre,cua_empresa,cua_ciudad) VALUES (?,?,?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_nombre);
        $stmt->bindValue(2, $cua_empresa);
        $stmt->bindValue(3, $cua_ciudad);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $conectar->lastInsertId();
        } else {
            return false;
        }
    }
    public function update_cuadrilla($cua_nombre, $cua_empresa, $cua_ciudad, $cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_cuadrilla SET cua_nombre = ?, cua_empresa = ?, cua_ciudad = ? WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_nombre);
        $stmt->bindValue(2, $cua_empresa);
        $stmt->bindValue(3, $cua_ciudad);
        $stmt->bindValue(4, $cua_id);
        $stmt->execute();
        return $stmt->rowCount();
    }


    public function get_cuadrillas()
    {
        try {
            // Conexión a la base de datos
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_cuadrilla";
            $stmt = $conectar->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar un colaborador
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

    public function marcarRecarga($cua_id, $recargas)
    {
        $conectar = parent::conexion();
        parent::set_names();
        // Preparar la consulta para actualizar el campo recargas
        $sql = "UPDATE tm_cuadrilla SET recargas = ? WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);

        // Enlazar los valores
        $stmt->bindValue(1, $recargas, PDO::PARAM_BOOL);  // True o False
        $stmt->bindValue(2, $cua_id, PDO::PARAM_INT);     // ID de la cuadrilla

        $stmt->execute();
        return $stmt->rowCount();
    }


    //Todos los registros de recargas los establezco en false
    public function desmarcarTodas()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_cuadrilla SET recargas = false";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }


    //Obtener cuadrillas que se le han hecho las recargas 
    public function obtenerRecargasTrue()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
    cua.cua_id, 
    cua.cua_nombre, 
    CASE cua.cua_ciudad
        WHEN 1 THEN 'Guayaquil'
        WHEN 2 THEN 'Quito'
        ELSE 'Desconocida'
    END AS ciudad_nombre, 
    IF(cua.recargas, 'Recarga realizada', 'No realizada') AS recargas,
    eq.serie,
    GROUP_CONCAT(col.col_nombre SEPARATOR ', ') AS colaboradores 
FROM tm_cuadrilla cua
LEFT JOIN tm_cuadrilla_equipo cqe ON cua.cua_id = cqe.cua_id
LEFT JOIN tm_equipos eq ON cqe.equipo_id = eq.equipo_id
LEFT JOIN tm_cuadrilla_colaborador cc ON cua.cua_id = cc.cua_id
LEFT JOIN tm_colaborador col ON cc.col_id = col.col_id
WHERE eq.datos = 1
GROUP BY cua.cua_id, cua.cua_nombre, cua.cua_ciudad, cua.recargas, eq.serie
ORDER BY 
    (CASE cua.cua_ciudad 
        WHEN 1 THEN 1 
        WHEN 2 THEN 2 
        ELSE 3 
    END),
    cua.cua_id; 
 
";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
