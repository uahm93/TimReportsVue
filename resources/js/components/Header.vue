<template>
  <div>
    <header class="main-header">
      <!-- Logo -->
      <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>XPD</b></span>
        <!-- logo for regular state and mobile devices -->
        <span><img src="https://timreports.expidetufactura.com.mx/tboreport/public/images/default.png" alt="LOGO XPD"></span>
      </a>

      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a
          href="#"
          class="sidebar-toggle"
          data-toggle="push-menu"
          role="button"
        >
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->

            <!-- Notifications: style can be found in dropdown.less -->
            <li class="dropdown notifications-menu ">
              <a
                href="#"
                class="dropdown-toggle disabled"
                data-toggle="dropdown"
                aria-expanded="true"

              >
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning"><i class="fa fa-fw fa-times-circle"></i></span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">
                  <h4><b>Notificaciones</b></h4>
                </li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu">
                    <li>
                      <a href="#" data-toggle="modal" data-target="#myModal"
                        ><i class="fa fa-fw fa-eye"></i> Ver notificaciones</a
                      >
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a
                href="#"
                class="dropdown-toggle"
                data-toggle="dropdown"
                aria-expanded="false"
              >
                <span class="hidden-xs"></span> <i class="fa fa-fw fa-user"></i
                ><i class="fa fa-fw fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <h2 v-for="item in usuario">{{ item.usuario }}</h2>

                  <p>
                    <font color="grey"
                      >Fecha de registro :
                      <small v-for="item in usuario">{{ item.fechah_registro }}</small>
                    </font>
                  </p>
                  <p>
                    <font color="grey"
                      >Timbrado disponible:
                      <small v-for="item in usuario"><b v-if="item.postpago == '1'" >Ilimitado</b>
                      <b v-else>{{ item.timbra_disponible }}</b> 
                      </small>
                    </font>
                  </p>
                </li>

                <!-- Menu Footer-->
                <div class="pull-right">
                  <button class="btn btn-default btn-flat" v-on:click="logout()"><i class="icon ion-power"></i> Salir </button>
                </div>
                <div class="pull-left">
                  <router-link v-bind:to="{name: 'Facturas'}" class="btn btn-default btn-flat"
                    ><i class="fa fa-fw fa-file-text-o"></i> Facturas </router-link> 
                </div>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <section class="content-header">
  <!-- Modal -->
  <div
    class="modal fade"
    id="myModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
          <br />
          <h3 class="modal-title" id="myModalLabel">
            <b>Expide tu factura</b>
          </h3>
        </div>
        <div class="modal-body">
          <img
            class="img-responsive pad"
            src="https://xpdportal1.expidetufactura.com.mx:8443/CFDI33/static/resources/img/AnuncioFronteraNorte.jpg;jsessionid=xtBaTZzBycibV7A-QAMEp0D1.undefined"
            width="550"
            height="500"
          />
          <h4><b>Ejemplo notificaciones</b></h4>
          <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
            ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
            aliquip ex ea commodo consequat. Duis aute irure dolor in
            reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
            pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
            culpa qui officia deserunt mollit anim id est laborum.
          </p>

          <img
            class="img-responsive pad"
            src="https://xpdportal1.expidetufactura.com.mx:8443/CFDI33/static/resources/img/AnuncioFronteraNorte.jpg;jsessionid=xtBaTZzBycibV7A-QAMEp0D1.undefined"
            width="550"
            height="500"
          />

          <h4><b>Ejemplo notificaciones</b></h4>
          <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
            ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
            aliquip ex ea commodo consequat. Duis aute irure dolor in
            reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
            pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
            culpa qui officia deserunt mollit anim id est laborum.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

  </div>
</template>
<script>
  export default{
      data:function(){
          return {   
                  usuario: []
                };
      },
      created: function(){          

             let uri = 'https://balanceadorzeus.expidetufactura.com.mx/tboreport/public/obtenerUsuario';
             Axios.get(uri).then((response) => {
              this.usuario = response.data;              
          });           
      },
      methods:{
        
          logout: function(){
              
              let uri = 'https://timreports.expidetufactura.com.mx/tboreport/public/logout';
              //alert(uri);
              window.location=uri;
            
        },
             
      }
  }
  
</script>