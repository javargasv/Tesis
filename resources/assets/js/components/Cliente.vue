<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Clientes
                    <button
                        type="button"
                        @click="abrirModal('persona', 'registrar')"
                        class="btn btn-secondary"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select
                                    class="form-control col-md-3"
                                    v-model="criterio"
                                >
                                    <option value="nombre">Nombre</option>
                                    <option value="num_documento"
                                        >Documento</option
                                    >
                                    <option value="email">Email</option>
                                    <option value="telefono">Teléfono</option>
                                    <option value="razon_social"
                                        >Razón social</option
                                    >
                                </select>
                                <input
                                    type="text"
                                    v-model="buscar"
                                    @keyup.enter="
                                        listarPersonas(1, buscar, criterio)
                                    "
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                />
                                <button
                                    type="submit"
                                    @click="listarPersonas(1, buscar, criterio)"
                                    class="btn btn-primary"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Tipo documento</th>
                                <th>N° documento</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Razón social</th>
                                <th>Nombre empresa</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="persona in arrayPersona"
                                :key="persona.id"
                            >
                                <td v-text="persona.nombre"></td>
                                <td v-text="persona.apellido"></td>
                                <td v-text="persona.tipo_documento"></td>
                                <td v-text="persona.num_documento"></td>
                                <td v-text="persona.email"></td>
                                <td v-text="persona.telefono"></td>
                                <td v-text="persona.direccion"></td>
                                <td v-text="persona.razon_social"></td>
                                <td v-text="persona.nombre_empresa"></td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm"
                                    >
                                        <i
                                            class="icon-pencil"
                                            @click="
                                                abrirModal(
                                                    'persona',
                                                    'actualizar',
                                                    persona
                                                ),
                                                    HabilitarEmpresa(
                                                        persona.razon_social
                                                    )
                                            "
                                        ></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li
                                class="page-item"
                                v-if="pagination.current_page > 1"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            pagination.current_page - 1,
                                            buscar,
                                            criterio
                                        )
                                    "
                                    >Ant</a
                                >
                            </li>
                            <li
                                class="page-item"
                                v-for="page in pagesNumber"
                                :key="page"
                                :class="[page == isActived ? 'active' : '']"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(page, buscar, criterio)
                                    "
                                    v-text="page"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="
                                    pagination.current_page <
                                        pagination.last_page
                                "
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            pagination.current_page + 1,
                                            buscar,
                                            criterio
                                        )
                                    "
                                    >Sig</a
                                >
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div
            class="modal fade"
            tabindex="-1"
            :class="{ mostrar: modal }"
            role="dialog"
            aria-labelledby="myModalLabel"
            style="display: none;"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 v-text="tituloModal"></h4>
                        <button
                            type="button"
                            class="close"
                            @click="cerrarModal(), DeshabilitarEmpresa()"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form
                            action=""
                            method="post"
                            enctype="multipart/form-data"
                            class="form-horizontal"
                        >
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 form-control-label"
                                            for="text-input"
                                            >Nombre</label
                                        >
                                        <div class="col-md-9">
                                            <input
                                                type="text"
                                                v-model="nombre"
                                                class="form-control"
                                                placeholder="Nombre del cliente"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 form-control-label"
                                            for="text-input"
                                            >Apellido</label
                                        >
                                        <div class="col-md-9">
                                            <input
                                                type="text"
                                                v-model="apellido"
                                                class="form-control"
                                                placeholder="Apellido del cliente"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 form-control-label"
                                            for="text-input"
                                            >Tipo de documento</label
                                        >
                                        <div class="col-md-9">
                                            <select
                                                v-model="tipo_documento"
                                                class="form-control"
                                            >
                                                <option
                                                    value="Cedula ciudadania"
                                                    >Cédula ciudadanía</option
                                                >
                                                <option
                                                    value="Cedula extranjera"
                                                    >Cédula Extranjera</option
                                                >
                                                <option value="NIT">NIT</option>
                                                <option value="Otro"
                                                    >Otro</option
                                                >
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 form-control-label"
                                            for="text-input"
                                            >Número del documento</label
                                        >
                                        <div class="col-md-9">
                                            <input
                                                type="text"
                                                v-model="num_documento"
                                                class="form-control"
                                                placeholder="Ingrese el número de documento"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 form-control-label"
                                            for="text-input"
                                            >Correo</label
                                        >
                                        <div class="col-md-9">
                                            <input
                                                type="email"
                                                v-model="email"
                                                class="form-control"
                                                placeholder="Ingrese el correo electrónico"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 form-control-label"
                                            for="text-input"
                                            >Teléfono</label
                                        >
                                        <div class="col-md-9">
                                            <input
                                                type="text"
                                                v-model="telefono"
                                                class="form-control"
                                                placeholder="Ingrese el número de contacto"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 form-control-label"
                                            for="text-input"
                                            >Dirección</label
                                        >
                                        <div class="col-md-9">
                                            <input
                                                type="text"
                                                v-model="direccion"
                                                class="form-control"
                                                placeholder="Ingrese la dirección"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 form-control-label"
                                            for="text-input"
                                            >Razón social</label
                                        >
                                        <div class="col-md-9">
                                            <select
                                                v-model="razon_social"
                                                class="form-control selectorrazonsocial"
                                                @click="validarRazonsocial()"
                                            >
                                                <option value="si">Sí</option>
                                                <option value="no">No</option>
                                            </select>
                                            <span
                                                >¿Pertenece a una razón
                                                social?</span
                                            >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 form-control-label deshabilitar campoempresa"
                                            for="text-input"
                                            >Nombre Empresa</label
                                        >
                                        <div class="col-md-9">
                                            <input
                                                v-model="nombre_empresa"
                                                type="text"
                                                class="form-control deshabilitar campoempresa"
                                                placeholder="Nombre de la empresa"
                                            />
                                        </div>
                                    </div>
                                    <div
                                        v-show="errorPersona"
                                        class="form-group row div-error"
                                    >
                                        <div class="text-center text-error">
                                            <div
                                                v-for="error in errorMostrarMsjPersona"
                                                :key="error"
                                                v-text="error"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="cerrarModal(), DeshabilitarEmpresa()"
                        >
                            Cerrar
                        </button>
                        <button
                            type="button"
                            v-if="tipoAccion == 1"
                            class="btn btn-primary"
                            @click="registrarPersona()"
                        >
                            Guardar
                        </button>
                        <button
                            type="button"
                            v-if="tipoAccion == 2"
                            @click="actualizarPersona()"
                            class="btn btn-primary"
                        >
                            Actualizar
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
export default {
    data() {
        return {
            persona_id: 0,
            nombre: "",
            apellido: "",
            tipo_documento: "Cédula ciudadanía",
            num_documento: "",
            email: "",
            telefono: "",
            direccion: "",
            razon_social: "",
            nombre_empresa: "",
            arrayPersona: [],
            modal: 0,
            tituloModal: "",
            tipoAccion: 0,
            errorPersona: 0,
            errorMostrarMsjPersona: [],
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0
            },
            offset: 3,
            criterio: "nombre",
            buscar: ""
        };
    },

    computed: {
        isActived: function() {
            return this.pagination.current_page;
        },
        //elementos de la paginacion
        pagesNumber: function() {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },

    methods: {
        listarPersonas(page, buscar, criterio) {
            let me = this;
            var url =
                "/cliente?page=" +
                page +
                "&buscar=" +
                buscar +
                "&criterio=" +
                criterio;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayPersona = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //actualiza la pagina actual
            me.pagination.current_page = page;
            //envia la peticion para visualizar la data de esa pagina
            me.listarPersonas(page, buscar, criterio);
        },

        encuentra(num){
                var sw=0;
                for(var i=0;i<this.arrayPersona.length;i++){
                    if(this.arrayPersona[i].num_documento==num){
                        sw=true;
                    }
                }
                return sw;
        },
       registrarPersona() {
        if (this.validarPersona()) {
            return;
        }
            let me = this;
            if(me.encuentra(me.num_documento)){
                        Swal.fire({
                        icon: "error",
                        title: "El cliente ya se encuentra registrado",
                        showConfirmButton: false,
                        timer: 1500
            });
            }
            else {
                axios
                    .post("/cliente/registrar", {
                        nombre: this.nombre,
                        apellido: this.apellido,
                        tipo_documento: this.tipo_documento,
                        num_documento: this.num_documento,
                        email: this.email,
                        telefono: this.telefono,
                        direccion: this.direccion,
                        razon_social: this.razon_social,
                        nombre_empresa: this.nombre_empresa
                    })
                    .then(function(response) {
                        me.cerrarModal();
                        me.listarPersonas(1, "", "nombre");
                        Swal.fire({
                            icon: "success",
                            title: "Cliente creado exitosamente",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        me.DeshabilitarEmpresa();
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
                }
        },

        actualizarPersona() {
            if (this.validarPersona()) {
                return;
            }

            let me = this;
            axios
                .put("/cliente/actualizar", {
                    nombre: this.nombre,
                    apellido: this.apellido,
                    tipo_documento: this.tipo_documento,
                    num_documento: this.num_documento,
                    email: this.email,
                    telefono: this.telefono,
                    direccion: this.direccion,
                    razon_social: this.razon_social,
                    nombre_empresa: this.nombre_empresa,
                    id: this.persona_id
                })
                .then(function(response) {
                    me.cerrarModal();
                    me.listarPersonas(1, "", "nombre");
                    Swal.fire({
                        icon: "success",
                        title: "Cliente actualizado exitosamente",
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        validarRazonsocial() {
            $("select.selectorrazonsocial").change(function() {
                var seleccionado = $(this)
                    .children("option:selected")
                    .val();
                if (seleccionado === "si") {
                    $(".campoempresa").removeClass("deshabilitar");
                }
            });
        },

        DeshabilitarEmpresa() {
            $(".campoempresa").addClass("deshabilitar");
        },

        HabilitarEmpresa(razon) {
            let social = razon;

            if (social === "si") {
                $(".campoempresa").removeClass("deshabilitar");
            }
        },
        /////////FUNCION PARA VALIDAR EMAIL //////////////////
        validEmail: function (email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        validarPersona() {
            this.errorPersona = 0;
            this.errorMostrarMsjPersona = [];
            if (!this.nombre)
                this.errorMostrarMsjPersona.push(
                    "El nombre del cliente no no puede estar vacío"
                );
            if (this.tipo_documento == "")
                this.errorMostrarMsjPersona.push(
                    "Debes seleccionar un tipo de documento del cliente."
                );
            if (!this.num_documento)
                this.errorMostrarMsjPersona.push(
                    "El número de documento del cliente no puede estar vacío"
                );
            if (!this.email)
                this.errorMostrarMsjPersona.push(
                    "El correo del cliente no puede estar vacío"
                );
            /////////VALIDACION DE EMAIL //////////////////
            if(!this.validEmail(this.email))
                this.errorMostrarMsjPersona.push(
                    "El correo no es válido"
                );
            if (!this.telefono)
                this.errorMostrarMsjPersona.push(
                    "El contacto del cliente no puede estar vacío"
                );
            if (!this.razon_social)
                this.errorMostrarMsjPersona.push(
                    "Falta un campo por llenar: ¿Pertenece a una razón social?"
                );
            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;
            return this.errorPersona;
        },

        cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.nombre = "";
            this.apellido = "";
            this.tipo_documento = "";
            this.num_documento = "";
            this.correo = "";
            this.telefono = "";
            this.direccion = "";
            this.razon_social = "No";
            this.nombre_empresa = "";
            this.errorPersona = 0;
        },

        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "persona": {
                    switch (accion) {
                        case "registrar": {
                            this.modal = 1;
                            this.nombre = "";
                            this.apellido = "";
                            this.tipo_documento = "";
                            this.num_documento = "";
                            this.email = "";
                            this.telefono = "";
                            this.direccion = "";
                            this.razon_social = "";
                            this.nombre_empresa = "";
                            this.tituloModal = "Registrar Cliente";
                            this.tipoAccion = 1;
                            break;
                        }
                        case "actualizar": {
                            this.modal = 1;
                            this.tituloModal = "Actualizar Cliente";
                            this.tipoAccion = 2;
                            this.nombre = data["nombre"];
                            this.apellido = data["apellido"];
                            this.tipo_documento = data["tipo_documento"];
                            this.num_documento = data["num_documento"];
                            this.email = data["email"];
                            this.telefono = data["telefono"];
                            this.direccion = data["direccion"];
                            this.razon_social = data["razon_social"];
                            this.nombre_empresa = data["nombre_empresa"];
                            this.persona_id = data["id"];
                            break;
                        }
                    }
                }
            }
        }
    },
    mounted() {
        this.listarPersonas(1, this.buscar, this.criterio);
    }
};
</script>

<style>
.deshabilitar {
    pointer-events: none;
    pointer-events: none;
    color: #b5b5b5;
    border-color: #f1dbdb !important;
}
.modal-content {
    width: 100% !important;
    position: absolute !important;
}

.mostrar {
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #0000007a !important;
}

.div-error {
    display: flex;
    justify-content: center;
}

.text-error {
    color: red;
    font-weight: bold;
}
</style>
