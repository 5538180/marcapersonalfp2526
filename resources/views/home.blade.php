@extends('dopetrope.master')

@section('menu')
    @parent
    <p>¡Hola {{ $nombre ?? 'colega' }}!</p>
@endsection

@section('content')
    <h1>La hora actual es {{ now() }}.</h1>

@if (count($users) === 1)
    <p>Solo hay un usuario!</p>
@elseif (count($users) > 1)
    <p>Hay muchos usuarios!</p>
@else
    <p>No hay ningún usuario :(</p>
@endif
<ul>
    @include('users.usersList', ["users" => $users])
</ul>
@endsection
