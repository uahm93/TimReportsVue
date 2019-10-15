<template>
  <div ref="formContainer">
    <div class="nav-tabs-custom col-md-10">
      <ul class="nav nav-tabs pull-right">
        <li class="">
          <a href="#tab_1-1" data-toggle="tab" aria-expanded="false"
            >Resumen <i class="fa fa-fw fa-angle-down"></i
          ></a>
        </li>
        <li class="pull-left header">
          <i class="fa fa-fw fa-file-text-o"></i> Facturas
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1-1">
          <div class="row">
            <div class="col-xs-12">
              <i class="page-header">Detalles de cuenta</i>
              <small class="pull-right">Fecha: {{ fecha }}</small>
            </div>
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Última factura</th>
                <th>Saldo Actual</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td v-for="item in usuario">{{ item.usuario }}</td>
                <td v-for="item in ultimaFactura">
                  {{ item.fechah_timbrado }}
                  <button
                    type="button"
                    style="margin-right: 1px;"
                    class="btn btn-social-icon btn-bitbucket"
                    v-on:click="obtenerxml( item.xml, item.fechah_timbrado )"
                  >
                    <i class="fa fa-fw fa-file-code-o"></i>
                  </button>
                  <button
                    type="button"
                    style="margin-right: 1px;"
                    class="btn btn-social-icon btn-google"
                    v-on:click="obtenerPDF( item.xml, item.fechah_timbrado )"
                    alt="Obtener PDF"
                  >
                    <i class="fa fa-fw fa-file-pdf-o"></i>
                  </button>
                </td>
                <td>$ 0.00</td>
              </tr>
            </tbody>
            <thead>
              <tr>
                <th>Metodo de pago actual</th>
                <th>Factura actual</th>
                <th>Siguiente saldo estimado</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Manual</td>
                <td>{{ fecha }}</td>
                <td v-for="item in ultimaFactura">
                  $ {{ item.monto | formatNumber }}
                </td>
              </tr>
            </tbody>
          </table>
          <div class="col-md-10">
            <div class="box box-primary" v-if="obtenerFacturas != ''">
              <div class="box-header">
                <h3 class="box-title"></h3>
              </div>
              Mostrar
              <select style="width: 5%;" v-model.number="per">
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
              Registros
              <div class="box-body no-padding" style="overflow: scroll">
                <center>
                  <table class="tabla-facturas">
                    <tbody>
                      <paginate
                        name="obtenerFacturas"
                        :list="obtenerFacturas"
                        :per="per"
                      >
                        <th>Id factura</th>
                        <th>Fecha creación</th>
                        <th>Importe de factura</th>
                        <th>Archivos</th>
                        <tr
                          v-for="item in paginated('obtenerFacturas')"
                          class="tabla-facturas"
                        >
                          <td>{{ item.iu_timbrado }}</td>
                          <td>{{ item.fechah_timbrado }}</td>
                          <td>$ {{ item.monto }}</td>
                          <td>
                            <center>
                              <button
                                type="button"
                                style="margin-right: 1px;"
                                class="btn btn-social-icon btn-bitbucket"
                                v-on:click="obtenerxml( item.xml, item.fechah_timbrado )"
                              >
                                <i class="fa fa-fw fa-file-code-o"></i>
                              </button>
                              <button
                                type="button"
                                style="margin-right: 1px;"
                                class="btn btn-social-icon btn-google"
                                v-on:click="obtenerPDF( item.xml, item.fechah_timbrado )"
                                alt="Obtener PDF"
                              >
                                <i class="fa fa-fw fa-file-pdf-o"></i>
                              </button>
                            </center>
                          </td>
                        </tr>
                      </paginate>
                    </tbody>
                    <paginate-links
                      for="obtenerFacturas"
                      :limit="15"
                      class="paginate"
                      :show-step-links="true"
                    ></paginate-links>
                  </table>
                </center>
              </div>
            </div>
          </div>
        </div>

        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_2-2"></div>
      </div>
      <!-- /.tab-content -->
    </div>
  </div>
</template>
<script>
  export default {
    data: function() {
      return {
        per: 5,
        fecha: "",
        usuario: [],
        facturas: [],
        obtenerFacturas: [],
        paginate: ["obtenerFacturas"],
        ultimaFactura: []
      };
    },
    created: function() {
      var numeral = require("numeral");

      Vue.filter("formatNumber", function(value) {
        return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
      });

      let loader = this.$loading.show({ container: this.fullPage ? null : this.$refs.formContainer  });

      let uri =
        "https://timreports.expidetufactura.com.mx/tboreport/public/obtenerUsuario";
      Axios.get(uri).then(response => {
        this.usuario = response.data;
      });
      let uri2 =
        "https://timreports.expidetufactura.com.mx/tboreport/public/ultimaFactura";
      Axios.get(uri2).then(response => {
        this.ultimaFactura = response.data;
      });
      let uri3 =
        "https://timreports.expidetufactura.com.mx/tboreport/public/obtenerFacturas";
      Axios.get(uri3).then(response => {
        this.obtenerFacturas = response.data;
        loader.hide();
      });
    },
    mounted() {
      var meses = new Array(
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
      );
      var f = new Date();
      this.fecha =
        f.getDate() + "/" + meses[f.getMonth()] + "/" + f.getFullYear();
    },
    methods: {
      obtenerxml: function(ruta, fecha) {
        var encode = window.btoa(ruta);
        let uri =
          "https://timreports.expidetufactura.com.mx/tboreport/public/xml/" +
          encode +
          "/" +
          fecha;
        window.location = uri;
        //console.log(uri);
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

  .tabla-facturas table {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }

  .tabla-facturas th {
    text-align: left;
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #262b69;
    color: white;
  }

  .tabla-facturas td {
    text-align: left;
    padding: 8px;
  }

  .tabla-facturas tr:nth-child(even) {
    background-color: #ddd;
  }
</style>
