<?php

use core\models;

function obtenerIniciales(string $texto): string
{
    $palabras = preg_split('/\s+/', trim($texto));
    $iniciales = '';

    foreach ($palabras as $palabra) {
        $iniciales .= mb_strtoupper(mb_substr($palabra, 0, 1));
    }

    return $iniciales;
}
?>
<div class="attendance-card">

    <div class="attendance-header">

        <h2 class="attendance-title">
            Control de Horas de Trabajo
        </h2>

        <div class="attendance-badge">
            Junio 2026
        </div>

    </div>

    <div class="attendance-table-container">

        <table class="attendance-table">

            <thead class="attendance-thead">

                <tr class="attendance-head-row">

                    <th class="attendance-th attendance-th-worker">
                        Trabajador
                    </th>

                    <th class="attendance-th attendance-th-date">
                        Fecha
                    </th>

                    <th class="attendance-th attendance-th-hour">
                        Hora 1
                    </th>

                    <th class="attendance-th attendance-th-hour">
                        Hora 2
                    </th>

                    <th class="attendance-th attendance-th-hour">
                        Hora 3
                    </th>

                    <th class="attendance-th attendance-th-hour">
                        Hora 4
                    </th>

                    <th class="attendance-th attendance-th-hour">
                        Hora 5
                    </th>

                    <th class="attendance-th attendance-th-hour">
                        Hora 6
                    </th>

                    <th class="attendance-th attendance-th-hour">
                        Hora 7
                    </th>

                    <th class="attendance-th attendance-th-hour">
                        Hora 8
                    </th>

                   <!--  <th class="attendance-th attendance-th-total">
                        Total
                    </th> -->

                </tr>

            </thead>

            <tbody class="attendance-tbody">
                <?php
                $reporte = models::CrudVeerM("*", "activ_sec", true, null, "ORDER BY fecha DESC LIMIT 30");
                foreach ($reporte as $key => $value) {
                    $user = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["trabajador"]]));
                    $persona = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["numero_documento", "=", $user["dni"]]));
                    $hora1 = $value["hora1"]!=""?'<i class="fa-solid fa-circle-check watchDetail" data-type="Modal" data-target="detalles" data-descripcion="'.$value["hora1"].'" style="color:blue;font-size:24px;"></i>':'<i class="fa-solid fa-circle-xmark" style="color:red;font-size:24px"></i>';
                    $hora2 = $value["hora2"]!=""?'<i class="fa-solid fa-circle-check watchDetail" data-type="Modal" data-target="detalles" data-descripcion="'.$value["hora2"].'" style="color:blue;font-size:24px;"></i>':'<i class="fa-solid fa-circle-xmark" style="color:red;font-size:24px"></i>';
                    $hora3 = $value["hora3"]!=""?'<i class="fa-solid fa-circle-check watchDetail" data-type="Modal" data-target="detalles" data-descripcion="'.$value["hora3"].'" style="color:blue;font-size:24px;"></i>':'<i class="fa-solid fa-circle-xmark" style="color:red;font-size:24px"></i>';
                    $hora4 = $value["hora4"]!=""?'<i class="fa-solid fa-circle-check watchDetail" data-type="Modal" data-target="detalles" data-descripcion="'.$value["hora4"].'" style="color:blue;font-size:24px;"></i>':'<i class="fa-solid fa-circle-xmark" style="color:red;font-size:24px"></i>';
                    $hora5 = $value["hora5"]!=""?'<i class="fa-solid fa-circle-check watchDetail" data-type="Modal" data-target="detalles" data-descripcion="'.$value["hora5"].'" style="color:blue;font-size:24px;"></i>':'<i class="fa-solid fa-circle-xmark" style="color:red;font-size:24px"></i>';
                    $hora6 = $value["hora6"]!=""?'<i class="fa-solid fa-circle-check watchDetail" data-type="Modal" data-target="detalles" data-descripcion="'.$value["hora6"].'" style="color:blue;font-size:24px;"></i>':'<i class="fa-solid fa-circle-xmark" style="color:red;font-size:24px"></i>';
                    $hora7 = $value["hora7"]!=""?'<i class="fa-solid fa-circle-check watchDetail" data-type="Modal" data-target="detalles" data-descripcion="'.$value["hora7"].'" style="color:blue;font-size:24px;"></i>':'<i class="fa-solid fa-circle-xmark" style="color:red;font-size:24px"></i>';
                    $hora8 = $value["hora8"]!=""?'<i class="fa-solid fa-circle-check watchDetail" data-type="Modal" data-target="detalles" data-descripcion="'.$value["hora8"].'" style="color:blue;font-size:24px;"></i>':'<i class="fa-solid fa-circle-xmark" style="color:red;font-size:24px"></i>';
                    echo '
                         <tr class="attendance-row">

                    <td class="attendance-worker-cell">

                        <div class="attendance-worker">

                            <div class="attendance-avatar">
                                '.obtenerIniciales($persona["nombres"]).'
                            </div>

                            <span class="attendance-worker-name">
                                '.$persona["nombres"].'
                            </span>

                        </div>

                    </td>

                    <td class="attendance-date">
                        '.$value["fecha"].'
                    </td>

                    <td class="attendance-hour">
                        <span class="attendance-hour-chip">'.$hora1.'</span>
                    </td>

                    <td class="attendance-hour">
                        <span class="attendance-hour-chip">'.$hora2.'</span>
                    </td>

                    <td class="attendance-hour">
                        <span class="attendance-hour-chip">'.$hora3.'</span>
                    </td>

                    <td class="attendance-hour">
                        <span class="attendance-hour-chip">'.$hora4.'</span>
                    </td>

                    <td class="attendance-hour">
                        <span class="attendance-hour-chip">'.$hora5.'</span>
                    </td>

                    <td class="attendance-hour">
                        <span class="attendance-hour-chip">'.$hora6.'</span>
                    </td>

                    <td class="attendance-hour">
                        <span class="attendance-hour-chip">'.$hora7.'</span>
                    </td>

                    <td class="attendance-hour">
                        <span class="attendance-hour-chip">'.$hora8.'</span>
                    </td>



                </tr>
                    ';
                }
                ?>

<!--                     <td class="attendance-total">
                        <span class="attendance-total-chip">
                            8 h
                        </span>
                    </td> -->
            </tbody>

        </table>

    </div>

</div>