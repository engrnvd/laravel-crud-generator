@extends('layouts.app-btsp')

@section('content')

    <h2>Profile: {{$profile->name}}</h2>

    <ul class="list-group">
        <li class="list-group-item">
            <h4>Name</h4>
            <h5>{{$profile->name}}</h5>
        </li>
        <li class="list-group-item">
            <h4>DOB</h4>
            <h5>{{$profile->dob}}</h5>
        </li>
        <li class="list-group-item">
            <h4>About</h4>
            <h5>{{$profile->about}}</h5>
        </li>
        <li class="list-group-item">
            <h4>Gender</h4>
            <h5>{{$profile->gender}}</h5>
        </li>
    </ul>

@endsection