# PetitionHub - Platform for Creating and Signing Petitions

![PetitionHub Banner](https://i.imgur.com/XYZpendingImageHere.png)

## 📋 Project Overview

PetitionHub is a modern, responsive web application that allows users to create petitions, gather signatures, and track support for various causes. Built with PHP, JavaScript, HTML, and CSS, the application offers real-time signature updates, animated UI elements, and a seamless user experience.

## 🎨 Design & Color Palette

The application uses a carefully selected color scheme:
- **Dark Olive Green (#3D5300)** - Primary dark color for headings and key elements
- **Sage Green (#ABBA7C)** - Primary color for borders and accents
- **Bright Yellow (#FFE31A)** - Accent bright color for highlights
- **Orange (#F09319)** - Accent color for interactive elements
- **Cream (#FFFDF5)** - Background color for better readability

## 🚀 Features

### Core Functionality
- **Create Petitions**: Users can create petitions with titles, descriptions, and deadlines
- **Sign Petitions**: Visitors can sign petitions by providing their personal information
- **Real-time Updates**: Signature counts and lists update in real-time without page refresh
- **Social Proof**: Display of recent signatures to encourage more participation
- **Responsive Design**: Mobile-friendly interface that works on all devices

### Enhanced User Experience
- **Animated Counters**: Dynamic counting animations for signature numbers
- **Floating Avatars**: Gentle animations for signature avatars
- **Form Interactivity**: Enhanced form fields with focus effects and validation
- **Shine Effects**: Subtle animations on buttons and interactive elements
- **Entrance Animations**: Content sections appear with smooth transitions

## 📂 Project Structure

```
/PetitionHub
│
├── index.php               # Root entry point (redirects to IHM/index.php)
│
├── /IHM                    # User Interface Layer (Frontend)
│   ├── index.php           # Homepage
│   ├── liste_petition.php  # List of all petitions
│   ├── signature.php       # Page to sign a petition
│   ├── ajouter_petition.php # Form to create a new petition
│   └── style.css           # Main stylesheet with all designs
│
├── /Traitement             # Processing Layer (Controllers)
│   ├── ajouter_petition.php  # Handle petition creation
│   ├── ajouter_signature.php # Process new signatures
│   ├── recent_signatures.php # Fetch recent signatures (AJAX)
│   └── get_signature_count.php # Get total signature count
│
├── /BD                     # Database Layer (Model)
│   ├── connexion.php       # Database connection setup
│   ├── petition.php        # Petition-related database functions
│   └── signature.php       # Signature-related database functions
│
└── README.md               # This documentation file
```

## 🛠️ Technical Architecture & Separation of Concerns

### Three-Tier Architecture
The application strictly follows a three-tier architecture to maintain clean separation of concerns:

1. **Presentation Layer (IHM)**: 
   - Contains all frontend files that the user directly interacts with
   - All HTML, CSS, and frontend JavaScript code resides here
   - No direct database queries or business logic should be placed in these files
   - Files should only call the appropriate processing scripts from the Traitement layer

2. **Application Layer (Traitement)**: 
   - Contains all business logic and processing scripts
   - Handles form submissions, data validation, and request processing
   - Acts as a bridge between the frontend (IHM) and the database (BD)
   - Files should only call functions from the BD layer for database operations

3. **Data Layer (BD)**: 
   - Contains all database-related functions and operations
   - Includes database connection setup and all SQL queries
   - Provides a clean API for the Traitement layer to interact with the database
   - No business logic or presentation code should be placed here

### Code Organization Rules
To maintain clean separation:

- **IHM files** should never:
  - Connect directly to the database
  - Contain SQL queries
  - Implement complex business logic
  
- **Traitement files** should:
  - Handle all form submissions and AJAX requests
  - Process and validate data
  - Call appropriate functions from the BD layer
  - Return formatted data or redirects based on the request
  
- **BD files** should:
  - Contain all database-related functions
  - Execute SQL queries and return results
  - Handle data retrieval, insertion, updates, and deletion
  - Not contain business logic or presentation code

### Key Technical Components
- **Real-time Signature Updates**: Uses AJAX to fetch and display signatures without page refresh
- **Form Validation**: Client and server-side validation for all forms
- **Responsive CSS**: Custom styling that adapts to various screen sizes
- **Animation System**: CSS animations and JavaScript for enhanced visual effects
- **Database Integration**: Structured MySQL database for storing petitions and signatures

## 📝 Database Schema

The database consists of two main tables:

### Petition Table
```sql
CREATE TABLE Petition (
    IDP INT AUTO_INCREMENT PRIMARY KEY,
    Titre VARCHAR(255) NOT NULL,
    Description TEXT NOT NULL,
    DatePublic DATE NOT NULL,
    DateFinP DATE NOT NULL,
    PorteurP VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL
);
```

### Signature Table
```sql
CREATE TABLE Signature (
    IDS INT AUTO_INCREMENT PRIMARY KEY,
    IDP INT NOT NULL,
    Nom VARCHAR(50) NOT NULL,
    Prenom VARCHAR(50) NOT NULL,
    Pays VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Date DATE NOT NULL,
    Heure TIME NOT NULL,
    FOREIGN KEY (IDP) REFERENCES Petition(IDP)
);
```

## 🚀 Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB
- Web server (Apache or Nginx)
- XAMPP, WAMP, MAMP, or similar stack (for local development)

### Setup Instructions
1. **Clone the repository or download files**
   ```bash
   git clone https://github.com/yourusername/PetitionHub.git
   ```

2. **Import the database**
   - Create a new database called `petitionhub`
   - Import the SQL file from the `database` folder (if provided)
   - Or execute the SQL commands to create tables manually

3. **Configure database connection**
   - Open `/BD/connexion.php`
   - Update the database credentials:
     ```php
     $host = 'localhost';
     $dbname = 'petitionhub';
     $username = 'your_username';
     $password = 'your_password';
     ```

4. **Place files in your web server directory**
   - For XAMPP: `htdocs/Tp3`
   - For WAMP: `www/Tp3`
   - For MAMP: `htdocs/Tp3`

5. **Access the application**
   - Visit `http://localhost/Tp3` in your web browser

## 💻 How to Use

### Creating a Petition
1. Navigate to the homepage
2. Click "Créer une pétition"
3. Fill out the petition form with title, description, dates, and your information
4. Submit the form to create your petition

### Signing a Petition
1. Browse the list of petitions
2. Select a petition you want to support
3. Fill out the signature form with your name, country, and email
4. Submit to add your signature (it will appear in real-time)

### Viewing Petitions and Signatures
1. Go to "Voir les pétitions" to browse all active petitions
2. Select a petition to view its details and current signatures
3. The signature count updates in real-time with new additions

## 🔍 Key Files and Their Functions

### IHM Layer (User Interface)
- **signature.php**: Displays petition details and manages the signature form
  - Contains AJAX functionality for real-time signature updates
  - Implements animated counters and visual effects
  
- **ajouter_petition.php**: Handles the petition creation form
  - Includes form validation and styling
  - Shows success/error messages for user feedback

- **style.css**: Main stylesheet for the entire application
  - Defines the color scheme and visual aspects
  - Contains all animations and responsive design rules

### Traitement Layer (Processing)
- **ajouter_signature.php**: Processes new signatures
  - Validates input data
  - Handles both AJAX and standard form submissions
  - Returns appropriate responses based on request type

- **recent_signatures.php**: Fetches and formats recent signatures
  - Returns HTML or JSON based on request parameters
  - Supports pagination and real-time updates

### BD Layer (Database)
- **connexion.php**: Establishes database connection
  - Sets up PDO connection with error handling
  - Used by all database-related operations

- **signature.php**: Contains functions for signature operations
  - Methods to add, retrieve, and count signatures
  - Used by the Traitement layer

## 🔍 How to Find Things in the Code

### Finding Features
To locate specific features in the codebase:

1. **For UI components**: Check the relevant PHP file in the `IHM` directory
   - Forms are in their respective PHP files (e.g., `ajouter_petition.php`)
   - Styles are in `style.css` organized by component type

2. **For data processing**: Look in the `Traitement` directory
   - Files are named according to their function (e.g., `ajouter_signature.php`)

3. **For database operations**: Check the `BD` directory
   - Each entity typically has its own file (e.g., `signature.php` for signature operations)

### Common Search Patterns
When looking for specific functionality:

- **AJAX functionality**: Search for `XMLHttpRequest` or `fetch` in JavaScript sections
- **Form processing**: Look for `$_POST` in PHP files
- **Database queries**: Search for `SELECT`, `INSERT`, or `$pdo->prepare`
- **Animations**: Find `@keyframes` or `.animate` in CSS
- **Event handlers**: Look for `addEventListener` in JavaScript

## 🤝 Contributing

Contributions to improve PetitionHub are welcome:

1. Fork the repository
2. Create a branch for your feature (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgements

- [Font Awesome](https://fontawesome.com/) for icons
- [Poppins Font](https://fonts.google.com/specimen/Poppins) by Google Fonts
- [XAMPP](https://www.apachefriends.org/) for the local development environment

---

*Developed as part of TP3 project* 