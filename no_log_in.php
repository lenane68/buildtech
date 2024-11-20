<?php
session_start();
$conn = require __DIR__ . "/database.php";

?>


<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>דף נחיתה</title>
    <link href="css/style2.css" rel="stylesheet">


    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap&key=AIzaSyD4pla3F8iMPajljQ3XL2GM5Tbs6G7T5Y0" defer></script>

</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#home">בית</a></li>
                <li><a href="#about">אודות</a></li>
                <li><a href="#services">שירותים</a></li>
                <li><a href="#contact">צור קשר</a></li>
                <li><a href="index.php">כניסת מנהל</a></li>
                <li><a href="client_login.php">כניסת לקוח</a></li>
            </ul>
        </nav>
    </header>
    <section id="home">
        <h1>ניהול אתרי בנייה ולקוחות בצורה חכמה, נוחה ויעילה</h1>
        <p>המערכת המושלמת לניהול אתרי בנייה – כל מה שאתה צריך במקום אחד</p>
        <a href="#about" class="btn">למידע נוסף</a>
    </section>
    <section id="about">
        <h2>אודותינו</h2>
        <p>המערכת שלנו מאפשרת לך לנהל את כל האתרים שלך, לעקוב אחרי התקדמות העבודה, לנהל לקוחות, תשלומים, משימות וצוותים – כל זה בממשק אחד פשוט וידידותי.</p>
    </section>
    <section id="services">
        <h2>השירותים שלנו</h2>
        <ul>
            <li>ניהול פרויקטים בזמן אמת – עקוב אחרי כל פרויקט בעזרת לוחות זמנים וסטטוסים עדכניים.</li>
            <li>מעקב אחרי עובדים ולקוחות – נהל את הצוותים והלקוחות שלך במקום אחד.</li>
            <li>דוחות וסטטיסטיקות חכמות – קבל תמונה ברורה על כל פרויקט עם דוחות בזמן אמת.</li>
            <li>חווית משתמש פשוטה וידידותית – מערכת אינטרנטית קלה לשימוש, לא נדרשת התקנה.</li>
            <li>ניהול תקציב והוצאות – מעקב אחרי התקציב וההוצאות בכל פרויקט.</li>
        </ul>
    </section>
    <section id="demo">
        <h2>הדגמת מערכת</h2>
        <video controls>
            <source src="img/my_clip.mp4" type="video/mp4">
            הדפדפן שלך אינו תומך בתג וידאו.
        </video>
        <div class="screenshots">
            <img src="img/ss1.png" alt="תמונת מסך 1">
            <img src="img/ss2.png" alt="תמונת מסך 2">
        </div>
    </section>
    <section id="contact">
        <h2>צור קשר</h2>
        <form id="leadForm">
            <label for="name">שם:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">אימייל:</label>
            <input type="email" id="email" name="email" required>
            <label for="phone">טלפון:</label>
            <input type="tel" id="phone" name="phone" required>
            <label for="message">הודעה:</label>
            <textarea id="message" name="message" required></textarea>
            <button type="submit">שלח</button>
        <div id="response"></div>
        </form>
    </section>
    <footer>
        <p>&copy; 2024 החברה שלך. כל הזכויות שמורות.</p>
    </footer>

    <script>
        document.getElementById('leadForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting the traditional way

    const formData = new FormData(this);

    fetch('submit_lead.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('response').innerText = data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
    </script>
</body>
</html>