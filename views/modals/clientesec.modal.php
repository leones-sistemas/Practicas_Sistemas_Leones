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
                                title="Este campo solo debe contener números (sin espacios ni letras)"
                                required>
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
                            <select class="vh-input" id="addProject">
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
                            <option value="facebook">Facebook</option>
                            <option value="tiktok">TikTok</option>
                            <option value="publicidad">Publicidad Física</option>
                            <option value="btl">Below the line</option>
                            <option value="ctoc">Cliente a cliente </option>
                            <option value="bp">Base Propia </option>
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
                        <textarea class="vh-input" rows="3" id="notasDetallado" name="notas" placeholder="Información adicional..."></textarea>
                    </div>

                </div>

                <button type="submit" class="vh-btn-submit">
                    <i class="fas fa-save"></i> Guardar Cliente
                </button>

            </form>

        </div>
    </div>
</div>
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