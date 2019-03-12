<?php

namespace App\Http\Controllers;
use Auth;
use App\d_usuario;

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
         session()->put('timbrado_disp', $data['timbra_disponible']);  
        if($data != null){
            return view('dashboard');
        }else{
            return view ('welcome');
        }
        

        
    }
}
