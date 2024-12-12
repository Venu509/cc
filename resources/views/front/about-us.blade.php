@extends('layouts.app')
@section('title', 'About Us')

@section('style')
@endsection

@section('content')
    <section class="about">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-9">
                    <h2>About</h2>
                    <h1>Building Bridges Between <br> Employers and Talent</h1>
                    <p>Welcome to Dream Career Telangana, your bridge between students, colleges, and companies. We connect skilled talent with top opportunities, empowering the next generation of professionals.</p>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('front.index') }}"><img src="{{ asset('template/images/new/cancel.png') }}" alt="Close"></a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
@endsection