<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $roles = Rol::orderBy('id', 'desc')->paginate(5);
        
        return [
            'roles' => $roles
        ];
    }

    public function selectRol(Request $request){
        $roles = Rol::where('condicion','=','1')
        ->select('id','nombre')
        ->orderBy('nombre','asc')->get();

        return ['roles'=> $roles];
    }
}
