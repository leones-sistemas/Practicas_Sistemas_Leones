<?php
date_default_timezone_set('America/Lima');
require_once "autoload.php";

use ajax\requests\marketing;
use routes\router;
use routes\route;
use enum\login;
$login =  new route("login");
$dashboard =  new route("dashboard",true);
$asistencia = new route("asistencia",true);
$empleados = new route("usuarios",true);
$repoasis = new route("repoasis",true);
$actividades = new route("actividades",true);
$coordenadas = new route("coordenadas",true);
$diario = new route("diario",true);
$repoacti = new route("repoacti",true);
$valacti = new route("valacti",true);
$perfil = new route("perfil",true);
$regacti = new route("regacti",true);
$seguimiento = new route("seguimiento",true);
$boleta = new route("boleta",true);
$repocli = new route("repocli",true);
$reposegui = new route("reposegui",true);
$dashboardcrm = new route("dashboardcrm",true);
$repoa = new route("repoa",true);
$metas = new route("metas",true);
$titulathon = new route("titulathon",true);
$actisec = new route("actisec",true);
$repoactisec = new route("repoactisec",true);
$clientesec = new route("clientesec",true);
$comparativo = new route("comparativo",true);
$calendario = new route("calendario",true);
$mktsol = new route("mktsol",true);
$mktrec = new route("mktrec",true);
$dashboardmkt = new route("dashboardmkt",true);
$tecsol = new route("tecsol",true);
$tecrec = new route("tecrec",true);
$dashboardtec = new route("dashboardtec",true);
$dashboardasis = new route("dashboardasis",true);
$kilometraje = new route("kilometraje",true);
$detallekilometraje = new route("detallekilometraje",true);
$retratados = new route("retratados",true);
$proyectos = new route("proyectos",true);


/* api */
$api_user = new route("user",false,"api");
$api_person = new route("persona",false,"api");
$api_contratos = new route("contratos",false,"api");
$api_asistencias = new route("asistencias",false,"api");
$api_actividades = new route("apiactividades",false,"api");
$api_clientes = new route("clientes",false,"api");
$api_landing = new route("landing",false,"api");
$push = new route("push",false,"api");
$marketing = new route("marketing",false,"api");
$tecnicos = new route("tecnicos",false,"api");


$router = new router();
$router->add($login);
$router->add($dashboard);
$router->add($asistencia);
$router->add($empleados);
$router->add($repoasis);
$router->add($actividades);
$router->add($coordenadas);
$router->add($diario);
$router->add($repoacti);
$router->add($valacti);
$router->add($perfil);
$router->add($regacti);
$router->add($seguimiento);
$router->add($boleta);
$router->add($reposegui);
$router->add($repocli);
$router->add($dashboardcrm);
$router->add($repoa);
$router->add($metas);
$router->add($titulathon);
$router->add($repoactisec);
$router->add($clientesec);
$router->add($comparativo);
$router->add($calendario);
$router->add($mktsol);
$router->add($mktrec);
$router->add($dashboardmkt);
$router->add($tecsol);
$router->add($tecrec);
$router->add($dashboardtec);
$router->add($dashboardasis);
$router->add($kilometraje);
$router->add($detallekilometraje);
$router->add($retratados);
$router->add($proyectos);

$router->add($api_user);
$router->add($api_person);
$router->add($api_contratos);
$router->add($api_asistencias);
$router->add($api_actividades);
$router->add($api_clientes);
$router->add($api_landing);
$router->add($actisec);
$router->add($push);
$router->add($marketing);
$router->add($tecnicos);


$router->render();

#$fin = microtime(true);
/* $tiempo = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
echo "Tiempo total: " . $tiempo . " segundos"; */


/* ["logistica","asistente-administrativo","asesor-de-ventas","asistente-comercial","asistente-tecnico","community-manager","contabilidad","disenador-publicitario","gerente-comercial","jefe-de-marketing","jefe-tecnico","recursos-humanos"] */