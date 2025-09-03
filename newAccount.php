<?php
require("connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fullname = trim($_POST['fullname'] ?? '');
    $age      = trim($_POST['age'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // حسب طلبك: MD5 (يفضّل لاحقاً password_hash)
    $hashedPassword = md5($password);

    $errors = [];
    if ($fullname === '') $errors[] = "اكتب الاسم الكامل";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "البريد الإلكتروني غير صالح.";
    if (strlen($password) < 8) $errors[] = "كلمة المرور يجب ألا تقل عن 8 أحرف.";

    if (empty($errors)) {
        // التأكد من عدم تكرار البريد
        $stmt = $con->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        if ($stmt->rowCount() != 0) {
            $errors[] = "هذا البريد موجود من قبل";
        } else {
            // إدخال البيانات مع active=0
            $stmt = $con->prepare("INSERT INTO users (fullname, age, email, password, active) VALUES (?, ?, ?, ?, 0)");
            $stmt->execute([$fullname, $age, $email, $hashedPassword]);

            // رابط التفعيل بدون توكن
            $baseUrl    = "http://localhost/forsaPlus"; // عدّلها لو عندك دومين
            $verifyLink = $baseUrl . "/verify.php?email=" . urlencode($email);

            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'forsaplus7@gmail.com'; // بريد الإرسال
                $mail->Password   = 'hvfhbvqhkvmcfnbx';     // App Password
                $mail->SMTPSecure = 'tls';                   // استخدم TLS بدل SSL
                $mail->Port       = 587;                     // منفذ TLS

                // حل مؤقت لمشكلة شهادات SSL على XAMPP
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                        'allow_self_signed' => true
                    ]
                ];

                $mail->CharSet = 'UTF-8';
                $mail->setFrom('forsaplus7@gmail.com', 'Forsa Plus');
                $mail->addAddress($email, $fullname);

                $mail->SMTPDebug = 3; // معلومات تفصيلية للـ debug

                $mail->isHTML(true);
                $mail->Subject = 'تفعيل حسابك في Forsa Plus';
                $mail->Body    = "
                    <h2>مرحباً {$fullname}</h2>
                    <p>اضغط على الرابط أدناه لتفعيل حسابك:</p>
                    <p><a href='{$verifyLink}'>{$verifyLink}</a></p>
                ";
                $mail->AltBody = "مرحباً {$fullname}\nفعّل حسابك من الرابط: {$verifyLink}";

                $mail->send();
                echo "<script>alert('تم التسجيل ✅. تحقق من بريدك لتفعيل الحساب');</script>";

            } catch (Exception $e) {
                echo "<script>alert('تم إنشاء الحساب لكن فشل إرسال البريد: " . addslashes($mail->ErrorInfo) . "');</script>";
            }
        }
    }

    if (!empty($errors)) {
        $allErrors = implode("\\n", $errors);
        echo "<script>alert('$allErrors');</script>";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Render All Element to Normally -->
    <link rel="stylesheet" href="css/normalize.css" />
    <!-- font Awesome Library -->
    <link rel="stylesheet" href="css/all.min.css" />
    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,200;0,400;0,500;0,600;0,700;0,800;1,200&display=swap"
        rel="stylesheet" />
    <!-- the main file to css -->
    <link rel="stylesheet" href="css/Hamdan.css" />
    <title>New Account </title>
</head>

<body>
<!-- Start Header Section  -->
<div class="header">
    <div class="container">
        <div class="main-heading">
            <a href="#" class="logo">
                <img src="imgs/Group.png" alt="" />
                <h2>Forsa Plus</h2>
            </a>
            <ul class="links">
                <li>
                    <a href="#consolations">
                        <i class="fa fa-edit"></i>
                        <i class="fas fa-bag-shopping-check"></i>
                        Consolations</a>
                </li>
                <li>
                    <a href="#my-offers">
                        <span class="fa-hand-with-3-stars">
                            <i class="fas fa-hand-holding"></i>
                            <i class="fas fa-star s1"></i>
                            <i class="fas fa-star s2"></i>
                            <i class="fas fa-star s3"></i>
                        </span>
                        My Offers</a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-chevron-down"></i>
                        Other Links</a>
                    <div class="mega-menu">
                        <div class="image">
                            <img src="imgs/megamenu.png" alt="" />
                        </div>
                        <ul class="link">
                            <li>
                                <a href="#my-works"><i class="fa fa-shopping-bag"></i> My Works</a>
                            </li>
                            <li>
                                <a href="#profile"><i class="far fa-building fa-fw"></i>Personal Profile</a>
                            </li>
                            <li>
                                <a href="#Consultation-detail"><i class="fas fa-comment-dots"></i>
                                    Consultation detail</a>
                            </li>
                        </ul>
                        <ul class="link">
                            <li>
                                <a href="#contact"><i class="fa fa-phone"></i>Contact Us</a>
                            </li>
                            <li>
                                <a href="#messages"><i class="fas fa-envelope"></i> messages</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <a href="login.php" class="login">Login</a>
        </div>
    </div>
</div>
<!-- End Header Section  -->

<!-- Start Landing Page Section -->
<div class="landing-2">
    <div class="container">
        <div class="left">
            <div class="group">
                <img src="imgs/Group.png" alt="" />
                <h1>Forsa Plus</h1>
            </div>
        </div>
        <div class="right">
            <div class="form">
                <svg>
                    <rect></rect>
                </svg>
                <form action="" method="post">
                    <span>Login</span>
                    <label for="full">Full Name</label>
                    <input type="text" id="full" name="fullname"/>
                    <label for="age">The Age</label>
                    <input type="number" id="age" name="age"/>
                    <label for="email">Email Company/Retired</label>
                    <input type="email" id="email" name="email"/>
                    <label for="pass">password</label>
                    <input type="password" id="pass" name="password" />
                    <input type="submit" value="login" />
                    <a href="#">Forget your password?</a>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Landing Page Section  -->
<script src="js/main.js"></script>
</body>

</html>

