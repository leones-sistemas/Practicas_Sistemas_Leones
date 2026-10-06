<?php
$proyectos = [
    1 => "Loma verde I",
    2 => "Loma verde II",
    3 => "Huaytapallana",
    4 => "Manantiales",
    5 => "Tupac Amaru I",
    6 => "Tupac Amaru II",
    7 => "Heroinas Toledo",
    8 => "San Roque",
    9 => "Nueva Colpa",
    10 => "Buenos Aires",
    11 => "Huracan",
    12 => "Chalay",
    13 => "Leones del sur"
];
?>
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
                                placeholder="999 999 999"
                                required
                                pattern="[0-9]{9}"
                                title="Ingrese un número de 9 dígitos">
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
                            <option value="facebook">Facebook</option>
                            <option value="tiktok">TikTok</option>
                            <option value="publicidad">Publicidad Física</option>
                            <option value="btl">Below the line</option>
                            <option value="ctoc">Cliente a cliente </option>
                        </select>
                    </div>

                    <div class="vh-form-group">
                        <label class="vh-label">Subcategoría</label>
                        <select id="vh-subcategoria" class="vh-input" name="subcategoria" name="detalle">
                            <option value="">Seleccione subcategoría</option>
                        </select>
                    </div>

                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Detalle</label>
                        <select id="vh-detalle" class="vh-input" name="detalle">
                            <option value="">Seleccione detalle</option>
                        </select>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Notas</label>
                        <textarea class="vh-input" rows="3" name="notas" placeholder="Información adicional..."></textarea>
                    </div>

                </div>

                <button type="submit" class="vh-btn-submit">
                    <i class="fas fa-save"></i> Guardar Cliente
                </button>

            </form>

        </div>
    </div>
</div>

