# 🧪 WeGPT API Testing & Endpoints Guide

This guide provides a comprehensive list of all available API endpoints, including example requests and expected responses for **GET, POST, PUT, and DELETE** methods.

---

## 🔑 Authentication (Public)

### 1. Register User

- **Method**: `POST`
- **Endpoint**: `/api/register`
- **Body (JSON)**:

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "securepassword123",
  "grade_id": 1
}
```

- **Expected Status**: `201 Created`

### 2. Login

- **Method**: `POST`
- **Endpoint**: `/api/login`
- **Body (JSON)**:

```json
{
  "email": "john@example.com",
  "password": "securepassword123"
}
```

- **Success Response**: Returns a `token`. Use this for all following requests.

---

## 🎓 Educational Management (Admin Restricted)

### 3. Grades

| Action   | Method   | Endpoint               | Body (JSON Example)              |
| :------- | :------- | :--------------------- | :------------------------------- |
| List All | `GET`    | `/api/grades`          | N/A                              |
| Create   | `POST`   | `/api/grades/create`   | `{"name": "Grade 10"}`           |
| Update   | `PUT`    | `/api/grades/update/1` | `{"name": "Grade 10 - Updated"}` |
| Delete   | `DELETE` | `/api/grades/delete/1` | N/A                              |

### 4. Subjects

| Action   | Method   | Endpoint                 | Body (JSON Example)                                  |
| :------- | :------- | :----------------------- | :--------------------------------------------------- |
| List All | `GET`    | `/api/subjects`          | N/A                                                  |
| Create   | `POST`   | `/api/subjects/create`   | `{"name": "Math", "grade_id": 1, "code": "MATH101"}` |
| Update   | `PUT`    | `/api/subjects/update/1` | `{"name": "Geometry"}`                               |
| Delete   | `DELETE` | `/api/subjects/delete/1` | N/A                                                  |

### 5. Terms

| Action   | Method   | Endpoint              | Body (JSON Example)                                   |
| :------- | :------- | :-------------------- | :---------------------------------------------------- |
| List All | `GET`    | `/api/terms`          | N/A                                                   |
| Create   | `POST`   | `/api/terms/create`   | `{"name": "Term 1", "grade_id": 1, "term_number": 1}` |
| Update   | `PUT`    | `/api/terms/update/1` | `{"name": "Term 1 - Final"}`                          |
| Delete   | `DELETE` | `/api/terms/delete/1` | N/A                                                   |

---

## 📖 Lessons Service

### 6. Lessons

- **GET All**: `GET /api/lessons`
- **GET One**: `GET /api/lessons/5`
- **Create**: `POST /api/lessons/create`
  - Body:

```json
{
  "title": "Algebra 101",
  "subject_id": 1,
  "grade_id": 1,
  "term_id": 1,
  "content_text": "Lesson details...",
  "difficulty_level": "medium",
  "is_published": 1
}
```

- **Update**: `PUT /api/lessons/update/5`
  - Body: `{"title": "Advanced Algebra"}`
- **Delete**: `DELETE /api/lessons/delete/5`

---

## 💬 AI & Conversations (User & Admin)

### 7. Conversations

- **List Mine**: `GET /api/conversations` (Checks JWT user_id)
- **Start New**: `POST /api/conversations/create`
  - Body: `{"context": "Subject: History"}`
- **Update Chat**: `PUT /api/conversations/update/8`
  - Body: `{"conversation_status": "closed"}`
- **Delete Chat**: `DELETE /api/conversations/delete/8`

### 8. Messages

- **Get History**: `GET /api/messages/8` (8 is conversation_id)
- **Send Message**: `POST /api/messages/send`
  - Body:

```json
{
  "conversation_id": 8,
  "content_text": "Explane the pyramids",
  "role": "user"
}
```

- **Edit Message**: `PUT /api/messages/update/120`
  - Body: `{"content_text": "Pyramids of Giza"}`
- **Delete Message**: `DELETE /api/messages/delete/120`

---

## 🖼️ Media & Uploads

### 9. File Upload

- **Method**: `POST`
- **Endpoint**: `/api/upload`
- **Type**: `multipart/form-data`
- **Params**:
  - `file`: (Binary)
  - `type`: `avatar` OR `lesson`
- **Response**: Returns JSON with `url` and `full_url`.

---

## ⚙️ Administration & AI Settings

### 10. AI Settings

- **List All**: `GET /api/ai-settings`
- **Create**: `POST /api/ai-settings/create`
  - Body: `{"key_name": "ai_model", "value": "gpt-4o"}`
- **Update**: `PUT /api/ai-settings/update/1`
- **Delete**: `DELETE /api/ai-settings/delete/1`

### 11. Feedbacks

- **List All**: `GET /api/feedbacks`
- **View One**: `GET /api/feedbacks/5`
- **Send Feed**: `POST /api/feedbacks/send`
  - Body: `{"message_id": 1, "user_id": 1, "type": "positive", "comment": "Great answer!"}`
- **Delete**: `DELETE /api/feedbacks/delete/5`

---

## 🛡️ Response Codes Summary

| Code  | Meaning                                       |
| :---- | :-------------------------------------------- |
| `200` | Successful operation                          |
| `201` | Successfully created resource                 |
| `401` | Unauthorized (Missing/Bad Token)              |
| `403` | Forbidden (Student trying to do Admin things) |
| `404` | Resource not found                            |
| `405` | Wrong HTTP Method used                        |
| `500` | Server/Database Error                         |
