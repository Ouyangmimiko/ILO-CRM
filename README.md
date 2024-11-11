# ILO-CRM
## Project Overview
ILO Partner Database System is a database management system designed for the Industrial Liaison Office (ILO) to handle information about organizations and contacts for various types of collaborations and engagements. The system is built using Laravel for the backend and Vue 3 for the frontend to provide dynamic data management and user-friendly interaction.

## Tech Stack
- Backend: Laravel 11, MySQL 8.0+
- Frontend: Vue 3, Axios
- Server: AWS EC2, GitHub Actions (for deployment automation)

## Features
- Manage organizational and contact information.
- Supports dynamic generation of data for multiple academic years.
- Includes a user authentication system with login, registration, and permission management.
- Provides data tables for viewing and editing, including academic year information (e.g., Mentoring, Year in Industry).
- RESTful API endpoints for frontend-backend interaction.

## Setup Instructions (Not complicated, may refer to GPT with 'How to run Laravel and vue 3 project)
1. Clone the Repository
```bash
git clone <repo-url>
cd <project-folder>
```
2. Install Backend Dependencies
```bash
composer install
```
3. Configure Environment Variables\
Copy the '.env.example' file and rename it to '.env', then update the environment variables as needed (e.g., database connection, mail configuration).
```bash
cp .env.example .env
```
4. Set Up the Database\
Run the migrations and seed the database:
```bash
php artisan migrate
```
5. Install Frontend Dependencies
```bash
npm install
```
6. Start Development Servers
  - Start the backend (Laravel):
```bash
php artisan serve
```
  - Start the frontend (Vue 3):
```bash
npm run dev
```

## Feature Details
1. Dynamic Academic Year Data\
The system allows dynamic data generation based on selected academic years (e.g., 2024-2025, 2025-2026). Users can choose from various academic years in tables and forms, and the system automatically populates the relevant fields.

2. User Authentication\
Laravel's built-in authentication system (via Auth) is used for user registration, login, and permission management. The frontend uses Vuex for session management, ensuring a smooth user experience.

## Deployment & CI/CD
1. AWS EC2 Setup\
The project is deployed on an AWS EC2 instance. It also utilizes GitHub Actions for continuous integration and deployment (CI/CD). The CI/CD pipeline automatically builds and deploys code whenever a new commit is pushed to the repository.

2. Code Deployment\
To automate the deployment process, GitHub Actions is used to deploy the application to the server after each commit.

## Common Issues
1. Database Connection Errors: \
If you encounter database connection issues, check the .env file and verify the DB_HOST, DB_DATABASE, DB_USERNAME, and DB_PASSWORD values.
2. CORS Issues: \
If you're running into CORS issues between the Vue frontend and the Laravel backend, ensure that the CORS middleware is properly configured in the Laravel application.

## Future Development
- Complete the email service and password reset functionality.
- Optimize database design for better query performance.
- Add multi-language support for internationalization.
## use of AWS EC2 
https://docs.google.com/document/d/15-jI7dai9uKKGYXAAgmA7VMU3JsqqG_agOWGdYhlDJQ/edit?usp=sharing

## domain name
https://www.uob-ilo-crm.com

## default admin user
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'iamAdmin_user',
