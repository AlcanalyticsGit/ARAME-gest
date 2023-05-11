filtroActivo = 11;
vUsers = [];
vUsuarios = [];

function setPaginaActiva(pagina) {
    pagina > 0 ? paginaActiva = pagina : paginaActiva = 1;
    pagina <= paginas ? paginaActiva = pagina : paginaActiva = paginas;
    listarUsuarios();
}

function listarUsuarios() {
    let tabla = document.getElementById("tablaItems");
    let inicio = paginaActiva * elementosPorPagina;
    let offset = inicio + elementosPorPagina;
    tabla.innerHTML = "";

    /* Calcula el inicio y el final de la muestra de datos en función del número de página y el número de elementos por página */
    if (offset > vUsers.length) {
        offset = vUsers.length;
    }

    for (let i = inicio; i < offset; i++) {
        /* Crea la fila */
        let trNode = document.createElement("tr");

        /* Enlaces */
        let ruta = `${rutaURL}/usuarios`;
        let enlaceEditar = `${ruta}/editar/${vUsers[i].username}?pag=${paginaActiva+1}`;
        let enlaceEliminar = `${ruta}/borrar/${vUsers[i].username}?pag=${paginaActiva+1}`;

        /* Crea las columnas que contendrá la fila */
        let tdUsername = document.createElement("td");
        let tdNombre = document.createElement("td");
        let tdRol = document.createElement("td");
        let tdAcciones = document.createElement("td");

        /* Columna Username*/
        let aLink = document.createElement("a");
        aLink.setAttribute("href", enlaceEditar);
        aLink.setAttribute("class", "nav-link nav-link-primary");
        aLink.setAttribute("title", "Modificar");
        aLink.innerHTML = vUsers[i].username;
        tdUsername.appendChild(aLink);

        /* Columna Nombre */
        let aSocia = document.createElement("a");
        aSocia.setAttribute("href", enlaceEditar);
        aSocia.setAttribute("class", "nav-link nav-link-primary");
        aSocia.setAttribute("title", `Ir a la ficha de ${vUsers[i].nombre}`);
        aSocia.appendChild(document.createTextNode(vUsers[i].nombre))
        tdNombre.appendChild(aSocia);
        
        /* Columna Rol */
        tdRol.appendChild(document.createTextNode(vUsers[i].rol_nombre));

        /* Columna Acciones */
        let divAcciones = document.createElement("div");
        divAcciones.setAttribute("class", "d-flex justify-content-end");

        if (auth < 30) {
            /* Div Editar */
            let aEditar = document.createElement("a");
            aEditar.className = "nav-link nav-link-primary";
            aEditar.setAttribute("href", enlaceEditar);
            aEditar.setAttribute("title", "Eliminar");

            /* Icono Editar */
            let aEditarIcono = document.createElement("i");
            aEditarIcono.className = "bi bi-pencil";
            aEditarIcono.title = "Editar premio";
            aEditar.appendChild(aEditarIcono);

            /* Asignar a la columna Editar */
            divAcciones.appendChild(aEditar);

            /* Div Eliminar */
            let aEliminar = document.createElement("a");
            aEliminar.className = "nav-link nav-link-primary";
            aEliminar.setAttribute("href", enlaceEliminar);
            aEliminar.setAttribute("title", "Eliminar");

            if(usuarioSesionUsername !== vUsers[i].username) {
                /* Icono Eliminar */
                let aEliminarIcono = document.createElement("i");
                aEliminarIcono.className = "bi bi-trash3 ms-2";
                aEliminarIcono.title = "Eliminar premio";
                aEliminar.appendChild(aEliminarIcono);
    
                /* Asignar a la columna Eliminar */
                divAcciones.appendChild(aEliminar);
            }

            /* Asigna la columna Acciones  */
            tdAcciones.appendChild(divAcciones);
        }

        /* Asigna cada columna a la fila */
        trNode.appendChild(tdUsername);
        trNode.appendChild(tdNombre);
        trNode.appendChild(tdRol);
        trNode.appendChild(tdAcciones);

        /* Inserta la fila en la tabla */
        tabla.appendChild(trNode);
    }

    dibujarPaginacion();
}

function cambiarFiltroUsuarios(filtro) {
    filtroActivo = filtro;
    paginaActiva=0;
    actualizarURL("pag", paginaActiva);
    filtrarUsuarios();
}

function filtrarUsuarios() {
    vUsers = [];
    let termino = document.getElementById("barraBusqueda").value.toString().replaceAll(" ", "").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

    vUsuarios.forEach(usuario => {

        let condicion = false;
        switch (filtroActivo) {
            case 0:
                condicion = true;
                break;
            case 11:
                /* Comprueba si la empresa figura como autónoma */
                if (usuario.rol == 20) condicion = true;
                break;
            case 12:
                /* Comprueba si la empresa no tiene NIF personal */
                if (usuario.rol == 30) condicion = true;
                break;
            default:
                break;
        }
        
        try {
            if ((filtroActivo == 0 ||
                    condicion) &&
                (usuario.username.toString().normalize().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                    usuario.nombre.toString().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                    usuario.rol_nombre.toString().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1
                    )
            ) {
                vUsers.push(usuario);
            }
        } catch ($error) {}
    });

    let enlaceActivo = "nav-link bg-principal active";
    let enlaceInactivo = "nav-link text-secondary";

    switch (filtroActivo) {
        case 0:
            document.getElementById("filtroTodos").setAttribute("class", enlaceActivo);
            document.getElementById("filtroAdmins").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroUsuarios").setAttribute("class", enlaceInactivo);
            break;
        case 11:
            document.getElementById("filtroTodos").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroAdmins").setAttribute("class", enlaceActivo);
            document.getElementById("filtroUsuarios").setAttribute("class", enlaceInactivo);
            break;
        case 12:
            document.getElementById("filtroTodos").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroAdmins").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroUsuarios").setAttribute("class", enlaceActivo);
            break;

        default:
            break;
    }

    paginaActiva = 0;
    listarUsuarios();
}

