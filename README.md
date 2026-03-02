Campus Cart 🛒  
Campus Cart is a secure, AI-powered C2C marketplace platform designed exclusively for the students of IIITDM Jabalpur. It allows students to buy and sell academic essentials, electronics, and campus gear in a verified and trusted environment.


🚀 Key Features


AI-Powered Tagging: Integrated Gemini 3 Flash to automatically generate SEO-friendly hashtags and categories based on product descriptions.


Verified Ecosystem: Restricts registration to the @iiitdmj.ac.in domain to ensure a safe, student-only environment.  


Secure Authentication: Implements BCRYPT password hashing for robust user data protection.  


Dynamic Listings: Real-time product uploads with image support and status tracking.  


Responsive UI: Built with Tailwind CSS for a seamless experience across mobile and desktop.  


🛠️ Tech Stack


Frontend: HTML5, Vanilla CSS3 (Responsive Design)


Backend: PHP


Database: MySQL


AI Integration: Google Gemini API


Tools: XAMPP, Git/GitHub


⚙️ Installation & Setup


Clone the repository:


Bash


git clone https://github.com/nikhiljain10905/Campus-Cart.git


Move to XAMPP directory:


Place the folder in C:/xampp/htdocs/.


Database Setup:


Open phpMyAdmin.


Create a database named campuscart.


Import the database.sql file provided in the repo.


Configure Environment:


Rename config.sample.php to config.php.


Add your Gemini API Key and Database credentials:


PHP


define('GEMINI_API_KEY', 'your_api_key_here');


🛡️ Security


This project follows industry standards for student data safety, including SQL injection prevention via prepared statements and secure session management.


Developed by Nikhil Jain
CSE Sophomore @ IIIT Jabalpur
