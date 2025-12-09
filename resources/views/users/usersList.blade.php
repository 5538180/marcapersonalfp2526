@foreach ($users as $user)
    <li>Usuario {{ $user["name"] }} con identificador: {{ $user["id"] }}</li>
@endforeach
