@extends('auth.contenido')

@section('login')

<div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
          <form class="form-horizontal was-validated" method="POST" action="{{route('login')}}">
            {{ csrf_field() }}
            <div class="card-body">
              <h1>Bienvenido/a</h1>
              <p class="text-muted">Control de acceso al sistema</p>
              <div class="input-group mb-3{{$errors->has('usuario' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" value="{{old('usuario')}}" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
                {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!}
              </div>
              <div class="input-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="icon-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
                {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
              </div>
              <div class="row">
                <div class="col-6">
                  <button type="submit" class="btn btn-primary px-4">Acceder</button>
                </div>
              </div>
            </div>
          </form>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Bienvenido al sistema integrafi</h2>
                <p>Si aún no haces parte de nosotros, registrate</p>
                <a href="{{ url('register') }}"><button class="btn btn-success">Aquí</button></a>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>


<style>

.input-group{
  display: block !important;
}

.input-group-addon{
  width: 100% !important;
}

.form-control {
  width: 100% !important;
}

</style>

@endsection