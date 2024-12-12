@extends('layouts.app')
@section('title', 'Jobs')

@section('style')
@endsection

@section('content')
    <section class="new">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-4 col-md-2 text-left">
                    <a href="{{ route('front.index') }}"><h3><img
                                    src="{{ asset('template/images/new/arrow_back.jpg') }}" alt="Back"> Back</h3></a>
                </div>
                <div class="col-4 col-md-8 text-center">
                    <h1>{{ $vacancy['title'] }}</h1>
                </div>
                <div class="col-4 col-md-2 text-right">
                    <img src="{{ asset('template/images/new/bookmark.jpg') }}" alt="Bookmark">
                    &nbsp;&nbsp;&nbsp;
                    <img src="{{ asset('template/images/new/send.jpg') }}" alt="Send">
                </div>
            </div>
        </div>
    </section>
    <br><br><br>

    <section class="section-one">
        <div class="info-section">
            <div class="info-card">
                <div class="info-item">
                    <img src="{{ asset('template/images/new/job.png') }}">
                    &nbsp;&nbsp; &nbsp;&nbsp; <span>{{ $vacancy['yearsOfExperiences'] }} Years</span>
                </div>
                <div class="info-item">
                    <img src="{{ asset('template/images/new/Cash.png') }}">
                    &nbsp;&nbsp; &nbsp;&nbsp; <span>{{ $vacancy['salary'] }} - {{ $vacancy['salaryFrequency'] }}</span>
                </div>
                <div class="info-item">
                    <img src="{{ asset('template/images/new/distance.png') }}">
                    &nbsp;&nbsp; &nbsp;&nbsp; <span>
                        @forelse($vacancy['locations'] as $location)
                            {{ $location }} ,
                        @empty
                            No locations available
                        @endforelse
                    </span>
                </div>
                <div class="info-item">
                    <img src="{{ asset('template/images/new/Brain.png') }}">
                    &nbsp;&nbsp; &nbsp;&nbsp; <span>
                        @forelse($vacancy['keySkills'] as $skill)
                            {{ $skill['title'] }} ,
                        @empty
                            No skills available
                        @endforelse
                    </span>
                </div>
            </div>
        </div>
    </section>
    <br><br><br>

    <section class="sectoin-tabs">
        <div class="container">
            <div class="row">
                <div class="tabs">
                    <div class="tab active" onclick="showTab('description', event)">Description</div>
                    <div class="tab" onclick="showTab('job-brief', event)">Job Brief</div>
                    <div class="tab" onclick="showTab('contact-details', event)">Contact Details</div>
                </div>

                <div id="description" class="tab-content active">
                    <p>{!! $vacancy['description'] !!}</p>
                </div>

                <div id="job-brief" class="tab-content">
                    <div class="row">
                        <div class="col-md-4 box">
                            <h4>Qualifications</h4>
                            <p>
                                @forelse($vacancy['qualifications'] as $qualification)
                                    {{ $qualification }} <br>
                                @empty
                                    No qualifications available
                                @endforelse
                            </p>
                        </div>
                        <div class="col-md-4 box">
                            <h4>Employee Type</h4>
                            <p>
                                @forelse($vacancy['workModes'] as $workMode)
                                    {{ $workMode }} <br>
                                @empty
                                    No work modes available
                                @endforelse
                            </p>
                        </div>
                        <div class="col-md-4 box">
                            <h4>Category</h4>
                            <p>{{ $vacancy['category']['parent']['title'] }} <br> {{ $vacancy['category']['title'] }}
                            </p>
                        </div>
                        <div class="col-md-4 box">
                            <h4>Benefits & Perks</h4>
                            <p>
                                @forelse($vacancy['benefits'] as $benefit)
                                    {{ $benefit }} <br>
                                @empty
                                    No benefits available
                                @endforelse
                            </p>
                        </div>
                    </div>
                </div>

                <div id="contact-details" class="tab-content">
                    <div class="col-md-4">
                        <strong>Phone:</strong> {{ $vacancy['company']['phone'] }} <br>
                        <strong>Email:</strong> {{ $vacancy['company']['email'] }}
                    </div>
                </div>

                @if(auth()->check())
                    <a href="{{ route('admin.jobs.show', $vacancy['id']) }}" class="apply-button">Apply Now</a>
                @else
                    <a href="{{ route('login', ['job-id' => $vacancy['id']]) }}" class="apply-button">Apply Now</a>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('script')
@endsection