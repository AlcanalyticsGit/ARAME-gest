filtroActivo = 11;
vSocs = [];
vSocias = [];

function setPaginaActiva(pagina) {
    pagina > 0 ? paginaActiva = pagina : paginaActiva = 1;
    pagina <= paginas ? paginaActiva = pagina : paginaActiva = paginas;
    listarSocias();
}

function listarSocias() {

    let tabla = document.getElementById("tablaItems");
    let inicio = paginaActiva * elementosPorPagina;
    let offset = inicio + elementosPorPagina;
    tabla.innerHTML = "";

    /* Calcula el inicio y el final de la muestra de datos en función del número de página y el número de elementos por página */
    if (offset > vSocs.length) {
        offset = vSocs.length;
    }

    for (let i = inicio; i < offset; i++) {


        let trNode = document.createElement("tr");
        let tdNombre = document.createElement("td");
        let spanNombre = document.createElement("span");
        let aLink = document.createElement("a");
        let textNodeNombre = `${vSocs[i].nombre.toUpperCase()} ${vSocs[i].apellidos.toUpperCase()}`;
        // aLink.setAttribute("href", `${rutaURL}/socias/editar/${vSocs[i].cod}?pag=${(paginaActiva+1)}`);
        aLink.setAttribute("class", "nav-link nav-link-primary");
        aLink.setAttribute("data-bs-toggle", "modal");
        aLink.setAttribute("data-bs-target", "#verPerfilModal");
        aLink.setAttribute("title", "Ver Perfil");
        aLink.onclick = function() { abrirModalSocia(vSocs[i]) };
        spanNombre.innerHTML = textNodeNombre;
        
        // Comprobar premios
        vSocs[i].premios.forEach(premio => {
            if (premio.year>=new Date().getFullYear()-1) {
                spanNombre.innerHTML += " ";
            let iPremio = document.createElement("i");
            iPremio.className = "bi bi-award-fill";
            iPremio.title=premio.year;
            spanNombre.appendChild(iPremio);
            }
        });
        
        aLink.appendChild(spanNombre)
        tdNombre.appendChild(aLink);
        let tdTelf = document.createElement("td");
        let tdMovil = document.createElement("td");
        let tdEmail = document.createElement("td");
        let tdEmpresas = document.createElement("td");
        let empresasSocia = "";
        tdTelf.appendChild(document.createTextNode(vSocs[i].tlf));
        tdMovil.appendChild(document.createTextNode(vSocs[i].movil));
        tdEmail.appendChild(document.createTextNode(vSocs[i].email));
        vSocs[i].empresas.forEach(empresa => {
            let aEmpresa = document.createElement("a");
            // aEmpresa.className = "nav-link nav-link-primary";
            aEmpresa.setAttribute("class", "nav-link nav-link-primary");
            aEmpresa.setAttribute("data-bs-toggle", "modal");
            aEmpresa.setAttribute("data-bs-target", "#verEmpresaModal");
            aEmpresa.setAttribute("title", "Ver Empresa");
            aEmpresa.onclick = function() { abrirModalEmpresa(empresa) };
            // aEmpresa.href = `${rutaURL}/empresas/editar/${empresa.cif}`;
            aEmpresa.innerHTML = empresa.empresa;
            tdEmpresas.appendChild(aEmpresa);
            aLink.onclick = function() { abrirModalSocia(vSocs[i], empresa) };
        });

        // tdEmpresas.appendChild(document.createTextNode(empresasSocia));

        let tdCheck = document.createElement("td");
        // crea el nodo input
        var inputNode = document.createElement("input");
        inputNode.type = "checkbox";
        inputNode.name = vSocs[i].cod;
        inputNode.value = vSocs[i].cod;

        tdCheck.appendChild(inputNode);


        
        let tdSocCod = document.createElement("td");
        let aSocCod = document.createElement("a");
        aSocCod.setAttribute("href", `${rutaURL}/socias/editar/${vSocs[i].cod}`);
        aSocCod.setAttribute("class", "nav-link nav-link-primary")
        aSocCod.appendChild(document.createTextNode(vSocs[i].cod));
        tdSocCod.appendChild(aSocCod);

        let tdAcciones = document.createElement("td");
        let divAcciones = document.createElement("div");
        divAcciones.setAttribute("class", "d-flex justify-content-end");

        // if (auth == 10) {
            // let aEditar = document.createElement("a");
            // aEditar.setAttribute("href", rutaURL + '/socias/editar/' + vSocs[i].cod);
            // aEditar.setAttribute("title", "Modificar");
            // let aEditarIcono = document.createElement("i");
            // aEditarIcono.setAttribute("class", "bi bi-pencil color-principal");
            // aEditar.appendChild(aEditarIcono);

            // let aEliminar = document.createElement("a");
            // aEliminar.setAttribute("href", rutaURL + '/socias/borrar/' + vSocs[i].cod);
            // aEliminar.setAttribute("title", "Eliminar");
            // let aEliminarIcono = document.createElement("i");
            // aEliminarIcono.setAttribute("class", "bi bi-trash3 color-principal");
            // aEliminar.appendChild(aEliminarIcono);

            // divAcciones.appendChild(aEditar);
            // divAcciones.innerHTML += "&nbsp;&nbsp;&nbsp;";
            // divAcciones.appendChild(aEliminar);
            // tdAcciones.appendChild(divAcciones);
        // }

        trNode.appendChild(tdCheck);
        trNode.appendChild(tdSocCod);    
        trNode.appendChild(tdNombre);
        trNode.appendChild(tdTelf);
        trNode.appendChild(tdMovil);
        trNode.appendChild(tdEmail);
        trNode.appendChild(tdEmpresas);
        // trNode.appendChild(tdAcciones);
        tabla.appendChild(trNode);

    }

    dibujarPaginacion();

    checkbox();
    // console.log("Páginas: " + paginas);
    // console.log("Longitud vUsuarios: " + vUsuarios.length);
    // console.log("Longitud vSocs: " + vSocs.length);
    // console.log("Items por página: " + elementosPorPagina);
    // console.log("Inicio: " + inicio);
    // console.log("Offset: " + offset);
}

