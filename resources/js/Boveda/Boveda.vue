<template>     
    <div>
    <toast-container></toast-container>   
    <div class="col-md-10"> 
      <div class="row">
       <div class="box box-primary" ref="formContainer">
          <div class="box-header with-border">
            <h3 class="box-title"><b>Bóveda</b></h3>
            <div class="box-tools pull-right"></div>
          </div>
        <form v-on:submit.prevent = "filtrar">
            <div class="form-group">
              <label>Estado</label>
              <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" v-model="estado"  required>
                <option value="emitidos">Emitidos</option>
              </select>
            </div> 
            <label>Rango de tiempo</label>
         <div class="row">
            <div class="col-md-6">
             <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <datetime 
                  value-zone   = "local" 
                  type         = "datetime" 
                  v-model      = "datetimeI" 
                  class        = "theme-orange" 
                  placeholder  = "Ingrese fecha inicio"
                  :phrases     = "{ok: 'Aplicar', cancel: 'Cancelar'}"
                  title        = "Seleccione fecha y hora"
                  min-datetime =  "2018-01-01"
                  :max-datetime  = "fecha"
                   
                  required
                  ></datetime>
             </div>
           </div>
           <div class="col-md-6">
             <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <datetime 
                 value-zone    = "local" 
                 type          = "datetime" 
                 v-model       = "datetimeF" 
                 class         = "theme-orange" 
                 placeholder   = "Ingrese fecha fin"
                 :phrases      = "{ok: 'Aplicar', cancel: 'Cancelar'}"
                 title         = "Seleccione fecha y hora"
                 :min-datetime  = "datetimeI"
                 :max-datetime  = "fecha"
                 
                 required
                 ></datetime>
             </div>
           </div>
           <div class="col-md-3"><br><button type="submit" class="btn btn-primary btn-block"><i class="fa fa-fw fa-filter"></i>Filtrar</button></div>
         </div>
        </form>
       </div>
      </div>
    </div> 
     <div class="col-md-10">              
      <div class="box box-primary" v-if="hide != '1'" >
        <div class="box-header">                  
        <h3 class="box-title">Registros encontrados <b>{{ total }}</b></h3>
        </div>
        Mostrar <select style="width: 5%;" v-model.number="per"> 
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select> Registros
        <div class="box-body no-padding" style = "overflow: scroll">
          <center>
          <table class="tabla-boveda">
              <tbody>
                <paginate name="filteredResources" :list="filteredResources" :per=per>
                <th>RFC Emisor <input class="form-control" type="text" v-model="BEmisor" placeholder="Emisor" /></th>
                <th>RFC Receptor <input class="form-control" type="text" v-model="BReceptor" placeholder="Receptor" /></th>
                <th>UUID <input class="form-control" type="text" v-model="BUuid" placeholder="UUID" /></th>
                <th>Monto <input class="form-control" type="text" v-model="BMonto" placeholder="Monto" /></th>
                <th>Serie<input class="form-control" type="text" v-model="BSerie" placeholder="Serie" /></th>
                <th>Fecha de emisión<input class="form-control" type="text" v-model="BFemision" placeholder="Fecha emisióm" /></th>
                <th>Fecha de timbrado<input class="form-control" type="text" v-model="Btimbrado" placeholder="Fecha timbrado" /></th>
                <th>XML</th>
                <th>PDF</th>
                  <tr v-for="item in paginated('filteredResources')" class="tabla-boveda">
                      <td>{{ item.rfc_emisor }}</td>
                      <td>{{ item.rfc_receptor }}</td>
                      <td>{{ item.uuid }}</td>
                      <td>{{ item.monto }}</td>
                      <td>{{ item.serie }}</td>
                      <td>{{ item.fechah_emision }}</td>
                      <td>{{ item.fechah_timbrado }}</td>
                      <td><center><button class="btn btn-social-icon btn-bitbucket" v-on:click="obtenerxml( item.xml, item.uuid )" clrouter-linkss="btn btn-social-icon btn-bitbucket" ><i class="fa fa-fw fa-code"></i></button></center></td>
                      <td>
                        <center>
                          <button
                              type="button"
                              style="margin-right: 1px;"
                              class="btn btn-social-icon btn-google"
                              v-on:click="obtenerPDF( item.xml, item.fechah_timbrado )"
                              alt="Obtener PDF"
                              ><i class="fa fa-fw fa-file-pdf-o"></i>
                              </button>
                        </center>
                    </td>
                      
                  </tr>      
                </paginate>
              </tbody>      
              <paginate-links for="filteredResources" :limit="15" class="paginate" :show-step-links="true"></paginate-links>
              <hr>
              <button v-on:click="exportExcel()"  class="btn btn-success"><i class="fa fa-fw fa-file-excel-o"></i>Exportar a excel</button>
              <button v-on:click="zipXml()" type="button" class="btn bg-orange margin "  ><i class="fa fa-fw fa-file-zip-o"></i>Descargar xml's</button>
          </table>
          </center>     
        </div>                  
      </div>  
   </div>  </div>         
