<?php

namespace core;

use ajax\requests\validator;
use model\database;

class reports
{
    public static function CantidadHoras($desde, $hasta)
    {
        $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM actividades WHERE fecha BETWEEN '$desde' AND '$hasta'");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function CantidadAsesores($desde, $hasta)
    {
        $pdo = database::connect()->prepare("SELECT persona FROM actividades WHERE fecha BETWEEN '$desde' AND '$hasta' GROUP BY persona");
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function TopHorasProyecto($desde, $hasta)
    {
        $pdo = database::connect()->prepare("SELECT proyecto, FLOOR(SUM(valor_por_tarea)) AS minutos FROM ( SELECT d.proyecto, 60 / COUNT(d.id) OVER (PARTITION BY d.actividad) AS valor_por_tarea FROM actividades a JOIN detalle_actividades d ON a.id_actividad = d.actividad WHERE a.fecha BETWEEN '$desde' AND '$hasta' ) t GROUP BY proyecto ORDER BY minutos DESC LIMIT 1;");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function TopHorasActividad($desde, $hasta)
    {
        $pdo = database::connect()->prepare("SELECT tarea, FLOOR(SUM(valor_por_tarea)) AS minutos FROM ( SELECT d.tarea, 60 / COUNT(d.id) OVER (PARTITION BY d.actividad) AS valor_por_tarea FROM actividades a JOIN detalle_actividades d ON a.id_actividad = d.actividad WHERE a.fecha BETWEEN '$desde' AND '$hasta' ) t GROUP BY tarea  ORDER BY minutos DESC LIMIT 1;");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function ListaHorasProyecto($desde, $hasta)
    {
        $pdo = database::connect()->prepare("SELECT proyecto, FLOOR(SUM(valor_por_tarea)) AS minutos FROM ( SELECT d.proyecto, 60 / COUNT(d.id) OVER (PARTITION BY d.actividad) AS valor_por_tarea FROM actividades a JOIN detalle_actividades d ON a.id_actividad = d.actividad WHERE a.fecha BETWEEN '$desde' AND '$hasta') t GROUP BY proyecto ORDER BY minutos DESC");
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function ActividadesVerificadas($desde, $hasta, $proyecto)
    {
        $pdo = database::connect()->prepare("SELECT proyecto, FLOOR(SUM(valor_por_tarea)) AS minutos FROM ( SELECT d.proyecto, 60 / COUNT(d.id) OVER (PARTITION BY d.actividad) AS valor_por_tarea FROM actividades a JOIN detalle_actividades d ON a.id_actividad = d.actividad WHERE a.fecha BETWEEN '$desde' AND '$hasta' AND a.estado = 1) t WHERE t.proyecto=$proyecto ORDER BY minutos DESC LIMIT 1;");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function ListarDetalleHorasProyecto($desde, $hasta)
    {
        $pdo = database::connect()->prepare("SELECT act.id_actividad,per.nombres, per.apellido_paterno, per.apellido_materno, act.hora, act.descripcion, act.fecha, act.fecha_actualizacion, act.estado FROM actividades act INNER JOIN personas per ON act.persona=per.id_persona WHERE act.fecha BETWEEN '$desde' AND '$hasta' ORDER BY act.persona, act.fecha, act.hora ASC");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function ListaHorasProyectoIndividual($desde, $hasta, $persona)
    {
        $pdo = database::connect()->prepare("SELECT proyecto, FLOOR(SUM(valor_por_tarea)) AS minutos FROM ( SELECT d.proyecto, 60 / COUNT(d.id) OVER (PARTITION BY d.actividad) AS valor_por_tarea FROM actividades a JOIN detalle_actividades d ON a.id_actividad = d.actividad WHERE a.fecha BETWEEN '$desde' AND '$hasta' AND a.persona=$persona) t GROUP BY proyecto ORDER BY minutos DESC");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function TopHorasActividadIndividual($desde, $hasta, $persona, $proyecto)
    {
        $pdo = database::connect()->prepare("SELECT proyecto, tarea, FLOOR(SUM(minutos)) AS minutos FROM ( SELECT d.proyecto, d.tarea, 60 / COUNT(d.id) OVER (PARTITION BY d.actividad) AS minutos FROM actividades a JOIN detalle_actividades d ON a.id_actividad = d.actividad WHERE a.fecha BETWEEN '$desde' AND '$hasta' AND a.persona = $persona ) t WHERE proyecto = $proyecto GROUP BY proyecto, tarea ORDER BY minutos DESC;");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function ListaRecordatorio($user)
    {
        $inicio = date('Y-m-d 00:00:00');
        $fin = date('Y-m-d 00:00:00', strtotime('+1 day'));
        $pdo = database::connect()->prepare("SELECT cli.nombres,cli.apellidos,cli.celular, seg.proyecto,sc.descripcion,sc.fecha_hora FROM seguicita sc INNER JOIN (seguimiento seg INNER JOIN clientes cli ON seg.cliente = cli.id) ON sc.seguimiento=seg.id WHERE seg.registro = $user AND fecha_hora >= '$inicio' AND fecha_hora < '$fin' AND sc.visita=0 ORDER BY sc.fecha_hora");
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function usersNotAssist($date)
    {
        $pdo = database::connect()->prepare("SELECT u.* FROM users u WHERE NOT EXISTS ( SELECT 1 FROM asistencias a WHERE a.usuario = u.id AND a.fecha = '$date' );");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function cliente_exists($desde, $hasta, $tipo, $search = true)
    {
        if ($search) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM clientes WHERE created_at BETWEEN '$desde' AND '$hasta' AND origen = '$tipo';");
            $pdo->execute();
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM clientes WHERE created_at BETWEEN '$desde' AND '$hasta' ;");
            $pdo->execute();
        }

        return $pdo->fetch();
    }

    public static function dist_cli($desde, $hasta)
    {
        $pdo = database::connect()->prepare("SELECT us.dni,COUNT(*) as Cantidad FROM clientes cli INNER JOIN users us ON cli.register_by = us.id WHERE cli.created_at BETWEEN '$desde' AND '$hasta' GROUP BY cli.register_by ORDER BY Cantidad DESC;");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function listar_clientes($origen, $desde, $hasta)
    {
        if ($origen == 'all') {
            $pdo = database::connect()->prepare("SELECT * FROM clientes WHERE created_at BETWEEN '$desde' AND '$hasta' ORDER BY nombres");
        } else {
            $pdo = database::connect()->prepare("SELECT * FROM clientes WHERE origen = '$origen' AND created_at BETWEEN '$desde' AND '$hasta' ORDER BY nombres");
        }

        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function listarPersonalActivo()
    {
        $pdo = database::connect()->prepare("SELECT p.id_persona,p.nombres, p.apellido_paterno, p.apellido_materno,p.numero_documento FROM personas p LEFT JOIN contratos c ON p.id_persona = c.id_persona WHERE (c.puesto = 'Asesor de Ventas' OR c.puesto = 'Jefe de Equipo') AND p.estado = 'activo' ORDER BY id_persona ASC");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function cantidadLlamadas($user, $desde, $hasta)
    {
        if ($user == '') {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicontac WHERE fecha_hora BETWEEN '$desde' AND '$hasta' AND tipo = 0 ");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicontac sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND tipo = 0 AND s.registro = $user");
        }
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function cantidadAgendas($user, $desde, $hasta)
    {
        if ($user == '') {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita WHERE fecha_registro BETWEEN '$desde' AND '$hasta'");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_registro BETWEEN '$desde' AND '$hasta'AND s.registro = $user");
        }
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function cantidadVisitas($user, $desde, $hasta)
    {
        if ($user == '') {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita WHERE fecha_registro BETWEEN '$desde' AND '$hasta' AND visita = 1");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_registro BETWEEN '$desde' AND '$hasta'AND s.registro = $user  AND visita = 1");
        }
        $pdo->execute();
        return $pdo->fetch();
    }

    public static function ListarTotalLlamadas($desde, $hasta)
    {
        $pdo = database::connect()->prepare("SELECT s.registro,COUNT(*) as Cantidad FROM seguicontac sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND tipo = 0 GROUP BY s.registro ORDER BY Cantidad DESC");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function ListarSeguimientos($user, $desde, $hasta)
    {
        if ($user == '') {
            $pdo = database::connect()->prepare("SELECT s.id,c.nombres,c.celular,c.origen,s.proyecto,s.estado,s.registro FROM seguimiento s INNER JOIN clientes c ON s.cliente=c.id INNER JOIN users u ON s.registro = u.id WHERE s.fecha_registro BETWEEN '$desde' AND '$hasta' AND s.estado !=10 ORDER BY s.registro,s.estado DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.id,c.nombres,c.celular,c.origen,s.proyecto,s.estado,s.registro FROM seguimiento s INNER JOIN clientes c ON s.cliente=c.id INNER JOIN users u ON s.registro = u.id WHERE s.fecha_registro BETWEEN '$desde' AND '$hasta' AND s.registro = $user AND s.estado !=10  ORDER BY s.registro,s.estado DESC");
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function LlamadasxSeguimiento($seguimiento)
    {
        $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicontac sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.tipo = 0 AND sc.seguimiento=$seguimiento");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function CitasxSeguimiento($seguimiento)
    {
        $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.seguimiento=$seguimiento");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function VisitasxSeguimiento($seguimiento)
    {
        $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.seguimiento=$seguimiento AND sc.visita=1");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function DetallecantidadLlamadas($user, $desde, $hasta)
    {
        if ($user == '') {
            $pdo = database::connect()->prepare("SELECT s.registro,s.proyecto,s.estado,c.nombres,c.apellidos,c.celular,sc.fecha_hora FROM seguicontac sc INNER JOIN (seguimiento s INNER JOIN clientes c ON s.cliente=c.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND tipo = 0 ORDER BY s.registro,sc.fecha_hora DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.registro,s.proyecto,s.estado,c.nombres,c.apellidos,c.celular,sc.fecha_hora FROM seguicontac sc INNER JOIN (seguimiento s INNER JOIN clientes c ON s.cliente=c.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND tipo = 0 AND s.registro = $user ORDER BY s.registro,sc.fecha_hora DESC");
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function DetalleCantidadCitas($user, $desde, $hasta)
    {
        if ($user == '') {
            $pdo = database::connect()->prepare("SELECT s.registro,s.proyecto,s.estado,c.nombres,c.apellidos,c.celular,sc.fecha_hora FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes c ON s.cliente=c.id) ON sc.seguimiento = s.id WHERE sc.fecha_registro BETWEEN '$desde' AND '$hasta' ORDER BY s.registro,sc.fecha_hora DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.registro,s.proyecto,s.estado,c.nombres,c.apellidos,c.celular,sc.fecha_hora FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes c ON s.cliente=c.id) ON sc.seguimiento = s.id WHERE sc.fecha_registro BETWEEN '$desde' AND '$hasta' AND s.registro = $user ORDER BY s.registro,sc.fecha_hora DESC");
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function DetalleCantidadVisitas($user, $desde, $hasta)
    {
        if ($user == '') {
            $pdo = database::connect()->prepare("SELECT s.registro,s.proyecto,s.estado,c.nombres,c.apellidos,c.celular,sc.fecha_hora FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes c ON s.cliente=c.id) ON sc.seguimiento = s.id WHERE s.fecha_registro BETWEEN '$desde' AND '$hasta' AND sc.visita = 1 ORDER BY s.registro,sc.fecha_hora DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.registro,s.proyecto,s.estado,c.nombres,c.apellidos,c.celular,sc.fecha_hora FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes c ON s.cliente=c.id) ON sc.seguimiento = s.id WHERE s.fecha_registro BETWEEN '$desde' AND '$hasta' AND s.registro = $user AND sc.visita = 1 ORDER BY s.registro,sc.fecha_hora DESC");
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }

    /* DASHBOARD */
    public static function ClientesMes($desde, $hasta, $registro)
    {
        if ($registro == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM clientes WHERE created_at BETWEEN '$desde' AND '$hasta'");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM clientes WHERE created_at BETWEEN '$desde' AND '$hasta' AND register_by = $registro");
        }

        $pdo->execute();
        return $pdo->fetch();
    }
    public static function SeguimientosMes($desde, $hasta, $registro)
    {
        if ($registro == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguimiento WHERE fecha_registro BETWEEN '$desde' AND '$hasta'");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguimiento WHERE fecha_registro BETWEEN '$desde' AND '$hasta' AND registro = $registro");
        }

        $pdo->execute();
        return $pdo->fetch();
    }
    public static function RetratamientosMes($desde, $hasta, $registro)
    {
        if ($registro == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguimiento WHERE fecha_registro BETWEEN '$desde' AND '$hasta' AND estado = 10");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguimiento WHERE fecha_registro BETWEEN '$desde' AND '$hasta' AND estado = 10 AND registro = $registro");
        }
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function LlamadasMes($desde, $hasta, $registro)
    {
        if ($registro == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicontac WHERE fecha_hora BETWEEN '$desde' AND '$hasta' ");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicontac sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND s.registro = $registro ");
        }
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function CitasMes($desde, $hasta, $registro)
    {
        if ($registro == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita WHERE fecha_hora BETWEEN '$desde' AND '$hasta'");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND s.registro = $registro");
        }
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function VisitasMes($desde, $hasta, $registro)
    {
        if ($registro == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita WHERE fecha_hora BETWEEN '$desde' AND '$hasta' AND visita = 1");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.visita = 1 AND s.registro = $registro");
        }
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function SeguimientosProyectoMes($desde, $hasta, $registro)
    {
        if ($registro == 0) {
            $pdo = database::connect()->prepare("SELECT proyecto,COUNT(*) as Cantidad FROM seguimiento WHERE fecha_registro BETWEEN '$desde' AND '$hasta' GROUP BY proyecto ORDER BY Cantidad DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT proyecto,COUNT(*) as Cantidad FROM seguimiento WHERE fecha_registro BETWEEN '$desde' AND '$hasta' AND registro = $registro GROUP BY proyecto ORDER BY Cantidad DESC");
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function EstadosUserMes($estado, $user)
    {
        $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguimiento WHERE estado = $estado AND registro = $user");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function MetasLlamadas($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicontac WHERE fecha_hora BETWEEN '$desde' AND '$hasta'");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicontac sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND s.registro = $user");
        }

        $pdo->execute();
        return $pdo->fetch();
    }
    public static function MetasCitas($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita WHERE fecha_hora BETWEEN '$desde' AND '$hasta'");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND s.registro = $user");
        }
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function MetasVisitas($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita WHERE fecha_hora BETWEEN '$desde' AND '$hasta' AND visita = 1");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita sc INNER JOIN seguimiento s ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.visita = 1 AND s.registro=$user");
        }

        $pdo->execute();
        return $pdo->fetch();
    }
    public static function MetasMarketing($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM mkt_solicitud WHERE fecha_registro BETWEEN '$desde' AND '$hasta' AND registro != 23");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM mkt_solicitud WHERE fecha_registro BETWEEN '$desde' AND '$hasta' AND registro = $user");
        }

        $pdo->execute();
        return $pdo->fetch();
    }
    public static function clientes_sec()
    {
        $pdo = database::connect()->prepare('SELECT asignado FROM clientes WHERE asignado IS NOT NULL ORDER BY id DESC LIMIT 1');

        $pdo->execute();
        return $pdo->fetch();
    }
    public static function MetasLlamadasTotal($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro FROM seguicontac sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' ORDER BY us.dni ASC,sc.fecha_hora DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro FROM seguicontac sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND s.registro = $user ORDER BY us.dni ASC,sc.fecha_hora DESC");
        }

        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function MetasCitasTotal($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' ORDER BY us.dni ASC,sc.fecha_hora DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND s.registro = $user ORDER BY us.dni ASC,sc.fecha_hora DESC");
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function MetasVisitasTotal($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.visita = 1 ORDER BY us.dni ASC,sc.fecha_hora DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.visita = 1 AND s.registro=$user ORDER BY us.dni ASC,sc.fecha_hora DESC");
        }

        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function MetasMarketingTotal($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT * FROM mkt_solicitud WHERE fecha_registro BETWEEN '$desde' AND '$hasta' AND registro != 23 ORDER BY fecha_registro DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT * FROM mkt_solicitud WHERE fecha_registro BETWEEN '$desde' AND '$hasta' AND registro = $user ORDER BY fecha_registro DESC");
        }

        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function ClientesAsignados($filtro, $pagina, $consulta)
    {
        if ($pagina != 1) {
            $pagina = ($pagina - 1) * 20;
        } else {
            $pagina = 0;
        }

        if ($filtro == null) {
            $pdo = database::connect()->prepare("SELECT id,cli.nombres nc,cli.apellidos ac,cli.celular cc, p.nombres na, p.numero_documento,origen,notas,asignado,created_at FROM clientes cli INNER JOIN personas p ON cli.asignado = p.id_persona WHERE asignado IS NOT NULL $consulta ORDER BY cli.id DESC LIMIT $pagina,20");
        } else {
            $pdo = database::connect()->prepare("SELECT id,cli.nombres nc,cli.apellidos ac,cli.celular cc, p.nombres na, p.numero_documento,origen,notas,asignado,created_at FROM clientes cli INNER JOIN personas p ON cli.asignado = p.id_persona WHERE asignado = $filtro $consulta AND asignado IS NOT NULL ORDER BY cli.id DESC LIMIT $pagina,20");
        }

        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function CantidadClientesAsignados($filtro, $consulta)
    {
        if ($filtro == null) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as numero FROM clientes cli INNER JOIN personas p ON cli.asignado = p.id_persona WHERE asignado IS NOT NULL $consulta ORDER BY cli.id DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as numero FROM clientes cli INNER JOIN personas p ON cli.asignado = p.id_persona WHERE asignado = $filtro $consulta AND asignado IS NOT NULL ORDER BY cli.id DESC");
        }

        $pdo->execute();
        return $pdo->fetch();
    }

    /* public static function ClientesAsignados($filtro)
    {
        if ($filtro == null) {
            $pdo = database::connect()->prepare("SELECT id,cli.nombres nc,cli.apellidos ac,cli.celular cc, p.nombres na, p.numero_documento,origen,notas,asignado,created_at FROM clientes cli INNER JOIN personas p ON cli.asignado = p.id_persona WHERE asignado IS NOT NULL ORDER BY cli.id DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT id,cli.nombres nc,cli.apellidos ac,cli.celular cc, p.nombres na, p.numero_documento,origen,notas,asignado,created_at FROM clientes cli INNER JOIN personas p ON cli.asignado = p.id_persona WHERE asignado = $filtro AND asignado IS NOT NULL ORDER BY cli.id DESC");
        }

        $pdo->execute();
        return $pdo->fetchAll();
    } */

    public static function calendario($fecha, $user)
    {
        if ($user == null) {
            $pdo = database::connect()->prepare("SELECT sc.id,s.registro,s.proyecto, c.celular, c.nombres, TIME(sc.fecha_hora) hora, sc.visita, sc.apoyo,sc.descripcion,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes c ON s.cliente = c.id) ON sc.seguimiento=s.id WHERE DATE(fecha_hora) = '$fecha' ORDER BY hora ASC;");
        } else {
            $pdo = database::connect()->prepare("SELECT sc.id,s.registro,s.proyecto, c.celular, c.nombres, TIME(sc.fecha_hora) hora, sc.visita, sc.apoyo,sc.descripcion,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes c ON s.cliente = c.id) ON sc.seguimiento=s.id WHERE DATE(fecha_hora) = '$fecha' AND (s.registro = $user OR sc.apoyo =$user) ORDER BY hora ASC;");
        }


        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function totalSolicitudes($inicio = null, $fin = null)
    {
        $extra = "";
        if ($inicio != null && $fin != null) {
            $extra = "WHERE fecha_registro BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT SUM(cantidad) as total FROM mkt_solicitud $extra");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function totalTrabajos($estado = null, $inicio = null, $fin = null)
    {
        $extra = "";
        if ($inicio != null || $fin != null || $estado != null) {
            $extra .= "WHERE";
            if ($inicio != null && $fin != null) {
                $extra .= " fecha_pendiente BETWEEN '$inicio' AND '$fin'";
                if ($estado != null) {
                    $extra .= " AND";
                }
            }
            if ($estado != null) {
                $extra .= " estado = '$estado'";
            }
        }
        $pdo = database::connect()->prepare("SELECT COUNT(*) as total FROM mkt_recepcion $extra");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function responsables($inicio = null, $fin = null)
    {
        $extra = "";
        if ($inicio != null || $fin != null) {
            $extra .= "WHERE";
            $extra .= " rec.fecha_pendiente BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT COALESCE(p.nombres, 'SIN RELACION') AS nombre,COUNT(*) AS cantidad FROM mkt_recepcion rec LEFT JOIN personas p ON rec.relacion = p.id_persona $extra GROUP BY COALESCE(p.nombres, 'SIN RELACION') ORDER BY cantidad DESC");
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function actividades($inicio = null, $fin = null)
    {
        $extra = "";
        if ($inicio != null || $fin != null) {
            $extra .= "WHERE";
            $extra .= " fecha_pendiente BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT actividad, COUNT(*) AS cantidad FROM mkt_recepcion $extra GROUP BY actividad ORDER BY cantidad DESC");
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function produccion($inicio = null, $fin = null)
    {
        $pdo = database::connect()->prepare("WITH RECURSIVE dias AS (

            SELECT DATE('$inicio') AS fecha

            UNION ALL

            SELECT DATE_ADD(fecha, INTERVAL 1 DAY)
            FROM dias
            WHERE fecha < '$fin'

        )

        SELECT 

            CASE MONTH(dias.fecha)

                WHEN 1 THEN CONCAT('Ene-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 2 THEN CONCAT('Feb-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 3 THEN CONCAT('Mar-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 4 THEN CONCAT('Abr-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 5 THEN CONCAT('May-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 6 THEN CONCAT('Jun-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 7 THEN CONCAT('Jul-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 8 THEN CONCAT('Ago-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 9 THEN CONCAT('Sep-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 10 THEN CONCAT('Oct-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 11 THEN CONCAT('Nov-', DATE_FORMAT(dias.fecha,'%d'))
                WHEN 12 THEN CONCAT('Dic-', DATE_FORMAT(dias.fecha,'%d'))

            END AS fecha,

            COUNT(mkt_recepcion.fecha_culminado) AS cantidad

        FROM dias

        LEFT JOIN mkt_recepcion
        ON DATE(mkt_recepcion.fecha_culminado) = dias.fecha
        AND mkt_recepcion.fecha_culminado IS NOT NULL

        GROUP BY dias.fecha

        ORDER BY dias.fecha;");
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function trabajos($inicio = null, $fin = null)
    {
        $extra = "WHERE mr.estado != 'CULMINADO'";
        if ($inicio != null || $fin != null) {
            $extra = "";
            $extra .= "WHERE";
            $extra .= " mr.fecha_pendiente BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT mr.id,mr.proyecto,ms.titulo, ms.detalle, mr.actividad, mr.relacion, mr.observacion, mr.porcentaje, mr.estado,mr.asignado, DATE(mr.fecha_pendiente) as fecha FROM mkt_recepcion mr INNER JOIN mkt_solicitud ms ON mr.solicitud=ms.id $extra ORDER BY mr.porcentaje DESC, mr.fecha_pendiente DESC");
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function proyectos($inicio = null, $fin = null)
    {
        $extra = "WHERE estado = 'CULMINADO'";
        if ($inicio != null && $fin != null) {
            $extra .= " AND fecha_pendiente BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT proyecto,COUNT(*) as total FROM mkt_recepcion $extra GROUP BY proyecto ORDER BY total DESC LIMIT 1");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function productividad($inicio = null, $fin = null)
    {
        $extra = "WHERE estado = 'CULMINADO'";
        if ($inicio != null && $fin != null) {
            $extra .= " AND fecha_pendiente BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT asignado,COUNT(*) as total FROM mkt_recepcion $extra GROUP BY asignado ORDER BY total DESC LIMIT 1");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function carga($inicio = null, $fin = null)
    {
        $extra = "";
        if ($inicio != null && $fin != null) {
            $extra = "WHERE fecha_pendiente BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT actividad, COUNT(*) AS cantidad, ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER (), 2) AS porcentaje FROM mkt_recepcion $extra GROUP BY actividad ORDER BY porcentaje DESC LIMIT 1");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function responsable($inicio = null, $fin = null)
    {
        $extra = "";
        if ($inicio != null || $fin != null) {
            $extra .= "WHERE";
            $extra .= " rec.fecha_pendiente BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT CONCAT(p.nombres, ' ', p.apellido_paterno, ' ', p.apellido_materno) AS nombres, COUNT(*) AS cantidad, ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER (), 2) AS porcentaje FROM mkt_recepcion rec LEFT JOIN personas p ON rec.relacion = p.id_persona $extra GROUP BY rec.relacion ORDER BY cantidad DESC LIMIT 1");
        $pdo->execute();
        return $pdo->fetch();
    }

    public static function individualMkt($inicio = null, $fin = null)
    {
        $extra = "WHERE rec.estado = 'CULMINADO'";
        if ($inicio != null || $fin != null) {
            $extra .= " AND rec.fecha_culminado BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT asignado, COUNT(*) AS cantidad FROM mkt_recepcion rec $extra GROUP BY asignado ORDER BY cantidad DESC");
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function individualActividadMkt($inicio = null, $fin = null, $trabajador = null)
    {
        $extra = "WHERE rec.asignado = $trabajador AND rec.estado = 'CULMINADO'";
        if ($inicio != null || $fin != null) {
            $extra .= " AND rec.fecha_culminado BETWEEN '$inicio' AND '$fin'";
        }
        $pdo = database::connect()->prepare("SELECT actividad, COUNT(*) AS cantidad FROM mkt_recepcion rec $extra GROUP BY actividad ORDER BY cantidad DESC");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function clientesDash($desde, $hasta, $tipo)
    {
        if ($tipo == 0) {
            if ($desde == $hasta) {
                $pdo = database::connect()->prepare("SELECT * FROM clientes ORDER BY id DESC;");
            } else {
                $pdo = database::connect()->prepare("SELECT * FROM clientes WHERE created_at BETWEEN '$desde' AND '$hasta' ORDER BY id DESC;");
            }
        } else {
            if ($tipo == 1) {
                $condicion = " asignado IS NULL";
            } else {
                $condicion = " asignado IS NOT NULL";
            }
            if ($desde == $hasta) {
                $pdo = database::connect()->prepare("SELECT * FROM clientes WHERE $condicion ORDER BY id DESC;");
            } else {
                $pdo = database::connect()->prepare("SELECT * FROM clientes WHERE created_at BETWEEN '$desde' AND '$hasta' AND $condicion ORDER BY id DESC;");
            }
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function clientesDashAsignado($desde, $hasta)
    {
        if ($desde == $hasta) {
            $pdo = database::connect()->prepare("SELECT asignado, COUNT(*) as cantidad FROM clientes WHERE asignado IS NOT NULL GROUP BY asignado");
            $pdo->execute();
        } else {
            $pdo = database::connect()->prepare("SELECT asignado, COUNT(*) as cantidad FROM clientes WHERE asignado IS NOT NULL AND created_at BETWEEN '$desde' AND '$hasta' GROUP BY asignado");
            $pdo->execute();
        }

        return $pdo->fetchAll();
    }
    public static function clientesDashFuente($desde, $hasta)
    {
        if ($desde == $hasta) {
            $pdo = database::connect()->prepare("SELECT origen, COUNT(*) as cantidad FROM clientes  WHERE register_by = 43 GROUP BY origen");
        } else {
            $pdo = database::connect()->prepare("SELECT origen, COUNT(*) as cantidad FROM clientes  WHERE register_by = 43 AND created_at BETWEEN '$desde' AND '$hasta' GROUP BY origen");
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function clientesDashInteres($desde, $hasta)
    {
        if ($desde == $hasta) {
            $pdo = database::connect()->prepare("SELECT proyecto as cantidad, COUNT(*) as clientes FROM clientes  WHERE register_by != 43 AND proyecto IS NOT NULL GROUP BY proyecto ORDER BY clientes DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT proyecto as cantidad, COUNT(*) as clientes FROM clientes  WHERE register_by != 43  AND proyecto IS NOT NULL AND created_at BETWEEN '$desde' AND '$hasta' GROUP BY proyecto ORDER BY clientes DESC");
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function seguimiento_secretaria($desde, $hasta)
    {
        if ($desde == $hasta) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as seg FROM clientes LEFT JOIN seguimiento ON clientes.id = seguimiento.cliente WHERE register_by = 43 AND seguimiento.id IS NOT NULL;");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as seg FROM clientes LEFT JOIN seguimiento ON clientes.id = seguimiento.cliente WHERE register_by = 43 AND seguimiento.id IS NOT NULL AND created_at BETWEEN '$desde' AND '$hasta';");
        }

        $pdo->execute();
        return $pdo->fetch();
    }
    public static function listar_seguimiento_secretaria()
    {
        $pdo = database::connect()->prepare("SELECT personas.nombres as responsable, clientes.nombres as cliente, clientes.celular, clientes.proyecto, clientes.created_at FROM clientes INNER JOIN personas ON clientes.asignado = personas.id_persona LEFT JOIN seguimiento ON clientes.id = seguimiento.cliente WHERE register_by = 43 AND seguimiento.id IS NULL;");

        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function lista_envio()
    {
        $pdo = database::connect()->prepare("SELECT token FROM tokens WHERE usuario = 14 OR usuario = 23 OR usuario = 41 OR usuario = 43 OR usuario = 44 OR usuario = 36");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function clientes_retratamientos()
    {
        $user = validator::userId();
        $usuario = models::CrudVeerM("*", "users", false, array(["id", "=", $user]));
        $data = models::CrudVeerM("con.puesto", "contratos con INNER JOIN personas per ON con.id_persona = per.id_persona", false, array(["per.numero_documento", "=", $usuario["dni"]]));
        $area = $data["puesto"];

        if ($area == "Asesor de Ventas") {
            $celular = "CONCAT(LEFT(cli.celular, 1), '********') as celular";
            $condicion = "AND s.registro = $user";
        } else {
            $celular = "cli.celular";
            $condicion = "";
        }

        $pdo = database::connect()->prepare("SELECT 
        CONCAT(cli.nombres, ' ', cli.apellidos) AS cliente,
        $celular,

        CASE s.proyecto
            WHEN 0 THEN 'No menciona / No opina'
            WHEN 1 THEN 'Loma verde I'
            WHEN 2 THEN 'Loma verde II'
            WHEN 3 THEN 'Huaytapallana'
            WHEN 4 THEN 'Manantiales'
            WHEN 5 THEN 'Tupac Amaru I'
            WHEN 6 THEN 'Tupac Amaru II'
            WHEN 7 THEN 'Heroinas Toledo'
            WHEN 8 THEN 'San Roque'
            WHEN 9 THEN 'Nueva Colpa'
            WHEN 10 THEN 'Buenos Aires'
            WHEN 11 THEN 'Huracan'
            WHEN 12 THEN 'Chalay'
            WHEN 13 THEN 'Oficina Central'
            WHEN 14 THEN 'Residencial San Agustin'
            WHEN 15 THEN 'Huracan 2'
            WHEN 16 THEN 'Chalay 2'
            WHEN 17 THEN 'Residencial San Agustin 2'
            ELSE 'Proyecto desconocido'
        END AS proyecto,

        CONCAT(
            p.nombres, ' ',
            p.apellido_paterno, ' ',
            p.apellido_materno
        ) AS asesor,

        s.fecha_registro,
        s.comentario,
        s.actualizacion_comentario

    FROM seguimiento s

    INNER JOIN (
        users us
        INNER JOIN personas p 
            ON us.dni COLLATE utf8mb4_unicode_ci =
            p.numero_documento COLLATE utf8mb4_unicode_ci
    )
        ON s.registro = us.id

    INNER JOIN clientes cli 
        ON s.cliente = cli.id

    WHERE s.estado = 10 $condicion

    ORDER BY s.actualizacion_comentario DESC;");
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function estado_cita($inicio, $final, $estado)
    {
        $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita WHERE fecha_hora BETWEEN '$inicio' AND '$final' AND visita = $estado");
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function CanceloVisitasTotal($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.visita = 2 ORDER BY us.dni ASC,sc.fecha_hora DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.visita = 2 AND s.registro=$user ORDER BY us.dni ASC,sc.fecha_hora DESC");
        }

        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function NoLlegoVisitasTotal($user, $desde, $hasta)
    {
        if ($user == 0) {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.visita = 3 ORDER BY us.dni ASC,sc.fecha_hora DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.visita = 3 AND s.registro=$user ORDER BY us.dni ASC,sc.fecha_hora DESC");
        }

        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function calendario_actividades($fecha)
    {
        $pdo = database::connect()->prepare("SELECT * FROM calendario WHERE DATE(fecha_actividad) = '$fecha' ORDER BY fecha_actividad ASC");
        $pdo->execute();
        return $pdo->fetchAll();
    }
    public static function cantidad_recontactos($desde, $hasta)
    {
        if ($desde == $hasta) {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as cantidad FROM reasignados");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as cantidad FROM reasignados  WHERE fecha_registro BETWEEN '$desde' AND '$hasta'");
        }
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function buscar_recontactos($desde, $hasta)
    {
        if ($desde == $hasta) {
            $pdo = database::connect()->prepare("SELECT CONCAT(c.nombres, ' ', c.apellidos) as nombre_completo,c.celular,r.proyecto,r.asesor,r.fecha_registro FROM reasignados r INNER JOIN clientes c ON r.cliente = c.id");
        } else {
            $pdo = database::connect()->prepare("SELECT CONCAT(c.nombres, ' ', c.apellidos) as nombre_completo,c.celular,r.proyecto,r.asesor,r.fecha_registro FROM reasignados r INNER JOIN clientes c ON r.cliente = c.id WHERE r.fecha_registro BETWEEN '$desde' AND '$hasta'");
        }
        $pdo->execute();
        return $pdo->fetchAll();
    }

    public static function tipo_cita($desde, $hasta, $tipo)
    {
        if ($tipo == "propio") {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita WHERE fecha_hora BETWEEN '$desde' AND '$hasta' AND apoyo IS NULL");
        } else {
            $pdo = database::connect()->prepare("SELECT COUNT(*) as Cantidad FROM seguicita WHERE fecha_hora BETWEEN '$desde' AND '$hasta' AND apoyo IS NOT NULL");
        }
        $pdo->execute();
        return $pdo->fetch();
    }
    public static function CitasTotalTipo($desde, $hasta, $tipo)
    {
        if ($tipo == "propio") {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.apoyo IS NULL ORDER BY us.dni ASC,sc.fecha_hora DESC");
        } else {
            $pdo = database::connect()->prepare("SELECT s.proyecto,cli.nombres,cli.apellidos,cli.celular,s.estado,us.dni,sc.fecha_hora,sc.descripcion,sc.fecha_registro,sc.detalle_visita FROM seguicita sc INNER JOIN (seguimiento s INNER JOIN clientes cli ON s.cliente = cli.id INNER JOIN users us ON s.registro = us.id) ON sc.seguimiento = s.id WHERE sc.fecha_hora BETWEEN '$desde' AND '$hasta' AND sc.apoyo IS NOT NULL ORDER BY us.dni ASC,sc.fecha_hora DESC");
        }

        $pdo->execute();
        return $pdo->fetchAll();
    }
}
