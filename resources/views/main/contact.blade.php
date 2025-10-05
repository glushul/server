@extends('main.layout')
@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <h3>Contacts</h3>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $contact['name'] }}</p>
            <p><strong>Address:</strong> {{ $contact['adress'] }}</p>
            <p><strong>Phone:</strong> {{ $contact['phone'] }}</p>
        </div>
    </div>
@endsection