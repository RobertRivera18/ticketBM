<?php
class Usuario extends Conectar
{

    public function login()
    {
        $conectar = parent::conexion();
        parent::set_names();
        if (isset($_POST["enviar"])) {
            $correo = $_POST["usu_correo"];
            $pass = $_POST["usu_pass"];
            $rol = $_POST["rol_id"];
            if (empty($correo) and empty($pass)) {
                header("Location:" . conectar::ruta() . "index.php?m=2");
                exit();
            } else {
                $sql = "SELECT * FROM tm_usuario WHERE usu_correo=? and usu_pass=MD5(?) and rol_id=? and est=1";
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $correo);
                $stmt->bindValue(2, $pass);
                $stmt->bindValue(3, $rol);
                $stmt->execute();
                $resultado = $stmt->fetch();
                if (is_array($resultado) and count($resultado) > 0) {
                    $_SESSION["usu_id"] = $resultado["usu_id"];
                    $_SESSION["usu_nom"] = $resultado["usu_nom"];
                    $_SESSION["usu_ape"] = $resultado["usu_ape"];
                    $_SESSION["rol_id"] = $resultado["rol_id"];
                    header("Location:" . Conectar::ruta() . "view/Home/");
                    exit();
                } else {
                    header("Location:" . Conectar::ruta() . "index.php?m=1");
                    exit();
                }
            }
        }
    }

    public function insert_usuario($usu_nom, $usu_ape, $usu_cedula, $usu_correo, $usu_pass, $rol_id, $empresa_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $hashed_pass = MD5($usu_pass);
        $sql = "INSERT INTO tm_usuario (usu_id, usu_nom, usu_ape, usu_cedula, usu_correo, usu_pass, rol_id, empresa_id, fech_crea, fech_modi, fech_elim, est) 
                    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, NOW(), NULL, NULL, '1')";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_nom);
        $sql->bindValue(2, $usu_ape);
        $sql->bindValue(3, $usu_cedula);
        $sql->bindValue(4, $usu_correo);
        $sql->bindValue(5, $hashed_pass);
        $sql->bindValue(6, $rol_id);
        $sql->bindValue(7, $empresa_id);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return $conectar->lastInsertId();
        } else {
            return false;
        }
    }


    public function update_usuario($usu_id, $usu_nom, $usu_ape, $usu_cedula, $usu_correo, $usu_pass, $rol_id, $empresa_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_usuario set
                usu_nom = ?,
                usu_ape = ?,
                usu_cedula = ?,
                usu_correo = ?,
                usu_pass = ?,
                rol_id = ?,
                empresa_id=?
                WHERE
                usu_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_nom);
        $sql->bindValue(2, $usu_ape);
        $sql->bindValue(3, $usu_cedula);
        $sql->bindValue(4, $usu_correo);
        $sql->bindValue(5, $usu_pass);
        $sql->bindValue(6, $rol_id);
        $sql->bindValue(7, $empresa_id);
        $sql->bindValue(8, $usu_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function delete_usuario($usu_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "call sp_d_usuario_01(?)";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function get_usuario()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "call sp_l_usuario_01()";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function get_usuario_x_rol()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_usuario where est=1 and rol_id=2";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function get_usuario_x_id($usu_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "call sp_l_usuario_02(?)";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function get_usuario_total_x_id($usu_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT COUNT(*) as TOTAL FROM tm_ticket where usu_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function get_usuario_totalabierto_x_id($usu_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT COUNT(*) as TOTAL FROM tm_ticket where usu_id = ? and tick_estado='Abierto'";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function get_usuario_totalcerrado_x_id($usu_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT COUNT(*) as TOTAL FROM tm_ticket where usu_id = ? and tick_estado='Cerrado'";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function get_usuario_grafico($usu_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT tm_categoria.cat_nom as nom,COUNT(*) AS total
                FROM   tm_ticket  JOIN  
                    tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id  
                WHERE    
                tm_ticket.est = 1
                and tm_ticket.usu_id = ?
                GROUP BY 
                tm_categoria.cat_nom 
                ORDER BY total DESC";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function update_usuario_pass($usu_id, $usu_pass)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_usuario
                SET
                    usu_pass = MD5(?)
                WHERE
                    usu_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_pass);
        $sql->bindValue(2, $usu_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }



    public function guardarRutaArchivo($usu_id, $ruta)
    {
        try {
            $conectar = parent::conexion();
            if (!$conectar) {
                throw new Exception("No se pudo establecer la conexión a la base de datos");
            }
            parent::set_names();
            $sql = "UPDATE tm_usuario SET ruta_comprobante = ? WHERE usu_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $ruta, PDO::PARAM_STR);
            $stmt->bindValue(2, $usu_id, PDO::PARAM_INT);
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

    //Para descargar el comprobante
    public function obtenerRutaArchivo($usu_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT ruta_comprobante FROM tm_usuario WHERE usu_id = ? LIMIT 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['ruta_comprobante'] : '';
        } catch (Exception $e) {
            return '';
        }
    }

    //Obtener el Qr de equipo
    public function get_qr_usuario_equipo($usu_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT qr_codigo FROM tm_usuario WHERE usu_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   


    public function get_usuario_id_qr($usu_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_usuario WHERE usu_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }




    public function update_qr_usuario($usu_id, $qr_codigo)
    {
        $conectar = parent::conexion();
        parent::set_names();

        if (!$conectar) {
            throw new Exception("Error en la conexión a la base de datos");
        }

        $sql = "UPDATE tm_usuario SET qr_codigo = ? WHERE usu_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $qr_codigo, PDO::PARAM_STR);
        $stmt->bindValue(2, $usu_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
