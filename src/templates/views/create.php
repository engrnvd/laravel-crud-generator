@extends('layouts.app-btsp')

@section('content')

    <h2>Create a New Profile</h2>

    <form action="/profile" method="post">

        {{ csrf_field() }}

        {!! \Nvd\Crud\Form::input("name")->show() !!}

        {!! \Nvd\Crud\Form::input("dob","date")->label('Date of Birth')->show() !!}

        {!! \Nvd\Crud\Form::select( "is_a_good_person", [ "Yes", "No" ] )->show() !!}

        {!! \Nvd\Crud\Form::textarea( "about" )->label(false)->attributes(['placeholder' => 'Tell us about this user'])->show() !!}

        {!! \Nvd\Crud\Form::select( "gender", [ "Male","Female" ] )->show() !!}

        <button type="submit" class="btn btn-default">Submit</button>

    </form>

@endsection