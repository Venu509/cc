<div class="card">
    <div class="card-content">
        <img class="business-icon" src="{{ asset('template/images/new/business_center.jpg') }}">
        <p style="margin-left: -90px;margin-top: 50px;" class="job-title">{{ $vacancy['title'] }}</p>
        <p style="margin-left: -190px;" class="company">{{ $vacancy['company']['name'] }}</p>
        <p class="time">1 day ago</p>
        <p style="margin-top: 100px;" class="location"><img class="locate" src="{{ asset('template/images/new/location_on.jpg') }}"> {{ $vacancy['location'] }}</p>

        <a style="float: right;" href="{{ route('front.jobs.show', $vacancy['slug']) }}" class="view-link">View &rarr;</a>
    </div>
</div>