<div class="modal-cointainer" id="process" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="vh-form-container">

            <h2 class="vh-form-title">Inicio del proceso</h2>

            <form id="seguimiento_create">

                <div class="vh-form-grid">
                    <div class="vh-form-group">
                        <label class="vh-label">Digite su numero de celular *</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-phone"></i>
                            <input
                                id="celcli"
                                type="tel"
                                class="vh-input"
                                placeholder="999 999 999"
                                required
                                pattern="[0-9]{9}"
                                title="Ingrese un número de 9 dígitos">
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Correo electrónico</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="hidden" name="cliente" id="Did">
                            <input type="email" class="vh-input" placeholder="correo@email.com" id="DCorreo" readonly>
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Nombres</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="vh-input" placeholder="Ingrese nombres" id="DNombres" readonly>
                        </div>
                    </div>

                    <div class="vh-form-group">
                        <label class="vh-label">Apellidos</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="vh-input" placeholder="Ingrese apellidos" id="DApellidos" readonly>
                        </div>
                    </div>

                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Dirección</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="vh-input" placeholder="Dirección completa" id="DDireccion" readonly>
                        </div>
                    </div>

                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Proyecto</label>
                        <select id="vh-origen" class="vh-input" name="proyecto">
                            <option value="">Seleccione proyecto</option>
                            <?php
                            foreach ($proyectos as $k => $v) {
                                echo '<option value="' . $k . '">' . $v . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="vh-btn-submit" style="background-color:tomato;">
                    <i class="fas fa-save"></i> Iniciar Seguimiento
                </button>

            </form>

        </div>
    </div>
</div>

<div class="modal-cointainer" id="details" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="vh-form-container">

            <h2 class="vh-form-title">Detalle de seguimiento del cliente: <strong id="clienteNombres"></strong></h2>

            <form id="llamadas_create">

                <div class="vh-form-grid">
                    <div class="vh-form-group">
                        <label class="vh-label">Tipo</label>
                        <select id="vh-origen" class="vh-input" name="tipo">
                            <option value="">Elegir tipo de seguimiento</option>
                            <option value="0">Llamada</option>
                            <option value="1">Mensaje</option>
                            <option value="2">No existe</option>
                        </select>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Fecha/Hora de registro</label>
                        <div class="vh-input-icon">
                            <i class="fa-regular fa-clock"></i>
                            <input type="hidden" id="seguimiento" name="seguimiento">
                            <input type="datetime-local" class="vh-input" name="fecha_hora" value="<?= date("Y-m-d H:i") ?>">
                        </div>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Detalle de la actividad:</label>
                        <textarea class="vh-input" rows="3" name="descripcion" placeholder="Información adicional..."></textarea>
                    </div>
                </div>

                <button type="submit" class="vh-btn-submit" style="background-color:slateblue;">
                    <i class="fas fa-save"></i> Agregar Seguimiento
                </button>

            </form>
            <div class="tbl-details">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Tipo</th>
                            <th>Descripcion</th>
                            <th>Hora llamada</th>
                        </tr>
                    </thead>
                    <tbody id="body-details">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal-cointainer" id="dateils" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="vh-form-container">

            <h2 class="vh-form-title">Detalle de agendas con el cliente</h2>

            <form id="date_create">

                <div class="vh-form-grid">
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Fecha/Hora de cita:</label>
                        <div class="vh-input-icon">
                            <i class="fa-regular fa-clock"></i>
                            <input type="hidden" id="seguimientoDate" name="seguimiento">
                            <input type="datetime-local" class="vh-input" name="fecha_hora" value="<?= date("Y-m-d H:i") ?>">
                        </div>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Detalle de la actividad:</label>
                        <textarea class="vh-input" rows="3" name="descripcion" placeholder="Información adicional..."></textarea>
                    </div>
                </div>

                <button type="submit" class="vh-btn-submit" style="background-color:slateblue;">
                    <i class="fas fa-save"></i> Agregar cita
                </button>

            </form>
            <div class="tbl-details">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Cita</th>
                            <th>Registro</th>
                            <th>Detalles</th>
                            <th>¿Visito?</th>
                        </tr>
                    </thead>
                    <tbody id="body-date">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal-cointainer" id="textDetails" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="vh-form-container">

            <h2 class="vh-form-title">Detalles: </h2>
            <div class="vh-form-group vh-full">
                <label class="vh-label">Detalle de la actividad:</label>
                <textarea class="vh-input" rows="3" id="descripcionData"></textarea>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal-cointainer" id="visita" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <form id="date_state">
            <div class="vh-form-container">
                <div class="vh-form-group vh-full">
                    <input type="hidden" id="seguimientoVis" name="seguimiento">
                    <div class="vh-form-group">
                        <label class="vh-label">Estado</label>
                        <select class="vh-input" name="visita" id="estadoVisita">
                            <option value="0">Agendado</option>
                            <option value="1">Visito</option>
                            <option value="2">Cancelo</option>
                            <option value="3">No llego</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="vh-btn-submit" style="background-color:slateblue;">
                    <i class="fas fa-save"></i> Actualizar estado
                </button>
            </div>
        </form>

    </div>
</div>
</div>

<div class="modal-cointainer" id="editClient" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="vh-form-container">

            <h2 class="vh-form-title">Editar Cliente</h2>

            <form id="clientes_edit">

                <div class="vh-form-grid">
                    <div class="vh-form-group">
                        <label class="vh-label">Número de celular *</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-phone"></i>
                            <input type="hidden" id="idCliente" value="0" name="id">
                            <input
                                name="celular"
                                type="tel"
                                class="vh-input"
                                placeholder="999 999 999"
                                required
                                pattern="[0-9]{9}"
                                title="Ingrese un número de 9 dígitos" id="Ecelular"
                                readonly>
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Correo electrónico</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="vh-input" placeholder="correo@email.com" name="correo" id="Ecorreo">
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Nombres</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="vh-input" placeholder="Ingrese nombres" name="nombres" id="Enombres">
                        </div>
                    </div>

                    <div class="vh-form-group">
                        <label class="vh-label">Apellidos</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="vh-input" placeholder="Ingrese apellidos" name="apellidos" id="Eapellidos">
                        </div>
                    </div>

                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Dirección</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="vh-input" placeholder="Dirección completa" name="direccion" id="Edireccion">
                        </div>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Notas</label>
                        <textarea class="vh-input" rows="3" id="Enotas" name="notas" placeholder="Información adicional..."></textarea>
                    </div>

                </div>

                <button type="submit" class="vh-btn-submit">
                    <i class="fas fa-save"></i> Actualizar Cliente
                </button>

            </form>

        </div>
    </div>
</div>