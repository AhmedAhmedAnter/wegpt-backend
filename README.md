# WeGPT Backend API

A high-performance, lightweight PHP-based RESTful API for the WeGPT educational platform.

## 🚀 Overview

This project provides the backend infrastructure for WeGPT, handling user authentication, educational content management (grades, subjects, lessons), and AI-driven chat conversations.

## 📂 Project Structure

```text
backend/
├── api/
│   ├── users/            # Auth, Registration & Profile management
│   ├── lessons/          # Educational content delivery
│   ├── conversations/    # Chat session management
│   ├── messages/         # Message history and exchange
│   ├── grades/           # Grade level data
│   ├── subjects/         # Subject categories
│   ├── terms/            # Academic terms
│   ├── ai_settings/      # AI Model configurations
│   ├── helpers.php       # API utility functions
│   └── .htaccess         # Clean URL routing & CORS
├── config/
│   └── database.php      # Secure PDO connection
├── database/
│   └── schema.sql        # Full MySQL database schema
├── index.php             # API Health Check
└── README.md             # Documentation
```

## 🛠️ Technology Stack

- **Language:** PHP 8+
- **Database:** MySql (using PDO)
- **Web Server:** Apache (XAMPP/Lamp Stack)
- **Architecture:** Headless RESTful API

## 🔗 API Endpoints (Clean URLs)

### Authentication & Users

- `POST /api/register` - Create a new account
- `POST /api/login` - Authenticate and get session
- `GET /api/users` - List all users (Admin)
- `GET /api/users/{id}` - Get specific user profile

### Educational Content

- `GET /api/lessons` - List all published lessons
- `GET /api/lessons/{id}` - Get lesson details
- `GET /api/subjects` - List available subjects
- `GET /api/grades` - List available grades

### AI & Chat

- `GET /api/conversations?user_id={id}` - Get chat history for a user
- `POST /api/conversations/create` - Start a new AI chat session
- `GET /api/messages/{conversation_id}` - Get full message history
- `POST /api/messages/send` - Send a message (Student/AI)
- `POST /api/feedback/send` - Rate AI responses

## ⚙️ Installation

1. Clone this repository into your `htdocs` or public directory.
2. Import `database/schema.sql` into your MySQL server.
3. Update `config/database.php` with your database credentials.
4. Ensure Apache's `mod_rewrite` is enabled for clean URLs.

## 🔒 Security

- **SQL Injection:** Prevented by forced use of PDO Prepared Statements.
- **Passwords:** Securely hashed using `PASSWORD_DEFAULT` (Bcrypt).
- **CORS:** Pre-configured for cross-origin frontend communication.