function checkbox() {

    // Seleccionar el checkbox "Seleccionar todos"
    var seleccionar = document.getElementById("seleccionar");

    // Obtener todos los checkboxes en el formulario
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');

    alMenosUnoMarcado(checkboxes);

    // EventListener del select
    seleccionar.onchange = function() {
       
        vSocias.forEach(soc => {
            soc.marcado = 0;
        });

        switch (seleccionar.value) {
            case "1":
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    vSocias.forEach(socia => {
                    socia.marcado = 1;
                    });
                });

                break;

            case "2":
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    vSocias.forEach(socia => {
                    if (checkbox.value == socia.cod) {
                        socia.marcado = 1;
                    }
                    });
                });

                break;

            case "3":
                                
                for (var i = 0; i < checkboxes.length; i++) {
                  checkboxes[i].checked = false;
                }

                break;
            default:
                // Si no se selecciona ninguna opción, no se hace nada
                break;
        }
        //console.log(seleccionar.value);
        seleccionar.selectedIndex = 0;

        actualizarBotonEnviarCorreo(checkboxes);
        
        rellenarArrayMail(vSocias);
    };

    
    // EventListener de los checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', (event) => {
            if (checkbox.checked) {
                vSocias.forEach(socia => {
                    if (checkbox.value == socia.cod) {
                        socia.marcado = 1;
                    }
                });
            } else {
                vSocias.forEach(socia => {
                    if (checkbox.value == socia.cod) {
                        socia.marcado = 0;
                    }
                });
            }

            actualizarBotonEnviarCorreo(checkboxes);
            rellenarArrayMail(vSocias);
        });
    });

    // Iterar sobre los checkboxes y marcar los seleccionados
    checkboxes.forEach(checkbox => {
        vSocias.forEach(socia => {
            if (socia.marcado == 1 && checkbox.value == socia.cod) {
                checkbox.checked = true;
            }
        });
    });

    // Reiniciar los checkboxes al cargar la página
    window.addEventListener('pageshow', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        actualizarBotonEnviarCorreo(checkboxes);
    });

    
}

