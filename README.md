# GPTChance Survey Application

This project provides a simple consent form and a 59‑question survey that stores answers in a MySQL database. Responses are saved automatically as each field is changed. A basic report page visualizes the results.

## Setup
1. Create a MySQL database and user.
2. Copy `config.php` and update the database connection constants.
3. Create the table with the following SQL:

```sql
CREATE TABLE survey_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) UNIQUE,
    age VARCHAR(255),
    gender VARCHAR(255),
    gender_self VARCHAR(255),
    q1 VARCHAR(255),
    q2 VARCHAR(255),
    q3 VARCHAR(255),
    q4 VARCHAR(255),
    q5 VARCHAR(255),
    q6 VARCHAR(255),
    q7 VARCHAR(255),
    q8 VARCHAR(255),
    q9 VARCHAR(255),
    q10 VARCHAR(255),
    q11 VARCHAR(255),
    q12 VARCHAR(255),
    q13 VARCHAR(255),
    q14 VARCHAR(255),
    q15 VARCHAR(255),
    q16 VARCHAR(255),
    q17 VARCHAR(255),
    q18 VARCHAR(255),
    q19 VARCHAR(255),
    q20 VARCHAR(255),
    q21 VARCHAR(255),
    q22 VARCHAR(255),
    q23 VARCHAR(255),
    q24 VARCHAR(255),
    q25 VARCHAR(255),
    q26 VARCHAR(255),
    q27 VARCHAR(255),
    q28 VARCHAR(255),
    q29 VARCHAR(255),
    q30 VARCHAR(255),
    q31 VARCHAR(255),
    q32 VARCHAR(255),
    q33 VARCHAR(255),
    q34 VARCHAR(255),
    q35 VARCHAR(255),
    q36 VARCHAR(255),
    q37 VARCHAR(255),
    q38 VARCHAR(255),
    q39 VARCHAR(255),
    q40 VARCHAR(255),
    q41 VARCHAR(255),
    q42 VARCHAR(255),
    q43 VARCHAR(255),
    q44 VARCHAR(255),
    q45 VARCHAR(255),
    q46 VARCHAR(255),
    q47 VARCHAR(255),
    q48 VARCHAR(255),
    q49 VARCHAR(255),
    q50 VARCHAR(255),
    q51 VARCHAR(255),
    q52 VARCHAR(255),
    q53 VARCHAR(255),
    q54 VARCHAR(255),
    q55 VARCHAR(255),
    q56 VARCHAR(255)
);
```

## Running
1. Place the project in a PHP-enabled web server environment.
2. Access `index.php` in a browser to fill out the survey.
3. Visit `report.php` to see a simple chart of survey results.

## Notes
* Answers are optional; the survey saves each field after it changes.
* Modify `.gitignore` if you use additional environment files or vendor directories.

## User Accounts
To support login and consent, create a `users` table:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    dob DATE,
    password_hash VARCHAR(255),
    sec_question VARCHAR(255),
    sec_answer VARCHAR(255)
);
```

Access `index.php` to read about the survey, then register or login to start `survey.php`.

## Case Management Module

The application now includes a simple case management portal. Create a `cases` table:

```sql
CREATE TABLE cases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    status VARCHAR(50)
);
```

Login as `admin` to access `admin.php` and enable features for the `admin` or `client` roles. Users can visit `portal.php` after logging in to access enabled features such as creating and updating cases.

## Feature Catalog

`features.json` lists all available modules. Use `admin.php` to enable or disable each feature per role. The catalog now includes user experience options like dark mode and dashboard widgets, security controls, case management enhancements, survey tools, collaboration features, analytics, admin utilities, and mobile integrations.

## Standard Operating Procedures (SOP)

Comprehensive operational guidelines are available in the SOP documentation:

- **[SOP.md](SOP.md)** - Complete Standard Operating Procedures for all system operations, user roles, security protocols, and administrative guidelines
- **[SOP-Overview.md](SOP-Overview.md)** - Executive summary and overview for stakeholders and management

These documents provide detailed guidance for:
- System administration and user management
- Survey administration protocols
- Case management workflows  
- Data privacy and security procedures
- Training guidelines and troubleshooting
- Emergency procedures and crisis response
