# CloudFiler - Cloud File Storage Service

**CloudFiler** is a cloud-based file storage service that allows users to store and share files with others. The project is built with vanilla PHP and uses JSON Web Token (JWT) for authentication, leveraging access and refresh tokens for session management.

![Logo](public/images/logo.png)

### CloudFiler Features:
- Cloud storage for user files
- File sharing with other users
- JWT-based authentication with access and refresh tokens
- Data security with modern authentication methods

---

### Running Locally

To run **CloudFiler** locally, follow these steps:

#### Requirements:
- PHP >= 7.4
- Composer
- XAMPP or another PHP-supported local server

#### Installation Steps:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/your-repository/cloud-filer.git
    ```

2. **For XAMPP (Windows/MacOS/Linux)**:
   - Move the cloned repository into XAMPP’s `htdocs` directory. Typically found at:
     - Windows: `C:/xampp/htdocs/`
     - MacOS: `/Applications/XAMPP/htdocs/`
     - Linux: `/opt/lampp/htdocs/`
   
   **For other systems (e.g., LAMP/WAMP):**
   - Place the project in the root folder of your web server. For LAMP, it’s `/var/www/html/`, and for WAMP, it's `C:/wamp/www/`.

3. **Install dependencies via Composer:**

    Navigate to the project directory and run:

    ```bash
    composer install
    ```

4. **Set up environment variables:**
   - Create a `.env` file in the project root and define the necessary environment variables (e.g., database connection, JWT secret key).

5. **Start the local server:**

   Start your local server using XAMPP (or any other installed server) and open the browser.

6. **Access CloudFiler:**

    Visit the following URL in your browser:

    ```bash
    http://localhost/cloud-filer/
    ```

---

### Technology Stack

- **Backend**: PHP (vanilla)
- **Authorization**: JWT (JSON Web Token)
- **Database**: MySQL (or other supported databases)
- **Package Manager**: Composer

---

### Key Features:

1. **JWT Authentication**:
   - `Access token` is used to access resources with a limited lifespan.
   - After expiration, the `access token` can be refreshed using a `refresh token`.

2. **Security**:
   - All user data is protected, with authentication and authorization implemented using modern methods.

3. **Repositories**:
   - **CloudFiler** uses **repositories** to manage database interactions. Repositories act as data managers for specific database tables.
   - Each repository represents a corresponding database table and can override parent class `DB` methods, providing flexibility and customization for business logic.

---

Thank you for using **CloudFiler**! 😄
