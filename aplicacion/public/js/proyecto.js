const addTarea = document.getElementById("agregarTarea");
addTarea.onclick = () => {
    if (!isValidTarea()) {
        event.preventDefault();
        return false;
    }
    agregarTarea();
}

const isValidTarea = () => {
    const nombreTarea = document.getElementById("nombreTarea");
    const descripcionTarea = document.getElementById("descripcionTarea");
    const trabajadorTarea = document.getElementById("search-navbar");

    const valueTarea = nombreTarea.value.trim();
    const valueDescripcionTarea = descripcionTarea.value.trim();
    const valueTrabajadorTarea = trabajadorTarea.value.trim();

    let valid = true;

    Array.from(document.querySelectorAll(".msg-error")).forEach(err => err.parentElement.removeChild(err));

    if (valueTarea.length === 0) {
        const p = document.createElement("p");
        p.classList.add("msg-error", "mt-2");
        p.innerText = "El campo tarea es obligatorio.";
        nombreTarea.parentElement.appendChild(p);
        valid = false;
    }

    if (valueTarea.length > 25) {
        const p = document.createElement("p");
        p.classList.add("msg-error", "mt-2");
        p.innerText = "El campo tarea excede los 25 caracteres.";
        nombreTarea.parentElement.appendChild(p);
        valid = false;
    }

    if (valueDescripcionTarea.length === 0) {
        const p = document.createElement("p");
        p.classList.add("msg-error", "mt-2");
        p.innerText = "El campo descripción es obligatorio.";
        descripcionTarea.parentElement.appendChild(p);
        valid = false;
    }

    if (valueDescripcionTarea.length > 250) {
        const p = document.createElement("p");
        p.classList.add("msg-error", "mt-2");
        p.innerText = "El campo descripción excede los 250 caracteres.";
        descripcionTarea.parentElement.appendChild(p);
        valid = false;
    }

    if (valueTrabajadorTarea.length === 0) {
        const p = document.createElement("p");
        p.classList.add("msg-error", "mt-2");
        p.innerText = "El campo trabajador es obligatorio.";
        trabajadorTarea.parentElement.appendChild(p);
        valid = false;
    }

    if (valueTrabajadorTarea.length > 25) {
        const p = document.createElement("p");
        p.classList.add("msg-error", "mt-2");
        p.innerText = "El campo trabajador excede los 25 caracteres.";
        trabajadorTarea.parentElement.appendChild(p);
        valid = false;
    }
    return valid;
}

const agregarTarea = async () => {
    const url = `${RUTA_API}/tarea`;
    const nombreTarea = document.getElementById("nombreTarea").value;
    const descripcionTarea = document.getElementById("descripcionTarea").value;
    const trabajadorTarea = document.getElementById("search-navbar").value;
    const idProyecto = document.getElementById("id_proyecto").value;

    const trabajador = await getUsuario(trabajadorTarea);
    if (!trabajador) {
        const p = document.createElement("p");
        p.classList.add("msg-error", "mt-2");
        p.innerText = "Trajador no encontrado.";
        document.getElementById("search-navbar").parentElement.appendChild(p);
        return;
    }

    const data = {
        id_proyecto: idProyecto,
        id_usuario: trabajador.id_usuario,
        nombre_tarea: nombreTarea,
        descripcion_tarea: descripcionTarea
    }

    const token = getCookie('token');
    await fetch(url, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                toastr.error(data.error, 'Error');
                return;
            }
            toastr.success('Tarea agregada con éxito', 'Éxito');
            setTimeout(() => {
                location.reload();
            }, 1000);
        }).catch(error => console.error('Error:', error));

}

