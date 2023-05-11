filtroActivo = 0;
vRecs = [];
vRecibos = [];

function poblarDesplegables() {
    let selectYear = document.getElementById("ficheros-year");
    let selectYearFicheros = document.getElementById("form-ficheros-year");
    let defaultYear = document.getElementById("defYearRecibos");
    let defaultYearFicheros = document.getElementById("defYearFicheros");

    // Desplegables recibos
        for (let year in vRecibosEmitidos) {
            let option = document.createElement("option");
            option.value = year;
            option.text = year;
            selectYear.add(option);

            if (urlParams.has('yr') && urlParams.get('yr') == year) {
                defaultYear.removeAttribute("selected");
                option.setAttribute("selected", "true");
                poblarDesplegablesRecibos();
            }

        }
    
    // Desplegables ficheros
    // if (selectYearFicheros.value == "") {
    //     for (let year in vRecibosEmitidos) {
    //         let option = document.createElement("option");
    //         option.value = year;
    //         option.text = year;
    //         selectYearFicheros.add(option);
    //         if (urlParams.has('yr') && urlParams.get('yr') == year) {
    //             defaultYearFicheros.selected=false;
    //             option.selected=true;
    //             poblarDesplegablesFicheros();
    //         }
    //     }
    // } 

    // if(urlParams.has('pag')) {
    //     paginaActiva=urlParams.get('pag');
    // } else {
    //     paginaActiva=0;
    // }
}

function poblarDesplegablesRecibos() {
    let selectYear = document.getElementById("ficheros-year");
    let selectSemestre = document.getElementById("ficheros-semestre");
    let year = selectYear.options[selectYear.selectedIndex].value;
    selectSemestre.innerHTML = "";

    if (selectYear.value!='') {
        vRecibosEmitidos[year]['semestres'].forEach(semester => {
            let optSem = document.createElement("option");
            optSem.value = semester.semestre;
            optSem.text = semester.semestre;
            selectSemestre.add(optSem);
            if (urlParams.has('sem') && urlParams.get('sem') == semester.semestre) {
                optSem.setAttribute("selected", "true");
            }
        });
        selectSemestre.removeAttribute("disabled");
    }

    

    if(urlParams.has('pag')) {
        paginaActiva=urlParams.get('pag')-1;
    } else {
        paginaActiva=0;
    }

    listarRecibos();
}

// function poblarDesplegablesFicheros() {

//     let selectYearFicheros = document.getElementById("form-ficheros-year");
//     let botonGenerar = document.getElementById("form-ficheros-boton");
//     let botonEnviar = document.getElementById("form-ficheros-boton-enviar");
//     let botonMasAcciones = document.getElementById("form-ficheros-boton-mas-acciones");
    
//     let selectSemester = document.getElementById("form-ficheros-semestre");
//     selectSemester.innerHTML = "";
//     year = selectYearFicheros.value;
//     if (selectYearFicheros.value!='') {
//         vRecibosEmitidos[year]['semestres'].forEach(semester => {
//             let optionSemester = document.createElement("option");
//             optionSemester.value = semester.semestre;
//             optionSemester.text = semester.semestre;
//             selectSemester.add(optionSemester);
//             if (urlParams.has('sem') && urlParams.get('sem') == semester.semestre) {
//                 optionSemester.setAttribute("selected", "true");
//             }
//         });
//         selectSemester.removeAttribute("disabled");
//     }

//     if (selectYearFicheros.value != "" && selectSemester != "") {
//         botonGenerar.removeAttribute("disabled");
//         // botonEnviar.removeAttribute("disabled");
//         botonMasAcciones.removeAttribute("disabled");
//     }
// }

/**
 * Muestra la pantalla de carga
 *
 */
function activarLS() {
    let overlay = document.getElementById("superposicion-carga");
    overlay.style = "";
}

/**
 * Recibe un número de página y lo establece como la página activa
 *
 * @param {*} pagina
 */
function setPaginaActiva(pagina) {
    pagina > 0 ? paginaActiva = pagina : paginaActiva = 1;
    pagina <= paginas ? paginaActiva = pagina : paginaActiva = paginas;
    /* Crea todas las filas de recibos partir de vRecs y las inserta en la tabla */
    listarRecibos();
}

