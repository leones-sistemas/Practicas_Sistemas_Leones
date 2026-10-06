<div class="modal-cointainer" id="detailsClient" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="vh-form-container">

            <h2 class="vh-form-title">Informacion del Cliente</h2>

            <form id="clientes_create">

                <div class="vh-form-grid">
                    <div class="vh-form-group">
                        <label class="vh-label">Número de celular *</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-phone"></i>
                            <input
                                id="celular"
                                type="tel"
                                class="vh-input"
                                placeholder="999999999"
                                pattern="^[0-9]+$"
                                title="Este campo solo debe contener números (sin espacios ni letras)"
                                disabled>
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Correo electrónico</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="vh-input" placeholder="correo@email.com" id="correo" disabled>
                        </div>
                    </div>
                    <div class="vh-form-group">
                        <label class="vh-label">Nombres</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="vh-input" placeholder="Ingrese nombres" id="nombres" disabled>
                        </div>
                    </div>

                    <div class="vh-form-group">
                        <label class="vh-label">Apellidos</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="vh-input" placeholder="Ingrese apellidos" id="apellidos" disabled>
                        </div>
                    </div>

                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Dirección</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="vh-input" placeholder="Dirección completa" id="direccion" disabled>
                        </div>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Origen</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="vh-input" placeholder="Origen de Informacion" id="origen" disabled>
                        </div>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Subcategoria</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="vh-input" placeholder="Subcategoria" id="subcategoria" disabled>
                        </div>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Detalle</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="vh-input" placeholder="Detalle de registro" id="detalle" placeholder>
                        </div>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Notas</label>
                        <textarea class="vh-input" rows="3" id="notas" placeholder="Información adicional..." disabled></textarea>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>