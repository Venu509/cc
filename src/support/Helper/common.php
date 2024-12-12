<?php

use App\Models\User;
use Domain\PageSetting\Models\PageSetting;
use Domain\Student\Models\Student;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('imageCheck')) {
    function imageCheck(
        string  $path,
        ?string $default = 'images/not_found.png'
    ): ?string {
        $storageDisk = env('DEFAULT_STORAGE', 'public');

        try {
            if ($path) {
                if (Storage::disk($storageDisk)->exists($path)) {
                    return Storage::disk($storageDisk)->url($path);
                }
                return asset($default);
            }
            return asset($default);
        } catch (Exception $e) {
            return asset($default);
        }
    }
}

if (!function_exists('slugGenerator')) {
    function slugGenerator($string): string
    {
        return Str::slug($string);
    }
}

if (!function_exists('displayPrice')) {
    function displayPrice(float|int $price): string
    {
        return 'INR ' . number_format($price, 2);
    }
}

if (!function_exists('getStringFromSlug')) {
    function getStringFromSlug(?string $slug = null): ?string
    {
        return $slug ? Str::title(str_replace('-', ' ', $slug)) : null;
    }
}

if (!function_exists('roleAbilities')) {
    function roleAbilities(): array
    {
        return [
            slugGenerator('Master') => [
                'type' => 'web',
                'domains' => [
                    'dashboard' => permissions(),
                    'my-accounts' => permissions(),
                    'notifications' => permissions(),
                    'categories' => permissions(),
                    'vacancies' => permissions(),
                    'candidates' => permissions(),
                    'branches' => permissions(),
                    'students' => permissions(),
                    'workshops' => permissions(),
                    'projects' => permissions(),
                    'leads' => permissions(),
                    'colleges' => permissions(),
                    'attendances' => permissions(),
                    'companies' => permissions(),
                    'resumes' => permissions(),
                    'roles' => permissions(),
                    'applied-candidates' => permissions(),
                    'projects-names' => permissions(),
                    'workshops-names' => permissions(),
                    'jobs' => permissions(),
                    'saved-jobs' => permissions(),
                    'countries' => permissions(),
                    'skills' => permissions(),
                    'vacancy-categories' => permissions(),
                    'applications' => permissions(),
                ],
            ],
            slugGenerator('Admin') => [
                'type' => 'web',
                'domains' => [
                    'dashboard' => permissions(),
                    'my-accounts' => permissions(),
                    'notifications' => permissions(),
                    'categories' => permissions(),
                    'vacancies' => permissions(),
                    'candidates' => permissions(),
                    'branches' => permissions(),
                    'students' => permissions(),
                    'workshops' => permissions(),
                    'projects' => permissions(),
                    'leads' => permissions(),
                    'colleges' => permissions(),
                    'attendances' => permissions(),
                    'companies' => permissions(),
                    'resumes' => permissions(),
                    'roles' => permissions(),
                    'applied-candidates' => permissions(),
                    'projects-names' => permissions(),
                    'workshops-names' => permissions(),
                    'jobs' => permissions(),
                    'saved-jobs' => permissions(),
                    'countries' => permissions(),
                    'skills' => permissions(),
                    'vacancy-categories' => permissions(),
                    'applications' => permissions(),
                ],
            ],
            slugGenerator('Government') => [
                'type' => 'web',
                'domains' => [
                    'dashboard' => excludePermissions([]),
                    'branches' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'students' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'assign', 'history', 'return', 'upload', 'change', 'apply']),
                    'workshops' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move','assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'projects' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                ],
            ],
            slugGenerator('Marketing') => [
                'type' => 'web',
                'domains' => [
                    'dashboard' => excludePermissions([]),
                    'leads' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'colleges' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'candidates' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'attendances' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'companies' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                ],
            ],
            slugGenerator('Institution') => [
                'type' => 'web',
                'domains' => [
                    'dashboard' => excludePermissions([]),
                    'branches' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'students' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'assign', 'history', 'return', 'change', 'apply']),
                    'workshops' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'projects' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                ],
            ],
            slugGenerator('Candidate') => [
                'type' => 'web',
                'domains' => [
                    'dashboard' => excludePermissions([]),
                    'my-accounts' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'notifications' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'jobs' => excludePermissions(['all', 'assign', 'history', 'view', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'delete']),
                    'saved-jobs' => excludePermissions(['all', 'assign', 'history', 'view', 'status', 'read', 'move', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'countries' => excludePermissions(['all', 'assign', 'history', 'view', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'skills' => excludePermissions(['all', 'assign', 'history', 'view', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                ],
            ],
            slugGenerator('Company') => [
                'type' => 'web',
                'domains' => [
                    'dashboard' => excludePermissions([]),
                    'my-accounts' => excludePermissions(['all', 'fetch', 'assign', 'history', 'view', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'notifications' => excludePermissions(['all', 'fetch', 'assign', 'history', 'view', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'categories' => excludePermissions(['all', 'assign', 'history', 'view', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'vacancies' => excludePermissions(['all', 'fetch', 'assign', 'history', 'status', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'resumes' => excludePermissions(['all', 'assign', 'store', 'create',  'history', 'view', 'status', 'read', 'move', 'update', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'applied-candidates' => excludePermissions(['all', 'assign', 'store', 'create',  'history', 'view', 'read', 'move', 'update', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'vacancy-categories' => excludePermissions(['all', 'assign', 'history', 'view', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                    'applications' => excludePermissions(['all', 'assign', 'history', 'read', 'move', 'destroy', 'assign', 'history', 'download', 'return', 'upload', 'change', 'uploading', 'bulk', 'apply', 'delete']),
                ],
            ],
        ];
    }
}


if (!function_exists('permissions')) {
    function permissions(?array $except = []): array
    {
        $permissions = [
            'all',
            'index',
            'create',
            'store',
            'show',
            'fetch',
            'assign',
            'history',
            'view',
            'status',
            'read',
            'move',
            'update',
            'destroy',
            'delete',
            'download',
            'return',
            'upload',
            'change',
            'rules',
            'uploading',
            'bulk',
            'apply',
        ];

        if (!empty($except)) {
            return array_values(
                array_flip(
                    array_diff_key(array_flip($permissions), array_flip($except))
                )
            );
        }

        return $permissions;
    }
}

if (!function_exists('excludePermissions')) {
    function excludePermissions(array $exclusions): array
    {
        return array_diff(permissions(), $exclusions);
    }
}

if (!function_exists('domains')) {
    function domains(): array
    {
        return [
            'dashboard',
            'notifications',
            'roles',
            'users',
            'tests',
            'branches',
            'students',
            'workshops',
            'attendances',
            'leads',
            'colleges',
            'projects',
            'candidates',
            'my-accounts',
            'banners',
            'companies',
            'vacancies',
            'categories',
            'resumes',
            'jobs',
            'applied-candidates',
            'projects-names',
            'workshops-names',
            'saved-jobs',
            'countries',
            'skills',
            'vacancy-categories',
            'applications',
        ];
    }
}

if (!function_exists('menus')) {
    function menus(): array
    {
        return [
            [
                'name' => 'Dashboard',
                'route' => 'admin.dashboard',
                'params' => [],
                'icon' => 'HomeIcon',
                'component' => 'Dashboard',
                'access' => checkIfRouteRestricted('dashboard'),
                'isActive' => activeMenu('dashboard', ['index']),
                'children' => null,
                'hasChildren' => false,
            ],
            [
                'name' => 'Leads',
                'route' => 'admin.leads.index',
                'params' => [],
                'icon' => 'ChartBarIcon',
                'component' => 'Leads',
                'access' => checkIfRouteRestricted('leads'),
                'isActive' => activeMenu('leads', ['index', 'create', 'show']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Add',
                        'route' => 'admin.leads.create',
                        'params' => [],
                        'icon' => 'PlusIcon',
                        'component' => 'Leads/Create',
                        'access' => checkIfRouteRestricted('leads'),
                        'isActive' => activeMenu('leads', ['create', 'show']),
                    ],
                    [
                        'name' => 'Manage',
                        'route' => 'admin.leads.index',
                        'params' => [],
                        'icon' => 'DocumentSearchIcon',
                        'component' => 'Leads/Index',
                        'access' => checkIfRouteRestricted('leads'),
                        'isActive' => activeMenu('leads', ['index']),
                    ],
                ],
            ],
            [
                'name' => 'Branches',
                'route' => 'branches',
                'params' => [],
                'icon' => 'ViewListIcon',
                'component' => 'Branches',
                'access' => checkIfRouteRestricted('branches'),
                'isActive' => activeMenu('branches', ['index', 'create', 'show']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Add',
                        'route' => 'admin.branches.create',
                        'params' => [],
                        'icon' => 'PlusIcon',
                        'component' => 'Branches/Create',
                        'access' => checkIfRouteRestricted('branches'),
                        'isActive' => activeMenu('branches', ['create', 'show']),
                    ],
                    [
                        'name' => 'Manage',
                        'route' => 'admin.branches.index',
                        'params' => [],
                        'icon' => 'DocumentSearchIcon',
                        'component' => 'Branches/Index',
                        'access' => checkIfRouteRestricted('branches'),
                        'isActive' => activeMenu('branches', ['index']),
                    ],
                ],
            ],
            [
                'name' => 'Colleges',
                'route' => 'colleges',
                'params' => [],
                'icon' => 'MenuAlt4Icon',
                'component' => 'Colleges',
                'access' => checkIfRouteRestricted('colleges'),
                'isActive' => activeMenu('colleges', ['index', 'create', 'show']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Add',
                        'route' => 'admin.colleges.create',
                        'params' => [],
                        'icon' => 'PlusIcon',
                        'component' => 'Colleges/Create',
                        'access' => checkIfRouteRestricted('colleges'),
                        'isActive' => activeMenu('colleges', ['create', 'show']),
                    ],
                    [
                        'name' => 'Manage',
                        'route' => 'admin.colleges.index',
                        'params' => [],
                        'icon' => 'DocumentSearchIcon',
                        'component' => 'Colleges/Index',
                        'access' => checkIfRouteRestricted('colleges'),
                        'isActive' => activeMenu('colleges', ['index']),
                    ],
                ],
            ],
            [
                'name' => 'Resumes',
                'route' => 'resumes',
                'params' => [],
                'icon' => 'DocumentSearchIcon',
                'component' => 'Resumes',
                'access' => checkIfRouteRestricted('resumes'),
                'isActive' => activeMenu('resumes', ['index', 'show']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Search',
                        'route' => 'admin.resumes.index',
                        'params' => [],
                        'icon' => 'SearchIcon',
                        'component' => 'Resumes/List',
                        'access' => checkIfRouteRestricted('resumes'),
                        'isActive' => activeMenu('resumes', ['index', 'show']),
                    ],
                ],
            ],
            [
                'name' => 'Jobs',
                'route' => 'jobs',
                'params' => [],
                'icon' => 'DocumentSearchIcon',
                'component' => 'Jobs',
                'access' => checkIfRouteRestricted('jobs') || checkIfRouteRestricted('saved-jobs'),
                'isActive' => activeMenu('jobs', ['index', 'show']) || activeMenu('saved-jobs', ['index']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Search',
                        'route' => 'admin.jobs.index',
                        'params' => [],
                        'icon' => 'SearchIcon',
                        'component' => 'Jobs/List',
                        'access' => checkIfRouteRestricted('jobs'),
                        'isActive' => activeMenu('jobs', ['index', 'show']),
                    ],
                    [
                        'name' => 'Saved',
                        'route' => 'admin.saved-jobs.index',
                        'params' => [],
                        'icon' => 'BookmarkIcon',
                        'component' => 'SavedVacancies/Index',
                        'access' => checkIfRouteRestricted('saved-jobs'),
                        'isActive' => activeMenu('saved-jobs', ['index']),
                    ],
                ],
            ],
            [
                'name' => 'Candidates',
                'route' => 'candidates',
                'params' => [],
                'icon' => 'UserGroupIcon',
                'component' => 'Candidates',
                'access' => checkIfRouteRestricted('candidates'),
                'isActive' => activeMenu('candidates', ['index', 'create', 'show']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Add',
                        'route' => 'admin.candidates.create',
                        'params' => [
                            'tab' => 'personal-details'
                        ],
                        'icon' => 'PlusIcon',
                        'component' => 'Candidates/Create',
                        'access' => checkIfRouteRestricted('candidates'),
                        'isActive' => activeMenu('candidates', ['create', 'show']),
                    ],
                    [
                        'name' => 'Manage',
                        'route' => 'admin.candidates.index',
                        'params' => [],
                        'icon' => 'DocumentSearchIcon',
                        'component' => 'Candidates/Index',
                        'access' => checkIfRouteRestricted('candidates'),
                        'isActive' => activeMenu('candidates', ['index']),
                    ],
                ],
            ],
            [
                'name' => 'Companies',
                'route' => 'companies',
                'params' => [],
                'icon' => 'OfficeBuildingIcon',
                'component' => 'Companies',
                'access' => checkIfRouteRestricted('companies'),
                'isActive' => activeMenu('companies', ['index', 'create', 'show']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Add',
                        'route' => 'admin.companies.create',
                        'params' => [],
                        'icon' => 'PlusIcon',
                        'component' => 'Companies/Create',
                        'access' => checkIfRouteRestricted('companies'),
                        'isActive' => activeMenu('companies', ['create', 'show']),
                    ],
                    [
                        'name' => 'Manage',
                        'route' => 'admin.companies.index',
                        'params' => [],
                        'icon' => 'DocumentSearchIcon',
                        'component' => 'Companies/Index',
                        'access' => checkIfRouteRestricted('companies'),
                        'isActive' => activeMenu('companies', ['index']),
                    ],
                ],
            ],
            [
                'name' => 'Students',
                'route' => 'students',
                'params' => [],
                'icon' => 'UserGroupIcon',
                'component' => 'Students',
                'access' => checkIfRouteRestricted('students'),
                'isActive' => activeMenu('students', ['index', 'create', 'show', 'uploading']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Add',
                        'route' => 'admin.students.create',
                        'params' => [],
                        'icon' => 'PlusIcon',
                        'component' => 'Students/Create',
                        'access' => checkIfRouteRestricted('students'),
                        'isActive' => activeMenu('students', ['create', 'show']),
                    ],
                    [
                        'name' => 'Manage',
                        'route' => 'admin.students.index',
                        'params' => [],
                        'icon' => 'DocumentSearchIcon',
                        'component' => 'Students/Index',
                        'access' => checkIfRouteRestricted('students'),
                        'isActive' => activeMenu('students', ['index']),
                    ],
                    [
                        'name' => 'Bulk Uploading',
                        'route' => 'admin.students.uploading',
                        'params' => [],
                        'icon' => 'UploadIcon',
                        'component' => 'Students/Uploading',
                        'access' => checkIfRouteRestricted('students'),
                        'isActive' => activeMenu('students', ['uploading']),
                    ],
                ],
            ],
            [
                'name' => 'Workshops',
                'route' => 'workshops',
                'params' => [],
                'icon' => 'FlagIcon',
                'component' => 'Workshops',
                'access' => checkIfRouteRestricted('workshops'),
                'isActive' => activeMenu('workshops', ['index', 'create', 'show']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Add',
                        'route' => 'admin.workshops.create',
                        'params' => [],
                        'icon' => 'PlusIcon',
                        'component' => 'Workshops/Create',
                        'access' => checkIfRouteRestricted('workshops'),
                        'isActive' => activeMenu('workshops', ['create', 'show']),
                    ],
                    [
                        'name' => 'Manage',
                        'route' => 'admin.workshops.index',
                        'params' => [],
                        'icon' => 'DocumentSearchIcon',
                        'component' => 'Workshops/Index',
                        'access' => checkIfRouteRestricted('workshops'),
                        'isActive' => activeMenu('workshops', ['index']),
                    ],
                ],
            ],
            [
                'name' => 'Projects',
                'route' => 'projects',
                'params' => [],
                'icon' => 'UserGroupIcon',
                'component' => 'Projects',
                'access' => checkIfRouteRestricted('projects'),
                'isActive' => activeMenu('projects', ['index', 'create', 'show']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Add',
                        'route' => 'admin.projects.create',
                        'params' => [],
                        'icon' => 'PlusIcon',
                        'component' => 'Projects/Create',
                        'access' => checkIfRouteRestricted('projects'),
                        'isActive' => activeMenu('projects', ['create', 'show']),
                    ],
                    [
                        'name' => 'Manage',
                        'route' => 'admin.projects.index',
                        'params' => [],
                        'icon' => 'DocumentSearchIcon',
                        'component' => 'Projects/Index',
                        'access' => checkIfRouteRestricted('projects'),
                        'isActive' => activeMenu('projects', ['index']),
                    ],
                ],
            ],
            [
                'name' => 'Post a Job',
                'route' => 'vacancies',
                'params' => [],
                'icon' => 'UserGroupIcon',
                'component' => 'Vacancies',
                'access' => checkIfRouteRestricted('vacancies'),
                'isActive' => activeMenu('vacancies', ['index', 'create', 'show']),
                'hasChildren' => true,
                'children' => [
                    [
                        'name' => 'Add',
                        'route' => 'admin.vacancies.create',
                        'params' => [],
                        'icon' => 'PlusIcon',
                        'component' => 'Vacancies/Create',
                        'access' => checkIfRouteRestricted('vacancies'),
                        'isActive' => activeMenu('vacancies', ['create', 'show']),
                    ],
                    [
                        'name' => 'Manage',
                        'route' => 'admin.vacancies.index',
                        'params' => [],
                        'icon' => 'DocumentSearchIcon',
                        'component' => 'Vacancies/Index',
                        'access' => checkIfRouteRestricted('vacancies'),
                        'isActive' => activeMenu('vacancies', ['index']),
                    ],
                ],
            ],
            [
                'name' => 'Applications',
                'route' => 'admin.applications.index',
                'params' => [
                    'tab' => 'pending'
                ],
                'icon' => 'ChartBarIcon',
                'component' => 'Applications',
                'access' => checkIfRouteRestricted('applications'),
                'isActive' => activeMenu('applications', ['index', 'create', 'show']),
                'children' => null,
                'hasChildren' => false,
            ],
            [
                'name' => 'Roles',
                'route' => 'admin.roles.index',
                'params' => [],
                'icon' => 'ChartBarIcon',
                'component' => 'Roles',
                'access' => checkIfRouteRestricted('roles'),
                'isActive' => activeMenu('roles', ['index', 'create', 'show']),
                'children' => null,
                'hasChildren' => false,
            ],
            [
                'name' => 'Projects Names',
                'route' => 'admin.projects-names.index',
                'params' => [],
                'icon' => 'ChartBarIcon',
                'component' => 'ProjectsNames',
                'access' => checkIfRouteRestricted('projects-names'),
                'isActive' => activeMenu('projects-names', ['index', 'create', 'show']),
                'children' => null,
                'hasChildren' => false,
            ],
            [
                'name' => 'Workshops Names',
                'route' => 'admin.workshops-names.index',
                'params' => [],
                'icon' => 'ChartBarIcon',
                'component' => 'WorkshopsNames',
                'access' => checkIfRouteRestricted('workshops-names'),
                'isActive' => activeMenu('workshops-names', ['index', 'create', 'show']),
                'children' => null,
                'hasChildren' => false,
            ],
        ];
    }
}

if (!function_exists('activeMenu')) {
    function activeMenu(?string $domain, ?array $abilities = []): bool
    {
        $string = Route::currentRouteName();

        $parts = explode('.', $string);

        if (count($parts) < 2) {
            return false;
        }

        $secondFromLast = $parts[count($parts) - 2] ?? null;

        $ability = end($parts);

        return $secondFromLast === $domain && !empty($abilities) && in_array($ability, $abilities, true);
    }
}

if (!function_exists('checkIfRouteRestricted')) {
    function checkIfRouteRestricted(?string $domain): bool
    {
        return in_array($domain, array_values(
            getUserAssignedUniqueDomains()->get('domains')
        ), true);
    }
}

if (!function_exists('getUserAssignedUniqueDomains')) {
    function getUserAssignedUniqueDomains(?string $column = 'domain', $user = null): Collection
    {
        $user = $user ?: auth()->user();

        $domains = $user ? $user->permissions->pluck($column)->unique()->values()->toArray() : [];
        $abilities = $user ? $user->permissions->pluck('ability')->unique()->values()->toArray() : [];

        return collect([
            'domains' => $domains,
            'abilities' => $abilities,
        ]);
    }
}

if (!function_exists('findUserById')) {
    function findUserById(int $id)
    {
        return User::withTrashed()->where('id', $id)->first();
    }
}

if (!function_exists('findVacancyById')) {
    function findVacancyById(string $id)
    {
        return Vacancy::withTrashed()->where('id', $id)->first();
    }
}

if (!function_exists('updateFolderPermissions')) {
    function updateFolderPermissions($path): void
    {
        $directories = File::directories($path);

        foreach ($directories as $directory) {
            File::chmod($directory, 0777);
            updateFolderPermissions($directory);
        }
    }
}

if (!function_exists('domainStates')) {
    function domainStates(?string $domain = 'register'): bool|int|string
    {
        $domains = [
            'register' => 'register',
            'created' => 'created',
            'accepted' => 'accepted',
            'pending' => 'pending',
            're-scheduled' => 're-scheduled',
            'approved' => 'approved',
            'completed' => 'completed',
            'delivered' => 'delivered',
            'rejected' => 'rejected',
            'subscribed' => 'subscribed',
            'updated' => 'updated',
            'canceled' => 'canceled',
            'payment' => 'payment',
            'assigned' => 'assigned',
            'allocated' => 'allocated',
            'called' => 'called',
        ];

        return array_search($domain, $domains, true);
    }
}

if (!function_exists('notificationStates')) {
    function notificationStates(?string $state = 'push'): bool|int|string
    {
        $states = [
            'general' => 'general',
            'offer' => 'offer',
            'push' => 'push',
            'call' => 'call',
        ];

        return array_search($state, $states, true);
    }
}

if (!function_exists('folderTypes')) {
    function folderTypes(): Collection
    {
        return collect(['thumbnail', 'medium']);
//        return collect(['thumbnail', 'large', 'medium']);
    }
}

if (!function_exists('formattedDateTime')) {
    function formattedDateTime($date, ?string $format = 'Y-m-d H:i A'): ?string
    {
        return $date ? Carbon::parse($date)->format($format) : null;
    }
}

if (!function_exists('pageSetting')) {
    function pageSetting(?string $type = 'about'): Model|Builder|null
    {
        return PageSetting::query()->where('type', $type)->first();
    }
}

if (!function_exists('manageSession')) {
    function manageSession()
    {
        $role = request()->get('role');

        session()->forget('selectedRole');
        session(['selectedRole' => $role]);

        return $role;
    }
}

if (!function_exists('calculateAge')) {
    function calculateAge(string $dob): int
    {
        return Carbon::parse($dob)->age;
    }
}

if (!function_exists('generateStdNumber')) {
    function generateStdNumber(): string
    {
        $prefix = 'STD';
        $latest = Student::query()->latest('student_id')
            ->value('student_id');

        $newNumber = $latest
            ? ((int) str_replace($prefix, '', $latest)) + 1
            : 1;

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('candidateTabs')) {
    function candidateTabs(?string $tab = 'personal-details'): string
    {
        $tabs = [
            'personal-details' => 'professional-information',
            'professional-information' => 'educational-background',
            'educational-background' => 'work-experiences',
            'work-experiences' => 'skill-and-certificates',
            'skill-and-certificates' => 'resume-and-portfolio',
            'resume-and-portfolio' => 'job-preferences',
            'job-preferences' => 'additional-information',
            'additional-information' => 'personal-details',
        ];

        return $tabs[$tab] ?? '';
    }
}

if(!function_exists('getTabNameFromStep')) {
    function getTabNameFromStep(string $step): string
    {
        $tabMapping = [
            'one' => 'personal-details',
            'two' => 'professional-information',
            'three' => 'educational-background',
            'four' => 'work-experiences',
            'five' => 'skill-and-certificates',
            'six' => 'resume-and-portfolio',
            'seven' => 'job-preferences',
            'eight' => 'additional-information',
        ];

        return $tabMapping[$step] ?? 'personal-details';
    }
}

if(!function_exists('getUserRole')) {
    function getUserRole($user): ?string
    {
        return $user ? $user->roles()->first()->name : null;
    }
}


if(!function_exists('addHyphenBetweenNumbers')) {
    function addHyphenBetweenNumbers(?string $string): array|string|null
    {
        return preg_replace('/(\d+)\s+(\d+)/', '$1-$2', $string);
    }
}