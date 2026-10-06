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

<div class="modal-cointainer" id="actualizar" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <form class="form-actualizar">
            <h2>Registro de contratos</h2>

            <div class="form-grid">

                <div class="form-group full">
                    <label>Nombres</label>
                    <input type="text" placeholder="Ingrese el nombre" numero="nombres" name="nombres" id="nombresA">
                </div>

                <div class="form-group">
                    <label>Apellido Paterno</label>
                    <input type="text" placeholder="Apellido paterno" numero="apellido_paterno" name="apellido_paterno" id="apellido_paternoA">
                </div>

                <div class="form-group">
                    <label>Apellido Materno</label>
                    <input type="text" placeholder="Apellido materno" numero="apellido_materno" name="apellido_materno" id="apellido_maternoA">
                </div>
                <div class="form-group">
                    <label>Número de Documento</label>
                    <input type="text" placeholder="Ingrese documento" name="numero_documento" id="numero_documentoA">
                </div>
                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" placeholder="Número celular" name="celular" id="celularA" readonly>
                </div>
                <div class="form-group full">
                    <label>Correo electrónico</label>
                    <input type="email" placeholder="correo@gmail.com" name="correo_electronico" id="correo_electronicoA">
                </div>

            </div>
            <div class="form-buttons">
                <button type="submit">Actualizar Datos</button>
            </div>
        </form>
    </div>
</div>