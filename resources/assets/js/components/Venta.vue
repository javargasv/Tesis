<template>
            <main class="main">
            <!-- Breadcrumb -->

            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Pedidos
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo pedido
                        </button>
                    </div>
                    <!-- Listado-->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio" id="selec" @click="textoplace()">
                                        <option value="id">N° de pedido</option>
                                        <option value="estado">Estado</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                        <option value="total">Total</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control textoBusqueda" placeholder="Ej: Registrado/Rechazado/Aceptado...">
                                    <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>N° pedido</th>
                                        <th>Cliente</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td v-text="venta.id"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.created_at"></td>
                                        <td>$ {{formato(venta.total)}}</td>
                                        <td>
                                            <div v-if="venta.estado=='Registrado'">
                                            <span class="badge badge-primary">Registrado</span>
                                            </div>
                                            <div v-if="venta.estado=='Cotizando'">
                                            <span class="badge badge-info">Cotizando</span>
                                            </div>
                                            <div v-if="venta.estado=='Aceptado'">
                                            <span class="badge badge-dark">Aceptado</span>
                                            </div>
                                            <div v-if="venta.estado=='En despacho'">
                                            <span class="badge badge-light">Cotizando</span>
                                            </div>
                                            <div v-if="venta.estado=='Entregado'">
                                            <span class="badge badge-success">Entregado</span>
                                            </div>
                                            <div v-if="venta.estado=='Rechazado/cancelado'">
                                            <span class="badge badge-danger">Rechazado/cancelado</span>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                            <i class="icon-doc"></i>
                                            </button> &nbsp;
                                            <template v-if="venta.estado !='Rechazado/cancelado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <!--Fin Listado-->
                    <!-- Detalle-->
                    <template v-else-if="listado==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9" id="verc">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <v-select
                                        @search="selectCliente"
                                        label="nombre"
                                        :options="arrayCliente"
                                        placeholder="Buscar Clientes..."
                                        @input="getDatosCliente"                                        
                                    >

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <span class="badge badge-warning">Valor del pedido antes de IVA</span>
                            </div>

                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Artículo <span style="color:red;" v-show="idarticulo==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Código producto">
                                        <button @click="listarArticulo(buscarA,criterioA),abrirModal()" class="btn btn-primary">Buscar producto&nbsp;&nbsp;<i class="fa fa-search"></i></button>
                                        <input type="text" readonly class="form-control inp" v-model="articulo">
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Leyenda N° 1</label>
                                    <input type="text" step="any" class="form-control" v-model="leyenda1">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Leyenda N° 2</label>
                                    <input type="text" step="any" class="form-control" v-model="leyenda2">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Leyenda N° 3</label>
                                    <input type="text" step="any" class="form-control" v-model="leyenda3">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Leyenda N° 4</label>
                                    <input type="text" step="any" class="form-control" v-model="leyenda4">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span style="color:red;" class="removespan">(*)</span></label>
                                    <input type="number" value="0" step="any" class="form-control preciocliente" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color:red;" class="removespan">(*)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-secondary form-control btnagregar" id="btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Leyenda 1</th>
                                            <th>Leyenda 2</th>
                                            <th>Leyenda 3</th>
                                            <th>Leyenda 4</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo">
                                            </td>
                                            <td>
                                                <input v-model="detalle.leyenda1" type="text"  class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.leyenda2" type="text"  class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.leyenda3" type="text"  class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.leyenda4" type="text"  class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" class="form-control noeditar">
                                            </td>
                                            <td>
                                                <input v-model="detalle.cantidad" type="number" class="form-control">
                                            </td>
                                            <td>
                                                $ {{formato(detalle.precio*detalle.cantidad)}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="8" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{formato(total=calcularTotal)}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay productos agregados
                                            </td>
                                        </tr>
                                    </tbody>                                    
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Pedido</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- Fin Detalle-->
                    <!-- Ver Venta -->
                    <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">IMPUESTO</label>
                                    <span class="badge badge-warning">Valor del pedido antes de IVA</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Leyenda1</th>
                                            <th>Leyenda2</th>
                                            <th>Leyenda3</th>
                                            <th>Leyenda4</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo">
                                            </td>
                                            <td v-text="detalle.precio">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td v-text="detalle.leyenda1">
                                            </td>
                                            <td v-text="detalle.leyenda2">
                                            </td>
                                            <td v-text="detalle.leyenda3">
                                            </td>
                                            <td v-text="detalle.leyenda4">
                                            </td>
                                            <td>
                                                $ {{formato(detalle.precio*detalle.cantidad)}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="7" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{formato(total)}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
                                                NO hay artículos agregados
                                            </td>
                                        </tr>
                                    </tbody>                                    
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- fin ver ingreso -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-4" v-model="criterioA">
                                        <option value="nombre">Nombre</option>
                                        <option value="precio_venta">Precio</option>
                                        <option value="codigo">Código</option>
                                        </select>
                                        <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Precio Venta</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="articulo.codigo"></td>
                                            <td v-text="articulo.nombre"></td>
                                            <td>$ {{formato(articulo.precio_venta)}}</td>
                                            <td>
                                                <div v-if="articulo.condicion">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                                
                                            </td>
                                        </tr>                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
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
    import vSelect from 'vue-select';

    export default {
        data (){
            return {
                venta_id: 0,
                idcliente:0,
                cliente:'',
                total:0,
                arrayVenta : [],
                arrayCliente: [],
                arrayDetalle : [],
                listado:1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorVenta : 0,
                errorMostrarMsjVenta : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'estado',
                buscar : '',
                criterioA:'nombre',
                buscarA: '',
                arrayArticulo: [],
                idarticulo: 0,
                codigo: '',
                articulo: '',
                precio: 0,
                cantidad:0,
                leyenda1: '',
                leyenda2: '',
                leyenda3: '',
                leyenda4: ''
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad)
                }
                return resultado;
            }
        },
        methods : {
            textoplace(){
                let me = this;
                var combo = document.getElementById("selec");
                var selected = combo.options[combo.selectedIndex].text;
                console.log(selected);
                if(selected === 'N° de pedido'){
                    me.buscar='';
                    $('.textoBusqueda').attr('placeholder','Número del pedido')
                } else if (selected === 'Estado') {
                    me.buscar='';
                    $('.textoBusqueda').attr('placeholder','Ej: Registrado/Rechazado/Aceptado...')
                } else if (selected === 'Fecha-Hora') {
                    me.buscar='';
                    $('.textoBusqueda').attr('placeholder','AAAA-MM-DD')
                } else {
                    me.buscar='';
                    $('.textoBusqueda').attr('placeholder','$ 1000')
                }
            },
            formato(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            listarVenta (page,buscar,criterio){
                let me=this;
                var url= '/venta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayVenta = respuesta.ventas.data;
                    me.pagination= respuesta.pagination;
                    if(me.arrayVenta.length == 0) {
                        Swal.fire({
                            icon: "warning",
                            title: "No hay registro",
                            text: "No se encuentra resultados de tu búsqueda",
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCliente(search,loading){
                let me=this;
                loading(true)

                var url= '/cliente/selectCliente?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayCliente=respuesta.clientes;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCliente(val1){
                let me = this;
                me.loading = true;
                me.idcliente = val1.id;
            },
            buscarArticulo(){
                let me=this;
                var url= '/articulo/buscarArticuloVenta?filtro=' + me.codigo;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticulo = respuesta.articulos;

                    if (me.arrayArticulo.length>0){
                        me.articulo=me.arrayArticulo[0]['nombre'];
                        me.idarticulo=me.arrayArticulo[0]['id'];
                        me.precio=me.arrayArticulo[0]['precio_venta'];
                    }
                    else{
                        me.articulo='No existe artículo';
                        me.idarticulo=0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            pdfVenta(id){
                window.open('http://ideasalvajes.net/venta/pdf/'+id+','+'_blank');
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarVenta(page,buscar,criterio);
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idarticulo==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },
            agregarDetalle(){
                let me=this;
                if(me.idarticulo==0 || me.cantidad==0 || me.precio==0){
                    Swal.fire({
                        icon: "error",
                        title: "Por favor complete todos los campos requeridos",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                else{
                    if(me.encuentra(me.idarticulo)){
                        Swal.fire({
                        icon: "error",
                        title: "El producto ya se encuentra agregado",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    }
                    else{
                        $('.btnagregar').removeClass('btn-secondary');
                        $('#btnagregar').addClass('btn-success');
                        me.arrayDetalle.push({
                        idarticulo: me.idarticulo,
                        articulo: me.articulo,
                        cantidad: me.cantidad,
                        precio: me.precio,
                        leyenda1: me.leyenda1,
                        leyenda2: me.leyenda4,
                        leyenda3: me.leyenda3,
                        leyenda4: me.leyenda4
                        });
                        me.codigo="";
                        me.idarticulo=0;
                        me.articulo="";
                        me.leyenda1="";
                        me.leyenda2="";
                        me.leyenda3="";
                        me.leyenda4="";
                        me.cantidad=0;
                        me.precio=0; 
                    }
                    
                }

                

            },
            agregarDetalleModal(data =[]){
                let me=this;
                if(me.encuentra(data['id'])){
                        Swal.fire({
                        icon: "error",
                        title: "El producto ya se encuentra agregado",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    }
                    else{
                            me.arrayDetalle.push({
                            idarticulo: data['id'],
                            articulo: data['nombre'],
                            cantidad: 1,
                            precio: data['precio_venta'],
                            leyenda1: " ",
                            leyenda2: " ",
                            leyenda3: " ",
                            leyenda4: " "
                        }); 
                    }
            },
            listarArticulo (buscar,criterio){
                let me=this;
                var url= '/articulo/listarArticuloVenta?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticulo = respuesta.articulos.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarVenta(){
                if (this.validarVenta()){
                    return;
                }
                
                let me = this;

                axios.post('/venta/registrar',{
                    'idcliente': this.idcliente,
                    'total' : this.total,
                    'data': this.arrayDetalle
                }).then(function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Pedido registrado satisfactoriamente",
                        text: "El pedido esta en estado REGISTRADO, cuando el cliente acepte el precio total con IVA incluido, pasara a COTIZACIÓN.",
                        showConfirmButton: false,
                        timer: 2500
                    });
                    me.listado=1;
                    me.listarVenta(1,'','estado');
                    me.idcliente=0;
                    me.total=0.0;
                    me.idarticulo=0;
                    me.articulo='';
                    me.cantidad=0;
                    me.precio=0;
                    me.codigo='';
                    me.leyenda1='';
                    me.leyenda2='';
                    me.leyenda3='';
                    me.leyenda4='';
                    me.arrayDetalle=[];

                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarVenta(){
                var url= '/venta/verCliente';
                axios.get(url).then(function (response) {
                        var usuario= response.data;
                        console.log(usuario);
                        if(usuario==2){
                            let me=this;
                            me.errorVenta=0;
                            me.errorMostrarMsjVenta =[];

                            if (me.idcliente==0) me.errorMostrarMsjVenta.push("Seleccione un Cliente");
                            if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Ingrese los detalles de la venta");

                            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                            return me.errorVenta;
                        } else {
                            let me=this;
                            me.errorVenta=0;
                            me.errorMostrarMsjVenta =[];

                            if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Ingrese los detalles de la venta");

                            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                            return me.errorVenta;
                        }
                    });

            },
            verCliente(){
                var url= '/venta/verCliente';
                axios.get(url).then(function (response) {
                        var usuario= response.data;
                        console.log(usuario);
                        if(usuario==3){
                            $('#verc').addClass('invisible');
                            $('.preciocliente').addClass('noeditar');
                        }
                    });
            },
            mostrarDetalle(){

                if (this.verCliente()){
                    return;
                }

                let me=this;
                me.listado=0;

                me.idproveedor=0;
                me.tipo_comprobante='BOLETA';
                me.serie_comprobante='';
                me.num_comprobante='';
                me.impuesto=0.18;
                me.total=0.0;
                me.idarticulo=0;
                me.articulo='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verVenta(id){
                let me=this;
                me.listado=2;
                
                //Obtener los datos del ingreso
                var arrayVentaT=[];
                var url= '/venta/obtenerCabecera?id=' + id;
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayVentaT = respuesta.venta;

                    me.cliente = arrayVentaT[0]['nombre'];
                    me.total=arrayVentaT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
                var urld= '/venta/obtenerDetalles?id=' + id;
                
                axios.get(urld).then(function (response) {
                    console.log(response);
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });               
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            }, 
            abrirModal(){               
                this.arrayArticulo=[];
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios artículos';
            },
            desactivarVenta(id){
                Swal.fire({
                title: '¿Estas seguro de cancelar o rechazar el pedido?',
                text: "Esta acción es permanente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, confirmar!',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/venta/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarVenta(1,'','num_comprobante');
                        swal.fire(
                        'Anulado!',
                        'El pedido ha sido anulado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
        },
        mounted() {
            this.listarVenta(1,this.buscar,this.criterio);
        }
    }
</script>
<style>

    .noeditar {
        background: #d0d0d0a8 !important;
        pointer-events: none;
    }

    .inp {
        background: #fff !important;
        margin-left: 1rem;
        border-radius: 10px;
        box-shadow: 3px 4px 7px 0px #808080b8;
        text-align: center;
        color: #000000b0 !important;
        text-transform: uppercase;
    }

    .invisible {
        display:none;
    }

    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>
