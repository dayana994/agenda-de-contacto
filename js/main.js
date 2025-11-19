/**
 * JavaScript para Agenda de Contactos
 */

const API_URL = 'api/contactos.php';
let contactoIdEliminar = null;
let contactos = [];
let modalEliminar = null;

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    cargarContactos();
    
    // Event listeners
    document.getElementById('buscarContacto').addEventListener('input', filtrarContactos);
    document.getElementById('filtroCategoria').addEventListener('change', filtrarContactos);
    
    // Limpiar formulario al cerrar modal
    const modal = document.getElementById('modalContacto');
    modal.addEventListener('hidden.bs.modal', function() {
        limpiarFormulario();
    });
    
    // Inicializar modal de eliminación una sola vez
    const modalEliminarElement = document.getElementById('modalEliminar');
    if (modalEliminarElement) {
        modalEliminar = new bootstrap.Modal(modalEliminarElement);
    }
});

/**
 * Cargar todos los contactos
 */
function cargarContactos() {
    fetch(API_URL)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                contactos = data.data;
                mostrarContactos(contactos);
            } else {
                const mensaje = data.error || 'Error al cargar contactos';
                mostrarMensaje(mensaje, 'danger');
                
                // Si es error de conexión, mostrar ayuda adicional
                if (mensaje.includes('conexión') || mensaje.includes('Error de conexión')) {
                    setTimeout(() => {
                        mostrarMensaje('💡 Ayuda: Abre test-connection.php para diagnosticar el problema', 'info');
                    }, 3000);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('Error de conexión con el servidor. Verifica que PHP y MySQL estén corriendo.', 'danger');
            setTimeout(() => {
                mostrarMensaje('💡 Ayuda: Visita test-connection.php para diagnosticar el problema', 'info');
            }, 3000);
        });
}

/**
 * Mostrar contactos en la página
 */
function mostrarContactos(listaContactos) {
    const contenedor = document.getElementById('listaContactos');
    
    if (listaContactos.length === 0) {
        contenedor.innerHTML = `
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>No hay contactos</h3>
                    <p>Comienza agregando tu primer contacto</p>
                </div>
            </div>
        `;
        return;
    }
    
    contenedor.innerHTML = listaContactos.map(contacto => crearCardContacto(contacto)).join('');
}

/**
 * Crear HTML para un card de contacto
 */
function crearCardContacto(contacto) {
    const inicial = (contacto.nombre.charAt(0) + contacto.apellido.charAt(0)).toUpperCase();
    const categoriaClass = `categoria-${contacto.categoria.toLowerCase()}`;
    const fechaNac = contacto.fecha_nacimiento ? new Date(contacto.fecha_nacimiento).toLocaleDateString('es-ES') : 'No especificada';
    
    return `
        <div class="col-md-6 col-lg-4">
            <div class="contacto-card">
                <div class="contacto-header">
                    <div class="d-flex align-items-center">
                        <div class="contacto-inicial">${inicial}</div>
                        <div>
                            <h3 class="contacto-nombre">${contacto.nombre} ${contacto.apellido}</h3>
                            <span class="contacto-categoria ${categoriaClass}">${contacto.categoria}</span>
                        </div>
                    </div>
                </div>
                
                <div class="contacto-info">
                    <div class="contacto-info-item">
                        <i class="bi bi-telephone-fill"></i>
                        <a href="tel:${contacto.telefono}">${contacto.telefono}</a>
                    </div>
                    <div class="contacto-info-item">
                        <i class="bi bi-envelope-fill"></i>
                        <a href="mailto:${contacto.email}">${contacto.email}</a>
                    </div>
                    ${contacto.direccion ? `
                    <div class="contacto-info-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>${contacto.direccion}</span>
                    </div>
                    ` : ''}
                    <div class="contacto-info-item">
                        <i class="bi bi-calendar-event"></i>
                        <span>${fechaNac}</span>
                    </div>
                    ${contacto.notas ? `
                    <div class="contacto-info-item">
                        <i class="bi bi-sticky-fill"></i>
                        <span>${contacto.notas}</span>
                    </div>
                    ` : ''}
                </div>
                
                <div class="contacto-acciones">
                    <button class="btn btn-action btn-editar" onclick="editarContacto(${contacto.id})">
                        <i class="bi bi-pencil-fill me-1"></i>Editar
                    </button>
                    <button class="btn btn-action btn-eliminar" onclick="eliminarContacto(${contacto.id}, '${contacto.nombre} ${contacto.apellido}')">
                        <i class="bi bi-trash-fill me-1"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>
    `;
}

/**
 * Abrir modal para crear nuevo contacto
 */
function abrirModalCrear() {
    document.getElementById('modalContactoLabel').innerHTML = '<i class="bi bi-person-plus me-2"></i>Nuevo Contacto';
    limpiarFormulario();
    document.getElementById('contactoId').value = '';
}

/**
 * Editar contacto
 */
