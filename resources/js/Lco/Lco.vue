<template>
  <div>
    <toast-container></toast-container>
    <div class="col-md-10">
      <div class="box box-primary" ref="formContainer">
        <div class="box-header with-border">
          <h3 class="box-title"><b>LCO</b></h3>

          <div class="box-tools pull-right"></div>
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
            <form novalidate="true" v-on:submit.prevent="checkForm">
              <div class="col-md-12">
                <div class="form-group">
                  <label>RFC</label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="RFC"
                    v-model="rfc"
                    name="rfc"
                    id="rfc"
                    required
                  />
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>No. Certificado</label>
                  <input
                    type="number"
                    class="form-control"
                    placeholder="No. certificado"
                    v-model="certificado"
                    required
                  />
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>Estado</label>
                  <select
                    class="form-control select2 select2-hidden-accessible"
                    style="width: 100%;"
                    tabindex="-1"
                    aria-hidden="true"
                    v-model="estado"
                    required
                  >
                    <option value="todos">Todos</option>
                    <option value="A">Activo</option>
                    <option value="R">Revocado</option>
                    <option value="C">Cancelado</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-block">
                  <i class="fa fa-fw fa-filter"></i>Filtrar
                </button>
              </div>
              <!-- /.col -->
            </form>
          </div>
          <!-- /.row -->
        </div>
      </div>
    </div>
    <div class="col-md-10">
      <div class="box box-primary" v-if="lco != '' ">
        <div class="box-header">
          <h3 class="box-title"><b>LCO</b></h3>
        </div>
        <div class="box-body no-padding">
          <center>
            <table class="tabla-lco" v-if="hide == '0'">
              <tbody>
                <paginate name="lco" :list="lco" :per="50">
                  <th>No. Certificado</th>
                  <th>RFC emisor</th>
                  <th>Fecha inicio</th>
                  <th>Fecha fin</th>
                  <th>Estado</th>
                  <th>Validez</th>
                  <tr v-for="item in paginated('lco')" class="tabla-lco">
                    <td>{{ item.no_serie }}</td>
                    <td>{{ item.rfc }}</td>
                    <td>{{ item.fecha_inicio }}</td>
                    <td>{{ item.fecha_fin }}</td>
                    <td v-if="item.estado == 'C'">Cancelado</td>
                    <td v-else-if="item.estado == 'A'">Activo</td>
                    <td v-else>Revocado</td>
                    <td v-if="item.validez_obligaciones == '1'">OK</td>
                    <td v-else>NG</td>
                  </tr>
                </paginate>
              </tbody>
              <paginate-links
                for="lco"
                :limit="10"
                class="paginate"
                :show-step-links="true"
              ></paginate-links>
              <hr>
              <button v-on:click="exportExcel()" class="btn btn-success">
                <i class="fa fa-fw fa-file-excel-o"></i>Exportar a excel
              </button>
            </table>
          </center>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    data: function() {
      return {
        rfc: "",
        lco: [],
        hide: 0,
        errors: [],
        estado: "",
        paginate: ["lco"],
        certificado: ""
      };
    },

    methods: {
      checkForm: function(e) {
        this.errors = [];
        if (!this.rfc) {
            this.errors.push('RFC requerido');
          }
        if (!this.estado) {
          this.errors.push('Estado requerido');
        }
        else if (!this.validrfc(this.rfc)) {
          this.errors.push("RFC invalido");
        }
        if (!this.errors.length) {
          this.filtrar();
          return true;
        }
        e.preventDefault();
      },

      validrfc: function(rfc) {
        var re = /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
        return re.test(rfc);
      },

      filtrar: function() {
        let loader = this.$loading.show({
          container: this.fullPage ? null : this.$refs.formContainer
        });
        if(this.certificado == "" ){this.certificado = "null";}
        let uri5 =
          "https://timreports.expidetufactura.com.mx/tboreport/public/lco/" +
          this.rfc +
          "/" +
          this.certificado +
          "/" +
          this.estado +
          "";
        console.log(uri5);
        Axios.get(uri5).then(response => {
          this.lco = response.data;

          loader.hide();
          if (this.lco == "Vacio") {
            Vue.prototype.$vueOnToast.pop(
              "warning",
              "No se econtrarón registros",
              ""
            );
            this.hide = 1;
          } else {
            this.hide = 0;
          }
        });
      },

      exportExcel: function() {
        let uriLco =
          "https://timreports.expidetufactura.com.mx/tboreport/public/excelLco";
        window.location = uriLco;
      }
    }
  };
</script>
<style lang="scss">
  .paginate a {
    cursor: pointer;
  }
  .paginate li {
    text-align: center;
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    display: inline-block;
    margin: 0 10px;
    margin-top: 60px;
    font-size: 20px;
  }

  .paginate li.active a {
    font-weight: bold;
  }

  .tabla-lco table {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }

  .tabla-lco td,
  th {
    text-align: left;
    padding: 8px;
  }

  .tabla-lco tr:nth-child(even) {
    background-color: #ffe5e5;
  }
</style>
