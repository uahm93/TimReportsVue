<template>     
       <div>
        <toast-container></toast-container>   
          <div class="col-md-10">
            <div class="box box-primary"> 
              <h3 class="box-title" align="center"><b>Timbrado <font color="#262b69">{{ fecha }} </font></b></h3>
              <!--<button class="btn btn-social-icon btn-tumblr" v-on:click="mesAnterior()" ><i class="fa fa-fw fa-chevron-left"></i></button>-->
            <line-chart ></line-chart>     
            </div>   
          </div>        
            <div class="col-md-5">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><b>Resumen histórico de <font color="#318CB8">comprobantes emitidos</font></b></h3>
                </div>
                  <div class="box-body no-padding">
                    <table class="table table-condensed">
                      <tbody>
                      <tr>                  
                        <td><p class="text-light-blue"><b>Total histórico de comprobantes emitidos</b></p></td>
                        <td><span class="badge bg-light-blue">{{ historico }}</span></td>
                      </tr>      
                      <tr>                  
                        <td><b><p class="text-green">Promedio diario de comprobantes emitidos</p></b></td>
                        <td><span class="badge bg-green">{{ promedio_historico }}</span></td>
                      </tr>
                      <tr>                  
                        <td><b><p class="text-light-blue">Total de comprobantes emitidos del día de ayer</p></b></td>
                        <td><span class="badge bg-light-blue">{{ timbrado_ayer }}</span></td>
                      </tr>             
                    </tbody></table>
                  </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><b>Resumen histórico de <font color="#318CB8"> comprobantes cancelados</font></b></h3>
                </div>
                  <div class="box-body no-padding">
                    <table class="table table-condensed">
                      <tbody>
                      <tr>                  
                        <td><p class="text-light-blue"><b>Total histórico de comprobantes cancelados</b></p></td>
                        <td><span class="badge bg-light-blue">PENDIENTE</span></td>
                      </tr>      
                      <tr>                  
                        <td><b><p class="text-green">Promedio histórico de comprobantes cancelados</p></b></td>
                        <td><span class="badge bg-green">PENDIENTE</span></td>
                      </tr>  
                      <tr>                  
                        <td><b><p class="text-light-blue">Total de comprobantes cancelados del día de ayer</p></b></td>
                        <td><span class="badge bg-light-blue">PENDIENTE</span></td>
                      </tr>             
                    </tbody></table>
                  </div>                  
              </div>
            </div>
            <div class="col-md-10">              
                <div class="box box-primary">
                  <div class="box-header">                  
                  <h3 class="box-title"><b>Resumen <font color="#318CB8">timbrado hoy</font></b></h3>
                  </div>
                  <div class="box-body no-padding">
                    
                    <table class="table table-condensed">
                        <tbody>
                          <tr>                  
                            <td><p class="text-light-blue"><b>Total timbrados</b></p></td>
                            <td><span class="badge bg-light-blue">{{ timbrado_hoy }}</span></td>
                          </tr>      
                          <tr>                  
                            <td><b><p class="text-green">Pendientes de envío</p></b></td>
                            <td><span class="badge bg-green">{{ pendientes_hoy }}</span></td>
                          </tr>  
                          <tr>                  
                            <td><b><p class="text-light-blue">Total cancelados</p></b></td>
                            <td><span class="badge bg-light-blue">PENDIENTE</span></td>
                          </tr>             
                        </tbody>                        
                    </table>
                    
                  </div>                  
                </div>  
            </div>
            <div class="col-md-10">              
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title"><b>Históricos <font color="#318CB8">complementos</font></b></h3>
                  </div>
                  <button class="btn bg-navy btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                     Desplegar timbrado histórico con complementos <span class="pull-right-container">
                    <i class="fa fa-fw fa-get-pocket"></i>
                  </span>                                  
                  </button>
                  <div class="collapse" id="collapseExample">
                    <div class="well">
                      <div class="box">
                        <div class="box-header with-border">
                          <h3 class="box-title">Histórico por complementos</h3>
                        </div>
                          <div class="box-body">
                            <center>
                              <table class="tabla-boveda">
                                <tbody>
                                <tr>                                  
                                  <th>Complemento</th>
                                  <th>Total</th>
                                </tr>
                                <tr v-for="item in complementos" class="tabla-boveda">      
                                  <td>{{ item.complemento }}</td>
                                  <td>{{ item.TOTAL }}</td>                                                                    
                                </tr>                              
                                </tbody>
                              </table>
                            </center>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>  
            </div>

         </div>             
