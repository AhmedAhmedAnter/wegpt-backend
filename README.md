# WeGPT Backend API - Ultimate Technical Documentation 🚀

This documentation provides an exhaustive guide to the **WeGPT API**. It covers all endpoints, request/response structures, and security protocols required for frontend integration.

---

## 📑 Table of Contents

1. [Core Fundamentals](#-core-fundamentals)
2. [Testing Guide (All Endpoints)](./API_TEST_GUIDE.md)
3. [User & Auth Service](#-1-user--auth-service)
4. [Lessons & Content](#-2-lessons--educational-content)
5. [AI & Messaging](#-3-ai-messaging--conversations)
6. [Media & Uploads](#-5-media--file-handling)
7. [Resource Map](#-6-resource-map-crud-tables)

---

## 📑 Core Fundamentals

### 🔒 Authentication & Authorization

The API uses **Stateless JWT Authentication**.

- **Auth Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Token Lifespan**: 24 Hours.
- **RBAC**:
  - `Admin`: Full CRUD access to all educational content and system settings.
  - `Student`: Read access to educational content, Full access to personal conversations and profile.

### 🌐 Global Success Response Structure

Most success responses follow this pattern:

```json
{
  "status": "success",
  "message": "Human readable message",
  "data": { ... } // or array of objects
}
```

### ❌ Error Response Structure

```json
{
  "error": "Short error description"
}
```

---

## 🛠 1. User & Auth Service

### `POST /api/register` (Public)

Creates a new student account.

- **Request Body**:
  - `name` (String, Required)
  - `email` (String, Required)
  - `password` (String, Required)
  - `grade_id` (Int, Optional)
- **Response (201)**: `{"message": "User registered successfully", "user_id": 12}`

### `POST /api/login` (Public)

Authenticates a user and generates a token.

- **Request Body**:
  - `email` (String, Required)
  - `password` (String, Required)
- **Response (200)**:

```json
{
  "message": "Login successful",
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6...",
  "user": { "id": 1, "name": "...", "role": "student", ... }
}
```

---

## 📚 2. Lessons & Educational Content

### `GET /api/lessons` (User)

Returns all published lessons.

- **Response**: List of Lesson objects including `title`, `author_id`, `difficulty_level`, `pdf_path`, etc.

### `POST /api/lessons/create` (Admin)

Creates a new lesson module.

- **Request Body**:
  - `title` (String, Required)
  - `subject_id` (Int, Required)
  - `grade_id` (Int, Required)
  - `term_id` (Int, Required)
  - `content_text` (String, Optional)
  - `pdf_path` (URL/Path, Optional)
  - `video_url` (URL, Optional)
  - `attachments` (JSON/Array, Optional) - List of additional file links.
  - `difficulty_level` (Enum: 'easy', 'medium', 'hard', Default: 'medium')
- **Response (201)**: `{"message": "Lesson created", "id": 45}`

### `PUT /api/lessons/update/{id}` (Admin)

Update any property of a lesson. Supports partial updates.

- **Body Example**: `{"title": "New Title", "is_published": 1}`

---

## 💬 3. AI Messaging & Conversations

### `POST /api/conversations/create` (User)

Initializes a new session for AI interaction.

- **Request Body**: (Optional) `{"context": "Subject: Physics", "lessons_ids": [1, 5]}`
- **Response**: `{"id": 8, "message": "Conversation started"}`

### `GET /api/messages/{conversation_id}` (User/Owner)

Fetches chronological message history for a chat.

- **Response**:

```json
[
  { "id": 1, "role": "user", "content_text": "Hello AI" },
  { "id": 2, "role": "assistant", "content_text": "Hello! How can I help?" }
]
```

### `POST /api/messages/send` (User)

Sends a user message and triggers AI logging.

- **Request Body**:
  - `conversation_id` (Int, Required)
  - `content_text` (String, Required)
  - `role` (Enum: 'user', 'assistant')
  - `attachments` (JSON/Array, Optional)
- **Response**: `{"message": "Message sent", "id": 120}`

---

## ⚙️ 4. System & AI Settings

### `GET /api/ai-settings` (User)

Fetch active AI behavioral configurations.

- **Response**: Array of settings: `[{ "key_name": "temperature", "value": "0.7" }, ...]`

### `POST /api/ai-settings/create` (Admin)

- **Request Body**: `{"key_name": "...", "value": "...", "category": "general"}`

---

## 📁 5. Media & File Handling

### `POST /api/upload` (User)

Uploads files to the server.

- **Body**: `multipart/form-data`
  - `file`: The binary file.
  - `type`: 'avatar' (Images) or 'lesson' (PDF/Docs).
- **Response**:

```json
{
  "message": "File uploaded successfully",
  "url": "/uploads/avatar/file_name.png",
  "full_url": "http://domain.com/uploads/avatar/file_name.png"
}
```

---

## 🧬 6. Resource Map (CRUD Tables)

| Resource            |       GET (All)        |       GET (One)       |         POST (Create)         |             PUT (Edit)             |               DELETE               |
| :------------------ | :--------------------: | :-------------------: | :---------------------------: | :--------------------------------: | :--------------------------------: |
| **Grades**          |     `/api/grades`      |           -           |     `/api/grades/create`      |     `/api/grades/update/{id}`      |     `/api/grades/delete/{id}`      |
| **Subjects**        |    `/api/subjects`     |           -           |    `/api/subjects/create`     |    `/api/subjects/update/{id}`     |    `/api/subjects/delete/{id}`     |
| **Terms**           |      `/api/terms`      |           -           |      `/api/terms/create`      |      `/api/terms/update/{id}`      |      `/api/terms/delete/{id}`      |
| **Specializations** | `/api/specializations` |           -           | `/api/specializations/create` | `/api/specializations/update/{id}` | `/api/specializations/delete/{id}` |
| **Feedbacks**       |    `/api/feedbacks`    | `/api/feedbacks/{id}` |     `/api/feedbacks/send`     |    `/api/feedbacks/update/{id}`    |    `/api/feedbacks/delete/{id}`    |

---

## 🚀 Deployment & Modes

### Setting the Environment

In `.env`:

- `APP_MODE=development`: Detailed PDO exceptions for debugging.
- `APP_MODE=production`: Silent errors for security.

### Routing Logic

The `.htaccess` creates "Clean URLs". You do NOT need the `.php` extension in your API calls.

- **Wrong**: `GET /api/lessons/index.php`
- **Right**: `GET /api/lessons`

---

_Document Version: 1.1.0_
_7. [Developer Setup](#setup) 8. [**Full Endpoints Test Guide**](./API_TEST_GUIDE.md)
\_Developer: Ahmed Anter_
