
<?php
session_start();
require("connection.php");

$msgScript = "";
$email = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "البريد الإلكتروني غير صالح.";
    }

    if ($password === '') {
        $errors[] = "اكتب كلمة المرور.";
    }

	
    if (empty($errors)) {
        $stmt = $con->prepare("SELECT id, fullname, email, password FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        if ($stmt->rowCount() === 0) {
            $errors[] = "البريد غير موجود";
        } else {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user['password'] !== md5($password)) {
                $errors[] = "كلمة المرور غير صحيحة.";
            } else {
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $msgScript = "<script>window.location.href='dashboard.php';</script>";
            }
        }
    }

    if (!empty($errors)) {
        $allErrors = implode("\\n", $errors);
        $msgScript = "<script>alert('$allErrors');</script>";
    }
}
?>

<!-- Hamdan -->
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
		<title>Login </title>
	</head>

	<body>

		<?php
		if (!empty($msgScript)) echo $msgScript;
	?>
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
									<a href="#my-works"><i class="fa fa-shopping-bag"></i>
	
										My Works</a>
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
									<a href="#messages"><i class="fas fa-envelope"></i>
										</i>messages</a>
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
						<form action="login.php" method="post">
							<span>Login</span>
							<label for="e">e-mail</label>
							<input type="email" id="e" name="email"/>
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
