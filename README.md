# Campus Cart 🛒

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Gemini_AI-8E75B2?style=for-the-badge&logo=googlebard&logoColor=white" alt="Gemini API" />
</p>

**Campus Cart** is a secure, AI-powered C2C (Consumer-to-Consumer) marketplace platform designed exclusively for the students of **IIITDM Jabalpur**. It allows students to buy and sell academic essentials, electronics, and campus gear in a verified, trusted, and domain-restricted environment.

---

## 🚀 Key Features

* **🤖 AI-Powered Tagging:** Integrated with Google Gemini API to automatically generate SEO-friendly hashtags and categories based on user-provided product descriptions.
* **🎓 Verified Ecosystem:** Restricts user registration strictly to the `@iiitdmj.ac.in` domain to ensure a 100% safe, student-only community.
* **🔒 Secure Authentication:** Implements `BCRYPT` password hashing for robust user data protection and secure credential storage.
* **📦 Dynamic Listings:** Features real-time product uploads with image support, detailed descriptions, and active status tracking.
* **📱 Responsive UI:** Built utilizing HTML5, Vanilla CSS3, and Tailwind CSS to deliver a seamless, modern browsing experience across both mobile and desktop devices.
* **🛡️ Robust Security:** Follows industry standards for student data safety, actively preventing SQL injections via PHP Prepared Statements and ensuring secure session management.

---

## 🛠️ Tech Stack

### Frontend
* HTML5
* CSS3 (Vanilla & Tailwind CSS for responsive design)

### Backend & Database
* **Server-side:** PHP
* **Database:** MySQL

### Integrations & Tools
* **AI Engine:** Google Gemini API (Gemini 1.5 Flash / Pro)
* **Local Development:** XAMPP
* **Version Control:** Git & GitHub

---

## ⚙️ Installation & Setup

Follow these instructions to get a local copy of Campus Cart up and running on your machine.

### Prerequisites
* [XAMPP](https://www.apachefriends.org/index.html) (or any equivalent local server stack like WAMP/LAMP)
* Git installed on your system
* A [Google Gemini API Key](https://aistudio.google.com/app/apikey)

### Step-by-Step Setup

1.  **Clone the Repository:**
    Open your terminal/command prompt and clone the repository.
    ```bash
    git clone https://github.com/nikhiljain10905/Campus-Cart.git
    ```

2.  **Move to the Local Server Directory:**
    Move the cloned `Campus-Cart` folder into your XAMPP `htdocs` directory.
    * **Windows:** `C:/xampp/htdocs/`
    * **Mac/Linux:** `/Applications/XAMPP/xamppfiles/htdocs/` or `/opt/lampp/htdocs/`

3.  **Database Configuration:**
    * Start **Apache** and **MySQL** modules from the XAMPP Control Panel.
    * Open your browser and navigate to `http://localhost/phpmyadmin/`.
    * Create a new database named `campuscart`.
    * Select the `campuscart` database, navigate to the **Import** tab, and upload the `campuscart.sql` file provided in the repository's root directory.

4.  **Environment Configuration:**
    * Navigate to the project folder inside `htdocs`.
    * Locate the file named `config.sample.php` and rename it to `config.php`.
    * Open `config.php` in your code editor and update the database credentials and Gemini API Key:
        ```php
        <?php
        // Database connection settings
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'root'); // default XAMPP username
        define('DB_PASSWORD', ''); // default XAMPP password (leave blank)
        define('DB_NAME', 'campuscart');

        // Gemini API Setting
        define('GEMINI_API_KEY', 'your_gemini_api_key_here');
        ?>
        ```

5.  **Run the Application:**
    Open your web browser and go to:
    ```
    http://localhost/Campus-Cart/
    ```

---

## 👨‍💻 Author

**Nikhil Jain**
* CSE Sophomore @ IIITDM Jabalpur
* GitHub: [@nikhiljain10905](https://github.com/nikhiljain10905)

---


<p align="center">
  <i>If you like this project, please consider giving it a ⭐️ on GitHub!</i>
</p>
