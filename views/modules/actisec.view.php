<?php

use ajax\requests\validator;
use core\fecha;
use core\models;
$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
$day = (isset($fecha[2])) ? ($fecha[2] != "" ? $fecha[2] : fecha::this()) : fecha::this();
    $datos = models::CrudVeerM("*","activ_sec",false,array(["fecha","=",$day],"&&",["trabajador","=",validator::userId()]));
    if(isset($datos["id"])){
        $hora1 = $datos["hora1"];
        $hora2 = $datos["hora2"];
        $hora3 = $datos["hora3"];
        $hora4 = $datos["hora4"];
        $hora5 = $datos["hora5"];
        $hora6 = $datos["hora6"];
        $hora7 = $datos["hora7"];
        $hora8 = $datos["hora8"];
    }else{
        $hora1 = "";
        $hora2 = "";
        $hora3 = "";
        $hora4 = "";
        $hora5 = "";
        $hora6 = "";
        $hora7 = "";
        $hora8 = "";
    }
?>
<section class="daily-report">

    <div class="daily-report__card">

        <header class="daily-report__header">

            <h1 class="daily-report__title">
                Reporte Diario de Actividades
            </h1>

            <p class="daily-report__subtitle">
                Registro detallado de actividades realizadas durante la jornada laboral.
            </p>

        </header>

        <div class="daily-report__content">

            <div class="report-information">

                <div class="form-group">
                    <label class="form-group__label">
                        Fecha
                    </label>
                    <input
                        type="date"
                        class="form-group__input"
                        value="<?= $day ?>"
                        id="fecha_activ" disabled>
                </div>

            </div>

            <div class="activity-table-wrapper">

                <table class="activity-table">

                    <thead class="activity-table__head">
                        <tr>
                            <th class="activity-table__head-cell">
                                Horario
                            </th>
                            <th class="activity-table__head-cell">
                                Actividad Realizada Detallado
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr class="activity-table__row">
                            <td class="activity-table__cell activity-table__hour">
                                08:30 AM - 09:30 AM
                            </td>
                            <td class="activity-table__cell">
                                <input type="text" class="activity-table__input" id="hora1" value="<?= $hora1 ?>">
                            </td>
                        </tr>

                        <tr class="activity-table__row">
                            <td class="activity-table__cell activity-table__hour">
                                09:30 AM - 10:30 AM
                            </td>
                            <td class="activity-table__cell">
                                <input type="text" class="activity-table__input" id="hora2" value="<?= $hora2 ?>">
                            </td>
                        </tr>

                        <tr class="activity-table__row">
                            <td class="activity-table__cell activity-table__hour">
                                10:30 AM - 11:30 AM
                            </td>
                            <td class="activity-table__cell">
                                <input type="text" class="activity-table__input" id="hora3" value="<?= $hora3 ?>">
                            </td>
                        </tr>

                        <tr class="activity-table__row">
                            <td class="activity-table__cell activity-table__hour">
                                11:30 AM - 01:00 PM
                            </td>
                            <td class="activity-table__cell">
                                <input type="text" class="activity-table__input"  id="hora4" value="<?= $hora4 ?>">
                            </td>
                        </tr>

                        <tr class="activity-table__row">
                            <td class="activity-table__cell activity-table__hour">
                                02:00 PM - 03:00 PM
                            </td>
                            <td class="activity-table__cell">
                                <input type="text" class="activity-table__input" id="hora5" value="<?= $hora5 ?>">
                            </td>
                        </tr>

                        <tr class="activity-table__row">
                            <td class="activity-table__cell activity-table__hour">
                                03:00 PM - 04:00 PM
                            </td>
                            <td class="activity-table__cell">
                                <input type="text" class="activity-table__input" id="hora6" value="<?= $hora6 ?>">
                            </td>
                        </tr>

                        <tr class="activity-table__row">
                            <td class="activity-table__cell activity-table__hour">
                                04:00 PM - 05:00 PM
                            </td>
                            <td class="activity-table__cell">
                                <input type="text" class="activity-table__input" id="hora7" value="<?= $hora7 ?>">
                            </td>
                        </tr>

                        <tr class="activity-table__row">
                            <td class="activity-table__cell activity-table__hour">
                                05:00 PM - 06:00 PM
                            </td>
                            <td class="activity-table__cell">
                                <input type="text" class="activity-table__input" id="hora8" value="<?= $hora8 ?>">
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

        <footer class="daily-report__footer">

            <button
            id="guardar_actividades"
                type="submit"
                class="button button--primary">

                Guardar Reporte

            </button>

        </footer>

    </div>

</section>