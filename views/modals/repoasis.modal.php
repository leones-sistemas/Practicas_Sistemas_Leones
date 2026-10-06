<?php
    $hora = date("H:i");
?>
<div class="modal-cointainer" id="editAssist">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
            <form id="assist_create">

                <div class="vh-form-grid">
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Ingrese la hora de registro</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="hidden" class="vh-input" id="idE" name="id" value="0">
                            <input type="hidden" class="vh-input" id="fechaE" name="fecha">
                            <input type="hidden" class="vh-input" id="tipo" name="tipo">
                            <input type="hidden" class="vh-input" id="usuario" name="usuario">
                            <input type="time" class="vh-input" name="hora" value="<?= $hora ?>">
                        </div>
                    </div>

                </div>

                <button type="submit" class="vh-btn-submit">
                    <i class="fas fa-save"></i> Actualizar hora
                </button>

            </form>
    </div>
</div>

<div class="modal-cointainer" id="mensajes" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
            <form id="assist_message">

                <div class="vh-form-grid">
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Recordatorio:</label>
                        <div class="vh-input-icon">
                            <input type="hidden" class="vh-input" id="idM" name="id" value="0">
                            <textarea class="vh-input" id="detalleM"  name="detalle" style="width: 300px; height: 120px;"></textarea>
                        </div>
                    </div>

                </div>

                <button type="submit" class="vh-btn-submit">
                    <i class="fas fa-save"></i> Actualizar recordatorio
                </button>

            </form>
    </div>
</div>