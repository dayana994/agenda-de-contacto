<?php
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<div class="container my-5">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-person-lines-fill me-2"></i>Mi Agenda de Contactos
                    </h1>
                    <p class="text-muted">Gestiona tus contactos de manera fácil y rápida</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="ver-archivos.php" class="btn btn-success btn-lg">
                        <i class="bi bi-file-earmark-text me-2"></i>Ver Archivos
                    </a>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalContacto" onclick="abrirModalCrear()">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Contacto
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de búsqueda y filtros -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search text-primary"></i>
                </span>
                <input type="text" class="form-control" id="buscarContacto" placeholder="Buscar por nombre, email o teléfono...">
            </div>
        </div>
        <div class="col-md-6">
            <select class="form-select" id="filtroCategoria">
                <option value="">Todas las categorías</option>
                <option value="Personal">Personal</option>
                <option value="Trabajo">Trabajo</option>
                <option value="Familia">Familia</option>
                <option value="Amigos">Amigos</option>
            </select>
        </div>
    </div>

    <!-- Mensaje de estado -->
    <div id="mensajeAlerta" class="alert alert-dismissible fade" role="alert">
        <span id="mensajeTexto"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Lista de contactos -->
    <div class="row" id="listaContactos">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar contacto -->
<div class="modal fade" id="modalContacto" tabindex="-1" aria-labelledby="modalContactoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalContactoLabel">
                    <i class="bi bi-person-plus me-2"></i>Nuevo Contacto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formContacto">
                    <input type="hidden" id="contactoId">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">
                                <i class="bi bi-person me-1"></i>Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">
                                <i class="bi bi-person me-1"></i>Apellido <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="apellido" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">
                                <i class="bi bi-telephone me-1"></i>Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control" id="telefono" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion" class="form-label">
                            <i class="bi bi-geo-alt me-1"></i>Dirección
                        </label>
                        <textarea class="form-control" id="direccion" rows="2"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">
                                <i class="bi bi-calendar me-1"></i>Fecha de Nacimiento
                            </label>
                            <input type="date" class="form-control" id="fecha_nacimiento">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label">
                                <i class="bi bi-tag me-1"></i>Categoría
                            </label>
                            <select class="form-select" id="categoria">
                                <option value="Personal">Personal</option>
                                <option value="Trabajo">Trabajo</option>
                                <option value="Familia">Familia</option>
                                <option value="Amigos">Amigos</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notas" class="form-label">
                            <i class="bi bi-sticky me-1"></i>Notas
                        </label>
                        <textarea class="form-control" id="notas" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="guardarContacto()">
                    <i class="bi bi-save me-1"></i>Guardar Contacto
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este contacto?</p>
                <p class="fw-bold" id="nombreEliminar"></p>
                <p class="text-danger small">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarEliminar()">
                    <i class="bi bi-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

