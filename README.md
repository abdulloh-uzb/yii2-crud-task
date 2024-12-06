# API Documentation

This API provides a set of endpoints for user authentication, password recovery, and managing posts.

## Endpoints

### 1. **POST /register**
- **Description**: Registers a new user.
- **Request Body**:
  ```json
  {
    "username": "exampleuser",
    "email": "user@example.com",
    "password": "securepassword"
  }

### 2. **POST /auth**
- **Description**: login, returns token.
- **Request Body**:
  ```json
  {
    "username": "exampleuser",
    "password": "securepassword"
  }

### 3. **POST /request/password**
- **Description**: Sends a password reset request to the provided email
- **Request Body**:
  ```json
  {
    "password": "securepassword"
  }

### 4. **POST /reset/password**
- **Description**: Reset password using token which was sent to email
- **Request Body**:
  ```json
  {
    "token": "token",
    "new_password": "12345678"
  }

### 5. **POST /posts**
- **Description**: Create post
- **Request Body**:
  ```json
  {
    "title": "Post Title",
    "body": "lorem ipsum"
  }

### 6. **GET /posts/id**
- **Description**: Get single post

### 7. **GET /posts**
- **Description**: Get all posts

### 8. **PUT /posts/id**
- **Description**: Update post
- **Request Body**:
  ```json
  {
    "title": "updated title",
    "body": "updated body"
  } 