</template>
<script>
window.Vue = require('vue');
import VueChartJs    from 'vue-chartjs';
Vue.component('line-chart', {
  extends: VueChartJs.Line,
  props: ["data"],
  data:function(){
          return { grafica: [] };
      },
  mounted () {
              
              let loader = this.$loading.show({ container: this.fullPage ? null : this.$refs.formContainer  });
              let toast = this.$vueOnToast.pop({
                toastContainerId: '1',
                type: 'default', 
                title: 'Espere un momento...',
                bodyOutputType: 'TrustedHtml',
                body: '<i class="fa fa-fw fa-hourglass-start"></i> Procesando gráfica de su timbrado mensual',
              })
             
  	          let uri = 'https://balanceadorzeus.expidetufactura.com.mx/tboreport/public/grafica';
              Axios.get(uri).then((response) => {
              this.grafica = response.data;    
              this.renderLineChart();
              loader.hide();  
              this.$vueOnToast.remove(toast.toastId) 

               }); 
  },
  methods: {
    renderLineChart: function() {
    this.renderChart(
      {
        labels: ["01",  "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20","21","22","23","24","25","26","27","28","29","30","31" ],
        datasets: [
          {
            label: 'Total de timbrado',
            backgroundColor: '#a2832f',
            data: this.grafica,
          }
        ]
      },
      { responsive: true, maintainAspectRatio: false }
    );      
    }
  }, 
});
  export default{
      data:function(){
          return {
                  per: 8,
                  fecha: '',
                  contador: 1,  
                  historico: '',
                  complementos: [],
                  timbrado_hoy: '',
                  timbrado_ayer: '',
                  pendientes_hoy: '',  
                  promedio_historico: '',
                                
                };
      },
      mounted(){
       this.timbrado_hoy;
       var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
       var f=new Date();      
       this.fecha = meses[f.getMonth()] + " de " + f.getFullYear();
      },
      created: function(){          
            let uri = 'https://balanceadorzeus.expidetufactura.com.mx/tboreport/public/totales/';
             Axios.get(uri).then((response) => {
              this.historico = response.data;              
          });           
          let uri1 = 'https://balanceadorzeus.expidetufactura.com.mx/tboreport/public/t_ayer/';
             Axios.get(uri1).then((response) => {
              this.timbrado_ayer = response.data;              
          }); 
          let uri2 = 'https://balanceadorzeus.expidetufactura.com.mx/tboreport/public/t_hoy/';
             Axios.get(uri2).then((response) => {
              this.timbrado_hoy = response.data;              
          });   
          let uri3 = 'https://balanceadorzeus.expidetufactura.com.mx/tboreport/public/pendientes_hoy/';
             Axios.get(uri3).then((response) => {
              this.pendientes_hoy = response.data;              
          });
          let uri4 = 'https://balanceadorzeus.expidetufactura.com.mx/tboreport/public/totales_complementos/';            
          Axios.get(uri4).then((response) => {
          this.complementos = response.data;            
          })
          let uri5 = 'https://balanceadorzeus.expidetufactura.com.mx/tboreport/public/promedio_historico/';            
          Axios.get(uri5).then((response) => {
          this.promedio_historico = response.data;            
          })
                              
      },
      methods:{
            mesAnterior(){
               
               this.contador++;
               let uri = 'https://balanceadorzeus.expidetufactura.com.mx/tboreport/public/grafica_historica/'+this.contador;
               Axios.get(uri).then((response) => {
                this.grafica = response.data;    
               }); 
               

            }

      }
  }
  
</script>
<style lang="scss">
.paginate a {
    cursor: pointer;
  }
.paginate li {
  text-align: center;
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  display: inline-block;
  margin: 0 10px;
  margin-top: 60px;
  font-size: 20px;
}

.paginate li.active a {
    font-weight: bold;
  }


.tabla-boveda table {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.tabla-boveda td, th {
  
  text-align: left;
  padding: 8px;
}

.tabla-boveda tr:nth-child(even) {
  background-color: #FFE5E5;
}


</style>
