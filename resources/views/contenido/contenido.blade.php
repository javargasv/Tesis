@extends('principal')
@section('contenido')

           
@if(Auth::check())
            @if(Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <h1>Inicio</h1>
            </template>
            <template v-if="menu==1">
                <articulo></articulo>
            </template>
            <template v-if="menu==5">
                <user></user>
            </template>
            <template v-if="menu==6">
                <rol></rol>
            </template>
            <template v-if="menu==7">
                <h1>Reporte de ventas</h1>
            </template>
            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==0">
                <h1>Inicio</h1>
            </template>

            <template v-if="menu==2">
                <venta></venta>
            </template>

            <template v-if="menu==3">
                <cliente></cliente>
            </template>

            <template v-if="menu==4">
                <h1>Contenido menú 4</h1>
            </template>

            <template v-if="menu==7">
                <h1>Contenido menú 7</h1>
            </template>

            <template v-if="menu==8">
                <h1>Contenido menú 8</h1>
            </template>

            <template v-if="menu==9">
                <h1>Contenido menú 9</h1>
            </template>
            @else
            @endif
        @endif()

@endsection