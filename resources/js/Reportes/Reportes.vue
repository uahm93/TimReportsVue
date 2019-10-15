<template>
  <div>
    <toast-container></toast-container>
    <div class="nav-tabs-custom col-md-10">
      
      <ul class="nav nav-tabs pull-right">
        <li>
          <a href="#tab_1-1" data-toggle="tab" aria-expanded="false">
            <h4 class="box-title"><b>Total por RFC emisor</b></h4></a
          >
        </li>
        <li>
          <a href="#tab_2-2" data-toggle="tab" aria-expanded="false"
            ><h4 class="box-title"><b>Total por subcuentas</b></h4>
          </a>
        </li>
        <li class="pull-left header">
          <i class="fa fa-fw fa-file-text-o"></i> Total timbrado
        </li>
      </ul>
    
      <div class="tab-content" ref="formContainer">
        <div class="tab-pane active" id="tab_1-1">
          
            <div class="box box-primary" ref="formContainer">
              <div class="box-header with-border">
                <h3 class="box-title"><b>RFC Emisor</b></h3>

                <div class="box-tools pull-right"></div>
              </div>
              <div class="box box-default">
                <div class="row">
                  <form v-on:submit.prevent="filtrarEmisor">
                    <div class="col-md-6">
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
                          <option value="EXITO">Exitosos</option>
                          <option value="FALLIDO">Fallidos</option>
                          <option value="TOTAL">Totales</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <datetime
                          value-zone="local"
                          type="datetime"
                          v-model="datetimeI"
                          class="theme-orange"
                          placeholder="Ingrese fecha inicio"
                          :phrases="{ok: 'Aplicar', cancel: 'Cancelar'}"
                          title="Seleccione fecha y hora"
                          min-datetime="2018-01-01"
                          :max-datetime="fecha"
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
                          value-zone="local"
                          type="datetime"
                          v-model="datetimeF"
                          class="theme-orange"
                          placeholder="Ingrese fecha fin"
                          :phrases="{ok: 'Aplicar', cancel: 'Cancelar'}"
                          title="Seleccione fecha y hora"
                          :min-datetime="datetimeI"
                          :max-datetime="fecha"
                          required
                        ></datetime>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <br /><button
                        type="submit"
                        class="btn btn-primary btn-block"
                      >
                        <i class="fa fa-fw fa-filter"></i>Filtrar
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_2-2">
          
            <div class="box box-primary" ref="formContainer">
              <div class="box-header with-border">
                <h3 class="box-title"><b>Subcuentas</b></h3>

                <div class="box-tools pull-right"></div>
              </div>
              <div class="box box-default">
                <div class="row">
                  <form v-on:submit.prevent="filtrar">
                    <div class="col-md-6">
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
                          <option value="EXITO">Exitosos</option>
                          <option value="FALLIDO">Fallidos</option>
                          <option value="TOTAL">Totales</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <datetime
                          value-zone="local"
                          type="datetime"
                          v-model="datetimeI"
                          class="theme-orange"
                          placeholder="Ingrese fecha inicio"
                          :phrases="{ok: 'Aplicar', cancel: 'Cancelar'}"
                          title="Seleccione fecha y hora"
                          min-datetime="2018-01-01"
                          :max-datetime="fecha"
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
                          value-zone="local"
                          type="datetime"
                          v-model="datetimeF"
                          class="theme-orange"
                          placeholder="Ingrese fecha fin"
                          :phrases="{ok: 'Aplicar', cancel: 'Cancelar'}"
                          title="Seleccione fecha y hora"
                          :min-datetime="datetimeI"
                          :max-datetime="fecha"
                          required
                        ></datetime>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <br /><button
                        type="submit"
                        class="btn btn-primary btn-block"
                      >
                        <i class="fa fa-fw fa-filter"></i>Filtrar
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      
      <!-- /.tab-content -->
    </div>
    <div class="col-md-10" v-if="hide != '1'">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title"><b>Totales</b></h3>
        </div>
        <div class="box-body no-padding">
          <center>
            <table class="tabla-totalEmisor">
              <tbody>
                <th>Emisor</th>
                <th>Total</th>
                <tr class="tabla-totalEmisor" v-for="item in totalEmisor">
                  <td>{{ item.id }}</td>
                  <td>{{ item.total }}</td>
                </tr>
              </tbody>
              <br />
            </table>
          </center>
          <hr />
          <button v-on:click="exportExcel()" class="btn btn-success">
            <i class="fa fa-fw fa-file-excel-o"></i>Exportar a excel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    data: function() {
      return {
        hide: 1,
        estado: "",
        emisores: "",
        datetimeF: "",
        datetimeI: "",
        totalEmisor: []
      };
    },
    created: function() {

      let loader = this.$loading.show();
      let uri =
        "https://timreports.expidetufactura.com.mx/tboreport/public/emisores";
      Axios.get(uri).then(response => {
        this.emisores = response.data;
        loader.hide();
      });

      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1; //January is 0!
      var hh = today.getHours();
      var min = today.getMinutes();

      var yyyy = today.getFullYear();
      if (dd < 10) {
        dd = "0" + dd;
      }
      if (mm < 10) {
        mm = "0" + mm;
      }
      if (hh < 10) {
        hh = "0" + hh;
      }
      if (min < 10) {
        min = "0" + min;
      }
      this.fecha = yyyy + "-" + mm + "-" + dd + "T" + hh + ":" + min;
    },

    methods: {
      filtrar: function() {
        //FILTRO POR SUBCUENTAS

        let loader = this.$loading.show({
          container: this.fullPage ? null : this.$refs.formContainer
        });
        if(this.emisores == "CnSubcuentas"){ 
        let uri2 =
          "https://timreports.expidetufactura.com.mx/tboreport/public/totalEmisor/" +
          this.datetimeI +
          "/" +
          this.datetimeF +
          "/" +
          this.estado;
        Axios.get(uri2).then(response => {
          this.totalEmisor = response.data;
          loader.hide();
          if (this.totalEmisor == "Vacio") {
            Vue.prototype.$vueOnToast.pop(
              "warning",
              "No se econtraron registros",
              ""
            );

            this.hide = 1;
          } else {
            this.hide = 0;
          }
        });
       }else{
          
          Vue.prototype.$vueOnToast.pop(
              "warning",
              "No se econtrarón subcuentas",
              ""
            );
          loader.hide();
       }
      },

      filtrarEmisor: function() {
        //FILTRO POR RFC EMISOR

        let loader = this.$loading.show({
          container: this.fullPage ? null : this.$refs.formContainer
        });

        let uri2 =
          "https://timreports.expidetufactura.com.mx/tboreport/public/totalRFCEmisor/" +
          this.datetimeI +
          "/" +
          this.datetimeF +
          "/" +
          this.estado;
        Axios.get(uri2).then(response => {
          this.totalEmisor = response.data;
          loader.hide();
          if (this.totalEmisor == "Vacio") {
            Vue.prototype.$vueOnToast.pop(
              "warning",
              "No se econtraron registros",
              ""
            );
            this.hide = 1;
          } else {
            this.hide = 0;
          }
        });
      },

      exportExcel: function() {
        //Manda peticion para descargar el reporte en excel
        let uri5 =
          "https://timreports.expidetufactura.com.mx/tboreport/public/excelReportes";
        window.location = uri5;
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

  .tabla-totalEmisor table {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }

  .tabla-totalEmisor td,
  th {
    text-align: left;
    padding: 8px;
  }

  .tabla-totalEmisor tr:nth-child(even) {
    background-color: #ffe5e5;
  }

  .theme-orange .vdatetime-popup__header,
  .theme-orange .vdatetime-calendar__month__day--selected > span > span,
  .theme-orange .vdatetime-calendar__month__day--selected:hover > span > span {
    background: #318cb8;
  }

  .theme-orange .vdatetime-year-picker__item--selected,
  .theme-orange .vdatetime-time-picker__item--selected,
  .theme-orange .vdatetime-popup__actions__button {
    color: #318cb8;
  }
  .theme-orange input {
    width: 100%;
    padding: 10px 70px;
    cursor: pointer;
  }
</style>