function editarContacto(id) {
    fetch(`${API_URL}?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const contacto = data.data;
                document.getElementById('modalContactoLabel').innerHTML = '<i class="bi bi-pencil-fill me-2"></i>Editar Contacto';
                document.getElementById('contactoId').value = contacto.id;
                document.getElementById('nombre').value = contacto.nombre;
                document.getElementById('apellido').value = contacto.apellido;
                document.getElementById('telefono').value = contacto.telefono;
                document.getElementById('email').value = contacto.email;
                document.getElementById('direccion').value = contacto.direccion || '';
                document.getElementById('fecha_nacimiento').value = contacto.fecha_nacimiento || '';
                document.getElementById('categoria').value = contacto.categoria || 'Personal';
                document.getElementById('notas').value = contacto.notas || '';
                
                const modal = new bootstrap.Modal(document.getElementById('modalContacto'));
                modal.show();
            } else {
                mostrarMensaje('Error al cargar contacto', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('Error de conexión', 'danger');
        });
}

/**
 * Guardar contacto (crear o actualizar)
 */
function guardarContacto() {
    const form = document.getElementById('formContacto');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const id = document.getElementById('contactoId').value;
    const contacto = {
        nombre: document.getElementById('nombre').value.trim(),
        apellido: document.getElementById('apellido').value.trim(),
        telefono: document.getElementById('telefono').value.trim(),
        email: document.getElementById('email').value.trim(),
        direccion: document.getElementById('direccion').value.trim(),
        fecha_nacimiento: document.getElementById('fecha_nacimiento').value || null,
        categoria: document.getElementById('categoria').value,
        notas: document.getElementById('notas').value.trim()
    };
    
    const url = id ? `${API_URL}?id=${id}` : API_URL;
    const method = id ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(contacto)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarMensaje(data.message || 'Contacto guardado exitosamente', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalContacto'));
            modal.hide();
            cargarContactos();
        } else {
            mostrarMensaje(data.error || 'Error al guardar contacto', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarMensaje('Error de conexión', 'danger');
    });
}

/**
 * Eliminar contacto
 */
function eliminarContacto(id, nombre) {
    if (!id) {
        console.error('ID de contacto no válido');
        return;
    }
    
    contactoIdEliminar = id;
    const nombreElement = document.getElementById('nombreEliminar');
    if (nombreElement) {
        nombreElement.textContent = nombre;
    }
    
    // Usar la instancia del modal ya inicializada, o crearla si no existe
    if (!modalEliminar) {
        const modalEliminarElement = document.getElementById('modalEliminar');
        if (modalEliminarElement) {
            modalEliminar = new bootstrap.Modal(modalEliminarElement);
        } else {
            console.error('No se encontró el elemento del modal de eliminación');
            mostrarMensaje('Error: No se puede abrir el modal de confirmación', 'danger');
            return;
        }
    }
    
    if (modalEliminar) {
        modalEliminar.show();
    }
}

/**
 * Confirmar eliminación
 */
function confirmarEliminar() {
    if (!contactoIdEliminar) {
        console.error('No hay ID de contacto para eliminar');
        return;
    }
    
    // Deshabilitar el botón para evitar doble clic
    const btnEliminar = document.querySelector('#modalEliminar .btn-danger');
    if (btnEliminar) {
        btnEliminar.disabled = true;
        btnEliminar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Eliminando...';
    }
    
    fetch(`${API_URL}?id=${contactoIdEliminar}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            mostrarMensaje(data.message || 'Contacto eliminado exitosamente', 'success');
            if (modalEliminar) {
                modalEliminar.hide();
            } else {
                // Fallback: intentar obtener la instancia
                const modalElement = document.getElementById('modalEliminar');
                if (modalElement) {
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            }
            contactoIdEliminar = null;
            cargarContactos();
        } else {
            mostrarMensaje(data.error || 'Error al eliminar contacto', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarMensaje('Error de conexión al eliminar contacto', 'danger');
    })
    .finally(() => {
        // Rehabilitar el botón
        if (btnEliminar) {
            btnEliminar.disabled = false;
            btnEliminar.innerHTML = '<i class="bi bi-trash me-1"></i>Eliminar';
        }
    });
}

/**
 * Filtrar contactos
 */
function filtrarContactos() {
    const busqueda = document.getElementById('buscarContacto').value.toLowerCase();
    const categoria = document.getElementById('filtroCategoria').value;
    
    let filtrados = contactos.filter(contacto => {
        const coincideBusqueda = !busqueda || 
            contacto.nombre.toLowerCase().includes(busqueda) ||
            contacto.apellido.toLowerCase().includes(busqueda) ||
            contacto.email.toLowerCase().includes(busqueda) ||
            contacto.telefono.includes(busqueda);
        
        const coincideCategoria = !categoria || contacto.categoria === categoria;
        
        return coincideBusqueda && coincideCategoria;
    });
    
    mostrarContactos(filtrados);
}

/**
 * Limpiar formulario
 */
function limpiarFormulario() {
    document.getElementById('formContacto').reset();
    document.getElementById('contactoId').value = '';
}

/**
 * Mostrar mensaje de alerta
 */
function mostrarMensaje(mensaje, tipo) {
    const alerta = document.getElementById('mensajeAlerta');
    const texto = document.getElementById('mensajeTexto');
    
    alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
    texto.textContent = mensaje;
    
    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alerta);
        bsAlert.close();
    }, 5000);
}

