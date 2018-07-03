@extends('layouts.app')

@section('content')
    <form action="/auth/facebook/register" method="post">
        {{ csrf_field() }}
        <div class="card">
            <div class="card-block">
                <img class="img-thumbnail" src="{{ $user->avatar }}" alt="">                
            </div>
            <div class="card-block">
                <div class="form-group">
                    <label for="name" class="form-control-label">
                        Nombre
                    </label>
                    <input class="form-control" type="text" name="name" id="" value="{{ $user->name }}" readonly>
                </div>

                <div class="form-group">
                    <label for="name" class="form-control-label">
                        Email
                    </label>
                    <input class="form-control" type="text" name="email" id="" value="{{ $user->email }}" readonly>
                </div>

                <div class="form-group">
                    <label for="name" class="form-control-label">
                        Nombre de usuario
                    </label>
                    <input class="form-control" type="text" name="username" id="" value="{{ old('username') }}">
                </div>
            </div>
            <div>
                <button class="btn btn-primary" type="submit">
                    Registrarse
                </button>
            </div>
        </div>
    </form>
@endsection