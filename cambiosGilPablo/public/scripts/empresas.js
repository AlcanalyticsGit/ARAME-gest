filtroActivo = 11;
vEmps = [];
vEmpresas = [];

/**
 * Recibe un número de página y lo establece como la página activa
 *
 * @param {*} pagina
 */
function setPaginaActiva(pagina) {
    pagina > 0 ? paginaActiva = pagina : paginaActiva = 1;
    pagina <= paginas ? paginaActiva = pagina : paginaActiva = paginas;

    /* Crea todas las filas de empresas partir de vEmps y las inserta en la tabla */
    listarEmpresas();
}

/**
 * Crea todas las filas de empresas partir de vEmps y las inserta en la tabla
 *
 */
function listarEmpresas() {
    /* Selecciona la tabla del DOM y borra el contenido */
    let tabla = document.getElementById("tablaItems");
    tabla.innerHTML = "";

    /* Calcula el inicio y el final de la muestra de datos en función del número de página y el número de elementos por página */
    let inicio = paginaActiva * elementosPorPagina;
    let offset = inicio + elementosPorPagina;
    if (offset > vEmps.length) {
        offset = vEmps.length;
    }

    /* Crea una fila para cada empresa en vEmps */
    for (let i = inicio; i < offset; i++) {

        /* Crea la fila */
        let trNode = document.createElement("tr");

        /* Enlaces */
        let ruta = `${rutaURL}/empresas`;
        let enlaceEditar = `${ruta}/editar/${vEmps[i].nif}?pag=${paginaActiva+1}`;
        let enlaceEliminar = `${ruta}/borrar/${vEmps[i].nif}?pag=${paginaActiva+1}`;

        /* Crea las columnas que contendrá la fila */
        let tdSocCod = document.createElement("td");
        let tdNombre = document.createElement("td");
        let tdTelf = document.createElement("td");
        let tdMovil = document.createElement("td");
        let tdEmail = document.createElement("td");
        let tdAcciones = document.createElement("td");

        /* Columna Código de socia */
        let aSocCod = document.createElement("a");
        aSocCod.setAttribute("href", enlaceEditar);
        aSocCod.setAttribute("class", "nav-link nav-link-primary")
        aSocCod.appendChild(document.createTextNode(vEmps[i].nif));
        tdSocCod.appendChild(aSocCod);

        /* Columna Nombre*/
        let aLink = document.createElement("a");
        aLink.setAttribute("href", enlaceEditar);
        aLink.setAttribute("class", "nav-link nav-link-primary")
        aLink.setAttribute("title", "Modificar");
        aLink.appendChild(document.createTextNode(vEmps[i].nombre.toUpperCase()))
        tdNombre.appendChild(aLink);

        /* Columna Teléfono */
        tdTelf.appendChild(document.createTextNode(vEmps[i].telefono));

        /* Columna Teléfono 2 */
        tdMovil.appendChild(document.createTextNode(vEmps[i].telefono_2));

        /* Columna Email */
        tdEmail.appendChild(document.createTextNode(vEmps[i].email));

        /* Columna Acciones */
        let divAcciones = document.createElement("div");
        divAcciones.setAttribute("class", "d-flex justify-content-end");

        if (auth < 30) {
            /* Div Eliminar */
            let aEliminar = document.createElement("a");
            aEliminar.className = "nav-link nav-link-primary"
            aEliminar.setAttribute("href", enlaceEliminar);
            aEliminar.setAttribute("title", "Eliminar");

            /* Icono Eliminar */
            let aEliminarIcono = document.createElement("i");
            aEliminarIcono.className = "bi bi-trash3";
            aEliminarIcono.title = "Eliminar empresa";
            aEliminar.appendChild(aEliminarIcono);

            /* Asignar a la columna Eliminar */
            divAcciones.appendChild(aEliminar);
            tdAcciones.appendChild(divAcciones);
        }

        /* Asigna cada columna a la fila */
        trNode.appendChild(tdSocCod);
        trNode.appendChild(tdNombre);
        trNode.appendChild(tdTelf);
        trNode.appendChild(tdMovil);
        trNode.appendChild(tdEmail);
        trNode.appendChild(tdAcciones);

        /* Inserta la fila en la tabla */
        tabla.appendChild(trNode);
    }

    /* Calcula e inserta la paginación */
    dibujarPaginacion();
}

/**
 * Recibe un código de filtro y lo asigna como el filtro activo
 *
 * @param {*} filtro
 */
function cambiarFiltroEmpresas(filtro) {
    filtroActivo = filtro;
    // document.getElementById("barraBusqueda").value = "";
    paginaActiva=0;
    actualizarURL("pag", paginaActiva);
    filtrarEmpresas();
}

/**
 * Copia en vEmps cada elemento en vEmpresas que coincida con el filtro activo y el término introducido en la barra de búsqueda
 *
 */
