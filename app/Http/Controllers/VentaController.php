<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Venta;
use App\DetalleVenta;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Notifications\NotifyAdmin;

class VentaController extends Controller {

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $idusuario = Auth::id();
        $rolusuario = Auth::user()->idrol;

        if($rolusuario = Auth::user()->idrol=='2') {
            if ($buscar==''){
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.fecha_hora' ,'ventas.total',
                'ventas.estado','personas.nombre','users.usuario')
                ->orderBy('ventas.id', 'desc')->paginate(5);
            }
            else{
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.fecha_hora','ventas.total',
                'ventas.estado','personas.nombre','users.usuario')
                ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
                ->orderBy('ventas.id', 'desc')->paginate(5);
            }
            
            return [
                'pagination' => [
                    'total'        => $ventas->total(),
                    'current_page' => $ventas->currentPage(),
                    'per_page'     => $ventas->perPage(),
                    'last_page'    => $ventas->lastPage(),
                    'from'         => $ventas->firstItem(),
                    'to'           => $ventas->lastItem(),
                ],
                'ventas' => $ventas
            ];
        }

        elseif($rolusuario = Auth::user()->idrol=='3') {
            if (!$request->ajax()) return redirect('/');

            $buscar = $request->buscar;
            $criterio = $request->criterio;
            $idusuario = Auth::id();
            
            if ($buscar==''){
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.fecha_hora' ,'ventas.total',
                'ventas.estado','personas.nombre','users.usuario')
                ->where('ventas.idcliente','=',$idusuario)
                ->orderBy('ventas.id', 'desc')->paginate(5);
            }
            else{
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.fecha_hora','ventas.total',
                'ventas.estado','personas.nombre','users.usuario')
                ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
                ->orderBy('ventas.id', 'desc')->paginate(5);
            }
            
            return [
                'pagination' => [
                    'total'        => $ventas->total(),
                    'current_page' => $ventas->currentPage(),
                    'per_page'     => $ventas->perPage(),
                    'last_page'    => $ventas->lastPage(),
                    'from'         => $ventas->firstItem(),
                    'to'           => $ventas->lastItem(),
                ],
                'ventas' => $ventas
            ];
        }
        
    }

    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.fecha_hora','ventas.total',
        'ventas.estado','personas.nombre','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();
        
        return ['venta' => $venta];
    }

    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->select('detalle_ventas.leyenda1','detalle_ventas.leyenda2','detalle_ventas.leyenda3','detalle_ventas.leyenda4','detalle_ventas.cantidad','detalle_ventas.precio',
        'articulos.nombre as articulo')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();
        
        return ['detalles' => $detalles];
    }

    public function pdf(Request $request, $id){

        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.fecha_hora' ,'ventas.total',
        'ventas.estado','personas.nombre','users.usuario','personas.tipo_documento','personas.num_documento','personas.telefono','personas.direccion','personas.email','ventas.created_at')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->select('detalle_ventas.leyenda1','detalle_ventas.leyenda2','detalle_ventas.leyenda3','detalle_ventas.leyenda4','detalle_ventas.cantidad','detalle_ventas.precio',
        'articulos.nombre as articulo')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();

        $idventa=Venta::Select('id')->where('id',$id)->get();
        $pdf = \PDF::loadView('pdf.venta',['venta'=>$venta,'detalles'=>$detalles]);
        return $pdf->download('venta-'.$idventa[0]->id.'.pdf');
    }

    public function store(Request $request)
    {       

        if($rolusuario = Auth::user()->idrol=='2') {

            if (!$request->ajax()) return redirect('/');

            try{
                DB::beginTransaction();

                $mytime= Carbon::now('America/Bogota');

                $venta = new Venta();
                $venta->idcliente = $request->idcliente;
                $venta->idusuario = \Auth::user()->id;
                $venta->fecha_hora = $mytime->toDateString();
                $venta->total = $request->total;
                $venta->estado = 'Registrado';
                $venta->save();

                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos

                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetalleVenta();
                    $detalle->idventa = $venta->id;
                    $detalle->idarticulo = $det['idarticulo'];
                    $detalle->leyenda1 = $det['leyenda1'];
                    $detalle->leyenda2 = $det['leyenda2'];
                    $detalle->leyenda3 = $det['leyenda3'];
                    $detalle->leyenda4 = $det['leyenda4'];
                    $detalle->cantidad = $det['cantidad'];
                    $detalle->precio = $det['precio'];     
                    $detalle->save();
                }          
                    DB::commit();
                } catch (Exception $e){
                    DB::rollBack();
                }
        }
        elseif($rolusuario = Auth::user()->idrol=='3') {
            
            if (!$request->ajax()) return redirect('/');

            try{
                DB::beginTransaction();

                $idcl = Auth::id();

                $mytime= Carbon::now('America/Bogota');

                $venta = new Venta();
                $venta->idcliente = $idcl;
                $venta->idusuario = \Auth::user()->id;
                $venta->fecha_hora = $mytime->toDateString();
                $venta->total = $request->total;
                $venta->estado = 'Registrado';
                $venta->save();

                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos

                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetalleVenta();
                    $detalle->idventa = $venta->id;
                    $detalle->idarticulo = $det['idarticulo'];
                    $detalle->leyenda1 = $det['leyenda1'];
                    $detalle->leyenda2 = $det['leyenda2'];
                    $detalle->leyenda3 = $det['leyenda3'];
                    $detalle->leyenda4 = $det['leyenda4'];
                    $detalle->cantidad = $det['cantidad'];
                    $detalle->precio = $det['precio'];     
                    $detalle->save();
                }          
                
                $numVentas = DB::table('ventas')->where('created_at', $mytime)->count();

                $arregloDatos = [
                    'ventas' => [
                        'numero' => $numVentas,
                        'msg' => 'Ventas'        
                    ]
                ];

                $allUsers = User::all();
                foreach ($allUsers as $notificar){
                    User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos));
                }
                    DB::commit();
                } catch (Exception $e){
                    DB::rollBack();
                }
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Rechazado/cancelado';
        $venta->save();
    }

    public function verCliente(){
        $rolusuario = Auth::user()->idrol;

        return $rolusuario;
    }
}

