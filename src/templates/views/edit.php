@extends('layouts.app-btsp')

@section('content')

    <h2>Update Profile: {{$profile->name}}</h2>

    <form action="/profile/{{$profile->id}}" method="post">

        {{ csrf_field() }}

        {{ method_field("PUT") }}

        {!! \Nvd\Crud\Form::input("name")->model($profile)->show() !!}

        {!! \Nvd\Crud\Form::input("dob","date")->model($profile)->label('Date of Birth')->show() !!}

        {!! \Nvd\Crud\Form::select( "is_a_good_person", [ "Yes", "No" ] )->model($profile)->show() !!}

        {!! \Nvd\Crud\Form::textarea( "about" )->model($profile)->label(false)->attributes(['placeholder' => 'Tell us about this user'])->show() !!}

        {!! \Nvd\Crud\Form::select( "gender", [ "Male", "Female" ] )->model($profile)->show() !!}

        <button type="submit" class="btn btn-default">Submit</button>

    </form>

@endsection