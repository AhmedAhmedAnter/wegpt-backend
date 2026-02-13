# WeGPT Backend API - Comprehensive Documentation 🚀

Welcome to the official documentation for the **WeGPT Backend**. This is a production-ready, RESTful API built with PHP 8+, utilizing clean architecture principles, JWT authentication, and a robust security layer.

---

## � Table of Contents

1. [Introduction](#introduction)
2. [Project Structure](#project-structure)
3. [Authentication & Security](#authentication--security)
4. [API Architecture (Clean URLs)](#api-architecture)
5. [End-to-End API Reference](#api-reference)
   - [User Management](#user-management)
   - [Lessons Service](#lessons-service)
   - [Educational Data (Grades, Subjects, etc.)](#educational-data)
   - [AI & Conversations](#ai--conversations)
   - [Media & Uploads](#media--uploads)
6. [Configuration & Environment](#configuration)
7. [Developer Setup](#setup)

---

## 1. Introduction <a name="introduction"></a>

The WeGPT Backend serves as the engine for an AI-powered educational platform. It manages everything from course content (lessons/subjects) to real-time AI conversations between students and virtual tutors.

### Technologies

- **PHP 8.1+**: Core logic.
- **MySQL**: Relational data storage.
- **JWT**: Stateless, secure authentication.
- **Apache/mod_rewrite**: Clean RESTful paths.

---

## 2. Project Structure <a name="project-structure"></a>

```text
/backend
├── api/                  # All API endpoints
│   ├── users/            # Login, Registration, Profile
│   ├── lessons/          # Full Lesson CRUD
│   ├── grades/           # Educational Grade context
│   ├── subjects/         # Subject management
│   ├── conversations/    # AI Chat session management
│   ├── messages/         # Real-time message storage
│   ├── media/            # File upload handlers
│   ├── auth_middleware.php # Security & Guard functions
│   ├── jwt_helper.php    # JWT Encoding/Decoding logic
│   └── helpers.php       # Global utilities (JSON responses, input handling)
├── config/               # System configuration
│   ├── database.php      # PDO connection (Powered by .env)
│   └── env_loader.php    # Custom .env parser
├── database/             # Database migrations & schemas
├── uploads/              # Storage for avatars and documents (Gitignored)
├── .env                  # Private environment variables
└── seed_admin.php        # Initial system bootstrap script
```

---

## 3. Authentication & Security <a name="authentication--security"></a>

### JWT Workflow

1. Client sends credentials to `/api/login`.
2. Server validates and returns a **JWT Token** (signed with `JWT_SECRET`).
3. Client includes this token in the header for all subsequent requests:
   `Authorization: Bearer <TOKEN>`

### Role-Based Access Control (RBAC)

- **Public**: `POST /api/login`, `POST /api/register`.
- **Student**: Read access to lessons/grades, full access to their own chat history.
- **Admin**: Master control for all content creation, modification, and user management.

---

## 4. API Architecture <a name="api-architecture"></a>

We use `.htaccess` to map human-readable URLs to PHP files:

- **Standard**: `/api/subjects/index.php` -> `GET /api/subjects`
- **Parameterized**: `/api/lessons/update.php?id=5` -> `PUT /api/lessons/update/5`

---

## 5. API Reference <a name="api-reference"></a>

### 👤 User Management <a name="user-management"></a>

| Method   | Endpoint                 | Access | Body Example                                         |
| :------- | :----------------------- | :----- | :--------------------------------------------------- |
| `POST`   | `/api/register`          | Public | `{"name": "...", "email": "...", "password": "..."}` |
| `POST`   | `/api/login`             | Public | `{"email": "...", "password": "..."}`                |
| `GET`    | `/api/users`             | Admin  | Returns all registered users.                        |
| `DELETE` | `/api/users/delete/{id}` | Admin  | Permanent account removal.                           |

### 📚 Lessons Service <a name="lessons-service"></a>

**Endpoint Base**: `/api/lessons`

| Method | Endpoint                   | Description                        | Body         |
| :----- | :------------------------- | :--------------------------------- | :----------- |
| `GET`  | `/api/lessons`             | List all published lessons.        | `null`       |
| `GET`  | `/api/lessons/{id}`        | View detailed lesson content.      | `null`       |
| `POST` | `/api/lessons/create`      | **Admin Only**. Create new lesson. | See below    |
| `PUT`  | `/api/lessons/update/{id}` | **Admin Only**. Update details.    | Partial JSON |

**Create Body Sample:**

```json
{
  "title": "Introduction to Algebra",
  "subject_id": 1,
  "grade_id": 1,
  "term_id": 1,
  "content_text": "Content goes here...",
  "difficulty_level": "medium",
  "is_published": 1
}
```

### � AI & Conversations <a name="ai--conversations"></a>

Manage student-AI interactions.

| Method | Endpoint                    | Description                             |
| :----- | :-------------------------- | :-------------------------------------- |
| `GET`  | `/api/conversations`        | List current user's chat history.       |
| `POST` | `/api/conversations/create` | Initialize a new AI session.            |
| `GET`  | `/api/messages/{conv_id}`   | Get all messages for a specific chat.   |
| `POST` | `/api/messages/send`        | Send a new message & store AI response. |

---

## 6. Configuration <a name="configuration"></a>

The system is controlled via the `.env` file:

- **DB_HOST**: Database server (default: `localhost`).
- **DB_NAME**: Database name.
- **DB_USER / DB_PASS**: Credentials.
- **JWT_SECRET**: High-entropy string for signing tokens.
- **BASE_URL**: used for generating full paths for uploaded files.
- **APP_MODE**:
  - `development`: Shows specific SQL errors for debugging.
  - `production`: Shows generic "Connection refused" to hide server details.

---

## 7. Developer Setup <a name="setup"></a>

### Phase 1: Environment

1. Copy `.env.example` to `.env`.
2. Create a database in MySQL and import `database/schema.sql`.

### Phase 2: Seeding

To access **Admin** functions, you need an admin account. Run the seeder:

```bash
php seed_admin.php
```

### Phase 3: Web Server

Ensure your Apache server allows `.htaccess` overrides:

```apache
<Directory "/opt/lampp/htdocs/backend">
    AllowOverride All
    Require all granted
</Directory>
```

Make sure `mod_rewrite` and `mod_headers` are enabled in your PHP/Apache configuration.

---

## � Common Error Codes

- `200 OK`: Request successful.
- `201 Created`: Resource successfully saved.
- `401 Unauthorized`: Invalid or missing JWT token.
- `403 Forbidden`: Role mismatch (e.g., student trying to delete a lesson).
- `404 Not Found`: Resource ID does not exist.
- `405 Method Not Allowed`: Using GET on a POST-only endpoint.
- `500 Internal Error`: Database or syntax failure.

---

_Built with ❤️ by the WeGPT Team._
