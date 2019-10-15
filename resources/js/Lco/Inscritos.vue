<template>
  <div>
    <toast-container></toast-container>
    <div class="col-md-10">
      <div class="row">
        <div class="box box-primary" ref="formContainer">
          <div class="box-header with-border">
            <h3 class="box-title"><b>RFC's Inscritos</b></h3>

            <div class="box-tools pull-right">

            </div>
          </div>
          <!-- /.box-header -->
          <div class="box box-default">
            <div class="callout callout-warning" v-if="errors.length">
              <h4>Favor de corregir los siguientes errores:</h4>
              <ul>
                <li v-for="error in errors">{{ error }}</li>
              </ul>
            </div>
            <form novalidate="true" v-on:submit.prevent = "checkForm">

              <div class="form-group">
                <label>RFC</label>
                <input type="text" class="form-control" placeholder="RFC"
                  v-model="rfc" name="rfc" id="rfc" required>
              </div>
             <div v-if="bandera == '1'">
               <h4> <p class="bg-success">El RFC ingresado se encuentra inscrito dentro de la lista de contribuyentes</p></h4>
             </div>
             <div v-else-if="bandera == '2'">
               <h4><p class="bg-danger">El RFC ingresado no se encuentra inscrito dentro de la lista de contribuyentes</p></h4>
             </div>
              <button type="submit" class="btn btn-primary btn-block"><i
                  class="fa fa-fw fa-filter"></i>Buscar</button>
              <!-- /.col -->
            </form>
          </div>
          <!-- /.row -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default{
      data:function(){
          return {   

                  rfc: null,
                  errors: [],
                  bandera: 0,
                  inscritos: '',

                };
      },
      
      
      methods:{

        
      checkForm: function (e) {
         this.errors = [];

          if (!this.rfc) {
            this.errors.push('RFC requerido');
          } else if (!this.validrfc(this.rfc)) {
            this.errors.push('RFC invalido');
          }

          if (!this.errors.length) {
            this.filtrar();
            return true;
          }

          e.preventDefault();
          

        },
      validrfc: function (rfc) {
          var re = /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
          return re.test(rfc);
        },

        filtrar: function () {
          
          let loader = this.$loading.show(); 
             let uri = 'https://timreports.expidetufactura.com.mx/tboreport/public/Inscritos/'+this.rfc;
             //console.log(uri);
             Axios.get(uri).then((response) => {
              this.inscritos = response.data;     
              if (this.inscritos == 1){
                this.bandera = 1;
                loader.hide();
                Vue.prototype.$vueOnToast.pop('info', 'RFC valido', '');
               }else{
                this.bandera = 2;
                 loader.hide();
                 Vue.prototype.$vueOnToast.pop('error', 'RFC no valido', '');

               } 
          });

        },
      
    }
  }
</script>
<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>