function filtrarEmpresas() {
    /* Inicializa las variables y normaliza el término introducido en la barra de búsqueda para ignorar espacios, acentos y caracteres especiales */
    let termino = document.getElementById("barraBusqueda").value.toString().replaceAll(" ", "").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    vEmps = [];

    /* Cambia el aspecto de los filtros para marcar el que está activo y desmarcar los demás */
    let enlaceActivo = "nav-link bg-principal active";
    let enlaceInactivo = "nav-link text-secondary";
    switch (filtroActivo) {
        case 0:
            document.getElementById("filtroTodos").setAttribute("class", enlaceActivo);
            document.getElementById("filtroAutonoma").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroEmpresas").setAttribute("class", enlaceInactivo);
            break;
        case 10:
            document.getElementById("filtroTodos").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroAutonoma").setAttribute("class", enlaceActivo);
            document.getElementById("filtroEmpresas").setAttribute("class", enlaceInactivo);
            break;
        case 11:
            document.getElementById("filtroTodos").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroAutonoma").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroEmpresas").setAttribute("class", enlaceActivo);
            break;
        default:
            break;
    }

    /* Establece la variable _condicion_ como true si se cumplen los requisitos del filtro activo */
    vEmpresas.forEach(empresa => {
        let condicion = false;
        switch (filtroActivo) {
            case 10:
                /* Comprueba si la empresa figura como autónoma */
                if (empresa.es_autonoma == 1) condicion = true;
                break;
            case 11:
                /* Comprueba si la empresa no tiene NIF personal */
                if (empresa.es_autonoma == 0) condicion = true;
                break;
            default:
                break;
        }

        /* Inserta la empresa en vEms si cumple los requisitos */
        try {
            if ((filtroActivo == 0 ||
                    condicion) &&
                (empresa.nif.toString().normalize().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                empresa.nombre.toString().normalize().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                empresa.telefono.toString().normalize().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                empresa.telefono_2.toString().normalize().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                empresa.email.toString().normalize().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 
                )
                ) {
                vEmps.push(empresa);
            }
        } catch (error) {}
    });

    /* Crea todas las filas de empresas partir de vEmps y las inserta en la tabla */
    paginaActiva = 0;
    listarEmpresas();
}

/**
 * Copia en _sectores-empresa-listado_ la opción seleccionada en _sectores_
 *
 */
function incorporarSector() {
    /* Inicializa las variables */
    let select = document.getElementById("sectores");
    let selectSectoresEmpresa = document.getElementById("sectores-empresa-listado");
    let selected = select.options[select.selectedIndex];
    let valor = selected.value;
    let selectedCopia = selected.cloneNode(true);

    /* Modifica el ID y el comportamiento de la opción copiada */
    selectedCopia.id = `sectores-empresa-${valor}`;
    selectedCopia.setAttribute("ondblclick", "desIncorporarSector()")

    /* Copia la copia modificada del elemento en _sectores-empresa-listado_ */
    if (!document.getElementById(`sectores-empresa-${valor}`)) {
        selectSectoresEmpresa.appendChild(selectedCopia);
    }

    /* Elimina todos los elementos de _sectores-empresa_ y vuelve a insertar los valores actualizados  */
    actualizarListaSectoresEmpresa();
}

/**
 * Borra la opción seleccionada de _sectores-empresa-listado_
*
*/
function desIncorporarSector() {
    let selectSectoresEmpresa = document.getElementById("sectores-empresa-listado");
    let opt = selectSectoresEmpresa.options[selectSectoresEmpresa.selectedIndex];
    document.getElementById(opt.id).remove();
    
    /* Elimina todos los elementos de _sectores-empresa_ y vuelve a insertar los valores actualizados  */
    actualizarListaSectoresEmpresa();
}

/**
 * Elimina todos los elementos de _sectores_ y vuelve a insertar los valores actualizados
 *
 */
function actualizarListaSectoresEmpresa() {
    let select = document.getElementById("sectores-empresa-listado");
    let hiddenInputs = document.getElementById("sectores-empresa");
    hiddenInputs.innerHTML = "";

    for (opt of select) {
        let input = document.createElement("input");
        input.value = opt.value;
        input.name = "sectores-empresa[]";
        input.setAttribute("type", "hidden");
        hiddenInputs.appendChild(input);
    }
}

/**
 * Copia en _socias-empresa-listado_ la opción seleccionada en _socias_
 *
 */
function incorporarSocia() {
    let select = document.getElementById("socias");
    let selected = select.options[select.selectedIndex];
    let valor = selected.value;
    let selectedCopia = selected.cloneNode(true);
    let selectSectoresEmpresa = document.getElementById("socias-empresa-listado");

    selectedCopia.id = `socias-empresa-${valor}`;
    selectedCopia.setAttribute("ondblclick", "desIncorporarSocia()")

    if (!document.getElementById(`socias-empresa-${valor}`)) {
        selectSectoresEmpresa.appendChild(selectedCopia);
    }

    /* Elimina todos los elementos de _socias-empresa_ y vuelve a insertar los valores actualizados  */
    actualizarListaSociasEmpresa();
}

/**
 * Borra la opción seleccionada de _socias-empresa-listado_
 *
 */
function desIncorporarSocia() {
    let selectSectoresEmpresa = document.getElementById("socias-empresa-listado");
    let opt = selectSectoresEmpresa.options[selectSectoresEmpresa.selectedIndex];
    document.getElementById(opt.id).remove();

    /* Elimina todos los elementos de _socias-empresa_ y vuelve a insertar los valores actualizados  */
    actualizarListaSociasEmpresa();
}

/**
 * Elimina todos los elementos de _socias-empresa_ y vuelve a insertar los valores actualizados
 *
 */
function actualizarListaSociasEmpresa() {
    let select = document.getElementById("socias-empresa-listado");
    let hiddenInputs = document.getElementById("socias-empresa");
    hiddenInputs.innerHTML = "";

    for (opt of select) {
        let input = document.createElement("input");
        input.value = opt.value;
        input.name = "socias-empresa[]";
        input.setAttribute("type", "hidden");
        hiddenInputs.appendChild(input);
    }
}