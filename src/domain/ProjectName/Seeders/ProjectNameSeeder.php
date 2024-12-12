<?php

namespace Domain\ProjectName\Seeders;

use Domain\ProjectName\Models\ProjectName;
use Illuminate\Database\Seeder;

    class ProjectNameSeeder extends Seeder
{
    public function run(): void
    {
        $projectsNames = [
            [
                'name' => 'Face Recognition System using Python',
            ],
            [
                'name' => 'Online Shopping Portal with Payment Gateway',
            ],
            [
                'name' => 'IoT-based Smart Home Automation',
            ],
            [
                'name' => 'Machine Learning-Based Stock Prediction',
            ],
            [
                'name' => 'Blockchain-Based Voting System',
            ],
            [
                'name' => 'Mobile Banking App with Secure Payment',
            ],
            [
                'name' => 'Data Visualization with Pandas/Matplotlib',
            ],
            [
                'name' => 'Secure File Sharing System with Blockchain',
            ],
            [
                'name' => 'AI Chatbot for Customer Service',
            ],
            [
                'name' => 'Movie Recommendation System',
            ],
            [
                'name' => 'Smart Traffic Management System using IoT',
            ],
            [
                'name' => 'Home Automation with Arduino',
            ],
            [
                'name' => 'GSM-based Digital Notice Board',
            ],
            [
                'name' => 'IoT-based Health Monitoring System',
            ],
            [
                'name' => 'RFID-based Attendance System',
            ],
            [
                'name' => 'Image Processing with MATLAB',
            ],
            [
                'name' => 'Robotic Arm Controlled via Bluetooth',
            ],
            [
                'name' => 'GPS and GSM-based Vehicle Tracking',
            ],
            [
                'name' => '5G Antenna Design & Simulation',
            ],
            [
                'name' => 'Smart Energy Meter with IoT',
            ],
            [
                'name' => 'Solar Power Management System',
            ],
            [
                'name' => 'Smart Energy Meter with GSM',
            ],
            [
                'name' => 'Design of Electric Vehicle Charging Station',
            ],
            [
                'name' => 'Power Quality Improvement in Distribution Systems',
            ],
            [
                'name' => 'Smart Grid Automation',
            ],
            [
                'name' => 'Fault Detection in Electrical Systems',
            ],
            [
                'name' => 'Automated Irrigation System using Solar Power',
            ],
            [
                'name' => 'Microgrid Design & Simulation',
            ],
            [
                'name' => 'Voltage Stability in Power Systems',
            ],
            [
                'name' => '3D Printed Model Design & Fabrication',
            ],
            [
                'name' => 'Automated Conveyor Belt System',
            ],
            [
                'name' => 'Solar-Powered Water Heater',
            ],
            [
                'name' => 'Pneumatic Arm for Material Handling',
            ],
            [
                'name' => 'Regenerative Braking System for EVs',
            ],
            [
                'name' => 'Wind Turbine Blade Design & Analysis',
            ],
            [
                'name' => 'Robotic Pick & Place Mechanism',
            ],
            [
                'name' => 'Air Conditioning System Simulation',
            ],
            [
                'name' => 'Fuel Efficiency in IC Engines',
            ],
            [
                'name' => 'Earthquake-Resistant Building Design',
            ],
            [
                'name' => 'Smart City Development with GIS',
            ],
            [
                'name' => 'Water Management System for Rural Areas',
            ],
            [
                'name' => 'Green Building Design with Renewable Energy',
            ],
            [
                'name' => 'Soil Stabilization Techniques for Pavements',
            ],
            [
                'name' => 'Automated Irrigation System',
            ],
            [
                'name' => 'Traffic Management System for Urban Areas',
            ],
            [
                'name' => 'Environmental Impact of Construction',
            ],
            [
                'name' => 'Bridge Design & Load Analysis',
            ],
            [
                'name' => 'Rainwater Harvesting System',
            ],
            [
                'name' => 'DNA Extraction from Plant Cells',
            ],
            [
                'name' => 'Antibiotic Resistance Study in Bacteria',
            ],
            [
                'name' => 'Plant Tissue Culture for Genetic Modification',
            ],
            [
                'name' => 'Water Pollution Analysis with Microbial Indicators',
            ],
            [
                'name' => 'Bioethanol Production from Agricultural Waste',
            ],
            [
                'name' => 'Microbial Fuel Cells for Energy',
            ],
            [
                'name' => 'Biodegradation of Plastic by Microbes',
            ],
            [
                'name' => 'Herbal Remedies for Common Ailments',
            ],
            [
                'name' => 'Immunological Techniques for Disease Detection',
            ],
            [
                'name' => 'Solar Power Generator Design',
            ],
            [
                'name' => 'Light Reflection & Refraction Study',
            ],
            [
                'name' => 'Electric Motor Construction',
            ],
            [
                'name' => 'Tesla Coil Design',
            ],
            [
                'name' => 'Projectile Motion Analysis',
            ],
            [
                'name' => 'Solar Water Heater Design',
            ],
            [
                'name' => 'Study of Superconductors',
            ],
            [
                'name' => 'Simple Harmonic Oscillator Simulation',
            ],
            [
                'name' => 'Synthesis of Biodegradable Plastics',
            ],
            [
                'name' => 'Water Purification Techniques',
            ],
            [
                'name' => 'Essential Oil Extraction from Plants',
            ],
            [
                'name' => 'pH Analysis of Soaps & Detergents',
            ],
            [
                'name' => 'Synthesis of Aspirin',
            ],
            [
                'name' => 'Corrosion Study & Prevention',
            ],
            [
                'name' => 'Heavy Metals in Drinking Water Analysis',
            ],
            [
                'name' => 'Biofuel Production from Waste',
            ],
            [
                'name' => 'Cryptography & Data Security',
            ],
            [
                'name' => 'Climate Data Statistical Analysis',
            ],
            [
                'name' => 'Mathematical Modelling of Epidemics',
            ],
            [
                'name' => 'Traffic Flow Optimization using Graph Theory',
            ],
            [
                'name' => 'Machine Learning Algorithms & Applications',
            ],
            [
                'name' => 'Investment & Risk Analysis Models',
            ],
            [
                'name' => 'Population Growth Mathematical Modelling',
            ],
            [
                'name' => 'Water Quality Analysis',
            ],
            [
                'name' => 'Rainwater Harvesting System Design',
            ],
            [
                'name' => 'Soil Fertility & Crop Yield Analysis',
            ],
            [
                'name' => 'Urbanization Impact on Natural Habitats',
            ],
            [
                'name' => 'Waste Segregation at Campus',
            ],
            [
                'name' => 'Carbon Footprint Calculation',
            ],
            [
                'name' => 'Renewable Energy Solutions for Rural Areas',
            ],
            [
                'name' => 'Organic Fertilizer Development',
            ],
            [
                'name' => 'Drip Irrigation System Design',
            ],
            [
                'name' => 'Soil Health Analysis for Crops',
            ],
            [
                'name' => 'Greenhouse Farming Study',
            ],
            [
                'name' => 'Pest Management Using Natural Predators',
            ],
            [
                'name' => 'Biogas Production from Waste',
            ],
            [
                'name' => 'Agricultural Marketing Channels Analysis',
            ],

            ['name' => 'Business Process Re-engineering for Efficiency'],
            ['name' => 'Strategic Analysis of a Company\'s Global Expansion'],
            ['name' => 'Change Management Strategy in a Post-Merger Scenario'],
            ['name' => 'Digital Marketing Strategy for a New Product Launch'],
            ['name' => 'Market Research Project: Analyzing Consumer Preferences for a Product'],
            ['name' => 'Customer Segmentation & Targeting for a Retail Chain'],
            ['name' => 'Portfolio Analysis & Investment Strategy for an Individual Investor'],
            ['name' => 'Financial Statement Analysis of Leading Companies'],
            ['name' => 'Capital Budgeting & Feasibility Analysis for a Manufacturing Unit'],
            ['name' => 'Study on Employee Turnover & Retention Strategies'],
            ['name' => 'Designing a Performance Appraisal System for an Organization'],
            ['name' => 'Talent Acquisition Strategy for a Tech Company'],
            ['name' => 'Optimization of Logistics and Distribution for an FMCG Company'],
            ['name' => 'Inventory Management System for a Manufacturing Company'],
            ['name' => 'Application of Six Sigma in Process Improvement'],
            ['name' => 'Feasibility Study of a Startup Idea'],
            ['name' => 'Strategies for Scaling Up a Startup in India'],
            ['name' => 'Business Model Development for an E-Commerce Platform'],
            ['name' => 'Implementing a CRM System for a Retail Chain'],
            ['name' => 'Using AI to Optimize Business Processes in a Manufacturing Firm'],
            ['name' => 'Development of a Data Analytics Dashboard for Sales Performance']
        ];


        collect($projectsNames)->each(function ($projectName) {
            ProjectName::factory()->create([
                'name' => $projectName['name'],
            ]);
        });
    }
}
