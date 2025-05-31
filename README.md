# ShiftPay Tracker ![Finished](https://img.shields.io/badge/finished-brightgreen) ![Outdated](https://img.shields.io/badge/outdated-grey)

[![🌐 HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)  [![⚙️ JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript) [![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net/) ![Oracle](https://img.shields.io/badge/Oracle-black?style=flat&logo=oracle&logoColor=red)


**ShiftPay Tracker** is a lightweight web application built with native PHP and JavaScript, designed to record daily server duty shifts and calculate compensation for IT support teams. It helps infrastructure teams track who's on duty and ensure accurate payouts based on shift data.

---

## 🚀 Features

- 🕒 Record daily server duty (shift) logs
- 💰 Automatically calculate daily compensation
- 📅 Filter logs by date or user
- 🔐 Simple login authentication
- 🌐 Works in modern browsers (no framework dependencies)

---

## 🛠️ Tech Stack

- **Backend:** Native PHP (v5.2+)
- **Frontend:** HTML, CSS, JavaScript (vanilla)
- **Database:** Oracle 11c
- **Others:** XAJAX for dynamic interactions

---

## 📦 Installation

1. **Clone or download** the project to your server root directory:
   ```bash
   git clone https://github.com/romtoni/shiftpay-tracker.git
   ```
2. **Configure** your database connection in config/db.php:
	
   ```sql
	$conn = oci_connect('username', 'password', 'hostname/service_name');
	```

3. **Set folder permissions** (if needed) for logs or uploads.

4. **Import** the database structure:

    Run the provided .sql or .ddl file in Oracle SQL Developer or similar tools.

5. **Access** the application via your browser:

    http://localhost/shiftpay-tracker/

## ⚠ Warning

This application built-in late 2015, so it may not works if using latest PHP version. 

## 📸 Screenshots

Here are some views from the application:

### 🖼️ Login
![SS-1](/SCREENSHOT/SS1.png)

### 🖼️ Home
![SS-2](/SCREENSHOT/SS2.png)

### 🖼️ Check Calendar
![SS-3](/SCREENSHOT/SS3.png)

### 🖼️ Date Input
![SS-4](/SCREENSHOT/SS4.png)

## 🤝 Contributing

This project is open for improvements! Feel free to fork and submit pull requests. For major changes, please open an issue to discuss it first.
## 📄 License

ShiftPay Tracker is licensed under the MIT License. See the LICENSE file for details.

## Thank you for using ShiftPay Tracker!
