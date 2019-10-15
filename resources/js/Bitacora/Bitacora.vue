<template>     
  <div>          
      <toast-container></toast-container>   
      <div class="col-md-10"> 
        <div class="box box-primary" ref="formContainer">
         <div class="box-header with-border">
           <h3 class="box-title"><b>Bitácora</b></h3>
           <div class="alert alert-info alert-dismissible">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
               <h4><i class="icon fa fa-info"></i> Aviso</h4>
               Los registros mostrados en esta tabla se limitan a las últimas 72 horas.
             </div>
           <div class="box-tools pull-right">
           </div>
         </div>
         <!-- /.box-header -->
         <div class="box box-default">
           <div class="row">
             <div class="col-md-6">
               <div class="callout callout-warning" v-if="errors.length">
               <h4>Favor de corregir los siguientes errores:</h4>
               <ul>
                 <li v-for="error in errors">{{ error }}</li>
               </ul>
             </div>
           </div>
              <form novalidate="true" v-on:submit.prevent = "checkForm">
               <div class="col-md-12" v-if="estado == 'snv'">
                 <div class="form-group">
                 <label >RFC emisor</label>
                 <input type="text" class="form-control" placeholder="RFC" v-model="rfc_emisor" name="rfc_emisor" id="rfc_emisor"  >
                 </div>
               </div>
             <div class="col-md-12" v-if="estado == 'uuid'">
               <div class="form-group">
                 <label>UUID</label>
                 <input type="text" class="form-control" placeholder="UUID" v-model="uuid" name="uuid" id="uuid" >
               </div>
               </div>
               
               <div class="col-md-12">
                 <div class="form-group">
                   <label>Estado</label>
                   <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" v-model="estado" required>
                     <option value="EXITO">Exitosos</option>
                     <option value="fallido">Fallidos</option>
                   </select>
                 </div>  
             </div>               
            <div class="col-md-12">
               <div class="form-group" v-if="estado == 'codigo'">
                 <label>Filtrar por tipo de error</label>
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                   <!--<button class="btn btn-block btn-info btn-xs" @click="disabled = (disabled + 1) % 2" > {{btn}}</button>-->
                 </div>
                 <input type="text" class="form-control"  v-model="err" required >
                  
               </div>                
             </div>  
             <div class="col-md-3"><button type="submit" class="btn btn-primary btn-block"><i class="fa fa-fw fa-filter"></i>Filtrar</button></div>
             <!-- /.col -->
            </form>
           </div>
           <!-- /.row -->
         </div>
     </div>
   </div>   
    <div class="col-md-10">              
     <div class="box box-primary" v-if="bnd != '1'" >
       <div class="box-header">                  
       <h3 class="box-title">Registros encontrados <b>{{ total }}</b></h3>
       </div>
       Mostrar <select style="width: 5%;" v-model.number="per"> 
                   <option selected="true" value="5">5</option>
                   <option value="10">10</option>
                   <option value="50">50</option>
                   <option value="100">100</option>
               </select> Registros
       <!-- /.box-header -->
       <div class="box-body no-padding" style = "overflow: scroll">
         <center>
         <table class="tabla-bitacora"   >
             <tbody>
               <paginate name="filteredResources" :list="filteredResources" :per="per">
               <th>UUID <input class="form-control" type="text" v-model="BUuid" placeholder="UUID" /></th>
               <th>RFC Emisor <input class="form-control" type="text" v-model="BEmisor" placeholder="Emisor" /></th>
               <th>RFC Receptor <input class="form-control" type="text" v-model="BReceptor" placeholder="Receptor" /> </th>
               <th>Fecha<input class="form-control" type="text" v-model="BFecha" placeholder="Fecha" /> </th>
               <th>Respuesta <input class="form-control" type="text" v-model="BRespuesta" placeholder="Mensaje Respuesta" /></th>
               <th>XML timbrado</th>
               <th>XML sin timbrar</th>
                 <tr v-for="item in paginated('filteredResources')" class="tabla-bitacora" >
                     <td>{{ item.uuid }}</td>
                     <td>{{ item.rfc_emisor }}</td>
                     <td>{{ item.rfc_receptor }}</td>
                     <td>{{ item.fechah }}</td>
                     <td>{{ item.respuesta }}</td>
                     <td><center><button class="btn btn-social-icon btn-bitbucket" v-on:click="obtenerxml( item.PATH_TIMBRADO, item.uuid, estado )" clrouter-linkss="btn btn-social-icon btn-bitbucket" ><i class="fa fa-fw fa-code"></i></button></center></td>
                     <td><center><button class="btn btn-social-icon btn-google" v-on:click="obtenerxml( item.PATH_BITACORA, estado )" ><i class="fa fa-fw fa-code"></i></button></center></td>
                 </tr>      
               </paginate>
             </tbody>      
             <paginate-links for="filteredResources" :limit="15" class="paginate" :show-step-links="true"> </paginate-links>
             <hr>
             <button v-on:click="exportExcel()"  class="btn btn-success"><i class="fa fa-fw fa-file-excel-o"></i>Exportar a excel</button>  
         </table>
         </center>     
       </div>                  
       <!-- /.box-body -->
     </div>  
  </div>
 </div>             
</template>
<script src="xlsx.full.min.js"></script>

