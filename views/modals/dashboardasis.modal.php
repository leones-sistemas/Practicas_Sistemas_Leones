<div class="modal-cointainer" id="clientes" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="vh-form-container">

            <h2 class="vh-form-title">Agregar Cliente</h2>

            <form id="clientes_create">

                <div class="vh-form-grid">
                    <div class="vh-form-group">
                        <label class="vh-label">Número de celular *</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-phone"></i>
                            <input
                                name="celular"
                                type="tel"
                                class="vh-input"
                                placeholder="999999999"
                                pattern="^[0-9]+$"
                                title="Este campo solo debe contener números (sin espacios ni letras)">
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Correo electrónico</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="vh-input" placeholder="correo@email.com" name="correo">
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Nombres</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="vh-input" placeholder="Ingrese nombres" name="nombres">
                        </div>
                    </div>

                    <div class="vh-form-group">
                        <label class="vh-label">Apellidos</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="vh-input" placeholder="Ingrese apellidos" name="apellidos">
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Eliga el proyecto de interes: </label>
                        <div class="vh-input-icon">
                            <select class="vh-input" id="addProject" name="proyecto">
                                <option value="No menciona / No opina">No menciona / No opina</option>
                                <option value="Loma verde I">Loma verde I</option>
                                <option value="Loma verde II">Loma verde II</option>
                                <option value="Huaytapallana">Huaytapallana</option>
                                <option value="Manantiales">Manantiales</option>
                                <option value="Tupac Amaru I">Tupac Amaru I</option>
                                <option value="Tupac Amaru II">Tupac Amaru II</option>
                                <option value="Heroinas Toledo">Heroinas Toledo</option>
                                <option value="San Roque">San Roque</option>
                                <option value="Nueva Colpa">Nueva Colpa</option>
                                <option value="Buenos Aires">Buenos Aires</option>
                                <option value="Huracan">Huracan</option>
                                <option value="Chalay">Chalay</option>
                                <option value="Leones del sur">Leones del sur</option>
                                <option value="Residencial San Agustin">Residencial San Agustin</option>
                                <option value="Huracan II">Huracan II</option>
                                <option value="Chalay II">Chalay II</option>
                            </select>
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Presupuesto: S/. </label>
                        <div class="vh-input-icon">
                            <i class="fa-solid fa-money-bill"></i>
                            <input type="number" class="vh-input" value="0" step="0.1" id="presupuesto">
                        </div>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Dirección</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="vh-input" placeholder="Dirección completa" name="direccion">
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Origen</label>
                        <select id="vh-origen" class="vh-input" name="origen">
                            <option value="">Seleccione origen</option>
                            <option value="facebook" selected>Facebook</option>
                            <option value="tiktok">TikTok</option>
                            <option value="publicidad">Publicidad Física</option>
                            <option value="btl">Below the line</option>
                            <option value="ctoc">Cliente a cliente </option>
                            <option value="bp">Base Propia </option>
                        </select>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Nivel de interes</label>
                        <select id="vh-origen" class="vh-input" name="interes">
                            <option value="No definido">No Definido</option>
                            <option value="Bajo">Bajo</option>
                            <option value="Medio" selected>Medio</option>
                            <option value="Alto">Alto</option>
                        </select>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Notas</label>
                        <textarea class="vh-input" rows="3" id="notasDetallado" name="notas" placeholder="Información adicional..."></textarea>
                    </div>
                </div>

                <button type="submit" class="vh-btn-submit">
                    <i class="fas fa-save"></i> Guardar Cliente
                </button>
                <div class="vh-form-group vh-full">
                    <div class="ignorar-panel">

                        <div class="ignorar-panel__encabezado">
                            <div class="ignorar-panel__icono">
                                !
                            </div>

                            <div>
                                <h3 class="ignorar-panel__titulo">Personal a notificar</h3>
                                <p class="ignorar-panel__descripcion">
                                    Selecciona las personas que deseas que les llegue notificacion.
                                </p>
                            </div>
                        </div>

                        <div class="ignorar-panel__lista">
                            <?php

                            use core\reports;

                            $personal = reports::listarPersonalActivo();
                            foreach ($personal as $key => $value) {
                                echo '
                                                                    <label class="ignorar-opcion">
                                    <input
                                        type="checkbox"
                                        class="ignorar-opcion__checkbox"
                                        name="ignorar_personas[]"
                                        value="' . $value["id_persona"] . '" checked>

                                    <span class="ignorar-opcion__check"></span>

                                    <span class="ignorar-opcion__contenido">
                                        <span class="ignorar-opcion__nombre">
                                            ' . $value["nombres"] . '
                                        </span>

                                        <span class="ignorar-opcion__detalle">
                                            Recibirá esta notificación
                                        </span>
                                    </span>
                                </label>
                                    ';
                            }
                            ?>


                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- MODAL CLIENTES CON SEGUIMIENTO -->
