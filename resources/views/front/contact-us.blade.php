@extends('layouts.app')
@section('title', 'Contact Us')

@section('style')
@endsection

@section('content')
   
        <div class="dotts">
            <div class="header-top">
            </div>
            
            </div>
            <br><br><br><br>
            <img src="{{ asset('images/contact1.png') }}" class="img-fluid" style="height:auto"/>

        
   
    <ol class="breadcrumb justify-content-left">
        <li class="breadcrumb-item">
            <a href="{{ route('front.index') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">Contact Us</li>
    </ol>

    @php
        $pageSetting = pageSetting('contact');
    @endphp

    <section style="background-color:white" class="banner-bottom-wthree pt-lg-5 pt-md-3 pt-3">
        <div class="inner-sec-w3ls pt-md-5 pt-md-3 pt-3">
            <h3 class="tittle text-center mb-lg-5 mb-3"> <span>Get Intouch</span>Contact Us</h3>
            <p style="margin-left:20px; margin-right:20px" class="text-center">We're here to help and answer any questions you may have. Reach out to us and we'll respond as soon as we can.</p>
            <div class="container mt-5">
            <div class="container">
    <div class="row">
        <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3805.3798852341097!2d78.39038297516726!3d17.48937008341527!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTfCsDI5JzIxLjciTiA3OMKwMjMnMzQuNyJF!5e0!3m2!1sen!2sin!4v1708164324617!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    </div>
<br><br><br>
                <div class="address row mb-5">
                    <div class="col-lg-4 address-grid">
                        <div class="row address-info">
                            <div class="col-md-3 address-left text-center">
                                <i class="far fa-map"></i>
                            </div>
                            
                            <div class="col-md-9 address-right text-left">
                                <h6 class="ad-info text-uppercase mb-2">Address</h6>
                                <p>613, Manjeera Trinity Corporate,<br>
                        KPHB, Kukutpally,<br>
                        Hyderabad, Telangana, India<br>
                        Pin Code:500085
                    </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 address-grid">
                        <div class="row address-info">
                            <div class="col-md-3 address-left text-center">
                                <i class="far fa-envelope"></i>
                            </div>
                            <div class="col-md-9 address-right text-left">
                                <h6 class="ad-info text-uppercase mb-2">Email Us</h6>
                                <p>Email : info@dreamdevtechs.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 address-grid">
                        <div class="row address-info">
                            <div class="col-md-3 address-left text-center">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="col-md-9 address-right text-left">
                                <h6 class="ad-info text-uppercase mb-2">Call Us</h6>
                                <p>+91 74163 15483</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mb-5">
                    <h3>We look forward to connecting with you!</h3>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
@endsection