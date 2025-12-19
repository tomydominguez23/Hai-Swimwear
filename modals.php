<!-- Nuevo Producto Modal -->
<div class="modal" id="nuevoProductoModal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Nuevo Producto</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="productoForm">
                <div class="form-group">
                    <label>Nombre del Producto *</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>SKU</label>
                    <input type="text" class="form-control" name="sku">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Categoría *</label>
                        <select class="form-control" name="categoria_id" id="modalCategoria" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subcategoría</label>
                        <select class="form-control" name="subcategoria">
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Precio CLP *</label>
                        <input type="number" class="form-control" name="precio" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Precio Anterior CLP</label>
                        <input type="number" class="form-control" name="precio_anterior" step="0.01">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" class="form-control" name="stock" value="0">
                    </div>
                    <div class="form-group">
                        <label>Peso (kg)</label>
                        <input type="number" step="0.01" class="form-control" name="peso">
                    </div>
                </div>
                <div class="form-group">
                    <label>Descripción Corta</label>
                    <textarea class="form-control" name="descripcion_corta" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Dimensiones</label>
                    <input type="text" class="form-control" name="dimensiones" placeholder="Ej: 10x5x2 cm">
                </div>
                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="incluye_iva" checked> Incluye IVA
                    </label>
                </div>
                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="producto_destacado"> Producto Destacado (aparecerá en la página principal)
                    </label>
                </div>
                <div class="form-group">
                    <label>Imágenes del Producto *</label>
                    <div class="image-upload-area">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Arrastra imágenes aquí o haz clic para seleccionar</p>
                        <p class="upload-hint">Puedes subir múltiples imágenes. La primera será la imagen principal.</p>
                        <input type="file" multiple accept="image/*" id="productImages" name="imagenes[]">
                    </div>
                    <div class="uploaded-images" id="uploadedImages"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-close">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Subir Imágenes Modal -->
<div class="modal" id="subirImagenesModal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Subir Imágenes</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="uploadImagesForm">
                <div class="image-upload-area large">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Arrastra y suelta tus imágenes aquí</p>
                    <p class="upload-hint">o haz clic para seleccionar archivos</p>
                    <input type="file" multiple accept="image/*" id="uploadImagesInput" name="imagen">
                </div>
                <div class="form-group">
                    <label>¿Dónde quieres usar esta imagen?</label>
                    <select class="form-control" id="imageLocation">
                        <option value="galeria">Galería (Recomendado)</option>
                        <option value="logo">Logo Principal</option>
                        <option value="banner">Banner Principal</option>
                        <option value="fondo">Fondo de Sección</option>
                    </select>
                </div>
                <div class="form-group" id="sectionSelect" style="display: none;">
                    <label>Selecciona la sección específica:</label>
                    <select class="form-control">
                        <option value="">Seleccionar sección...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Título:</label>
                    <input type="text" class="form-control" name="titulo" id="imagenTitulo" placeholder="Título de la imagen">
                </div>
                <div class="form-group">
                    <label>Descripción:</label>
                    <textarea class="form-control" name="descripcion" id="imagenDescripcion" rows="3" placeholder="Descripción opcional"></textarea>
                </div>
                <div class="form-group">
                    <label>Texto Alternativo (Alt Text):</label>
                    <input type="text" class="form-control" name="alt_text" placeholder="Texto alternativo para accesibilidad">
                </div>
                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox"> Imagen Principal (para ubicaciones que permiten múltiples imágenes)
                    </label>
                </div>
                <div class="image-preview" id="imagePreview"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-close">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir Imágenes</button>
                </div>
            </form>
        </div>
    </div>
</div>

