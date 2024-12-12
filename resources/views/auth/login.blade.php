@extends('layouts.app')
@section('content')

    <section class="log-in nw">
        @includeIf('front.components.auth.login')
    </section>
@endsection

