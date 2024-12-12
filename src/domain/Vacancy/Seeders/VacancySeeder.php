<?php

namespace Domain\Vacancy\Seeders;

use App\Models\User;
use Domain\Category\Models\Category;
use Domain\Job\Enums\ApplicantTracking;
use Domain\Vacancy\Models\UserVacancy;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Models\VacancyQuestion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class VacancySeeder extends Seeder
{
    public function run(): void
    {
        $vacancies = [
            [
                'title' => 'Software Engineer/Senior Software Engineer C++',
                'salary' => 20000,
                'description' => '<p>Job Description</p>
            <p>Software Engineer/ Senior Software Engineer C++, Noida, India</p>
            <p><strong>General Description:</strong></p>
            <p>Obtaining in-depth understanding of design and implementation of existing software product. Design, implement and deliver new features required in the product as per deadlines. Applying innovation and creativity in design and implementation of features. Resolve issues observed during testing and usage of the product. Document code consistently throughout the development process, perform thorough testing and take ownership. Candidate should be self-driven, motivated, innovative, good team player and open to feedback.</p>
            <p><strong>Work Experience Requirements:</strong></p>
            <ul>
                <li>C++, VC++, Windows or Linux/ Unix Platform (C++ must).</li>
                <li>Should have strong programming skills in C++.</li>
                <li>Should be good in Software Design and Architecture.</li>
                <li>Should have very good Analytical skills</li>
                <li>Research orientation in the area of Image/ Video Processing, Computer Vision, Pattern recognition and related domain.</li>
                <li>Have hands-on working experience in the area of Image/ Video Processing, Computer Vision, pattern Recognition and related domain (Preferred).</li>
            </ul>',
                'location' => 'Noida',
                'category' => 'Software Engineering',
            ],
            [
                'title' => 'Software Engineer, Senior Software Engineer, Module Lead',
                'salary' => 30000,
                'description' => '<p>Job Description</p>
            <p>Blue Prism Professionals | Xavient | Next-Gen Digital Solutions for Integrated Customer Experience Blue Prism Professionals - Total vacancies: 25</p>
            <p><strong>Proposed Designation:</strong> Software Engineer, Senior Software Engineer, Module Lead</p>
            <p><strong>Description:</strong></p>
            <ul>
                <li>Identifying and communicating the technical infrastructure requirements.</li>
                <li>Designing Blue Prism process solutions in accordance with standard Blue Prism design principles and conventions.</li>
                <li>Configuring new Blue Prism processes and objects using core workflow principles that are efficient, well structured, maintainable and easy to understand.</li>
                <li>Supporting existing processes and implementing change requirements as part of a structured change control process.</li>
                <li>Problem solving issues that arise in day to day running of Blue Prism processes and providing timely responses and solutions as required.</li>
                <li>Communicating with Blue Prism on software related issues, suggested improvements and participating with other users in the Blue Prism community.</li>
            </ul>',
                'location' => 'Noida',
                'category' => 'Software Engineering',
            ],
            [
                'title' => 'Sql Server Database Administrator',
                'salary' => 20000,
                'description' => '<p>The SQL Server DBA will be responsible for the implementation, configuration, maintenance, and performance of critical SQL Server RDBMS systems, to ensure the database availability catering to various applications. Provide 24x7 support for critical production systems Perform scheduled maintenance and support release deployment activities after hours.</p>
            <p><strong>Skills and Qualifications:</strong></p>
            <ul>
                <li>3 to 5 years MS SQL Server Administration experience required</li>
                <li>Excellent hand on managing SQL Server version 2005 to 2017</li>
                <li>Experience with Performance Tuning and Optimization (PTO), using native monitoring and troubleshooting tools (tracing, DMV, resource monitor etc.</li>
                <li>Experience with backups, restores and recovery models</li>
                <li>Experience with all kind of SQL Server troubleshooting activities</li>
                <li>Knowledge of All High Availability (HA) and Disaster Recovery (DR) options for SQL Server</li>
                <li>Excellent written and verbal communication</li>
                <li>Flexible, team player, get-it-done personality</li>
            </ul>',
                'location' => 'Jhandewalan ICICI Buliding, Delhi',
                'category' => 'Healthcare Support',
            ],
            [
                'title' => 'SQL QEUFM Software',
                'salary' => 20000,
                'description' => '<p>Job Description</p>
            <p>We at HT Media are hiring developers who are good in python and data structures. Key skills required for the job are:</p>
            <ul>
                <li>Good knowledge of data structures</li>
                <li>Aggregate of 65 in Academics in Xth, XII and B Tech</li>
                <li>Must be a graduate in computer science.</li>
            </ul>
            <p><strong>Other details:</strong></p>
            <ul>
                <li>Department: Application Programming / Maintenance</li>
                <li>Industry: IT - Software</li>
                <li>Skills: structures, academics, automata, dbms, addie, sql, python</li>
                <li>Other Skills: algorithm design, new hire orientations, algorithm analysis, data structures, theory of computation, career development, behavioral training, project administration, source insight, training delivery, socket programming, gnu debugger, discrete mathematics, training needs analysis</li>
            </ul>',
                'location' => 'H-125 Shudha Buliding Banglore',
                'category' => 'Environmental Engineering',
            ],
            [
                'title' => 'Software Developer(Java/.Net/PHP)',
                'salary' => 30000,
                'description' => '<p>Job Description</p>
            <p>Software Engineer/ Senior Software Engineer C++, Noida, India</p>
            <p><strong>General Description:</strong></p>
            <p>Obtaining in-depth understanding of design and implementation of existing software product. Design, implement and deliver new features required in the product as per deadlines. Applying innovation and creativity in design and implementation of features. Resolve issues observed during testing and usage of the product. Document code consistently throughout the development process, perform thorough testing and take ownership. Candidate should be self-driven, motivated, innovative, good team player and open to feedback.</p>
            <p><strong>Work Experience Requirements:</strong></p>
            <ul>
                <li>C++, VC++, Windows or Linux/ Unix Platform (C++ must).</li>
                <li>Should have strong programming skills in C++.</li>
                <li>Should be good in Software Design and Architecture.</li>
                <li>Should have very good Analytical skills</li>
                <li>Research orientation in the area of Image/ Video Processing, Computer Vision, Pattern recognition and related domain.</li>
                <li>Have hands-on working experience in the area of Image/ Video Processing, Computer Vision, pattern Recognition and related domain (Preferred).</li>
            </ul>',
                'location' => 'H-476 Noida Sector-12',
                'category' => 'Network Administration',
            ],
            [
                'title' => 'Sql Server Database Administrator',
                'salary' => 20000,
                'description' => '<p>The SQL Server DBA will be responsible for the implementation, configuration, maintenance, and performance of critical SQL Server RDBMS systems, to ensure the database availability catering to various applications. Provide 24x7 support for critical production systems Perform scheduled maintenance and support release deployment activities after hours.</p>
            <p><strong>Skills and Qualifications:</strong></p>
            <ul>
                <li>3 to 5 years MS SQL Server Administration experience required</li>
                <li>Excellent hand on managing SQL Server version 2005 to 2017</li>
                <li>Experience with Performance Tuning and Optimization (PTO), using native monitoring and troubleshooting tools (tracing, DMV, resource monitor etc.</li>
                <li>Experience with backups, restores and recovery models</li>
                <li>Experience with all kind of SQL Server troubleshooting activities</li>
                <li>Knowledge of All High Availability (HA) and Disaster Recovery (DR) options for SQL Server</li>
                <li>Excellent written and verbal communication</li>
                <li>Flexible, team player, get-it-done personality</li>
            </ul>',
                'location' => 'J-123 Sector 136 Greater Noida',
                'category' => 'IT Support',
            ],
            [
                'title' => 'SQL QEUFM Software',
                'salary' => 20000,
                'description' => '<p>Job Description</p>
            <p>We at HT Media are hiring developers who are good in python and data structures. Key skills required for the job are:</p>
            <ul>
                <li>Good knowledge of data structures</li>
                <li>Aggregate of 65 in Academics in Xth, XII and B Tech</li>
                <li>Must be a graduate in computer science.</li>
            </ul>
            <p><strong>Other details:</strong></p>
            <ul>
                <li>Department: Application Programming / Maintenance</li>
                <li>Industry: IT - Software</li>
                <li>Skills: structures, academics, automata, dbms, addie, sql, python</li>
                <li>Other Skills: algorithm design, new hire orientations, algorithm analysis, data structures, theory of computation, career development, behavioral training, project administration, source insight, training delivery, socket programming, gnu debugger, discrete mathematics, training needs analysis</li>
            </ul>',
                'location' => 'G-11/12 Sector-62, Noida',
                'category' => 'Data Analysis',
            ],
            [
                'title' => 'Software Developer(Java/.Net/PHP)',
                'salary' => 30000,
                'description' => '<p>Job Description</p>
            <p>Software Engineer/ Senior Software Engineer C++, Noida, India</p>
            <p><strong>General Description:</strong></p>
            <p>Obtaining in-depth understanding of design and implementation of existing software product. Design, implement and deliver new features required in the product as per deadlines. Applying innovation and creativity in design and implementation of features. Resolve issues observed during testing and usage of the product. Document code consistently throughout the development process, perform thorough testing and take ownership. Candidate should be self-driven, motivated, innovative, good team player and open to feedback.</p>
            <p><strong>Work Experience Requirements:</strong></p>
            <ul>
                <li>C++, VC++, Windows or Linux/ Unix Platform (C++ must).</li>
                <li>Should have strong programming skills in C++.</li>
                <li>Should be good in Software Design and Architecture.</li>
                <li>Should have very good Analytical skills</li>
                <li>Research orientation in the area of Image/ Video Processing, Computer Vision, Pattern recognition and related domain.</li>
                <li>Have hands-on working experience in the area of Image/ Video Processing, Computer Vision, pattern Recognition and related domain (Preferred).</li>
            </ul>',
                'location' => 'H-55 Noida Sector-11',
                'category' => 'Software Engineering',
            ],
            [
                'title' => 'SQL QEUFM Software',
                'salary' => 20000,
                'description' => 'Job Description
                    We at HT Media are hiring developers who are good in python and data structures. Key skills required for the job are:
                    1) Good knowledge of data structures
                    2) Aggregate of 65 in Academics in Xth, XII and B Tech
                    3) Must be a graduate in computer science.
                    
                    Other details:
                    Department: Application Programming / Maintenance
                    Industry: IT - Software
                    Skills: structures, academics, automata, dbms, addie, sql, python
                    Other Skills: algorithm design, new hire orientations, algorithm analysis, data structures, theory of computation, career development, behavioral training, project administration, source insight, training delivery, socket programming, gnu debugger, discrete mathematics, training needs analysis',
                'category' => 'Software Engineering',
                'location' => 'H-55 Noida Sector-11',
            ],
            [
                'title' => 'Web Developer',
                'salary' => 27500,
                'description' => 'PHP (Must)
                    MySQL (Must)
                    Should have knowledge of HTML,Bootstrap, and CSS',
                'location' => 'H-55 Noida Sector-11',
                'category' => 'Software Engineering',
            ],
            [
                'title' => 'PHP Developer',
                'salary' => 125641,
                'description' => 'Bachelor\'s degree in computer science or a similar field.
                    Knowledge of PHP web frameworks including Yii, Laravel, and CodeIgniter
                    Knowledge of front-end technologies including CSS3, JavaScript, and HTML5.
                    Understanding of object-oriented PHP programming.
                    Previous experience creating scalable applications.
                    Familiarity with SQL/NoSQL databases.
                    Good problem-solving skills.
                    Basic understanding of NodeJS
                    Someone who is passionate about creating awesome user experiences and who loves to code - A multifaceted role with a high degree of ownership and a broad spectrum of opportunities.',
                'category' => 'Software Engineering',
                'location' => 'H-55 Noida Sector-11',
            ],
            [
                'title' => 'WordPress Developer',
                'salary' => 2500000,
                'description' => 'WordPress Developer
                    Theme Customization
                    Plugin Development',
                'location' => 'H-55 Noida Sector-11',
                'category' => 'Software Engineering',
            ],
            [
                'title' => 'Laravel Tech Lead',
                'salary' => '1000000 LPA',
                'description' => '<h4>Key Responsibilities:</h4>
            <p>&nbsp;</p>
            <ul>
            <li>Lead and manage a team of developers, ensuring the timely and efficient delivery of projects.</li>
            <li>Develop and maintain robust, scalable, and secure backend systems using Laravel (versions 8-11) and PHP (versions 7-8).</li>
            <li>Collaborate closely with frontend developers to integrate APIs with Vue.js (versions 2 &amp; 3) and Tailwind CSS.</li>
            <li>Optimize applications for maximum speed and scalability.</li>
            <li>Ensure code quality and best practices through code reviews, regular refactoring, and adherence to Test-Driven Development (TDD) practices.</li>
            <li>Implement Domain-Driven Design (DDD) principles where applicable to ensure a well-structured and maintainable codebase.</li>
            <li>Manage and maintain databases, primarily MySQL and PostgreSQL.</li>
            <li>Utilize version control systems (e.g., Git) to manage and track code changes.</li>
            <li>Work with cloud platforms like AWS, Azure, and other major service providers to deploy and manage applications.</li>
            <li>Handle task management and prioritize work to meet project deadlines.</li>
            <li>Maintain and improve CI/CD pipelines for the development lifecycle.</li>
            <li>Provide technical guidance and mentorship to team members.</li>
            </ul>
            <h4>&nbsp;</h4>
            <h4>Required Skills and Qualifications:</h4>
            <p>&nbsp;</p>
            <ul>
            <li>Experience<strong>:</strong> Minimum 4 years in backend development, with at least 1 year as SSE or ATL.</li>
            <li>Backend<strong>:</strong> Strong knowledge of Laravel (versions 8-11), PHP (versions 7-8).</li>
            <li>Frontend Integration<strong>:</strong> Experience with Vue.js (versions 2 &amp; 3), Tailwind CSS, Axios.</li>
            <li>Databases<strong>:</strong> Proficient in MySQL and PostgreSQL.</li>
            <li>Team Management<strong>:</strong> Proven ability to lead a team, manage tasks, and meet deadlines.</li>
            <li>Version Control: Proficiency with version control systems, especially Git.</li>
            <li>Development Practices: Must have experience with Test-Driven Development (TDD).</li>
            <li>Additional Skills: Knowledge of Domain-Driven Design (DDD) principles (optional).</li>
            <li>Familiarity with the MERN stack.</li>
            <li>Experience with cloud services like AWS, Azure, and other major providers.</li>
            <li>Strong task and time management skills.</li>
            <li>Experience with CI/CD pipelines and deployment strategies.</li>
            </ul>
            <h4>&nbsp;</h4>
            <h4>Preferred Qualifications:</h4>
            <p>&nbsp;</p>
            <ul>
            <li>Experience with Docker and containerization.</li>
            <li>Familiarity with microservices architecture.</li>
            <li>Knowledge of RESTful APIs and Web Socket.</li>
            <li>Experience with Agile methodologies.</li>
            <li>Understanding of DevOps practices.</li>
            </ul>',
                'location' => 'Sri Lanka',
                'category' => 'Software Engineering',
            ]
        ];

        collect($vacancies)->each(function ($vacancy) {

            $location = $vacancy['location'];

            unset($vacancy['location']);

            $modifiedVacancy = [
                'category_id' => Category::query()->where('slug', slugGenerator($vacancy['category']))->first() ? Category::query()->where('slug', slugGenerator($vacancy['category']))->first()->id : Category::first()->id,
                'locations' => json_encode([$location], JSON_THROW_ON_ERROR | true),
            ];

            unset($vacancy['category']);
            $createdVacancy = Vacancy::factory()->create(array_merge(
                $modifiedVacancy,
                $vacancy
            ));

            $json = File::get(public_path('questions.json'));

            $questions = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            shuffle($questions);

            $randomQuestions = array_slice($questions, 0, 5);

            collect($randomQuestions)->each(function ($question) use ($createdVacancy) {
                $vacancyQuestion = new VacancyQuestion();

                $answers = [
                    $question['A'],
                    $question['B'],
                    $question['C'],
                    $question['D'],
                ];

                $vacancyQuestion->forceFill([
                    'question' => $question['question'],
                    'answers' => json_encode($answers, JSON_THROW_ON_ERROR | true),
                    'vacancy_id' => $createdVacancy->id,
                ]);

                $vacancyQuestion->save();
            });
        });

        $this->seedAdditionalVacancies(
            env('APP_ENV') === 'local' ? 100 : 1000
        );

//        if(env('APP_ENV') === 'local') {
//            $this->appliedCandidates();
//        }
    }

    private function seedAdditionalVacancies(int $limit = 100): void
    {
        $vacancies = [
            [
                'title' => 'DevOps Engineer',
                'salary' => 35000,
                'description' => '<p>Manage infrastructure deployment and automation, ensure smooth deployment of code, and maintain cloud infrastructure. Strong experience in cloud platforms (AWS/Azure), containerization (Docker), and CI/CD pipelines.</p>',
                'location' => 'Bangalore',
                'category' => 'DevOps',
            ],
            [
                'title' => 'Data Scientist',
                'salary' => 42000,
                'description' => '<p>Analyze large datasets, develop predictive models, and provide insights to support business decision-making. Expertise in Python, R, machine learning, and statistical analysis required.</p>',
                'location' => 'Mumbai',
                'category' => 'Data Science',
            ],
            [
                'title' => 'Software Engineer (Java)',
                'salary' => 38000,
                'description' => '<p>Develop and maintain software applications using Java. Strong knowledge of object-oriented programming, data structures, and algorithms required.</p>',
                'location' => 'Chennai',
                'category' => 'Software Development',
            ],
            [
                'title' => 'Network Administrator',
                'salary' => 31000,
                'description' => '<p>Configure, maintain, and monitor networks, ensure security, and troubleshoot network issues. Experience with LAN/WAN, routers, and firewalls required.</p>',
                'location' => 'Delhi',
                'category' => 'Network Administration',
            ],
            [
                'title' => 'IT Support Specialist',
                'salary' => 27000,
                'description' => '<p>Provide technical support to users, troubleshoot hardware and software issues, and ensure smooth IT operations. Familiarity with Windows, MacOS, and Linux environments preferred.</p>',
                'location' => 'Pune',
                'category' => 'IT Support',
            ],
            [
                'title' => 'Cyber Security Analyst',
                'salary' => 45000,
                'description' => '<p>Monitor security systems, detect and respond to threats, and ensure compliance with security standards. Strong knowledge of firewalls, SIEM tools, and incident response required.</p>',
                'location' => 'Hyderabad',
                'category' => 'Cyber Security',
            ],
            [
                'title' => 'Web Developer',
                'salary' => 30000,
                'description' => '<p>Develop and maintain websites using HTML, CSS, JavaScript, and backend frameworks. Experience with responsive design and CMS platforms is a plus.</p>',
                'location' => 'Noida',
                'category' => 'Web Development',
            ],
            [
                'title' => 'Mobile App Developer (iOS)',
                'salary' => 32000,
                'description' => '<p>Design and build mobile applications for iOS devices. Expertise in Swift, iOS frameworks, and mobile app lifecycle management required.</p>',
                'location' => 'Bangalore',
                'category' => 'Mobile App Development',
            ],
            [
                'title' => 'Mechanical Engineer',
                'salary' => 40000,
                'description' => '<p>Design and develop mechanical systems and products. Proficiency in CAD software and experience in product lifecycle management required.</p>',
                'location' => 'Chennai',
                'category' => 'Mechanical Engineering',
            ],
            [
                'title' => 'Electrical Engineer',
                'salary' => 38000,
                'description' => '<p>Design, test, and maintain electrical systems. Experience with circuit design, control systems, and power distribution required.</p>',
                'location' => 'Hyderabad',
                'category' => 'Electrical Engineering',
            ],
            [
                'title' => 'Civil Engineer',
                'salary' => 42000,
                'description' => '<p>Manage construction projects, design infrastructure systems, and oversee structural analysis. Experience with AutoCAD and project management required.</p>',
                'location' => 'Mumbai',
                'category' => 'Civil Engineering',
            ],
            [
                'title' => 'Software Engineer (Python)',
                'salary' => 40000,
                'description' => '<p>Develop and maintain software applications using Python. Strong knowledge of object-oriented programming and software design patterns required.</p>',
                'location' => 'Gurgaon',
                'category' => 'Software Engineering',
            ],
            [
                'title' => 'Chemical Engineer',
                'salary' => 45000,
                'description' => '<p>Develop processes for manufacturing chemicals and ensure compliance with safety standards. Experience with chemical production and plant operations required.</p>',
                'location' => 'Ahmedabad',
                'category' => 'Chemical Engineering',
            ],
            [
                'title' => 'Environmental Engineer',
                'salary' => 37000,
                'description' => '<p>Design projects to improve environmental health, monitor pollution levels, and ensure regulatory compliance. Experience in environmental impact assessment preferred.</p>',
                'location' => 'Delhi',
                'category' => 'Environmental Engineering',
            ],
            [
                'title' => 'Aerospace Engineer',
                'salary' => 50000,
                'description' => '<p>Design, test, and oversee the production of aircraft and spacecraft. Proficiency in aerospace design software and knowledge of aerodynamics required.</p>',
                'location' => 'Bangalore',
                'category' => 'Aerospace Engineering',
            ],
            [
                'title' => 'Registered Nurse',
                'salary' => 30000,
                'description' => '<p>Provide patient care in hospitals and clinics. Registered nursing certification and experience in a clinical setting are required.</p>',
                'location' => 'Chennai',
                'category' => 'Nursing',
            ],
            [
                'title' => 'Medical Administrator',
                'salary' => 35000,
                'description' => '<p>Oversee administrative operations of healthcare facilities. Strong organizational and management skills are required.</p>',
                'location' => 'Mumbai',
                'category' => 'Medical Administration',
            ],
            [
                'title' => 'Medical Lab Technician',
                'salary' => 28000,
                'description' => '<p>Perform laboratory tests to assist in the diagnosis and treatment of diseases. Certification and prior experience in medical lab work required.</p>',
                'location' => 'Pune',
                'category' => 'Medical Technicians',
            ],
            [
                'title' => 'Sales Representative',
                'salary' => 29000,
                'description' => '<p>Manage client relationships, sell products, and generate new business leads. Strong communication and negotiation skills are required.</p>',
                'location' => 'Delhi',
                'category' => 'Sales Representative',
            ],
            [
                'title' => 'SEO/SEM Specialist',
                'salary' => 32000,
                'description' => '<p>Optimize websites for search engines and manage pay-per-click campaigns. Expertise in SEO tools and Google AdWords is required.</p>',
                'location' => 'Bangalore',
                'category' => 'SEO/SEM Specialist',
            ],
        ];

        collect($vacancies)->each(function ($vacancy) {

            $userId = 1;

            $userIds = User::query()
                ->whereHas('roles', function (Builder $builder) {
                    $builder->where('name', 'company');
                })->whereNot('email', 'careerconnect@company.com')->get()->pluck('id')->toArray();

            if(count($userIds) > 1) {
                $userId = array_rand(array_flip(
                    User::query()
                        ->whereHas('roles', function (Builder $builder) {
                            $builder->where('name', 'company');
                        })->whereNot('email', 'careerconnect@company.com')->get()->pluck('id')->toArray()
                ));
            }

            $location = $vacancy['location'];

            unset($vacancy['location']);

            $modifiedVacancy = [
                'category_id' => Category::query()->where('slug', slugGenerator($vacancy['category']))->first() ? Category::query()->where('slug', slugGenerator($vacancy['category']))->first()->id : Category::first()->id,
                'company_id' => $userId,
                'modified_by' => $userId,
                'added_by' => $userId,
                'locations' => json_encode([$location], JSON_THROW_ON_ERROR | true),
            ];
            unset($vacancy['category']);
            $createdVacancy = Vacancy::factory()->create(array_merge(
                $modifiedVacancy,
                $vacancy
            ));

            $json = File::get(public_path('questions.json'));

            $questions = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            shuffle($questions);

            $randomQuestions = array_slice($questions, 0, 5);

            collect($randomQuestions)->each(function ($question) use ($createdVacancy) {
                $vacancyQuestion = new VacancyQuestion();

                $answers = [
                    $question['A'],
                    $question['B'],
                    $question['C'],
                    $question['D'],
                ];

                $vacancyQuestion->forceFill([
                    'question' => $question['question'],
                    'answers' => json_encode($answers, JSON_THROW_ON_ERROR | true),
                    'vacancy_id' => $createdVacancy->id,
                ]);

                $vacancyQuestion->save();
            });
        });

        Vacancy::factory($limit)->create();
    }

    private function appliedCandidates(): void
    {
        User::query()
            ->whereHas('roles', function (Builder $builder) {
                $builder->where('name', 'company');
            })->where('email', 'careerconnect@company.com')->get()
            ->each(function ($user) {

                Vacancy::query()->where('company_id', $user->id)
                    ->get()
                    ->each(function ($vacancy) {

                        User::query()
                            ->whereHas('roles', function (Builder $builder) {
                                $builder->where('name', 'candidate');
                            })
                            ->get()
                            ->each(function ($candidate) use ($vacancy) {
                                $userVacancy = new UserVacancy();

                                $userVacancy->forceFill([
                                    'vacancy_id' => $vacancy->id,
                                    'candidate_id' => $candidate->id,
                                    'status' => 'applied',
                                ]);

                                $userVacancy->applicantTracking(ApplicantTracking::APPLIED, ($candidate->name . ' submitted the resume'));

                                $userVacancy->save();
                            });
                    });
            });
    }
}