const getUsuario = async (nombre) => {
    const url = `${RUTA_API}usuario/correo/${nombre}`;
    const token = getCookie('token');
    let user = null;


    await fetch(url, {
        method: "GET",
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
        .then(response => response.json())
        .then(data => {
            user = data;
        })
        .catch(error => console.error('Error:', error));

    return user;
}

const borrar = document.querySelectorAll(".borrar");
borrar.forEach(b => {
    b.onclick = async (e) => {
        const id = e.target.id;
        const url = `${RUTA_API}/tarea/${id}`;
        const token = getCookie('token');
        Swal.fire({
            title: "¿Estas seguro de borrar esta tarea?",
            text: "Una vez borrada no se podrá recuperar",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#858796",
            confirmButtonText: "Si, borrar",
            cancelButtonText: "No, volver atras"
        }).then((result) => {
            if (result.isConfirmed) {
                accionBorrarTarea(id);
            }
        });
    }
});

const accionBorrarTarea = async (id) => {
    const token = getCookie('token');
    const response = await fetch(`${RUTA_API}tarea?id=${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    });

    if (response.status === 200) {
        swal.fire({
            title: "Tarea borrada",
            text: "La tarea ha sido borrado correctamente",
            icon: "success",
            confirmButtonText: "Aceptar"
        }).then((result) => {
            location.reload();
        });
    } else {
        swal.fire({
            title: "Error",
            text: "Ha ocurrido un error al borrar la tarea",
            icon: "error",
            confirmButtonText: "Aceptar",
        });
    }
}

const borrarProyecto = document.querySelector("#btnBorrarProyecto");
borrarProyecto.onclick = async () => {
    const id = document.getElementById("id_proyecto").value;
    const url = `${RUTA_API}/proyecto/${id}`;
    const token = getCookie('token');
    Swal.fire({
        title: "¿Estas seguro de borrar este proyecto?",
        text: "Una vez borrado no se podrá recuperar",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#858796",
        confirmButtonText: "Si, borrar",
        cancelButtonText: "No, volver atras"

    }).then((result) => {
        if (result.isConfirmed) {
            accionBorrarProyecto(id);
        }
    });
};

const accionBorrarProyecto = async (id) => {
    const token = getCookie('token');
    const response = await fetch(`${RUTA_API}proyecto?id=${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    });

    if (response.status === 200) {
        swal.fire({
            title: "Proyecto borrado",
            text: "El proyecto ha sido borrado correctamente",
            icon: "success",
            confirmButtonText: "Aceptar"
        }).then((result) => {
            location.href = "<?= RUTA_URL ?>/proyectos";
        });
    } else {
        swal.fire({
            title: "Error",
            text: "Ha ocurrido un error al borrar el proyecto",
            icon: "error",
            confirmButtonText: "Aceptar",
        });
    }
}

window.onload = () => {
    const botonesEditar = Array.from(document.querySelectorAll(".btn-edit"));

    botonesEditar.forEach(b => {
        b.onclick = (e) => {
            const id = e.target.id;
            const url = `${RUTA_API}tarea?id=${id}`;
            const token = getCookie('token');
            fetch(url, {
                method: "GET",
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("nombreTareaExistente").value = data.nombre_tarea;
                    document.getElementById("descripcionTareaExistente").value = data.descripcion_tarea;
                    document.getElementById("nombreTrabajador").value = data.correo;
                    document.getElementById("actualizarTarea").onclick = () => {
                        actualizarTarea(data.id_tarea);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });

    const actualizarTarea = async (id) => {
        const url = `${RUTA_API}/tarea?id=${id}`;
        const nombreTarea = document.getElementById("nombreTareaExistente").value;
        const descripcionTarea = document.getElementById("descripcionTareaExistente").value;
        const token = getCookie('token');
        const data = {
            nombre_tarea: nombreTarea,
            descripcion_tarea: descripcionTarea
        }

        await fetch(url, {
            method: "PUT",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    toastr.error(data.error, 'Error');
                    return;
                }
                toastr.success('Tarea actualizada con éxito', 'Éxito');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }).catch(error => console.error('Error:', error));
    }
};