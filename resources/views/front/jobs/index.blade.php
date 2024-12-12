@extends('layouts.app')
@section('title', 'Jobs')

@section('style')
@endsection

@section('content')
    <br><br>
    <style>
        @media (max-width: 768px) {
            .search-container {
                height: 200px;
                width: 100%;
                max-width: 410px;

            }

            .input-group {
                flex-direction: column;
                align-items: stretch;
            }

            .search-button {
                width: 100%;
            }

            .job-card {
                width: 100%;
                max-width: 480px;
                height: 300px;
                padding: 50px

            }

            .view-button, .apply-button {
                width: 80%;
                max-width: 150px


            }
        }

        /* -------------------------------------------------------------------------------------- */
        .input-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            height: 90px

        }

        input {
            background-color: rgb(63 131 248 / 0.5);;
            height: 55px
        }

        .tag {
            display: flex;
            align-items: center;
            background-color: #e2e2fc;
            color: #333;
            border-radius: 15px;
            padding: 5px 10px;
            font-size: 14px;
        }

        .tag button {
            background: none;
            border: none;
            margin-left: 5px;
            cursor: pointer;
            color: #666;
        }

        .tag button:hover {
            color: red;
        }

        input[type="text"] {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 8px;
            outline: none;
            font-size: 14px;
            width: 100%; /* Full width for responsiveness */
        }

        .search-button {
            border: none;
            background-color: blue;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .row {
            display: flex;
            width: 100%;
            margin-bottom: 10px;
        }

        .col-5 {
            flex: 0 0 41.66667%; /* Adjust to make two columns of equal width */
            max-width: 41.66667%;
            padding: 5px;
        }

        .col-2 {
            flex: 0 0 16.66667%; /* Search button width */
            max-width: 16.66667%;
            padding: 2px;
            background-color: white;
        }

    </style>

    <div class="search-container">
        <div class="row">
            <div class="col-5">
                <input type="text" id="keywordInput" placeholder="Job title or keywords">
            </div>
            <div class="col-5">
                <input type="text" id="locationInput" placeholder="Location">
            </div>
            <div class="col-2">
                <button class="search-button" onclick="searchJobs()">Search</button>
            </div>
        </div>
        <div class="input-container" id="tagsContainer"></div>
        <hr>
        <div class="d-flex justify-content-between align-items-center" style="width: 100%; margin-bottom: 10px;">
            <div style="flex: 0 0 auto;">
                <select id="jobType"
                        style="padding: 8px; border-radius: 10px; border: 2px solid #ccc; font-size: 14px;">
                    <option value="">Job Type</option>
                    <option value="permanent">Permanent</option>
                    <option value="contract">Contract</option>
                    <option value="freelancing">Freelancing</option>
                </select>
            </div>

            <div style="flex-grow: 1;"></div>

            <div style="flex: 0 0 auto; cursor: pointer; color: #007bff;" onclick="clearFilters()">
                Clear Filter
            </div>
        </div>
    </div>

    <div class="head">
        <h1 id="totalJobCount"></h1>

        <div id="jobsSection"></div>

        <div class="pagenationcol" id="paginationContainer"></div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.7/axios.min.js"></script>

<script>
    function clearFilters() {
        document.getElementById('jobType').value = '';
        alert('Filters cleared');
    }

    const keywordInput = document.getElementById('keywordInput');
    const tagsContainer = document.getElementById('tagsContainer');

    keywordInput.addEventListener('keypress', function (event) {
        if (event.key === 'Enter' && keywordInput.value.trim() !== '') {
            event.preventDefault(); // Prevent form submission
            createTag(keywordInput.value.trim());
            keywordInput.value = ''; // Clear input
        }
    });

    function createTag(text) {
        const tag = document.createElement('div');
        tag.classList.add('tag');
        tag.innerHTML = `${text} <button onclick="removeTag(this)">×</button>`;
        tagsContainer.appendChild(tag);
    }

    function removeTag(button) {
        tagsContainer.removeChild(button.parentElement);
    }

    function searchJobs() {
        // Implement search logic or call the function to fetch job data
        alert('Search function triggered');
    }

    const locationInput = document.getElementById('locationInput');

    locationInput.addEventListener('keypress', function (event) {
        if (event.key === 'Enter' && locationInput.value.trim() !== '') {
            event.preventDefault(); // Prevent form submission
            createTag(locationInput.value.trim());
            locationInput.value = ''; // Clear input
        }
    });

    fetchJobs()

    function fetchJobs() {
    let route = '{{ route("front.jobs.fetch") }}';

    let pathSegments = window.location.pathname.split('/');
    let lastSegment = pathSegments.pop() || pathSegments.pop();

    const urlParams = new URLSearchParams(window.location.search);
    const locations = urlParams.get('locations');
    const keywords = urlParams.get('keywords');
    let page = urlParams.get('page') || 1;

    axios.get(route, {
        params: {
            page: page,
            locations: locations,
            keywords: keywords,
        }
    })
        .then(function (response) {
            let jobs = response.data.jobs.data;

            let jobsSection = $('#jobsSection');
            jobsSection.empty();

            if (response.data.jobs.total === 0) {
                jobsSection.append(`
                <div class="mx-auto">
                    <h2>No Data Found</h2>
                </div>
                `)
            } else {
                let pagination = response.data.jobs;
                createPaginationControls(pagination, parseInt(page));

                $('#totalJobCount').text(`${pagination.total } total Jobs`)

                $.each(jobs, function (index, job) {
                    let showRoute = '{{ route("front.jobs.show", [":job"]) }}';
                    showRoute = showRoute.replace(':job', job.slug);
                    let authenticatedUser = '{{ auth()->check() ? auth()->user() : null }}';
                    let loginRoute = '{{ route('login', [':job-id']) }}';
                    if (authenticatedUser) {
                        loginRoute = '{{ route('admin.jobs.show', [':job-id']) }}'
                    }

                    loginRoute = loginRoute.replace(':job-id', job.id);
                    jobsSection.append(`
                        <div class="job-card">
                            <div class="job-details">
                                <div class="company-logo">
                                    <div class="logo-placeholder"></div>
                                </div>
                                <div class="job-info">
                                    <h2 class="job-title">${job['title']}</h2>
                                    <p class="company-name">${job['company']['name']}</p>
                                </div>
                                <div class="location">
                                    <img src="{{ asset('template/images/new/distance.png') }}">
                                    <span>${job['locations']}</span>
                                </div>
                            </div>

                            <div class="action-buttons">
                                <a style="float: right;" href="${showRoute}" class="view-link"><button class="view-button">View</button></a>
                                <a href="${loginRoute}" class="view-link"><button class="apply-button">Apply</button></a>
                            </div>
                            <span class="time-ago">1 day ago</span>
                        </div>
                    `);
                });
            }
        })
        .catch(function (error) {
        });
    }

    function createPaginationControls(pagination, currentPage) {
    let totalPages = pagination.last_page;
    let container = $('#paginationContainer');

    container.empty();

    if (totalPages > 1) {
        let paginationHTML = '<ul class="pagination">';
        if (currentPage > 1) {
            paginationHTML +=
                `<li class="page-item">
            <span
                class="page-link cursor-pointer"
                style="background-color: #6A6FFF; color: #000000; border: 1px solid #000000;cursor: pointer;"
                onclick="pagination(${currentPage - 1})"
            >
                Previous
            </span>
        </li>
    `;
        }
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML +=
                `<li class="page-item ${i === currentPage ? 'active' : ''}">
            <span
                class="page-link cursor-pointer"
                style="${i === currentPage ? 'background-color: #FFFFFF; color: #000000; border: 1px solid #000000;cursor: pointer;' : 'background-color: #6A6FFF; color: #FFFFFF;#000000; border: 1px solid #000000;cursor: pointer;'}"
                onclick="pagination(${i})"
            >
                ${i}
            </span>
        </li>
    `;
        }
        if (currentPage < totalPages) {
            paginationHTML +=
                `<li class="page-item"><span class="page-link cursor-pointer"  style="background-color: #6A6FFF; color: #000000; border: 1px solid #000000;cursor: pointer;" onclick="pagination(${currentPage + 1})">Next</span></li>`;
        }
        paginationHTML += '</ul>';
        container.html(paginationHTML);
    }
    }

    function pagination(page) {
    $('#clearFilterSection').removeClass('d-none')
    let url = new URL(window.location);
    url.searchParams.set('page', page);
    history.pushState(null, '', url.toString());
    fetchJobs();
    }
</script>

@endsection