<div class="vxdash_modal_overlay" id="vxdash_modal_seguimientos">

    <div class="vxdash_modal_contenedor">

        <!-- CABECERA -->
        <div class="vxdash_modal_header">

            <div class="vxdash_modal_titulo">

                <div class="vxdash_modal_titulo_icono">
                    <i class="fa-solid fa-phone-volume"></i>
                </div>

                <div>
                    <h2>Clientes con seguimiento</h2>
                    <p>Listado de clientes pendientes de seguimiento</p>
                </div>

            </div>

            <button type="button"
                class="vxdash_modal_cerrar"
                id="vxdash_cerrar_seguimientos">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <!-- RESUMEN -->
        <div class="vxdash_modal_resumen">

            <div class="vxdash_modal_resumen_icono">
                <i class="fa-solid fa-users"></i>
            </div>

            <div>
                <span>Total de clientes</span>
                <strong id="vxdash_total_seguimientos">0</strong>
            </div>

        </div>


        <!-- TABLA -->
        <div class="vxdash_tabla_wrapper">

            <table class="vxdash_tabla_clientes">

                <thead>
                    <tr>
                        <th>Responsable</th>
                        <th>Cliente</th>
                        <th>Celular</th>
                        <th>Proyecto</th>
                        <th>Fecha de registro</th>
                        <th>Tiempo transcurrido</th>
                    </tr>
                </thead>

                <tbody id="vxdash_tabla_seguimientos">
                </tbody>

            </table>

        </div>


        <!-- FOOTER -->
        <div class="vxdash_modal_footer">

            <span>
                <i class="fa-solid fa-circle-info"></i>
                Estos clientes aún no cuentan con seguimiento.
            </span>

            <button type="button"
                class="vxdash_btn_cerrar"
                id="vxdash_btn_cerrar_seguimientos">
                Cerrar
            </button>

        </div>

    </div>

</div>


<div class="modal-cointainer" id="crear">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <form class="form-empleado" enctype="multipart/form-data">
            <div class="photo-container">

                <label for="photoInput" class="photo-preview" id="photoPreview">
                    <img id="previewImage" src="/assets/img/user.png" alt="Foto empleado">
                </label>

                <input type="file" id="photoInput" accept="image/*" name="foto">

            </div>
            <h2>Registro de usuarios</h2>

            <div class="form-grid">

                <div class="form-group full">
                    <label>Nombre</label>
                    <input type="text" placeholder="Ingrese el nombre" name="nombres">
                </div>

                <div class="form-group">
                    <label>Apellido Paterno</label>
                    <input type="text" placeholder="Apellido paterno" name="apellido_paterno">
                </div>

                <div class="form-group">
                    <label>Apellido Materno</label>
                    <input type="text" placeholder="Apellido materno" name="apellido_materno">
                </div>
                <div class="form-group">
                    <label>Prefijo país</label>
                    <select id="countryCode" name="prefijo_celular">
                        <option value="">Seleccione país</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" id="phone" placeholder="Número celular" name="celular">
                </div>
                <div class="form-group">
                    <label>Tipo de Documento</label>
                    <select name="tipo_documento">
                        <option value="">Elegir un tipo</option>
                        <option value="DNI">DNI</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="Carnet de extranjeria">Carnet de extranjería</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Número de Documento</label>
                    <input type="text" placeholder="Ingrese documento" name="numero_documento">
                </div>
                <div class="form-group full">
                    <label>Correo electrónico</label>
                    <input type="email" placeholder="correo@gmail.com" name="correo_electronico">
                </div>
                <div class="form-group full">
                    <label>Dirección</label>
                    <input type="text" placeholder="Dirección del empleado" name="direccion">
                </div>

            </div>

            <div class="form-buttons">
                <button type="submit">Registrar Usuario</button>
                <button type="reset" class="close">Cancelar</button>
            </div>

        </form>
    </div>
