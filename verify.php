<?php
require("connection.php");

$email = $_GET['email'] ?? '';

if (!$email) {
    exit("طلب غير صالح ❌");
}

// نبحث عن المستخدم
$stmt = $con->prepare("SELECT id, active FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit("الحساب غير موجود ❌");
}

if ($user['active'] == 1) {
    exit("حسابك مفعل مسبقاً ✅");
}

// تحديث active = 1
$upd = $con->prepare("UPDATE users SET active = 1 WHERE email = ?");
$upd->execute([$email]);

echo "تم تفعيل حسابك بنجاح ✅. يمكنك الآن تسجيل الدخول.";
