<template>     
     <div>                
     <Header></Header>
      <Menu></Menu>  
	      <div class="row">
		      <section class="content">
		       <div class="content-wrapper">
		         <div class="container">
		            <transition name="fade">
		              <router-view></router-view> 
		            </transition>
		         </div>
		       </div>
		      </section>
	     </div>     
     </div>        
</template>