<template>
    <main class="main">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ventas</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="ventas"> </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Ventas de los últimos meses.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            varVenta: null,
            charVenta: null,
            ventas: [],
            varTotalVenta: [],
            varMesVenta: []
        };
    },
    methods: {
        getVentas() {
            let me = this;
            var url = "/dashboard";
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.ventas = respuesta.ventas;
                    //cargamos los datos del chart
                    me.loadVentas();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        loadVentas() {
            let me = this;
            me.ventas.map(function(x) {
                me.varMesVenta.push(x.mes);
                me.varTotalVenta.push(x.total);
            });
            me.varVenta = document.getElementById("ventas").getContext("2d");

            me.charVenta = new Chart(me.varVenta, {
                type: "bar",
                data: {
                    labels: me.varMesVenta,
                    datasets: [
                        {
                            label: "Ventas",
                            data: me.varTotalVenta,
                            backgroundColor: "rgba(54, 162, 235, 0.2)",
                            borderColor: "rgba(54, 162, 235, 0.2)",
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true
                                }
                            }
                        ]
                    }
                }
            });
        }
    },
    mounted() {
        this.getVentas();
    }
};
</script>
