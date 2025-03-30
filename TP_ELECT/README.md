# Electricity Management System

## Project Overview
This is a web-based electricity management system that facilitates interaction between electricity providers and clients. The application allows providers to manage clients, track electricity consumption, generate bills, and handle complaints, while clients can view their consumption, pay bills, and submit complaints.

## Project Structure

The project follows a modular architecture with clear separation of concerns:

### Root Directory
- `index.php` - The main entry point that provides access to client and provider portals
- `style.css` - Central stylesheet with modern design elements and responsive features

### Database Layer (BD/)
- `gestion_elec_db.sqlite` - SQLite database file storing all application data
- `db.php` - Core database connection and query functions
- Data models:
  - `Client.php` - Manages client data (registration, authentication, profile)
  - `Consumption.php` - Handles electricity consumption records
  - `Facture.php` - Manages billing operations and payment records
  - `Fournisseur.php` - Handles provider data and authentication
  - `Agent.php` - Manages service agents assigned to handle complaints
  - `Reclamation.php` - Handles customer complaints and their status
  - `Notification.php` - Manages system notifications for users

### User Interface (IHM/)
Front-end components organized by module:
- `client/` - Client portal interfaces
  - `connexion.php` - Client login page
  - `inscription.php` - Client registration page
  - `dashboard.php` - Client main dashboard
  - `profil.php` - Profile management
  - Views for consumption, bills, and complaints
- `factures/` - Billing interface components
  - `liste.php` - List of bills
  - `details.php` - Bill details
  - `paiement.php` - Payment processing
- `fournisseur/` - Provider portal interfaces
  - `login.php` - Provider login
  - `dashboard.php` - Provider main dashboard
  - Management interfaces for clients, consumption, and billing
- `notification/` - Notification interfaces
- `partials/` - Reusable UI components (header, footer, navigation)
- `reclamation/` - Complaint management interfaces
  - `nouvelle.php` - New complaint form
  - `liste.php` - List of complaints
  - `details.php` - Complaint details and status

### Business Logic (traitement/)
Processing scripts handling form submissions and AJAX requests:
- `clientTraitement.php` - Processes client registration, login, and profile updates
- `fournisseurTraitement.php` - Handles provider authentication and operations
- `reclamationTraitement.php` - Processes complaint submission and updates
- `consommationTraitement.php` - Handles consumption data submission and calculation
- `factureTraitement.php` - Manages bill generation and payment processing
- `notificationTraitement.php` - Handles creation and delivery of system notifications

### File Storage (uploads/)
Organizes uploaded documents:
- `complaints/` - Stores documents related to customer complaints
- `consumptions/` - Stores consumption proof documents (meter readings, etc.)

## Detailed Functionality

### Authentication System
- Separate authentication for clients and providers
- Session-based authentication with security measures
- Password hashing and validation

### Client Management
- Registration and profile management
- Address and contact information storage
- Service activation/deactivation
- Client history tracking

### Consumption Management
- Monthly consumption recording
- Historical consumption data visualization
- Consumption validation by provider
- Support for document uploads (meter readings)

### Billing System
- Automated bill generation based on consumption
- Configurable tariff system
- Payment tracking (paid, unpaid, partially paid)
- Payment history and receipt generation

### Complaint Management
- Multi-category complaint submission
- Status tracking (new, in progress, resolved)
- Assignment to service agents
- Resolution documentation

### Notification System
- Automated notifications for new bills
- Complaint status updates
- System announcements
- Email notification integration

## Database Schema

### Key Tables
- `clients` - Client information and authentication
- `fournisseurs` - Provider accounts and access levels
- `agents` - Service personnel information
- `consommations` - Monthly consumption records
- `factures` - Billing information and status
- `reclamations` - Customer complaints and their status
- `notifications` - System notifications

## Technical Implementation

### Front-end
- HTML5, CSS3, JavaScript
- Bootstrap 5 for responsive design
- Font Awesome 6 for iconography
- Custom CSS with modern design elements
- AOS library for smooth scroll animations
- Form validation and dynamic content loading

### UI Design Features
- Modern electric-themed color palette with vibrant accents
- Smooth animations and transitions
- Responsive cards with hover effects
- Dark mode support
- Wave separators and modern UI elements
- Animated page transitions
- Interactive form elements
- Consistent design language throughout the application

### Back-end
- PHP for server-side processing
- SQLite database for data storage
- Session management for authentication
- File upload handling with security validation

### Security Features
- Input sanitization and validation
- Password hashing
- CSRF protection
- Secure file upload handling

## Getting Started

1. **Prerequisites**
   - XAMPP or similar PHP development environment (version 7.0+)
   - SQLite support enabled in PHP
   - Web browser with JavaScript enabled

2. **Installation**
   - Place the project folder in your web server's document root (e.g., htdocs for XAMPP)
   - Ensure the web server has write permissions for the uploads/ directory
   - Access the application via http://localhost/TP_ELECT/

3. **Default Access**
   - Client portal: http://localhost/TP_ELECT/IHM/client/connexion.php
   - Provider portal: http://localhost/TP_ELECT/IHM/fournisseur/login.php

## Development Guidelines

- Database operations should be handled through the model classes in BD/
- UI changes should maintain the Bootstrap-based responsive design
- New features should follow the existing separation of concerns
- File uploads should include proper validation and security checks
- Maintain design consistency by using the centralized style.css file
- Follow the established color palette and design patterns