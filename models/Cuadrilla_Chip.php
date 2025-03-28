<?php
class Cuadrilla_Chip extends Conectar
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



    public function get_cuadrilla()
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_cuadrilla  ORDER BY cua_id DESC";
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

    //Asigno colaboradores a una cuadrilla 
    public function insert_cuadrilla_asignacion($cua_id, $col_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_cuadrilla_colaborador (cua_id, col_id) 
            VALUES (?, ?)";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cua_id, PDO::PARAM_INT);
        $sql->bindValue(2, $col_id, PDO::PARAM_INT);
        if ($sql->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_cuadrilla_equipos($cua_id, $equipo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_cuadrilla_equipo (cua_id, equipo_id) 
            VALUES (?, ?)";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cua_id, PDO::PARAM_INT);
        $sql->bindValue(2, $equipo_id, PDO::PARAM_INT);
        if ($sql->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //Obtener chips disponibles
    public function get_chips_disponibles()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT e.*
             FROM tm_equipos e
             LEFT JOIN tm_cuadrilla_equipo cc ON e.equipo_id = cc.equipo_id
             WHERE cc.equipo_id IS NULL AND e.datos=1";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }



    //Obtener equipos para tecnicos disponibles
    public function get_equiposTecnicos_disponibles()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT e.*
                FROM tm_equipos e
                LEFT JOIN tm_cuadrilla_equipo cc ON e.equipo_id = cc.equipo_id
                WHERE cc.equipo_id IS NULL AND e.datos=3";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }




    //Metodo usado para mostar los colaboradores por cuadrilla en el DataTable
    public function get_colaboradores_por_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT col.col_id, col.col_nombre
                FROM tm_cuadrilla_colaborador
                INNER JOIN tm_colaborador col ON tm_cuadrilla_colaborador.col_id = col.col_id
                WHERE tm_cuadrilla_colaborador.cua_id = ?  
                ORDER BY id DESC";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    //Metodo usado para mostar los equipos otorgados a las cuadrillas
    public function get_equipos_por_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT eq.equipo_id, eq.nombre_equipo, eq.marca, eq.serie
    FROM tm_cuadrilla_equipo
    INNER JOIN tm_equipos eq ON tm_cuadrilla_equipo.equipo_id = eq.equipo_id
    WHERE eq.datos = 1 AND  tm_cuadrilla_equipo.cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    //Metodo usado para mostar los equipos otorgados a las cuadrillas
    public function get_equipos_por_cuadrilla1($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT eq.equipo_id, eq.nombre_equipo, eq.marca, eq.serie
FROM tm_cuadrilla_equipo
INNER JOIN tm_equipos eq ON tm_cuadrilla_equipo.equipo_id = eq.equipo_id
WHERE eq.datos = 3 AND tm_cuadrilla_equipo.cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function delete_cuadrilla_colaborador($cua_id, $col_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "DELETE FROM tm_cuadrilla_colaborador WHERE cua_id = ? AND col_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $cua_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $col_id, PDO::PARAM_INT);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }




    public function delete_cuadrilla_equipo($cua_id, $equipo_id, $motivo)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();

            // Verificar si el equipo ya está asignado
            $sql_check = "SELECT * FROM tm_cuadrilla_equipo WHERE cua_id = ? AND equipo_id = ?";
            $stmt_check = $conectar->prepare($sql_check);
            $stmt_check->bindValue(1, $cua_id, PDO::PARAM_INT);
            $stmt_check->bindValue(2, $equipo_id, PDO::PARAM_INT);
            $stmt_check->execute();

            if ($stmt_check->rowCount() == 0) {
                return false; // No existe la asignación
            }

            // Insertar en la tabla de eliminados
            $sql_insert = "INSERT INTO tm_cuadrilla_equipo_eliminados (cua_id, equipo_id, motivo, fecha) VALUES (?, ?, ?, NOW())";
            $stmt_insert = $conectar->prepare($sql_insert);
            $stmt_insert->bindValue(1, $cua_id, PDO::PARAM_INT);
            $stmt_insert->bindValue(2, $equipo_id, PDO::PARAM_INT);
            $stmt_insert->bindValue(3, $motivo, PDO::PARAM_STR);
            $stmt_insert->execute();

            // Eliminar el equipo de la cuadrilla
            $sql_delete = "DELETE FROM tm_cuadrilla_equipo WHERE cua_id = ? AND equipo_id = ?";
            $stmt_delete = $conectar->prepare($sql_delete);
            $stmt_delete->bindValue(1, $cua_id, PDO::PARAM_INT);
            $stmt_delete->bindValue(2, $equipo_id, PDO::PARAM_INT);

            return $stmt_delete->execute() && $stmt_delete->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }


    //Metodo usado para mostar los equipos otorgados a las cuadrillas
    public function get_empresa_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Consulta para obtener los colaboradores de una cuadrilla
        $sql = "SELECT cua_empresa,cua_ciudad
         FROM tm_cuadrilla
         WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    //Crea word con detalle equipos asignados y colaboradores que pertenecen a la cuadrilla 
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
   e.datos =1 AND  cc.cua_id = ?
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


    //Genera word de asigancion de un equipo a tecnicos(Medidor de campo)
    public function create_word_equipo_cuadrilla($cua_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();

            $sql = "SELECT 
            GROUP_CONCAT(DISTINCT c.col_nombre SEPARATOR ',') AS nombres_colaboradores,
            GROUP_CONCAT(DISTINCT c.col_cedula SEPARATOR ',') AS cedulas_colaboradores,
            e.equipo_id, 
            e.nombre_equipo AS descripcion, 
            e.marca, 
            e.modelo, 
            e.serie AS serie,
            cu.cua_nombre AS nombre_cuadrilla
        FROM tm_cuadrilla_colaborador cc
        INNER JOIN tm_colaborador c ON cc.col_id = c.col_id
        LEFT JOIN tm_cuadrilla_equipo ce ON ce.cua_id = cc.cua_id
        LEFT JOIN tm_equipos e ON ce.equipo_id = e.equipo_id
        INNER JOIN tm_cuadrilla cu ON cu.cua_id = cc.cua_id
        WHERE e.datos =3 AND cc.cua_id = ?
        GROUP BY e.equipo_id, cu.cua_nombre;";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $cua_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$result) {
                return false;
            }

            return $result;
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }


    public function create_word_descargo($cua_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();

            $sql = "SELECT 
    GROUP_CONCAT(DISTINCT c.col_nombre SEPARATOR ',') AS nombres_colaboradores,
    GROUP_CONCAT(DISTINCT c.col_cedula SEPARATOR ',') AS cedulas_colaboradores,
    GROUP_CONCAT(DISTINCT e.serie SEPARATOR ',') AS equipos_asignados,
    GROUP_CONCAT(DISTINCT e.nombre_equipo SEPARATOR ',') AS nombres_equipos,
    GROUP_CONCAT(DISTINCT e.marca SEPARATOR ',') AS marcas_equipos,
    GROUP_CONCAT(DISTINCT e.modelo SEPARATOR ',') AS modelos_equipos,
    GROUP_CONCAT(DISTINCT e.serie SEPARATOR ',') AS series_equipos,
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
   e.datos=1 AND  cc.cua_id = ?";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $cua_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return false;
            }


            return $result;
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }


    //Metodo para subir un comprobante en la tabla cuadrilla
    public function guardarRutaComprobanteCuadrilla($cua_id, $ruta)
    {
        try {
            $conectar = parent::conexion();
            if (!$conectar) {
                throw new Exception("No se pudo establecer la conexión a la base de datos");
            }
            parent::set_names();
            $sql = "UPDATE tm_cuadrilla SET ruta_comprobante = ? WHERE cua_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $ruta, PDO::PARAM_STR);
            $stmt->bindValue(2, $cua_id, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                error_log("Error al ejecutar la consulta: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
            return true;
        } catch (PDOException $e) {
            error_log("Error en guardarRutaComprobanteCuadrilla: " . $e->getMessage());
            return false;
        }
    }

      //Para descargar el comprobante de recepcion de chip
      public function obtenerRutaArchivo($cua_id)
      {
          try {
              $conectar = parent::conexion();
              parent::set_names();
              $sql = "SELECT ruta_comprobante FROM tm_cuadrilla WHERE cua_id = ?";
              $stmt = $conectar->prepare($sql);
              $stmt->bindValue(1, $cua_id, PDO::PARAM_INT);
              $stmt->execute();
              $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
              return $resultado ? $resultado['ruta_comprobante'] : '';
          } catch (Exception $e) {
              return '';
          }
      }



      //Metodos para subir comprobante entrega de equipos a tecnicos claro
    public function guardarRutaComprobanteCuadrillaEquipos($cua_id, $ruta)
    {
        try {
            $conectar = parent::conexion();
            if (!$conectar) {
                throw new Exception("No se pudo establecer la conexión a la base de datos");
            }
            parent::set_names();
            $sql = "UPDATE tm_cuadrilla SET comprobante_equipoTecnicos = ? WHERE cua_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $ruta, PDO::PARAM_STR);
            $stmt->bindValue(2, $cua_id, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                error_log("Error al ejecutar la consulta: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
            return true;
        } catch (PDOException $e) {
            error_log("Error en guardarRutaComprobanteCuadrilla: " . $e->getMessage());
            return false;
        }
    }
    //Para descargar el comprobante de recepcion de chip
    public function obtenerRutaArchivoEquipo($cua_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT comprobante_equipoTecnicos FROM tm_cuadrilla WHERE cua_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $cua_id, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['comprobante_equipoTecnicos'] : '';
        } catch (Exception $e) {
            return '';
        }
    }

}
