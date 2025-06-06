# Copyflow API

> Backend of the Copyflow platform — an API that generates personalized marketing texts and integrates with social media platforms.

## 🔍 Overview

This Laravel-based backend receives input from the user, communicates with the OpenRouter API to generate marketing content, and optionally connects with the Meta Graph API for publishing directly to Facebook, Instagram, or WhatsApp.

## ⚙️ Features

- Receives structured input: product name, benefits, audience, goal, platform, tone, contact email, and location.
- Sends data to OpenRouter API to generate text.
- Optionally posts the generated content to Meta platforms (coming soon).
- RESTful API structure for frontend consumption.

## 🚀 Technologies

- **Framework:** Laravel
- **Text Generation:** [OpenRouter API](https://openrouter.ai/docs)
- **Social Media Integration:** [Meta Graph API](https://developers.facebook.com/docs/) (in progress)
- **Database:** MySQL or PostgreSQL

## 📦 Installation

> ⚠️ The project is under active development. A production-ready version is not yet available.

### Requirements

- PHP >= 8.1  
- Composer  
- Laravel CLI  
- MySQL / PostgreSQL  
- API keys for OpenRouter and Meta (for integration)

### Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/SeuUsuario/Copyflow.git
   cd Copyflow
2. Install dependency:
   ```bash
   composer install
3. Copy and configure the environment file:
   ```bash
   cp .env.example .env
4. Generate application key:
   ```bash
   php artisan key:generate
5. Configure .env with database credentials and API keys.
6. Run migrations:
   ```bash
   php artisan migrate
7. Start the development server:
   ```bash
   php artisan serve


## 🧪 API Usage

All endpoints are prefixed with `/api/authentication`.

### 1 . Register

```bash
POST /api/authentication/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "secret",
  "password_confirmation": "secret"
}


### 2 . Login → get your token

```bash
POST /api/authentication/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "secret"
}

### A successful response returns:
```bash
{
	"message": "Usuário logado com sucesso.",
	"user": {
		"id": 1,
		"name": "Nilton Matias Nhanteme",
		"email": "niltonmatias.dev@outlook.com",
		"email_verified_at": null,
		"created_at": "2025-05-25T18:46:14.000000Z",
		"updated_at": "2025-05-25T18:46:14.000000Z"
	},
	"token": "4|sEDXy34CFjCpWzWj4Au9PNUm7DYfkZRHugiVjBY1f5de4864"
}

## 3. Authenticated routes
| Method | Path            | Purpose                                 |
| ------ | --------------- | --------------------------------------- |
| GET    | `/me`           | Current user profile                    |
| GET    | `/my-inputs`    | List prompts you have saved             |
| POST   | `/prompt-input` | Save a new prompt body                  |
| POST   | `/generate`     | Ask OpenRouter to create marketing text |
| GET    | `/history`      | History of all texts you generated      |
| POST   | `/logout`       | Revoke the current token                |


### Example — fetch profile
```bash
curl -H "Authorization: Bearer <token>" \
     http://localhost:8000/api/authentication/me

### 🤝 Contributing
Contributions are welcome!
Open an issue or submit a pull request if you'd like to contribute or report a bug.

### 📝 License
This project is licensed under the MIT License.

### 📬 Contact
Email: niltonmatias.dev@outlook.com

## Status: Backend in development – Stay tuned for updates!
