@extends('layouts.app')
@section('title', 'Home')

@section('style')
@endsection

@section('content')
    <br><br><br><br>
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10">
                <h2>Unlock new opportunities for your future</h2>
                <h1>Hello, ready to Make <br> a career Move?</h1>
            </div>
            <div class="col-12 col-lg-2">
                <a href="{{ route('front.about-us') }}"><img src="{{ asset('template/images/new/error.png') }}" alt="About"></a>
            </div>
            <div class="col-md-6 mt-3">
                <a href="{{ route('front.jobs.index') }}"><button><h1>Browse Jobs</h1></button></a>
            </div>
        </div>
        <h1 style="font-size:40px;text-align:center;" class="div1 mt-5">Latest Opportunities</h1>
    </div>

    <!-- <section class="latest-jobs">
        <div class="carousel-container">
            <div class="carousel">
                @foreach($vacancies as $vacancy)
                    @includeIf('components.vacancies.vacancy')
                @endforeach
            </div>
        </div>
    </section> -->

    <section class="latest-jobs">
        <div class="carousel-container">
            <div class="carousel">
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">java fullstact developer</p>
                        <p style="margin-left: -190px;" class="company">Wipro</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
    
    
    
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">QA engineer</p>
                        <p style="margin-left: -190px;" class="company">TCS</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
    
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">flutter developer</p>
                        <p style="margin-left: -190px;" class="company">DreamDev</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
    
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">Human Resources Administration</p>
                        <p style="margin-left: -190px;" class="company">DreamDev</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
    
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">Network Engineer</p>
                        <p style="margin-left: -190px;" class="company">Infosys</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
    
    
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">Software Developer</p>
                        <p style="margin-left: -190px;" class="company">DreamDev</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
    
    
    
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">UI UX Designer</p>
                        <p style="margin-left: -190px;" class="company">DreamDev Technologies</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">UI UX Designer</p>
                        <p style="margin-left: -190px;" class="company">DreamDev Technologies</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
    
    
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">UI UX Designer</p>
                        <p style="margin-left: -190px;" class="company">DreamDev Technologies</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
                <div class="card">
                    <div class="card-content">
                        <img class="business-icon" src="template\images\new\business_center.jpg">
                        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">UI UX Designer</p>
                        <p style="margin-left: -190px;" class="company">DreamDev Technologies</p>
                        <p class="time">1 day ago</p>
                        <p style="margin-top: 100px;" class="location"><img class="locate" src="template\images\new\location_on.jpg"> Hyderabad</p>

                        <a style="float: right;" href="job.html" class="view-link">View &rarr;</a>
                    </div>
                </div>
    
                <!-- Repeat the card structure as needed -->
            </div>
        </div>
    
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carouselContainer = document.querySelector('.carousel-container');
            carouselContainer.addEventListener('wheel', (event) => {
                event.preventDefault();
                carouselContainer.scrollBy({   
                    left: event.deltaY < 0 ? -100 : 100,
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('template/images/new/business.png') }}" alt="Business Situation">
            </div>
            <br><br>

            <div class="col-md-6">
                <h1>Create Impact <br> with your online <br> Resume!</h1><br>
                <p>Your Online Resume is your chance to shine. Highlight your skills and make a lasting impression to
                    unlock new opportunities. Start making an impact today!</p>
            </div>
        </div>
    </div>

@endsection
@section('script')
@endsection