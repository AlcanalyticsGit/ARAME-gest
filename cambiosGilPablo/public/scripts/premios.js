filtroActivo = 11;
vPrems = [];
vPremios = [];

function setPaginaActiva(pagina) {
    pagina > 0 ? paginaActiva = pagina : paginaActiva = 1;
    pagina <= paginas ? paginaActiva = pagina : paginaActiva = paginas;
    listarPremios();
}

function listarPremios() {
    let tabla = document.getElementById("tablaItems");
    let inicio = paginaActiva * elementosPorPagina;
    let offset = inicio + elementosPorPagina;
    tabla.innerHTML = "";

    /* Calcula el inicio y el final de la muestra de datos en función del número de página y el número de elementos por página */
    if (offset > vPrems.length) {
        offset = vPrems.length;
    }

    for (let i = inicio; i < offset; i++) {

        /* Crea la fila */
        let trNode = document.createElement("tr");

        /* Enlaces */
        let ruta = `${rutaURL}/premios`;
        let enlaceEditar = `${ruta}/editar/${vPrems[i].year}/${vPrems[i].socia_cod}?pag=${(paginaActiva+1)}`;
        let enlaceEditarSocia = `${rutaURL}/socias/editar/${vPrems[i].socia_cod}?pag=${(paginaActiva+1)}`;
        let enlaceEliminar = `${ruta}/borrar/${vPrems[i].year}/${vPrems[i].socia_cod}`;

        /* Crea las columnas que contendrá la fila */
        let tdYear = document.createElement("td");
        let tdSocia = document.createElement("td");
        let tdDescripcion = document.createElement("td");
        let tdAcciones = document.createElement("td");

        /* Columna Año*/
        let aLink = document.createElement("a");
        aLink.setAttribute("href", enlaceEditar);
        aLink.setAttribute("class", "nav-link nav-link-primary")
        aLink.setAttribute("title", "Modificar");
        aLink.innerHTML = "<strong>"+vPrems[i].year+"</strong>";
        tdYear.appendChild(aLink);

        /* Columna Socia */
        let aSocia = document.createElement("a");
        aSocia.setAttribute("href", enlaceEditarSocia);
        aSocia.setAttribute("class", "nav-link nav-link-primary text-uppercase")
        aSocia.setAttribute("title", `Ir a la ficha de ${vPrems[i].socia_nombre} ${vPrems[i].socia_apellidos}`);
        aSocia.appendChild(document.createTextNode(`${vPrems[i].socia_nombre} ${vPrems[i].socia_apellidos}`))
        tdSocia.appendChild(aSocia);
        
        /* Columna Descripción */
        tdDescripcion.appendChild(document.createTextNode(vPrems[i].descripcion));

        /* Columna Acciones */
        let divAcciones = document.createElement("div");
        divAcciones.setAttribute("class", "d-flex justify-content-end");

        if (auth < 30) {
            /* Div Editar */
            let aEditar = document.createElement("a");
            aEditar.className = "nav-link nav-link-primary"
            aEditar.setAttribute("href", enlaceEditar);
            aEditar.setAttribute("title", "Eliminar");

            /* Icono Editar */
            let aEditarIcono = document.createElement("i");
            aEditarIcono.className = "bi bi-pencil me-2";
            aEditarIcono.title = "Editar premio";
            aEditar.appendChild(aEditarIcono);

            /* Asignar a la columna Editar */
            divAcciones.appendChild(aEditar);

            /* Div Eliminar */
            let aEliminar = document.createElement("a");
            aEliminar.className = "nav-link nav-link-primary"
            aEliminar.setAttribute("href", enlaceEliminar);
            aEliminar.setAttribute("title", "Eliminar");

            /* Icono Eliminar */
            let aEliminarIcono = document.createElement("i");
            aEliminarIcono.className = "bi bi-trash3";
            aEliminarIcono.title = "Eliminar premio";
            aEliminar.appendChild(aEliminarIcono);

            /* Asignar a la columna Eliminar */
            divAcciones.appendChild(aEliminar);

            /* Asigna la columna Acciones  */
            tdAcciones.appendChild(divAcciones);
        }

        /* Asigna cada columna a la fila */
        trNode.appendChild(tdYear);
        trNode.appendChild(tdSocia);
        trNode.appendChild(tdDescripcion);
        trNode.appendChild(tdAcciones);

        /* Inserta la fila en la tabla */
        tabla.appendChild(trNode);
    }

    dibujarPaginacion();
}

function cambiarFiltroPremios(filtro) {
    filtroActivo = filtro;
    // document.getElementById("barraBusqueda").value = "";
    paginaActiva=0;
    actualizarURL("pag", paginaActiva);
    filtrarPremios();
}

function filtrarPremios() {
    vPrems = [];
    let termino = document.getElementById("barraBusqueda").value.toString().replaceAll(" ", "").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

    vPremios.forEach(premio => {      
        
        if ((filtroActivo == 0 ||
                condicion) &&
            (premio.year.toString().normalize().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                (premio.socia_nombre + premio.socia_apellidos).toString().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                premio.descripcion.toString().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 )
        ) {
            vPrems.push(premio);

        }
    });

    // let enlaceActivo = "nav-link bg-principal active";
    // let enlaceInactivo = "nav-link text-secondary";

    // switch (filtroActivo) {
    //     case 0:
    //         document.getElementById("filtroTodos").setAttribute("class", enlaceActivo);
    //         document.getElementById("filtroAlta").setAttribute("class", enlaceInactivo);
    //         document.getElementById("filtroBaja").setAttribute("class", enlaceInactivo);
    //         break;
    //     case 11:
    //         document.getElementById("filtroTodos").setAttribute("class", enlaceInactivo);
    //         document.getElementById("filtroAlta").setAttribute("class", enlaceActivo);
    //         document.getElementById("filtroBaja").setAttribute("class", enlaceInactivo);
    //         break;
    //     case 12:
    //         document.getElementById("filtroTodos").setAttribute("class", enlaceInactivo);
    //         document.getElementById("filtroAlta").setAttribute("class", enlaceInactivo);
    //         document.getElementById("filtroBaja").setAttribute("class", enlaceActivo);
    //         break;

    //     default:
    //         break;
    // }

    paginaActiva = 0;
    listarPremios();
}