function mostrarAlertDescarga() {
    let alert = 'warning';
    let msg = 'La descarga se iniciará pronto. Por favor, no cierre la página hasta entonces.';
    let divAlerts = document.getElementById("divAlerts");

    let divAlert = document.createElement("div");
    divAlert.className = "fixed-top mt-5 pt-5 d-flex justify-content-center";
    let divAlert2 = document.createElement("div");
    divAlert2.className = `alert alert-${alert} alert-dismissible fade show shadow-lg col-12 col-md-10 col-lg-8`;
    divAlert2.innerText = msg;
    let button = document.createElement("button");
    button.type = "button";
    button.className = "btn-close";
    button.setAttribute("data-bs-dismiss", "alert");
    button.setAttribute("aria-label", "Close");

    divAlert2.appendChild(button);
    divAlert.appendChild(divAlert2);
    divAlerts.appendChild(divAlert);
}

function listarRecibos() {
    /* Selecciona la tabla del DOM y borra el contenido */
    let tabla = document.getElementById("tablaItems");
    tabla.innerHTML = "";

    let selectYear = document.getElementById("ficheros-year");
    let selectSemestre = document.getElementById("ficheros-semestre");
    let tablaParent = tabla.parentNode.parentNode;
    tablaParent.classList.remove("d-none"); 
    let semSelec = selectSemestre.value;
    let year = selectYear.value;

    vRecibosEmitidos[year].semestres.forEach(semestre => {
        if (semestre.semestre == semSelec) {
            vRecs = semestre.recibos;
        }
    });
    
    let enlaceListado = document.getElementById("enlaceListaRecibos");
    let enlaceDescargasPdf = document.getElementById("enlaceRecibosPdf");
    let enlaceEnviarRemesa = document.getElementById("enviarRemesa");
    let yr = selectYear.options[selectYear.selectedIndex].value;
    let sm = selectSemestre.options[selectSemestre.selectedIndex].value;
    enlaceListado.href=rutaURL+`/recibos/mostrarListadoRecibos/${yr}/${sm}`;
    enlaceDescargasPdf.href=rutaURL+`/recibos/descargarRemesa/${yr}/${sm}?pag=${(paginaActiva+1)}&yr=${yr}&sem=${sm}`;
    enlaceEnviarRemesa.href=rutaURL+`/recibos/enviarRemesa/${yr}/${sm}?pag=${(paginaActiva+1)}&yr=${yr}&sem=${sm}`;
    let divEnlaceListado =enlaceListado.parentNode;
    let divDescargasPdf =enlaceDescargasPdf.parentNode;
    divEnlaceListado.classList.remove("d-none"); 
    divDescargasPdf.classList.remove("d-none"); 
    enlaceEnviarRemesa.parentNode.classList.remove("d-none"); 
    
    

    /* Calcula el inicio y el final de la muestra de datos en función del número de página y el número de elementos por página */
    let inicio = paginaActiva * elementosPorPagina;
    let offset = inicio + elementosPorPagina;
    if (offset > vRecs.length) {
        offset = vRecs.length;
    }
    
    /* Crea una fila para cada empresa en vEmps */
    for (let i = inicio; i < offset; i++) {
        let socia = vRecs[i].socia;
        let recibo = vRecs[i].recibo;
        let recibo_codigo = recibo.year+"/"+recibo.semestre+"/"+recibo.cod;

        /* Crea la fila */
        let trNode = document.createElement("tr");

        let liClassName = "";
        let divSpanWarning = "";
    
        /* Comprueba si la socia se ha dado de baja durante el semestre y colorea la fila en rojo o amarillo en función de la fecha de baja */
        if (recibo.fecha_baja!=null) {
            let bajaYear = recibo.fecha_baja.substring(0,4);
            let bajaMonth = parseInt(recibo.fecha_baja.substring(5,7));
            
            if (bajaYear.toString()==recibo.year.toString()) {
                divSpanWarning = `<strong>Fecha de baja:</strong> ${recibo.fecha_baja}`;
                if (recibo.semestre=="1S") {
                    if (bajaMonth<=2) {
                        liClassName = "list-group-item-danger";
                    } else if (bajaMonth<=4) {
                        liClassName = "list-group-item-warning";
                    } else {
                        liClassName = "list-group-item-secondary";
                    }
                } else if(recibo.semestre=="2S") {
                    if (bajaMonth<=8) {
                        liClassName = "list-group-item-danger";
                    } else if (bajaMonth<=10) {
                        liClassName = "list-group-item-warning";
                    } else {
                        liClassName = "list-group-item-secondary";
                    }
                }
            }
        }

        /* Enlaces */
        let ruta = `${rutaURL}/recibos`;
        let enlaceEditar = `${ruta}/editar/${recibo.year}/${recibo.semestre}/${recibo.cod}?pag=${(paginaActiva+1)}`;
        let enlaceEliminar = `${ruta}/borrar/${recibo.year}/${recibo.semestre}/${recibo.cod}?pag=${(paginaActiva+1)}`;
        let enlaceVer = `${ruta}/generar/${recibo.year}/${recibo.semestre}/${recibo.cod}`;
        let enlaceDescargar = `${ruta}/generar/${recibo.year}/${recibo.semestre}/${recibo.cod}/D`;
        let enlaceEnviar = `${ruta}/enviar/${recibo.year}/${recibo.semestre}/${recibo.cod}?pag=${(paginaActiva+1)}`;

        /* Crea las columnas que contendrá la fila */
        trNode.className+=liClassName;
        let tdCod = document.createElement("td");
        let tdFecha = document.createElement("td");
        let tdAsociada = document.createElement("td");
        let tdImporte = document.createElement("td");
        let tdNotas = document.createElement("td");
        let tdAcciones = document.createElement("td");
        let divAcciones = document.createElement("div");

        tdCod.appendChild(document.createTextNode(recibo_codigo));
        tdFecha.appendChild(document.createTextNode(recibo.fecha));
        tdAsociada.appendChild(document.createTextNode(`${recibo.nombre_socia} ${recibo.apellidos_socia}`));
        tdImporte.appendChild(document.createTextNode(`${recibo.cuantia} €`));

        tdNotas.innerHTML = divSpanWarning;

        divAcciones.setAttribute("class", "d-flex justify-content-end");

        if (auth < 30) {
            /* Acción Editar */
            let aEditar = document.createElement("a");
            let aEditarIcono = document.createElement("i");
            aEditar.className = "nav-link nav-link-primary pe-2";
            aEditar.href = enlaceEditar;
            aEditar.setAttribute("title", "Ver recibo");
            aEditarIcono.className = "bi bi bi-pencil";
            aEditarIcono.title = "Modificar recibo";
            aEditar.appendChild(aEditarIcono);
            
            /* Acción Ver */
            let aVer = document.createElement("a");
            let aVerIcono = document.createElement("i");
            aVer.className = "nav-link nav-link-primary pe-2";
            aVer.href = enlaceVer;
            aVer.target="_blank";
            aVer.setAttribute("title", "Ver recibo");
            aVerIcono.className = "bi bi-file-text";
            aVerIcono.title = "Ver fichero recibo";
            aVer.appendChild(aVerIcono);
            
            /* Acción Descargar */
            let aDescargar = document.createElement("a");
            let aDescargarIcono = document.createElement("i");
            aDescargar.className = "nav-link nav-link-primary pe-2";
            aDescargar.href = enlaceDescargar;
            aDescargar.target="_blank";
            aDescargar.setAttribute("title", "Descargar recibo");
            aDescargarIcono.className = "bi bi-download";
            aDescargarIcono.title = "Descargar fichero";
            aDescargar.appendChild(aDescargarIcono);

            /* Acción Enviar */
            let aEnviar = document.createElement("a");
            let aEnviarIcono = document.createElement("i");
            aEnviar.className = "nav-link nav-link-primary pe-2";
            aEnviar.href = enlaceEnviar;
            aEnviarIcono.className = "bi bi-envelope";
            aEnviarIcono.title = "Enviar a la socia";
            aEnviar.appendChild(aEnviarIcono);
            
            /* Acción Eliminar */
            let aEliminar = document.createElement("a");
            let aEliminarIcono = document.createElement("i");
            aEliminar.className = "nav-link nav-link-primary";
            aEliminar.href = enlaceEliminar;
            aEliminar.setAttribute("title", "Ver recibo");
            aEliminarIcono.className = "bi bi-trash3";
            aEliminarIcono.title = "Eliminar recibo";
            aEliminar.appendChild(aEliminarIcono);

            divAcciones.appendChild(aEditar);
            divAcciones.appendChild(aVer);
            divAcciones.appendChild(aDescargar);
            divAcciones.appendChild(aEnviar);
            divAcciones.appendChild(aEliminar);
            tdAcciones.appendChild(divAcciones);
        }

        /* Asigna cada columna a la fila */
        trNode.appendChild(tdCod);
        trNode.appendChild(tdFecha);
        trNode.appendChild(tdImporte);
        trNode.appendChild(tdAsociada);
        trNode.appendChild(tdNotas);
        trNode.appendChild(tdAcciones);
        tabla.appendChild(trNode);
    }
    
    /* Calcula e inserta la paginación */
    dibujarPaginacion();

    actualizarURL("yr", year);
    actualizarURL("sem", semSelec);
}
