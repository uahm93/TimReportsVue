<?php

namespace App\Http\Controllers;
use Auth;
use App\d_usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //  public function __construct()
    //  {
    //       $this->middleware('auth');
    //  }

    
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        
         $data  = session()->get('key');                    
         session()->put('user', $data['usuario']);
         session()->put('no_usuario', $data['iu_usuario']);
         session()->put('timbrado_disp', $data['timbra_disponible']);
         session()->put('vigencia', $data['fechah_fin']);  
        
        if(session()->has('key')){

            return view('dashboard');
           
        }else{
            
            return redirect('/');
        }                
    }

    public function superuser()
    {   
          
        if(session()->has('key')){

            return view('superUser');
            
           
        }else{
            
            return redirect('/');
        }                
    }

    public function accesoTotal(Request $request)
    {   
        $usuario = $request['usuario'];
        session()->put('no_usuario', $usuario);
        return view('dashboard');               
    }

}
