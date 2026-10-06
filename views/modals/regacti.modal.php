<div class="modal-cointainer" id="crear">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <form id="saveCat">
            <div class="formux-grid">
                <div class="formux-group formux-full">
                    <label class="formux-label">Area: </label>
                    <select class="formux-select" name="area">
                        <option value="">Seleccione área</option>
                        <option value="VENTAS">Ventas</option>
                        <option value="MARKETING">Marketing</option>
                        <option value="ADMINISTRATIVO">Administrativo</option>
                        <option value="TECNICOS">Técnicos</option>
                    </select>
                </div>
                <div class="formux-group formux-full">
                    <label class="formux-label">Categoria: </label>
                    <input type="text" name="nombre" class="formux-input" placeholder="Ingrese nombre">
                </div>
            </div>
            <div class="formux-actions">
                <button type="submit" class="formux-btn formux-btn-primary">Guardar</button>
                <button type="reset" class="formux-btn formux-btn-secondary">Cancelar</button>
            </div>

        </form>

    </div>
</div>
<div class="modal-cointainer" id="subcategorias">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <form id="saveSubCat">
            <div class="formux-grid">
                <div class="formux-group formux-full">
                    <input type="hidden" value="0" id="idcat" name="categoria">
                    <label class="formux-label">Subcateogria: </label>
                    <input type="text" name="nombre" class="formux-input" placeholder="Ingrese nombre">
                </div>
            </div>
            <div class="formux-actions">
                <button type="submit" class="formux-btn formux-btn-primary">Guardar</button>
                <button type="reset" class="formux-btn formux-btn-secondary">Cancelar</button>
            </div>
        </form>
        <div class="tbl-details">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Area</th>
                        <th>Categoria</th>
                        <th>Subcategoria</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody id="body-subcat">

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-cointainer" id="editar">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <form id="editSubCat">
            <div class="formux-grid">
                <div class="formux-group formux-full">
                    <label class="formux-label">Area: </label>
                    <input type="hidden" name="id" id="idcateg">
                    <select class="formux-select" name="area" id="areaE">
                        <option value="">Seleccione área</option>
                        <option value="VENTAS">Ventas</option>
                        <option value="MARKETING">Marketing</option>
                        <option value="ADMINISTRATIVO">Administrativo</option>
                        <option value="TECNICOS">Técnicos</option>
                    </select>
                </div>
                <div class="formux-group formux-full">
                    <label class="formux-label">Categoria: </label>
                    <input type="text" name="nombre" id="categoriaE" class="formux-input" placeholder="Ingrese nombre">
                </div>
            </div>
            <div class="formux-actions">
                <button type="submit" class="formux-btn formux-btn-primary">Guardar</button>
                <button type="reset" class="formux-btn formux-btn-secondary">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<div class="modal-cointainer" id="esubcat" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <form id="updateSubCat">
            <div class="formux-grid">
                <div class="formux-group formux-full">
                    <input type="hidden" name="id" id="idsubcat">
                    <label class="formux-label">Subcategoria: </label>
                    <input type="text" name="nombre" id="subcategoriaE" class="formux-input" placeholder="Ingrese nombre">
                    <label class="formux-label">Descripcion: </label>
                    <textarea name="descripcion" id="descripcionE" class="formux-textarea" ></textarea>
                </div>
            </div>
            <div class="formux-actions">
                <button type="submit" class="formux-btn formux-btn-primary">Guardar</button>
                <button type="reset" class="formux-btn formux-btn-secondary">Cancelar</button>
            </div>
        </form>
    </div>
</div>