@extends('layouts.base_layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @php
                        if (session('message')):
                            $session = session('message');
                            $message = $session['message'];
                            $status = $session['status'];
                            <div class="alert alert-{{$status}}">
                                {{ $message) }}
                            </div>
                        endif
                    @endphp
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
