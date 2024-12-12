<?php

namespace Domain\Category\Seeders;

use Domain\Category\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Information Technology (IT)',
                'children' => [
                    'Software Development',
                    'IT Support',
                    'Network Administration',
                    'Cyber Security',
                    'Data Science',
                    'DevOps',
                    'Web Development',
                    'Mobile App Development',
                ],
            ],
            [
                'title' => 'Engineering',
                'children' => [
                    'Mechanical Engineering',
                    'Electrical Engineering',
                    'Civil Engineering',
                    'Chemical Engineering',
                    'Software Engineering',
                    'Environmental Engineering',
                    'Aerospace Engineering',
                ],
            ],
            [
                'title' => 'Healthcare',
                'children' => [
                    'Nursing',
                    'Medical Administration',
                    'Medical Technicians',
                    'Physicians',
                    'Healthcare Support',
                    'Pharmacy',
                    'Mental Health',
                ],
            ],
            [
                'title' => 'Sales and Marketing',
                'children' => [
                    'Sales Representative',
                    'Digital Marketing',
                    'Content Creation',
                    'Social Media Management',
                    'Public Relations',
                    'SEO/SEM Specialist',
                    'Advertising',
                ],
            ],
            [
                'title' => 'Finance and Accounting',
                'children' => [
                    'Accounting',
                    'Financial Analysis',
                    'Banking',
                    'Auditing',
                    'Taxation',
                    'Investment Banking',
                    'Payroll',
                ],
            ],
            [
                'title' => 'Education and Training',
                'children' => [
                    'Teaching (K-12, Higher Education)',
                    'Curriculum Development',
                    'Educational Administration',
                    'Training and Development',
                    'Online Instruction',
                ],
            ],
            [
                'title' => 'Human Resources (HR)',
                'children' => [
                    'Recruitment',
                    'HR Administration',
                    'Payroll',
                    'Employee Relations',
                    'Learning and Development',
                    'Compensation and Benefits',
                ],
            ],
            [
                'title' => 'Customer Service',
                'children' => [
                    'Call Center',
                    'Help Desk',
                    'Customer Support',
                    'Client Services',
                    'Technical Support',
                ],
            ],
            [
                'title' => 'Administration',
                'children' => [
                    'Office Management',
                    'Executive Assistant',
                    'Data Entry',
                    'Receptionist',
                    'Administrative Support',
                ],
            ],
            [
                'title' => 'Legal',
                'children' => [
                    'Legal Assistant',
                    'Paralegal',
                    'Attorney',
                    'Corporate Law',
                    'Compliance',
                    'Legal Administration',
                ],
            ],
            [
                'title' => 'Manufacturing and Production',
                'children' => [
                    'Production Management',
                    'Quality Control',
                    'Operations Management',
                    'Assembly Line Work',
                    'Supply Chain Management',
                    'Maintenance Technician',
                ],
            ],
            [
                'title' => 'Construction and Real Estate',
                'children' => [
                    'Construction Management',
                    'Real Estate Agent',
                    'Property Management',
                    'Architecture',
                    'Surveying',
                    'Project Management',
                ],
            ],
            [
                'title' => 'Arts and Design',
                'children' => [
                    'Graphic Design',
                    'Photography',
                    'Video Production',
                    'Fashion Design',
                    'Interior Design',
                    'Animation',
                ],
            ],
            [
                'title' => 'Hospitality and Tourism',
                'children' => [
                    'Hotel Management',
                    'Travel and Tourism',
                    'Event Planning',
                    'Food and Beverage',
                    'Guest Services',
                ],
            ],
            [
                'title' => 'Transportation and Logistics',
                'children' => [
                    'Truck Driving',
                    'Supply Chain Management',
                    'Warehouse Management',
                    'Shipping and Receiving',
                    'Fleet Management',
                    'Logistics Coordination',
                ],
            ],
            [
                'title' => 'Retail',
                'children' => [
                    'Store Management',
                    'Sales Associate',
                    'Inventory Management',
                    'Visual Merchandising',
                    'E-commerce',
                ],
            ],
            [
                'title' => 'Media and Communications',
                'children' => [
                    'Journalism',
                    'Public Relations',
                    'Broadcasting',
                    'Copyrighting',
                    'Editing',
                    'Communications Specialist',
                ],
            ],
            [
                'title' => 'Science and Research',
                'children' => [
                    'Research and Development',
                    'Laboratory Technician',
                    'Environmental Science',
                    'Biotechnology',
                    'Pharmaceutical Research',
                ],
            ],
            [
                'title' => 'Non-Profit and Social Services',
                'children' => [
                    'Social Work',
                    'Fundraising',
                    'Program Management',
                    'Community Outreach',
                    'Volunteer Coordination',
                ],
            ],
            [
                'title' => 'Government and Public Administration',
                'children' => [
                    'Policy Analysis',
                    'Public Relations',
                    'Urban Planning',
                    'Law Enforcement',
                    'Public Health',
                ],
            ],
            [
                'title' => 'Energy and Utilities',
                'children' => [
                    'Renewable Energy',
                    'Oil and Gas',
                    'Electric Power',
                    'Utility Management',
                    'Environmental Services',
                ],
            ],
            [
                'title' => 'Agriculture and Farming',
                'children' => [
                    'Agricultural Engineering',
                    'Farm Management',
                    'Horticulture',
                    'Veterinary Services',
                    'Fisheries',
                ],
            ],
            [
                'title' => 'Automotive',
                'children' => [
                    'Auto Mechanics',
                    'Sales and Service',
                    'Auto Body Repair',
                    'Automotive Engineering',
                ],
            ],
            [
                'title' => 'Security and Protective Services',
                'children' => [
                    'Security Guard',
                    'Firefighting',
                    'Law Enforcement',
                    'Emergency Response',
                    'Private Investigation',
                ],
            ],
            [
                'title' => 'Entertainment and Sports',
                'children' => [
                    'Acting',
                    'Music',
                    'Coaching',
                    'Fitness Training',
                    'Event Coordination',
                ],
            ],
        ];

        $categories = collect($data)->map(function ($item) {
            return [
                'title' => $item['title'],
                'slug' => slugGenerator($item['title']),
                'children' => $item['children'],
            ];
        })->toArray();

        collect($categories)->each(function ($categoryData) {
            $categoryChildren = $categoryData['children'] ?? [];
            $categoryDataWithoutChildren = $categoryData;
            unset($categoryDataWithoutChildren['children']);
            $parentCategory = Category::factory()->create($categoryDataWithoutChildren);

            collect($categoryChildren)->each(function ($category) use ($parentCategory) {
                Category::factory()->create([
                    'title' => $category,
                    'slug' => slugGenerator($category),
                    'parent_id' => $parentCategory->id,
                ]);
            });
        });
    }
}
