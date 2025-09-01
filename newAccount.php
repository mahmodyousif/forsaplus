<?php
require("connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fullname = $_POST['fullname'] ?? '';
    $age      = $_POST['age'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // بناءً على طلبك الاعتماد على MD5
    $hashedPassword = md5($password);

    $errors = [];

    if (empty($fullname)) {
        $errors[] = "اكتب الاسم الكامل";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "البريد الإلكتروني غير صالح.";
    }

    if (strlen($password) < 8) {
        $errors[] = "كلمة المرور يجب ألا تقل عن 8 أحرف.";
    }

    if (empty($errors)) {
        // التأكد من عدم تكرار البريد
        $stmt = $con->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        if ($stmt->rowCount() != 0) {
            $errors[] = "هذا البريد موجود من قبل";
        } else {
            // إدخال البيانات
            $stmt = $con->prepare("INSERT INTO users SET fullname = ?, age = ?, email = ?, password = ?");
            $stmt->execute([$fullname, $age, $email, $hashedPassword]);

            echo "<script>alert('تم التسجيل بنجاح ✅');</script>";
        }
    }

    // إذا فيه أخطاء نعرضها في Alert واحد
    if (!empty($errors)) {
        $allErrors = implode("\\n", $errors); // كل خطأ بسطر جديد
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
