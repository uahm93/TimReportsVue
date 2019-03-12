<?php

namespace App\Http\Controllers;


use Auth;
use App\d_usuario;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;



class LoginController extends Controller
{
    //
    

    
    public function login(Request $request){
        $pw  = $request->contrasena;
        $contrasena_cifrada = sha1($pw);
        $credentials = [
                        'usuario'    => $request['usuario'],
                        'contrasena' => $contrasena_cifrada
                         ];
          // dd($credentials);

          
        if (Auth::attempt($credentials)) {
             // Authentication passed...
             //$user = User::where('d_usuario', $request->d_usaurio)->first();                           
             // dd(Auth::check());
             
             $user = Auth::user();
             $usuario = session()->put('key', $user);                                      
             return redirect()->route('index');                
        }else{            
            return view ('welcome');
        }
    }

    public function salir(){

        session()->flush();   
        echo "<script>window.location='/';</script>";

    }
    
    
}