</template>
<script>
  export default{
      data:function(){
          return {   
                     //bnd: 1,
                     per: 5,
                     hide: 1,
                     total: '',
                     estado: '',
                     boveda: [],
                     paginate: ['filteredResources'],
                     datetimeI: '',
                     datetimeF: '',
                     BEmisor: '',
                     BReceptor: '',
                     BUuid: '',
                     BMonto: '',
                     BSerie: '',
                     BFemision: '',
                     Btimbrado: ''

                     
                };
      },
      computed: {
      filteredResources (){
          if(this.BEmisor){
            return this.boveda.filter((item)=>{
              this.refrescar(10);
              return item.rfc_emisor.startsWith(this.BEmisor);
            })
          }else if(this.BUuid){
            return this.boveda.filter((item)=>{
              this.refrescar(10);
              return item.uuid.startsWith(this.BUuid);
            })
          }else if(this.BReceptor){
            return this.boveda.filter((item)=>{
              this.refrescar(10);
              return item.rfc_receptor.startsWith(this.BReceptor);
            })
          }else if(this.BMonto){
            return this.boveda.filter((item)=>{
              this.refrescar(10);
              return item.monto.startsWith(this.BMonto);
            })  
          }else if(this.BSerie){
            return this.boveda.filter((item)=>{
              this.refrescar(10);
              return item.serie.startsWith(this.BSerie);
            })  
          }else if(this.BFemision){
            return this.boveda.filter((item)=>{
              this.refrescar(10);
              return item.fechah_emision.startsWith(this.BFemision);
            })  
          }else if(this.Btimbrado){
            return this.boveda.filter((item)=>{
              this.refrescar(10);
              return item.fechah_timbrado.startsWith(this.Btimbrado);
            })  
          }else{
            this.refrescar(5);
            return this.boveda;
          }  
      }
    },
      created: function(){     
             
             //Verirfica si la cuenta logueada tiene subcuentas
             let loader = this.$loading.show(); 
             let uri = 'https://timreports.expidetufactura.com.mx/tboreport/public/emisores';
             Axios.get(uri).then((response) => {
              this.emisores = response.data;     
              loader.hide();
              });
              
              //Obtiene fecha actual
              var today = new Date();
              var dd = today.getDate();
              var mm = today.getMonth() + 1; //Enero es 0
              var hh = today.getHours(); 
              var min = today.getMinutes(); 

              var yyyy = today.getFullYear();
              if (dd < 10) {
                dd = '0' + dd;
              } 
              if (mm < 10) {
                mm = '0' + mm;
              }
              if (hh < 10) {
                hh = '0' + hh;
              }  
              if (min < 10) {
                min = '0' + min;
              }  
              this.fecha = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;
                       
      },
      
      methods:{

        refrescar: function(pag){
         this.per = pag;
        },

        filtrar: function(){
                    

                    let loader = this.$loading.show({ container: this.fullPage ? null : this.$refs.formContainer  });
                    let total  = 0;
                    if(this.emisores == 'CnSubcuentas'){
               
                      var uri5 = 'https://timreports.expidetufactura.com.mx/tboreport/public/bovedaSubcuentas/'+this.datetimeI+'/'+this.datetimeF+'/'+this.estado+'';             

                      }else{
                      var uri5 = 'https://timreports.expidetufactura.com.mx/tboreport/public/boveda/'+this.datetimeI+'/'+this.datetimeF+'/'+this.estado+'';            
                      
                      }            
                    
                    Axios.get(uri5).then((response) => {
                        
                        this.boveda = response.data;   
                        this.total  = this.boveda.length;
                        this.total  = new Intl.NumberFormat().format(this.total);
                        loader.hide();  
                        if(this.boveda == "Vacio"){
                          Vue.prototype.$vueOnToast.pop('warning', 'No se econtraron registros', '');
                          this.hide = 1;
                          //this.bnd = 1;
                        }else{ 
                          this.hide = 0;
                          //this.bnd = 0;
                          Vue.prototype.$vueOnToast.pop('info', 'Se econtraron '+this.total+' registros', '');
                        }      

                    })
            },
        obtenerPDF: function(ruta, fecha) {
                var encode = window.btoa(ruta);
                let uri =
                  "https://timreports.expidetufactura.com.mx/tboreport/public/descargarXml/" +
                  encode +
                  "/" +
                  fecha;
                window.location = uri;
                //console.log(uri);
            },
        exportExcel: function () {
          
          let uri = 'https://timreports.expidetufactura.com.mx/tboreport/public/excelBoveda/'+this.emisores;            
          let toast = Vue.prototype.$vueOnToast.pop({
                type: 'default', 
                title: 'Espere un momento',
                bodyOutputType: 'TrustedHtml',
                body: '<i class="fa fa-fw fa-hourglass-start"></i>Procesando hoja de excel...',
                timeout: 0
              });
          window.location = uri;

        },
        zipXml: function () {
          
          let uri = 'https://timreports.expidetufactura.com.mx/tboreport/public/zipBoveda/'+this.emisores;            
          let toast = Vue.prototype.$vueOnToast.pop({
                type: 'default', 
                title: 'Espere un momento, este proceso puede tardar',
                bodyOutputType: 'TrustedHtml',
                body: '<i class="fa fa-fw fa-hourglass-start"></i>Descargando archivo zip con xml...',
                timeout: 0
              });
          window.location = uri;

        },
        obtenerxml: function(ruta,uuid){
          var encode = window.btoa(ruta);
          let uri = 'https://timreports.expidetufactura.com.mx/tboreport/public/xml/'+encode+'/'+uuid;
          window.location=uri;
          //console.log(uri);

        },    
              
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

.theme-orange .vdatetime-popup__header,
.theme-orange .vdatetime-calendar__month__day--selected > span > span,
.theme-orange .vdatetime-calendar__month__day--selected:hover > span > span {
  background: #318CB8;
}

.theme-orange .vdatetime-year-picker__item--selected,
.theme-orange .vdatetime-time-picker__item--selected,
.theme-orange .vdatetime-popup__actions__button {
  color: #318CB8;
}
.theme-orange input {
  width: 100%;
  padding: 14px 100px;
  cursor: pointer;
}
</style>