# WeGPT Backend API Documentation 🚀

Welcome to the **WeGPT Backend API**. This is a robust, secure, and RESTful PHP backend designed to power the WeGPT educational platform.

---

## 🛠 Features

- **Stateless Auth**: JWT (JSON Web Tokens) for secure session management.
- **RBAC**: Role-Based Access Control (Admin vs. Student).
- **Clean URLs**: RESTful routing via `.htaccess`.
- **Media Management**: Centralized file upload system for avatars and lessons.
- **Environment Aware**: Environment variables via `.env`.

---

## 🚪 Authentication

All protected endpoints require a **Bearer Token** in the header.
**Header:** `Authorization: Bearer <your_jwt_token>`

### Auth Endpoints

| Method | Endpoint        | Description          | Body                      |
| :----- | :-------------- | :------------------- | :------------------------ |
| `POST` | `/api/register` | Register new student | `{name, email, password}` |
| `POST` | `/api/login`    | Login & get JWT      | `{email, password}`       |

---

## 📚 Core Resources (CRUD)

### 📖 Lessons

| Method   | Endpoint                   | Access    | Body (JSON)                                           |
| :------- | :------------------------- | :-------- | :---------------------------------------------------- |
| `GET`    | `/api/lessons`             | User      | None                                                  |
| `GET`    | `/api/lessons/{id}`        | User      | None                                                  |
| `POST`   | `/api/lessons/create`      | **Admin** | `{title, subject_id, grade_id, term_id, ...}`         |
| `PUT`    | `/api/lessons/update/{id}` | **Admin** | `{title, content_text, ...}` (Partial update allowed) |
| `DELETE` | `/api/lessons/delete/{id}` | **Admin** | None                                                  |

### 🎓 Grades, Subjects, Terms & Specializations

These follow the same pattern as Lessons.

- **Read**: `GET /api/{resource}` (User)
- **Manage**: `POST`, `PUT`, `DELETE` (Admin)

---

## 💬 Chat & AI Functions

### Conversations

| Method   | Endpoint                         | Access      | Description           |
| :------- | :------------------------------- | :---------- | :-------------------- |
| `GET`    | `/api/conversations`             | User        | List my conversations |
| `POST`   | `/api/conversations/create`      | User        | Start new chat        |
| `DELETE` | `/api/conversations/delete/{id}` | Owner/Admin | Delete a chat         |

### Messages

| Method | Endpoint                    | Access      | Description      |
| :----- | :-------------------------- | :---------- | :--------------- |
| `GET`  | `/api/messages/{conv_id}`   | User        | Get chat history |
| `POST` | `/api/messages/send`        | User        | Send a message   |
| `PUT`  | `/api/messages/update/{id}` | Owner/Admin | Edit a message   |

---

## 📁 Media & Uploads

Standard multipart/form-data upload.

| Method | Endpoint      | Type     | Description               |
| :----- | :------------ | :------- | :------------------------ |
| `POST` | `/api/upload` | `avatar` | Upload profile pic        |
| `POST` | `/api/upload` | `lesson` | Upload PDF/Doc for lesson |

---

## ⚙️ Setup & Configuration

1. **Environment**:
   Rename `.env.example` to `.env` and configure your Database.
2. **Initial Admin**:
   Run the following to create your first admin user:
   ```bash
   php seed_admin.php
   ```
3. **Apache Requirements**:
   Ensure `mod_rewrite` and `mod_headers` are enabled.

---

## 🛡 Security Modes

Managed via `APP_MODE` in `.env`:

- `development`: Full SQL errors shown.
- `production`: Generic secure error messages.