<script>
 export default{
     data:function(){
         return {   
                    per: 5,
                    bnd: 1,
                    err: '' , 
                    uuid: '',
                    total: '',
                    errors: [],
                    estado: '',
                    emisores: '',
                    bitacora: [],
                    paginate: ['filteredResources'],
                    rfc_emisor: '',
                    BEmisor:'',
                    BFecha: '',
                    BUuid:'',
                    BReceptor:'',
                    BRespuesta:''
               };
     },

    computed: {

    filteredResources (){
          if(this.BEmisor){
            return this.bitacora.filter((item)=>{
              this.refrescar(10);
              return item.rfc_emisor.startsWith(this.BEmisor);
            })
          }else if(this.BUuid){
            return this.bitacora.filter((item)=>{
              this.refrescar(10);
              return item.uuid.startsWith(this.BUuid);
            })
          }else if(this.BReceptor){
            return this.bitacora.filter((item)=>{
              this.refrescar(10);
              return item.rfc_receptor.startsWith(this.BReceptor);
            })
          }else if(this.BRespuesta){
            return this.bitacora.filter((item)=>{
              this.refrescar(10);
              return item.respuesta.startsWith(this.BRespuesta);
            })  
          }else if(this.BFecha){
            return this.bitacora.filter((item)=>{
              this.refrescar(10);
              return item.fechah.startsWith(this.BFecha);
            })  
          }else{
            this.refrescar(5);
            return this.bitacora;
          }  
      }
    },
     created: function(){     
            
            //Verirfica si el padre tiene subcuentas
            let loader = this.$loading.show(); 
            let uri = 'https://timreports.expidetufactura.com.mx/tboreport/public/emisores';
            Axios.get(uri).then((response) => {
             this.emisores = response.data;     
             loader.hide();
         });
                             
     },
     
     methods:{

       refrescar: function(pag){
         this.per = pag;
        }, 
         
       checkForm: function (e) {
               
                switch (this.estado) {
                   case 'snv':
                     
                   this.errors = [];
                   if (!this.rfc_emisor) {
                      this.errors.push('RFC requerido');
                   }else if (!this.validrfc(this.rfc_emisor)) {
                     this.errors.push('RFC invalido');
                   }
                   if (!this.errors.length) {
                     this.uuid = "";
                     this.filtrar(this.rfc_emisor, this.uuid);
                     return true;
                   }
                   e.preventDefault();

                     break;
                   case 'uuid':

                     this.errors = [];

                     if (!this.validuuid(this.uuid)) {
                       this.errors.push('UUID no valido');
                     }
                     if (!this.errors.length) {
                       this.rfc_emisor ="";
                       this.filtrar(this.rfc_emisor, this.uuid);
                       return true;
                     }
                     e.preventDefault();
                     break;
                   
                   default:
                     this.filtrar();
                     //console.log("Default");
                 }
         },

       validrfc: function (rfc) {
           var re = /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
           return re.test(rfc);
         },

       validuuid: function (uuid) {
           var re = /[a-f0-9A-F]{8}-[a-f0-9A-F]{4}-[a-f0-9A-F]{4}-[a-f0-9A-F]{4}-[a-f0-9A-F]{12}/;
           return re.test(uuid);
         },  

         filtrar: function(rfc_emisor, uuid){
             
             let loader = this.$loading.show({ container: this.fullPage ? null : this.$refs.formContainer  });
             let total  = 0;
             
             if(rfc_emisor == "" ){rfc_emisor = "null";}
             if(uuid ==""){uuid = "null";}
             if(this.err == ""){this.err = "null";}
             if(this.estado == "uuid"){this.estado = 'snv';}

             //console.log(uuid, rfc_emisor)

           
             //Verifica si la cuenta logueada tiene subcuentas
             if(this.emisores == 'CnSubcuentas'){
              
             var uri5 = 'https://timreports.expidetufactura.com.mx/tboreport/public/subCuentasBita/'+rfc_emisor+'/'+uuid+'/'+this.estado+'/'+this.err+'';             

             }else{
             var uri5 = 'https://timreports.expidetufactura.com.mx/tboreport/public/FiltroBitacora/'+rfc_emisor+'/'+uuid+'/'+this.estado+'/'+this.err+'';            
             
             }

             Axios.get(uri5).then((response) => {
                 this.bitacora = response.data;   
                 this.total = this.bitacora.length;
                 this. total = new Intl.NumberFormat().format(this.total);
                 loader.hide();  
                 if(this.bitacora == "Vacio"){
                   this.bnd = 1;
                   Vue.prototype.$vueOnToast.pop('warning', 'No se econtraron registros', '');
                 }else{ 
                   this.bnd = 0;
                   Vue.prototype.$vueOnToast.pop('info', 'Se econtraron '+this.total+' registros', '');
                 }        
             })
               
                  

           },
         exportExcel: function () {
             //Manda peticion para descargar el reporte en excel
             let uri5 = 'https://timreports.expidetufactura.com.mx/tboreport/public/excel/'+this.emisores;            
             window.location = uri5;

             let toast = Vue.prototype.$vueOnToast.pop({
                type: 'default', 
                title: 'Espere un momento',
                bodyOutputType: 'TrustedHtml',
                body: '<i class="fa fa-fw fa-hourglass-start"></i> Procesando hoja de excel...',
                timeout: 0
              });

       },
         obtenerxml: function(ruta,uuid, estado){
             //Manda la ruta para poder descargar el xml
             if (estado == 'fallido') {
               Vue.prototype.$vueOnToast.pop('warning', 'No existe XML', '');
             }else{
             var encode = window.btoa(ruta);
             let uri = 'https://timreports.expidetufactura.com.mx/tboreport/public/xml/'+encode+'/'+uuid;
             window.location=uri;
             //console.log(uri);
           }
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


.tabla-bitacora table {
 font-family: 'Avenir', Helvetica, Arial, sans-serif;
 border-collapse: collapse;
 width: 100%;
}

.tabla-bitacora td, th {
 
 text-align: left;
 padding: 8px;
}

.tabla-bitacora tr:nth-child(even) {
 background-color: #FFE5E5;
}
</style>