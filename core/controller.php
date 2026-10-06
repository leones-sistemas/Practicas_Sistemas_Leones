<?php
namespace core;
use PDO;
class controller{
    /* CREATE */
    public function crudCrear($data){
        $parametros = array();
        $valores = array();
        $vartipo = self::typeCrud($data);
        foreach ($data as $key=>$value)
        {  
            array_push($parametros,$key);
            array_push($valores,":".$key);
        }
        $resultado= array("parametros"=>implode(",", $parametros),"valores"=>implode(",", $valores),"vartipo"=>$vartipo);
        return $resultado;
    }
    public function crudActualizar($data){
        $parametros="";
        $vartipo = self::typeCrud($data);
        foreach($data as $key=>$value)
        {
            $parametros.=$key.'=:'.$key.',';
        }
        $resultado = array("parametros"=>substr($parametros, 0, -1),"vartipo"=>$vartipo);
        return $resultado;
    }
    public function sanitizarCondicion($condicion){
        $estructura = "";
        $condiciones = array("&&"=>" AND ","||"=>" OR ");
        $contador = 0;
        $data = array();
        for($i=0;$i<count($condicion);$i++)
        {
            if(is_array($condicion[$i])){
                if(str_contains($condicion[$i][0],"."))
                {
                    $sanar = explode(".",$condicion[$i][0]);
                    $san = $sanar[1];
                }else
                {
                    $san = $condicion[$i][0];
                }
                $clave = $san.chr(($contador+1) + 64);
                $estructura .= $condicion[$i][0].$condicion[$i][1].":".$clave;
                $data[$clave]=$condicion[$i][2];
                $contador++;
            }else{
                if(isset($condiciones[$condicion[$i]]))
                {
                    $estructura .= $condiciones[$condicion[$i]];
                }else{
                    $estructura .= $condicion[$i];
                }

            }
        }
        $vartipo = self::typeCrud($data);
        $resultado = array("parametros"=>$estructura,"data"=>$data,"vartipo"=>$vartipo);
        return $resultado;
    }
    public function sanitizarDatos($pdo,$data,$tipo){
        foreach ($data as $key=>$value)
        {
            if(is_string($key))
            {
                !is_array($value) ? $pdo -> bindParam($key, $data[$key], $tipo[$key]) : $pdo -> bindParam($key, $data[$key][1], $tipo[$key]);
            }
        }
    }
    static public function typeCrud($data){
        $tipos = array("string"=>PDO::PARAM_STR,"double"=>PDO::PARAM_STR,"float"=>PDO::PARAM_STR,"integer"=>PDO::PARAM_INT,"NULL"=>PDO::PARAM_NULL,"boolean" => PDO::PARAM_BOOL);
        $vartipo = array();
        foreach ($data as $key=>$value)
        {  
                $vartipo[$key]=$tipos[gettype($value)];
        }
        return $vartipo;
    }
} 

?>