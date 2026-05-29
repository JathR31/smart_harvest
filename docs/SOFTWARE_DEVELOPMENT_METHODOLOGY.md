# CHAPTER 3
# SOFTWARE DEVELOPMENT METHODOLOGY

## 3.1 Overview

This chapter presents the software development methodology used in SmartHarvest, an agricultural intelligence platform for farmers and agricultural administrators in Benguet Province. The project followed a hybrid methodology that combines Agile development, iterative prototyping, MVC architectural design, service-oriented integration, testing-first quality practices, and DevOps deployment automation.

The objective of this methodology was to deliver a reliable, scalable, and user-centered system that can support climate-aware agricultural decision-making while remaining maintainable for future enhancement.

## 3.2 Development Approach

### 3.2.1 Agile and Iterative Development

SmartHarvest adopted Agile principles to support incremental delivery and continuous improvement. Development was organized into short cycles, with each cycle producing functional outputs that could be validated by stakeholders.

Key Agile practices included:
- Incremental release planning by feature group
- Frequent feedback integration from target users and advisers
- Continuous reprioritization of tasks based on implementation findings
- Documentation updates aligned with each completed feature

Implementation progressed through the following major phases:
1. User authentication and account management
2. Administrative dashboard and data workflows
3. Climate and production data analytics modules
4. Farmer-facing dashboard and API integration
5. Machine learning-enabled prediction features
6. Multi-language and OTP verification enhancements
7. Deployment hardening and production optimization

This iterative approach reduced risk and allowed the team to validate functionality before introducing more advanced modules.

### 3.2.2 MVC Architectural Method

The system follows the Model-View-Controller (MVC) pattern using Laravel 12.

- Model layer: Handles business entities such as users, crop data, market prices, climate patterns, and validation alerts.
- View layer: Uses Blade templates, Tailwind CSS, and Alpine.js for responsive, interactive interfaces.
- Controller layer: Encapsulates request handling, workflow logic, and API responses.

Using MVC ensured separation of concerns, improved code maintainability, and simplified future extensions.

### 3.2.3 Service-Oriented Integration

SmartHarvest uses a service-oriented integration strategy where the core Laravel application communicates with external and internal services through HTTP APIs.

Integrated services include:
- Python Flask machine learning API for prediction and recommendation workflows
- OpenWeather API for weather and climate context
- SMS service for OTP and notification workflows
- SMTP-based email service for verification and communication

This design allows independent scaling, isolated troubleshooting, and modular upgrades for each service.

## 3.3 System Architecture Methodology

### 3.3.1 Multi-Tier Architecture

The software architecture is organized into four tiers:
- Presentation tier: Blade UI templates with responsive frontend behavior
- Application tier: Laravel business logic, middleware, and controllers
- Data tier: Relational database via Eloquent ORM and migrations
- Integration tier: API communication with ML and external provider services

This layered design supports maintainability, security boundaries, and clearer responsibility mapping.

### 3.3.2 Database Design Method

The database design followed relational modeling and normalization principles.

Applied strategies:
- Migration-driven schema evolution for version-controlled database changes
- Foreign key and unique constraints for integrity and consistency
- Indexed fields for frequent queries and dashboard performance
- Seeder-based initialization for realistic local and test datasets

The migration-first approach ensures controlled schema evolution and rollback capability.

## 3.4 Quality Assurance and Testing Methodology

### 3.4.1 Testing Strategy

SmartHarvest applied a layered testing strategy combining automated and manual validation.

Automated testing tools:
- PHPUnit for unit and feature tests
- Pest for expressive test structure and readability

Manual and integration testing:
- API endpoint verification
- ML service connectivity and response checks
- Email and SMS delivery validation
- User workflow simulation for registration, login, OTP, and dashboard access

### 3.4.2 Validation and Data Quality Controls

To ensure data reliability, the system implements:
- Request-level validation rules
- Data-type and range checks
- Input sanitization and constraint enforcement
- Data validation alerts for anomaly monitoring
- Administrative activity logs for accountability

These mechanisms support both functional correctness and operational transparency.

## 3.5 DevOps and Deployment Methodology

### 3.5.1 Containerized Deployment

The project uses Docker-based deployment to keep environments consistent from development to production.

Containerized setup includes:
- PHP-FPM runtime for Laravel
- Nginx as web server and reverse proxy
- Supervisor for process management
- Automated build steps for Composer and frontend assets

### 3.5.2 CI/CD and Environment Management

Deployment is integrated with GitHub and Render for automated build and release execution.

Core practices include:
- Build trigger on push to the main branch
- Environment-variable-driven configuration
- Migration execution during release cycle
- Health monitoring and post-deploy checks

This process improves release repeatability and reduces configuration drift.

## 3.6 Security and Access Control Methodology

Security is built into the development process through framework-level and workflow-level controls.

Implemented controls:
- Password hashing and secure authentication guards
- CSRF protection and XSS-safe templating
- ORM-driven SQL injection resistance
- Role-based authorization (farmer, admin, superadmin)
- OTP and two-factor authentication for sensitive access
- Session control and audit logging of sensitive actions

These controls align with the system requirement for secure access to agricultural and administrative data.

## 3.7 Technology Stack Alignment

The chosen technologies were selected based on capability, maintainability, and deployment readiness.

- Backend: Laravel 12, PHP 8.2, Composer
- Frontend: Blade, Tailwind CSS, Alpine.js, Vite
- Data layer: MySQL/PostgreSQL with Eloquent ORM
- ML layer: Python 3.8+, Flask, scikit-learn, pandas, numpy
- Deployment: Docker, Nginx, Supervisor, Render
- Tooling: GitHub, npm, PHPUnit, Pest

This stack supports rapid development, clear architecture boundaries, and production operability.

## 3.8 Summary

SmartHarvest was developed using a hybrid methodology centered on Agile iteration, structured architecture, and continuous validation. The combination of MVC design, service-oriented integration, layered testing, and DevOps automation enabled the team to deliver a robust and extensible agricultural platform. This methodology also provides a repeatable foundation for future enhancements such as additional crop models, deeper analytics, and wider regional rollout.