function sociasElegi(array) { 
    
    // Obtén las referencias a los elementos de radio por su ID
    var opcion1 = document.getElementById("opcion1");
    var opcion2 = document.getElementById("opcion2");

    // Obtén el buscador por su id para poder desactivarlo
    var div1 = document.getElementById("ocultar1");
    var div2 = document.getElementById("ocultar2");

    // Agrega un evento click a la opción 1
    opcion1.addEventListener("click", function() {
        
        div1.classList.add("d-none");
        div2.classList.add("d-none");
        
        vSocs = array;
        paginaActiva = 0;
        listarSocias();
    });

    // Agrega un evento click a la opción 2
    opcion2.addEventListener("click", function() {
        
        div1.classList.remove("d-none");
        div2.classList.remove("d-none");
        
        cambiarFiltroSocias(11);
    });

    if (opcion1.checked) {
        vSocs = array;

        if (vSocs.length == 0) {
            cambiarFiltroSocias(11);
            opcion2.checked = true;
            div1.classList.remove("d-none");
            div2.classList.remove("d-none");
        }else{
            div1.classList.add("d-none");
            div2.classList.add("d-none");
            
            paginaActiva = 0;
            listarSocias();
        }
        
    }else if (opcion2.checked) {
       
        div1.classList.remove("d-none");
        div2.classList.remove("d-none");
        

        cambiarFiltroSocias(11);
    }

}

function rellenarArrayMail(array){
    

    var sociasElegidas = [];
    
    array.forEach(socia => {
        if (socia.marcado == 1) {
            if (!sociasElegidas.includes(socia)) {
                sociasElegidas.push(socia);
            }
        }
    });

    document.getElementById("miArrayInput").value = JSON.stringify(sociasElegidas);
    listarSocias();
    sociasElegi(sociasElegidas);
    
    //console.log(sociasElegidas);
}


// Función para actualizar el estado del botón "Enviar correo"
function actualizarBotonEnviarCorreo(checkboxes) {
    
    // Seleccionar el boton de enviar correo
    var botonEnviarCorreo = document.getElementById("botonEnviarCorreo");
    
    botonEnviarCorreo.style.visibility = alMenosUnoMarcado(checkboxes) ? 'visible' : 'hidden';


    var radios = document.getElementById('radios');
    radios.style.display = alMenosUnoMarcado(checkboxes) ? 'block' : 'none';


    // Obtén el botón por su id
    // var boton = document.getElementById("elegidas");

    // boton.style.display = alMenosUnoMarcado(checkboxes) ? 'block' : 'none';
}



// Función para ver si hay algún checkbox marcado
function alMenosUnoMarcado(checkboxes) {
    return [...checkboxes].some(checkbox => checkbox.checked);
}


function cambiarFiltroSocias(filtro) {
    filtroActivo = filtro;
    
    paginaActiva=0;
    actualizarURL("pag", paginaActiva);
    filtrarSocias();
}

function filtrarSocias() {
    // paginaActiva = 0;
    vSocs = [];
    let termino = document.getElementById("barraBusqueda").value.toString().replaceAll(" ", "").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

    
    vSocias.forEach(socia => {

        let condicion = false;

        switch (filtroActivo) {
            case 11:
                // Comprueba si la socia figura como alta
                if (socia.alta == 1) condicion = true;
                break;
            case 12:
                // Comprueba si la socia figura como baja
                if (socia.alta == 0) condicion = true;
                break;
            case 21:
                // Comprueba si la socia figura como autónoma
                if (socia.es_autonoma == 1) condicion = true;
                break;
            case 22:
                // Comprueba si la socia está vinculada a alguna empresa
                if (socia.empresas[0] != null) condicion = true;
                break;

            default:
                break;
        }

        let empresaCoincide = false;

        try {
            if (socia.empresas.length>0) {
                socia.empresas.forEach(empr => {
                    if (empr.empresa.normalize().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1) {
                        empresaCoincide=true;
                    }
                });
            }
        } catch (error) {
            console.log(error);
        }
        
        try{
            if ((filtroActivo == 0 ||
                    condicion) &&
                (socia.cod.toString().normalize().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                    (socia.nombre + socia.apellidos).toString().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                    (socia.tlf).toString().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                    (socia.movil).toString().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1 ||
                    (socia.email).toString().toLowerCase().replaceAll(" ", "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").search(termino) != -1
                    || empresaCoincide)
            ) {
                
                vSocs.push(socia);
            }
        } catch (error) {
            console.log(error);
        }
    });
   

    let enlaceActivo = "nav-link bg-principal active";
    let enlaceInactivo = "nav-link text-secondary";

    switch (filtroActivo) {
        case 0:
            document.getElementById("filtroTodos").setAttribute("class", enlaceActivo);
            document.getElementById("filtroAlta").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroBaja").setAttribute("class", enlaceInactivo);
            break;
        case 11:
            document.getElementById("filtroTodos").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroAlta").setAttribute("class", enlaceActivo);
            document.getElementById("filtroBaja").setAttribute("class", enlaceInactivo);
            break;
        case 12:
            document.getElementById("filtroTodos").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroAlta").setAttribute("class", enlaceInactivo);
            document.getElementById("filtroBaja").setAttribute("class", enlaceActivo);
            break;

        default:
            break;
    }

    
    
    
    paginaActiva = 0;
    listarSocias();
}

