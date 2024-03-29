@extends('principal')
@section('contenido')

           
@if(Auth::check())
            @if(Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <inicio></inicio>
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
                <dashboard></dashboard>
            </template>
            <template v-if="menu==4">
                <ayudaadmin></ayudaadmin>
            </template>
            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==0">
                <inicio></inicio>
            </template>

            <template v-if="menu==2">
                <venta></venta>
            </template>

            <template v-if="menu==3">
                <cliente></cliente>
            </template>

            <template v-if="menu==7">
                <dashboard></dashboard>
            </template>

            <template v-if="menu==8">
                <Ayudaejecutivo></Ayudaejecutivo>
            </template>

            @elseif (Auth::user()->idrol == 3)
            <template v-if="menu==0">
                <inicio>Inicio</inicio>
            </template>

            <template v-if="menu==2">
                <venta></venta>
            </template>

            <template v-if="menu==9">
                <Ayudacliente></Ayudacliente>
            </template>
            @else
            @endif
        @endif()

@endsection