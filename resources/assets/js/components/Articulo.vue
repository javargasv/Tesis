<template>
<main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Productos
                        <button type="button" @click="abrirModal('articulo','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="cargarPdf()" class="btn btn-info">
                            <i class="icon-doc"></i>&nbsp;Reporte de Productos
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarArticulos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarArticulos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio venta</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil" @click="abrirModal('articulo','actualizar',articulo)"></i>
                                        </button> &nbsp;
                                        <template v-if="articulo.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo.id)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                         <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarArticulo(articulo.id)">
                                            <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="articulo.nombre"></td>
                                    <td v-text="articulo.descripcion"></td>
                                    <td v-text="articulo.precio_venta,3"></td>
                                    <td>
                                        <div v-if="articulo.condicion">
                                        <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                        <span class="badge badge-danger">No disponible</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active': '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del producto">
                                        <span class="help-block">(*) Nombre del producto</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="codigo" class="form-control" placeholder="Ingrese el código del producto">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio de venta</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="precio_venta" class="form-control" placeholder="Ingrese el precio de venta">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="errorArticulo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
                            <button type="button" v-if="tipoAccion == 2" @click="actualizarArticulo()" class="btn btn-primary">Actualizar</button>
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
                articulo_id: 0,
                nombre: '',
                descripcion: '',
                codigo: '',
                precio_venta: '',
                arrayArticulo: [],
                modal : 0,
                tituloModal: '',
                tipoAccion: 0,
                errorArticulo: 0,
                errorMostrarMsjArticulo: [],
                pagination: {
                    'total': 0,
                    'current_page': 0,                
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,
                criterio: 'nombre',
                buscar: ''
            }
        },

        computed: {
            isActived: function(){
                return this.pagination.current_page;
            },
            //elementos de la paginacion
            pagesNumber: function(){
                if(!this.pagination.to){
                    return[];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },

        methods: {
            listarArticulos(page,buscar,criterio){
                let me = this;
                var url = '/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            cambiarPagina(page, buscar, criterio){
              let me = this;
            //actualiza la pagina actual
              me.pagination.current_page = page;
            //envia la peticion para visualizar la data de esa pagina
              me.listarArticulos(page,buscar,criterio);
            },
            cargarPdf(){
                window.open('http://localhost:8000/articulo/listarPdf','_blank');
            },
            registrarArticulo(){

                if(this.validarArticulo()){
                    return;
                }

                let me = this;
                axios.post('/articulo/registrar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    'codigo': this.codigo,
                    'precio_venta': this.precio_venta
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarArticulos(1,'','nombre');
                    Swal.fire({
                    icon: 'success',
                    title: 'Producto creado exitosamente',
                    showConfirmButton: false,
                    timer: 1500
                    });
                })
                .catch(function (error) {
                    console.log(error);
                })

            },

            actualizarArticulo(){
                
                if(this.validarArticulo()){
                    return;
                }

                let me = this;
                axios.put('/articulo/actualizar',{
                    'nombre': this.nombre,
                    'codigo': this.codigo,
                    'precio_venta': this.precio_venta,
                    'descripcion': this.descripcion,
                    'id': this.articulo_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarArticulos(1,'','nombre');
                    Swal.fire({
                    icon: 'success',
                    title: 'Producto actualizado exitosamente',
                    showConfirmButton: false,
                    timer: 1500
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            desactivarArticulo(id) {

                Swal.fire({
                title: '¿Estas seguro de desactivar el producto?',
                text: "El producto estará como: NO DISPONIBLE para todos los usuarios",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, confirmar!',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.value) {

                let me = this;
                
                axios.put('/articulo/desactivar', {
                    'id': id
                }).then(function (response) {
                    me.listarArticulos(1,'','nombre');
                    Swal.fire(
                    'Desactivado',
                    'El producto ahora está como no disponible para los usuarios',
                    'success'
                    )
                })
                .catch(function (error) {
                    console.log(error);
                });
                }
                })
            },

            activarArticulo(id) {

                Swal.fire({
                title: '¿Deseas activar el producto?',
                text: "El producto estará como: DISPONIBLE para todos los usuarios",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, confirmar!',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.value) {

                let me = this;
                axios.put('/articulo/activar',{
                    'id': id
                }).then(function (response) {
                    me.listarArticulos(1,'','nombre');
                    Swal.fire(
                    'Activado',
                    'El producto ahora está como disponible para los usuarios',
                    'success'
                    )
                })
                .catch(function (error) {
                    console.log(error);
                });
                }
                })
            },

            validarArticulo(){
                this.errorArticulo=0;
                this.errorMostrarMsjArticulo = [];
                if(!this.nombre) this.errorMostrarMsjArticulo.push("El nombre del producto no puede estar vacío")
                if(!this.codigo) this.errorMostrarMsjArticulo.push("El código del producto no puede estar vacío")
                if(this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;
                return this.errorArticulo;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.descripcion = '';
                this.codigo = '';
                this.precio_venta = '';

            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "articulo": 
                    {
                        switch(accion){
                            case "registrar": 
                            {
                                this.modal = 1;
                                this.codigo = '';
                                this.nombre = '';
                                this.precio_venta = '';
                                this.descripcion = '';
                                this.tituloModal = "Registrar Producto";
                                this.tipoAccion = 1;
                                break;
                            }
                            case "actualizar": 
                            {
                                this.modal = 1;
                                this.tituloModal = "Actualizar Producto";
                                this.tipoAccion = 2;
                                this.nombre = data['nombre'];
                                this.descripcion = data['descripcion'];
                                this.codigo = data['codigo'];
                                this.precio_venta = data['precio_venta'];
                                this.articulo_id = data['id'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarArticulos(1,this.buscar,this.criterio);
        }
    }
</script>

<style>

.modal-content{
    width: 100% !important;
    position: absolute !important;
}

.mostrar {
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #0000007a !important;
}

.div-error{
    display: flex;
    justify-content: center; 
}

.text-error{
    color: red;
    font-weight: bold;
}
    
</style>