</div>
<div class="modal-cointainer" id="contratos" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <form class="form-contrato">
            <h2>Registro de contratos</h2>

            <div class="form-grid">

                <div class="form-group full">
                    <label>Nombres completos:</label>
                    <input type="text" placeholder="Ingrese el nombre" id="nombres" readonly>
                    <input type="hidden" name="id_persona" id="id" readonly>
                    <input type="hidden" name="horario_entrada" id="e1">
                    <input type="hidden" name="horario_salida" id="s1">
                    <input type="hidden" name="horario_entrada_tarde" id="e2">
                    <input type="hidden" name="horario_salida_tarde" id="s2">
                    <input type="hidden" name="dias_trabajo" id="dias">
                </div>
                <div class="form-group">
                    <label>Número de Documento</label>
                    <input type="text" placeholder="Ingrese documento" id="numero_documento" readonly>
                </div>
                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" placeholder="Número celular" id="celular" readonly>
                </div>
                <div class="form-group full">
                    <label>Correo electrónico</label>
                    <input type="email" placeholder="correo@gmail.com" id="correo_electronico" readonly>
                </div>
                <div class="form-group">
                    <label>Área</label>
                    <select name="area" id="area">
                        <option value="">Seleccione área</option>
                        <option value="ventas">Ventas</option>
                        <option value="marketing">Marketing</option>
                        <option value="administrativo">Administrativo</option>
                        <option value="tecnicos">Técnicos</option>
                        <option value="gerencia">Gerencia</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Puesto</label>
                    <select name="puesto" id="rol">
                        <option value="">Seleccione un puesto</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tipo de contrato</label>
                    <select name="tipo_contrato">
                        <option value="Indeterminado">Indeterminado</option>
                        <option value="Plazo fijo">Plazo fijo</option>
                        <option value="Part time">Part time</option>
                        <option value="Practicas">Prácticas</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Salario</label>
                    <input type="number" step="0.01" id="salario" name="salario" placeholder="Ej: 1500">
                </div>
                <div class="form-group">
                    <label>Fecha inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio">
                </div>

                <div class="form-group">
                    <label>Fecha fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin">
                </div>

                <div class="form-group">
                    <label>Horas semanales</label>
                    <input type="number" name="horas_semanales" id="horas_semanales" placeholder="48" value="48">
                </div>

                <div class="form-group">
                    <label>Modalidad</label>
                    <select name="modalidad">
                        <option value="Presencial">Presencial</option>
                        <option value="Remoto">Remoto</option>
                        <option value="Hibrido">Híbrido</option>
                    </select>
                </div>

            </div>

            <div class="form-buttons">
                <button type="submit">Crear Contrato</button>
                <button type="button" data-type="Modal" data-target="horario">Registrar Horarios</button>
            </div>

        </form>
    </div>
</div>

<div class="modal-cointainer" id="horario" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="form-horario">
            <h2>Registro de horario</h2>

            <div class="form-grid">

                <div class="form-group">
                    <label>Entrada</label>
                    <input type="time" id="horario_entrada" value="08:30">
                </div>

                <div class="form-group">
                    <label>Salida Almuerzo</label>
                    <input type="time" id="horario_salida" value="13:00">
                </div>

                <div class="form-group">
                    <label>Entrada Tarde</label>
                    <input type="time" id="horario_entrada_tarde" value="14:00">
                </div>

                <div class="form-group">
                    <label>Salida Final</label>
                    <input type="time" id="horario_salida_tarde" value="18:00">
                </div>
                <div class="form-group full">
                    <label>Días de trabajo</label>

                    <div class="dias-trabajo">

                        <label><input type="checkbox" name="dias_trabajo[]" value="Lunes" checked> L</label>
                        <label><input type="checkbox" name="dias_trabajo[]" value="Martes" checked> M</label>
                        <label><input type="checkbox" name="dias_trabajo[]" value="Miercoles" checked> M</label>
                        <label><input type="checkbox" name="dias_trabajo[]" value="Jueves" checked> J</label>
                        <label><input type="checkbox" name="dias_trabajo[]" value="Viernes" checked> V</label>
                        <label><input type="checkbox" name="dias_trabajo[]" value="Sabado" checked> S</label>
                        <label><input type="checkbox" name="dias_trabajo[]" value="Domingo"> D</label>

                    </div>

                </div>
            </div>

            <div class="form-buttons">
                <button id="agregarHorario">Agregar Horario</button>
            </div>

        </div>
    </div>
</div>

<div class="modal-cointainer" id="VeerSeguimientos" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="crm-container">

            <!-- =========================
         HEADER
    ========================== -->

            <header class="crm-header">

                <div class="header-info">

                    <div class="header-icon">
                        <i class="fa-solid fa-building"></i>
                    </div>

                    <div>
                        <span class="crm-eyebrow">
                            GESTIÓN INMOBILIARIA
                        </span>

                        <h1>Seguimiento de proyectos</h1>

                        <p>
                            Historial de comunicaciones, citas y visitas
                            de tus proyectos inmobiliarios.
                        </p>
                    </div>

                </div>

            </header>


            <!-- =========================
         PROYECTOS
    ========================== -->

            <main class="proyectos-lista">


            </main>

        </div>
    </div>
</div>



<div class="modal-cointainer" id="recontactos">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div style="width: 100%; overflow-x: auto; font-family: Arial, sans-serif;">

            <table style="
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        font-size: 14px;
    ">

                <thead>
                    <tr style="background: #1f2937; color: #ffffff;">

                        <th style="
                    padding: 14px 16px;
                    text-align: left;
                    font-weight: 600;
                ">
                            Cliente
                        </th>

                        <th style="
                    padding: 14px 16px;
                    text-align: left;
                    font-weight: 600;
                ">
                            Celular
                        </th>

                        <th style="
                    padding: 14px 16px;
                    text-align: left;
                    font-weight: 600;
                ">
                            Proyecto
                        </th>

                        <th style="
                    padding: 14px 16px;
                    text-align: left;
                    font-weight: 600;
                ">
                            Fecha
                        </th>

                        <th style="
                    padding: 14px 16px;
                    text-align: center;
                    font-weight: 600;
                ">
                            Asignado
                        </th>

                    </tr>
                </thead>

                <tbody id="body_recontactos">


                </tbody>

            </table>

        </div>
    </div>
</div>