// function copiarDatosFactPers() {
//     let nombre = document.getElementById("nombre");
//     let apellidos = document.getElementById("apellidos");
//     let nif = document.getElementById("nif");
//     let dir = document.getElementById("dir");
//     let cp = document.getElementById("cp");
//     let poblacion = document.getElementById("poblacion");
//     let provincia = document.getElementById("provincia");
//     let pais = document.getElementById("pais");

//     let fact_nombre = document.getElementById("fact-nombre");
//     let fact_nif = document.getElementById("fact-nif");
//     let fact_dir = document.getElementById("fact-dir");
//     let fact_cp = document.getElementById("fact-cp");
//     let fact_poblacion = document.getElementById("fact-poblacion");
//     let fact_provincia = document.getElementById("fact-provincia");
//     let fact_pais = document.getElementById("fact-pais");

//     fact_nombre.value = `${nombre.value} ${apellidos.value}`;
//     fact_nif.value = nif.value;
//     fact_dir.value = dir.value;
//     fact_cp.value = cp.value;
//     fact_poblacion.value = poblacion.value;
//     fact_provincia.value = provincia.value;
//     fact_pais.value = pais.value;
// }

function copiarDatosFactEmp() {
    let selectEmpresasSocia = document.getElementById("empresas-socia-listado");
    let opt = selectEmpresasSocia.options[selectEmpresasSocia.selectedIndex];

    if (opt) {
        let empresa;

        vEmpresas.forEach(emp => {
            if (emp.nif == opt.value) {
                empresa = emp;
            }
        });

        let fact_nombre = document.getElementById("fact-nombre");
        let fact_nif = document.getElementById("fact-nif");
        let fact_dir = document.getElementById("fact-dir");
        let fact_cp = document.getElementById("fact-cp");
        let fact_poblacion = document.getElementById("fact-poblacion");
        let fact_provincia = document.getElementById("fact-provincia");
        let fact_pais = document.getElementById("fact-pais");

        fact_nombre.value = empresa.nombre;
        fact_nif.value = empresa.nif;
        fact_dir.value = empresa.dir;
        fact_cp.value = empresa.cp;
        fact_poblacion.value = empresa.poblacion;
        fact_provincia.value = empresa.provincia;
        fact_pais.value = empresa.pais;
    }

}

function incorporarEmpresa() {
    let select = document.getElementById("empresas");
    let selected = select.options[select.selectedIndex];
    let valor = selected.value;

    let selectedCopia = selected.cloneNode(true);

    selectedCopia.id = `empresas-socia-${valor}`;

    let selectEmpresasSocia = document.getElementById("empresas-socia-listado");
    selectEmpresasSocia.setAttribute("ondblclick", "desIncorporarEmpresa()");

    if (!document.getElementById(`empresas-socia-${valor}`)) {
        selectEmpresasSocia.appendChild(selectedCopia);
    }

    actualizarListaEmpresasSocia();
}

function desIncorporarEmpresa() {
    let selectEmpresasSocia = document.getElementById("empresas-socia-listado");
    let opt = selectEmpresasSocia.options[selectEmpresasSocia.selectedIndex];
    document.getElementById(opt.id).remove();

    actualizarListaEmpresasSocia();
}

function actualizarListaEmpresasSocia() {
    let select = document.getElementById("empresas-socia-listado");
    let hiddenInputs = document.getElementById("empresas-socia");
    hiddenInputs.innerHTML = "";

    for (opt of select) {

        let input = document.createElement("input");
        input.value = opt.value;
        input.name = "empresas-socia[]";
        input.setAttribute("type", "hidden");
        hiddenInputs.appendChild(input);
    }
}