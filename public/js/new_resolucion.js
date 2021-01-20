window.addEventListener("load", function() {
    $("#errorEditarTabla").hide();
    console.log("load");
    $.datepicker.regional["es"] = {
        closeText: "Cerrar",
        prevText: "< Ant",
        nextText: "Sig >",
        currentText: "Hoy",
        monthNames: [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
        monthNamesShort: [
            "Ene",
            "Feb",
            "Mar",
            "Abr",
            "May",
            "Jun",
            "Jul",
            "Ago",
            "Sep",
            "Oct",
            "Nov",
            "Dic"
        ],
        dayNames: [
            "Domingo",
            "Lunes",
            "Martes",
            "Miércoles",
            "Jueves",
            "Viernes",
            "Sábado"
        ],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"],
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
        weekHeader: "Sm",
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ""
    };
    $.datepicker.setDefaults($.datepicker.regional["es"]);

    $(".datefield")
        .attr("readonly", "readonly")
        .attr("style", "background-color:white")
        .datepicker({
            dateFormat: "dd/mm/yy",
            language: "es"
        });
    $(".ff").on("input", function() {
        $(this)
            .find(".invalid-feedback")
            .remove();
        $(this).removeClass("is-invalid");
    });

    $(".ff").on("change", function() {
        $(this)
            .find(".invalid-feedback")
            .remove();
        $(this).removeClass("is-invalid");
    });
    $("#boton_enviar").click(e => {
        $(".invalid-feedback").remove();
        $("input").removeClass("is-invalid");
        console.log("CLICK");
        e.preventDefault();

        let canGoNext = true;

        $(".ff").each(function() {
            if ($(this).attr("data-cedula") == "true" && canGoNext) {
                if ($(this).val().length > 0)
                    canGoNext = validarCedula($(this).val());
            }
            if ($(this).attr("data-ruc") == "true" && canGoNext) {
                if ($(this).val().length > 0)
                    canGoNext = validarRuc($(this).val());
            }

            if ($(this).hasClass("datefield") && canGoNext) {
                console.log('FECHAAA');
                if ($(this).val().length > 0) {
                    canGoNext = validarFecha($(this).val());
                } else {
                    canGoNext = false;
                }
            }
            if (!$(this)[0].checkValidity() && canGoNext) {
                canGoNext = false;
            }
        });

        if (canGoNext) {
            console.log("BIEN");

            let editando = false;

            $(".tablaContenedor").each(function() {
                editando = $(this).find(".form-control").length > 0;

                if (!editando) {
                    $(this)
                        .find('th[name="bstable-actions"]')
                        .remove();
                    $(this)
                        .find('td[name="bstable-actions"]')
                        .remove();

                    let tabla = `
                    <table style="font-size: 8pt; border-width:10px;border-color:black;border-style:solid;">
                        ${$(this)
                            .find("table")
                            .html()}
                    </table>
                `;

                    $(this)
                        .find(".inputTabla")
                        .first()
                        .val(tabla);

                    
                }
            });

            if (!editando) {
                $("#template_form").trigger("submit");
            } else {
                $("#errorEditarTabla").show();
            }
        } else {
            console.log("mal");
            e.preventDefault();
            $(".ff").each(function() {
                if ($(this).val().length == 0) {
                    $(this).addClass("is-invalid");

                    $(this)
                        .parent()
                        .append(
                            '<span class="invalid-feedback" role="alert"><strong>Campo Requerido</strong></span>'
                        );
                } else if ($(this).attr("data-cedula") == "true") {
                    if ($(this).val().length > 0) {
                        if (!validarCedula($(this).val())) {
                            $(this).addClass("is-invalid");

                            $(this)
                                .parent()
                                .append(
                                    '<span class="invalid-feedback" role="alert"><strong>Cédula no valida</strong></span>'
                                );
                        }
                    }
                } else if ($(this).attr("data-ruc") == "true") {
                    if ($(this).val().length > 0) {
                        if (!validarRuc($(this).val())) {
                            $(this).addClass("is-invalid");

                            $(this)
                                .parent()
                                .append(
                                    '<span class="invalid-feedback" role="alert"><strong>Ruc  no valido</strong></span>'
                                );
                        }
                    }
                } else if ($(this).hasClass("datefield")) {
                    if ($(this).val().length > 0) {
                        if (!validarFecha($(this).val())) {
                            $(this).addClass("is-invalid");

                            $(this)
                                .parent()
                                .append(
                                    '<span class="invalid-feedback" role="alert"><strong>Fecha no valida</strong></span>'
                                );
                        }
                    }
                } else if (!$(this)[0].checkValidity()) {
                    $(this).addClass("is-invalid");

                    $(this)
                        .parent()
                        .append(
                            '<span class="invalid-feedback" role="alert"><strong>Valor no valido</strong></span>'
                        );
                } else {
                    $(this).removeClass("is-invalid");
                }
            });
        }
    });
});

function validarRuc(ruc) {
    console.log("V1:" + validar_RUC_1(ruc));
    console.log("V2:" + validar_RUC_2(ruc));
    console.log("V3:" + validar_RUC_3(ruc));
    return (
        validar_RUC_1(ruc) == 1 ||
        validar_RUC_2(ruc) == 1 ||
        validar_RUC_3(ruc) == 1
    );
}

function validarCedula(cedula) {
    console.log("ENTRA");
    if (cedula.length != 10) return false;
    let coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    let digitosCedula = cedula.split("");

    let dosPriDigitos = parseInt(digitosCedula[0] + digitosCedula[1]);
    let tercerDigito = parseInt(digitosCedula[2]);

    let verifPriDig = dosPriDigitos >= 0 && dosPriDigitos <= 24;
    let veriTerDig = tercerDigito >= 0 && tercerDigito < 6;

    let sumaConCoefi = 0;

    for (let i = 0; i < coeficientes.length; i++) {
        const coeficiente = coeficientes[i];
        const digitoCedula = parseInt(digitosCedula[i]);
        let multi = coeficiente * digitoCedula;
        sumaConCoefi += multi >= 10 ? multi - 9 : multi;
    }

    let decenaSuperior = Math.ceil(sumaConCoefi / 10) * 10;
    let restaVerificacion = decenaSuperior - sumaConCoefi;
    let correcta =
        verifPriDig && veriTerDig && restaVerificacion == digitosCedula[9];
    return correcta;
}

// Rucs

//Validar el RUC de una persona juridica privada o extranjero no residente (sin cedula)
function validar_RUC_1(ruc) {
    //Valida dimension
    if (ruc.length != 13) {
        return 0;
    } else {
        let dos_primeros_digitos = parseInt(ruc.slice(0, 2));
        //Valida dos primeros digitos
        if (dos_primeros_digitos < 1 && dos_primeros_digitos > 22) {
            return 0;
        }
        let tercer_digito = parseInt(ruc[2]);
        //Valida tercer digito (Condicion creada con logica matematica y leyes de De Morgan)
        if (
            tercer_digito != 9 &&
            tercer_digito != 6 &&
            (tercer_digito < 0 || tercer_digito >= 6)
        ) {
            return 0;
        }
        //Realizamos la validacion del caso 1
        else {
            let ultimos_tres_digitos = parseInt(ruc.slice(10, 13));
            if (ultimos_tres_digitos <= 0) {
                return 0;
            }
            let total = 0;
            let nueve_primeros_digitos = ruc.slice(0, 9);
            let coeficientes = "432765432";
            let num;
            let coef;
            let valor;
            for (i = 0; i <= coeficientes.length - 1; i++) {
                num = parseInt(nueve_primeros_digitos[i]);
                coef = parseInt(coeficientes[i]);
                valor = num * coef;
                total = total + valor;
            }
            //Calculamos el residuo y el verificador
            let residuo = total % 11;
            let verificador;
            if (residuo == 0) {
                verificador = 0;
            } else {
                verificador = 11 - residuo;
            }
            if (verificador == parseInt(ruc[9])) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}

//Validar el RUC de una persona juridica publica
function validar_RUC_2(ruc) {
    //Valida dimension
    if (ruc.length != 13) {
        return 0;
    } else {
        let dos_primeros_digitos = parseInt(ruc.slice(0, 2));
        //Valida dos primeros digitos
        if (dos_primeros_digitos < 1 && dos_primeros_digitos > 22) {
            return 0;
        }
        let tercer_digito = parseInt(ruc[2]);
        //Valida tercer digito (Condicion creada con logica matematica y leyes de De Morgan)
        if (
            tercer_digito != 9 &&
            tercer_digito != 6 &&
            (tercer_digito < 0 || tercer_digito >= 6)
        ) {
            return 0;
        }
        //Realizamos la validacion del caso 2
        else {
            let ultimos_cuatro_digitos = parseInt(ruc.slice(9, 13));
            if (ultimos_cuatro_digitos <= 0) {
                return 0;
            }
            let total = 0;
            let ocho_primeros_digitos = ruc.slice(0, 8);
            let coeficientes = "32765432";
            let num;
            let coef;
            let valor;
            for (i = 0; i <= coeficientes.length - 1; i++) {
                num = parseInt(ocho_primeros_digitos[i]);
                coef = parseInt(coeficientes[i]);
                valor = num * coef;
                total = total + valor;
            }
            //Calculamos el residuo y el verificador
            let residuo = total % 11;
            let verificador;
            if (residuo == 0) {
                verificador = 0;
            } else {
                verificador = 11 - residuo;
            }
            if (verificador == parseInt(ruc[8])) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}

//Validar el RUC de una persona natural
function validar_RUC_3(ruc) {
    //Valida dimension
    if (ruc.length != 13) {
        return 0;
    } else {
        let dos_primeros_digitos = parseInt(ruc.slice(0, 2));
        //Valida dos primeros digitos
        if (dos_primeros_digitos < 1 && dos_primeros_digitos > 22) {
            return 0;
        }
        let tercer_digito = parseInt(ruc[2]);
        //Valida tercer digito (Condicion creada con logica matematica y leyes de De Morgan)
        if (
            tercer_digito != 9 &&
            tercer_digito != 6 &&
            (tercer_digito < 0 || tercer_digito >= 6)
        ) {
            return 0;
        }
        //Realizamos la validacion del caso 3
        else {
            let ultimos_tres_digitos = parseInt(ruc.slice(10, 13));
            if (ultimos_tres_digitos <= 0) {
                return 0;
            }
            let total = 0;
            let nueve_primeros_digitos = ruc.slice(0, 9);
            let coeficientes = "212121212";
            let num;
            let coef;
            let valor;
            for (i = 0; i <= coeficientes.length - 1; i++) {
                num = parseInt(nueve_primeros_digitos[i]);
                coef = parseInt(coeficientes[i]);
                valor = num * coef;
                if (valor >= 10) {
                    valor = (valor % 10) + 1;
                }
                total = total + valor;
            }
            //Calculamos el residuo y el verificador
            let residuo = total % 10;
            let verificador;
            if (residuo == 0) {
                verificador = 0;
            } else {
                verificador = 10 - residuo;
            }
            if (verificador == parseInt(ruc[9])) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}

function validarFecha(fecha) {
    console.log("VALIDAR FERCHA" + fecha);
    let hoy = new Date();
    let parts = fecha.split("/");
    let fechaPasada = new Date(
        Number(parts[2]),
        Number(parts[1]) - 1,
        Number(parts[0])
    );

    let anoPasado = addYears(hoy, -1);
    let anoSiguiente = addYears(hoy, +2);

    console.log({
        anoPasado,
        anoSiguiente,
        fechaPasada,
        valido:
            fechaPasada.getTime() > anoPasado &&
            fechaPasada.getTime() < anoSiguiente
    });

    return (
        fechaPasada.getTime() > anoPasado &&
        fechaPasada.getTime() < anoSiguiente
    );
}

function addYears(dt, n) {
    return new Date(dt.setFullYear(dt.getFullYear() + n));
}
