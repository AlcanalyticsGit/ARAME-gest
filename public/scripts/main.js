let filtroActivo = 0;
let elementosPorPagina = 10;
let paginas;
let vUsuarios = [];
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
/**
 * Calcula e inserta la paginación
 *
 */
function dibujarPaginacion() {
    let ulPaginacion = document.getElementById("ulPaginacion");
    let vPaginas = [];

    /* Calcula el número de páginas que tendrá la tabla a partir del número de registros y el número de elementos por página */
    if (typeof (vUsers) !== 'undefined') {
        paginas = Math.ceil(vUsers.length / elementosPorPagina)
    }
    if (typeof (vEmps) !== 'undefined') {
        paginas = Math.ceil(vEmps.length / elementosPorPagina)
    }
    if (typeof (vSocs) !== 'undefined') {
        paginas = Math.ceil(vSocs.length / elementosPorPagina)
    }
    if (typeof (vRecs) !== 'undefined') {
        paginas = Math.ceil(vRecs.length / elementosPorPagina)
    }
    if (typeof (vPrems) !== 'undefined') {
        paginas = Math.ceil(vPrems.length / elementosPorPagina)
    }

    /* Reinicia el contenido de la paginación */
    ulPaginacion.innerHTML = "";

    /* BOTÓN PRIMERA PÁGINA */
    let liPrimera = document.createElement("li");
    let buttonPrimera = document.createElement("a");
    let spanPrimera = document.createElement("span");
    liPrimera.setAttribute("class", paginaActiva <= 0 ? "page-item disabled" : "page-item");
    liPrimera.setAttribute("onclick", `setPaginaActiva(${0})`);
    buttonPrimera.setAttribute("class", "page-link color-principal");
    buttonPrimera.setAttribute("aria-label", "Anterior");
    buttonPrimera.setAttribute("onclick", `actualizarURL("pag", ${(1)})`);
    buttonPrimera.style = "cursor: pointer;";
    spanPrimera.setAttribute("aria-hidden", "true");
    spanPrimera.innerHTML = "Primera";
    buttonPrimera.appendChild(spanPrimera);
    liPrimera.appendChild(buttonPrimera);

    /* BOTÓN ÚLTIMA PÁGINA */
    let liUltima = document.createElement("li");
    let buttonUltima = document.createElement("a");
    let spanUltima = document.createElement("span");
    liUltima.setAttribute("class", paginaActiva == paginas - 1 ? "page-item disabled" : "page-item");
    liUltima.setAttribute("onclick", `setPaginaActiva(${paginas-1})`);
    buttonUltima.setAttribute("class", "page-link color-principal");
    buttonUltima.setAttribute("aria-label", "Anterior");
    buttonUltima.setAttribute("onclick", `actualizarURL("pag", ${paginas})`);
    buttonUltima.style = "cursor: pointer;";
    spanUltima.setAttribute("aria-hidden", "true");
    spanUltima.innerHTML = "Última";
    buttonUltima.appendChild(spanUltima);
    liUltima.appendChild(buttonUltima);

    /* BOTÓN PÁGINA ANTERIOR */
    let liAnterior = document.createElement("li");
    let buttonAnterior = document.createElement("a");
    let spanAnterior = document.createElement("span");
    liAnterior.setAttribute("class", paginaActiva <= 0 ? "page-item disabled" : "page-item");
    liAnterior.setAttribute("onclick", `setPaginaActiva(${paginaActiva - 1})`);
    buttonAnterior.setAttribute("class", "page-link color-principal");
    buttonAnterior.setAttribute("aria-label", "Anterior");
    // buttonAnterior.setAttribute("href", "#");
    buttonAnterior.setAttribute("onclick", `actualizarURL("pag", ${paginaActiva})`);
    buttonAnterior.style = "cursor: pointer;";
    spanAnterior.setAttribute("aria-hidden", "true");
    spanAnterior.innerHTML = "&laquo;";
    buttonAnterior.appendChild(spanAnterior);
    liAnterior.appendChild(buttonAnterior);

    /* BOTÓN PÁGINA SIGUIENTE */
    let liSiguiente = document.createElement("li");
    let buttonSiguiente = document.createElement("a");
    let spanSiguiente = document.createElement("span");
    liSiguiente.setAttribute("class", paginaActiva + 1 >= paginas ? `page-item disabled` : `page-item`);
    liSiguiente.setAttribute("onclick", `setPaginaActiva(${paginaActiva + 1})`);
    buttonSiguiente.setAttribute("class", `page-link color-principal`);
    buttonSiguiente.setAttribute("onclick", `actualizarURL("pag", ${paginaActiva+2})`);
    buttonSiguiente.style = "cursor: pointer;";
    buttonSiguiente.setAttribute("aria-label", "Siguiente");
    spanSiguiente.setAttribute("aria-hidden", "true");
    spanSiguiente.innerHTML = "&raquo;";
    buttonSiguiente.appendChild(spanSiguiente);
    liSiguiente.appendChild(buttonSiguiente);

    
        if (paginaActiva == 0 || paginaActiva == (paginas-1)) {
            $numPags = 4;
        }else if (paginaActiva == 1 || paginaActiva == (paginas-2)) {
            $numPags = 3;
        }
        else if (paginaActiva >= 2 || paginaActiva <= (paginas-3)) {
            $numPags = 2;
        }
        
        for (let i = paginaActiva - $numPags; i <= paginaActiva + $numPags; i++) {
            if (i >= 0 && i < paginas) {
                let li = document.createElement("li");
                li.setAttribute("onclick", `setPaginaActiva(${i})`);
                li.setAttribute("class", paginaActiva == i ? "page-item active" : "page-item color-principal");
                let button = document.createElement(paginaActiva == i ? "span" : "a");
                button.setAttribute("class", `page-link color-principal ${(paginaActiva == i ? `paginaSeleccionada` : ``)}`);
                // button.setAttribute("onclick", `setPaginaActiva(${i})`);
                button.setAttribute("aria-label", i + 1);
                // button.setAttribute("href", `#`);
                button.setAttribute("onclick", `actualizarURL("pag", ${i+1})`);
                button.style = "cursor: pointer;";
                let span = document.createElement("span");
                span.setAttribute("aria-hidden", "true");
                span.appendChild(document.createTextNode(`${i + 1}`));
                button.appendChild(span);
                li.appendChild(button);
                //vPaginas.push(li);
                if (paginas - i >= 2 || i - 0 >= 1) {
                    vPaginas.push(li);
                }
            }
        }
    
    
    /* Insertar números de página */
    if (paginaActiva > 0) {
        if (paginaActiva > 1) {
            ulPaginacion.appendChild(liPrimera);
        }
        ulPaginacion.appendChild(liAnterior);
    }

    vPaginas.forEach(pagina => {
        ulPaginacion.appendChild(pagina);
    });

    if (paginaActiva < paginas - 1) {
        ulPaginacion.appendChild(liSiguiente);

        if (paginaActiva < paginas - 2) {
            ulPaginacion.appendChild(liUltima);
        }
    }

    actualizarURL("pag", parseInt(paginaActiva) + 1);
}

function actualizarURL(parametro, valor) {
    window.history.replaceState('', '', updateURLParameter(window.location.href, parametro, valor));
}

/**
 * http://stackoverflow.com/a/10997390/11236
 */
function updateURLParameter(url, param, paramVal) {
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = ``;
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i = 0; i < tempArray.length; i++) {
            if (tempArray[i].split('=')[0] != param) {
                newAdditionalURL += `${temp}${tempArray[i]}`;
                temp = `&`;
            }
        }
    }

    var rows_txt = `${temp}${param}=${paramVal}`;
    return `${baseURL}?${newAdditionalURL}${rows_txt}`;
}

/* Muestra u oculta la sección de depuración al pie de la página */
function debug_code() {
    div_debug = document.getElementById("depuracion");
    div_debug.style.display == "none" ? div_debug.removeAttribute("style") : div_debug.style.display = "none";
}

function mostrarSociaVinculada() {
    let sociaVinculada = document.getElementById("sociaVinculada");
    let selectRol = document.getElementById("rol");

    if (selectRol.value == 30) {
        sociaVinculada.classList.remove("d-none");
    } else {
        sociaVinculada.classList.add("d-none");
    }
}