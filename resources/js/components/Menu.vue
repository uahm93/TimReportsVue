<template>     
         <div>          
           
             <aside class="main-sidebar">
              <!-- sidebar: style can be found in sidebar.less -->
              <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header"><b>Navegación principal</b></li>
                    <li>
                        <router-link v-bind:to="{name: 'Historicos'}"><i class="fa fa-fw fa-area-chart"></i><span> <b> Resumen histórico </b></span></router-link>  
                      </a>
                    </li>                            
                    <li>
                      <router-link v-bind:to="{name: 'Bitacora'}"><i class="fa fa-fw fa-file-text-o"></i><span> <b> Bitácora </b></span></router-link> 
                      </a>  
                    </li> 
                    <li>
                        <router-link  v-bind:to="{name: 'Boveda'}"><i class="fa fa-fw fa-archive"></i><span> <b> Bóveda </b> </span></router-link> 
                      </a>  
                    </li>
                    <li>
                        <router-link v-bind:to="{name: 'Lco'}"><i class="fa fa-fw fa-file-text"></i><span><b>  LCO </b> </span></router-link> 
                      </a>  
                    </li>
                    <li>
                        <router-link v-bind:to="{name: 'Inscritos'}"><i class="fa fa-fw fa-pencil"></i><span><b>  RFC's Inscritos </b> </span></router-link> 
                      </a>  
                    </li>
                        <li><router-link v-bind:to="{name: 'Reportes'}" ><i class="fa fa-fw fa-users"></i><span> <b> Subcuentas </b> </span></router-link></li>
                </ul>
                
              </section>
              <!-- /.sidebar -->
          </aside>

         </div>             
</template>

    