<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Persona;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $personas = Persona::orderBy('id', 'desc')
            ->where('clientec','=','0')
            ->paginate(5);
        } else {
            $personas = Persona::where($criterio,'like','%'.$buscar.'%')
            ->where('clientec','=','0')
            ->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total' => $personas->total(), 
                'current_page' => $personas->currentPage(),
                'per_page' => $personas->perPage(),
                'last_page' => $personas->lastPage(),
                'from' => $personas->firstItem(),
                'to' => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->apellido = $request->apellido;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->email = $request->email;
        $persona->telefono = $request->telefono;
        $persona->direccion = $request->direccion;
        $persona->razon_social = $request->razon_social;
        $persona->nombre_empresa = $request->nombre_empresa;
        $persona->clientec = '0';
        $persona->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->apellido = $request->apellido;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->email = $request->email;
        $persona->telefono = $request->telefono;
        $persona->direccion = $request->direccion;
        $persona->nombre_empresa = $request->nombre_empresa;
        $persona->razon_social = $request->razon_social;
        $persona->clientec = '0';
        $persona->save();
    }
}
