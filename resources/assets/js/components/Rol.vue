<template>
<main class="main">

            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Roles
                    </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="rol in arrayRol" :key="rol.id">
                                    <td v-text="rol.nombre"></td>
                                    <td>
                                        <div v-if="rol.condicion">
                                        <span class="badge badge-success">En sistema</span>
                                        </div>
                                        <div v-else>
                                        <span class="badge badge-danger">No disponible</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            
        </main>
</template>

<script>
    export default {

        data() {
            return {
                rol_id: 0,
                nombre: '',
                descripcion: '',
                arrayRol: [],
                modal : 0,
                tituloModal: '',
                tipoAccion: 0,
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
        },

        methods: {
            listarRoles(){
                let me = this;
                var url = '/rol';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRol = respuesta.roles.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            }
        },
        mounted() {
            this.listarRoles(1,this.buscar,this.criterio);
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