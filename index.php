<?php
include './inc/db.php';
include './inc/form.php';
include './inc/select.php'; 
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة السحوبات الكبرى - إدارة المسابقات</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
</head>
<body class="contest-portal-body">

    <!-- شريط تنبيهي علوي مع العداد -->
    <header class="top-announcement-bar">
        <div class="announcement-content">
            <span class="pulse-dot"></span>
            <p>السحب جارٍ الآن.. ينتهي خلال:</p>
            <div id="countdown" class="live-timer">0 يوم و 0 ساعة و 5 دقيقة و 0 ثانية</div>
        </div>
    </header>

    <!-- الهيكل الرئيسي مقسم لشبكة (Grid Layout) لتغيير الأماكن والتوزيع بالكامل -->
    <main class="main-dashboard-grid">
        
        <!-- قسم الشروط والتوجيهات (أصبح في اليمين أو المقدمة) -->
        <section class="rules-card-wrapper">
            <div class="card-header">
                <h2>تعليمات وقواعد الاشتراك</h2>
            </div>
            <div class="card-body">
                <ul class="styled-guidelines-list">
                    <li>
                        <span class="icon-badge">🎥</span>
                        <div>
                            <strong>الخطوة الأولى:</strong> 
                            <a href="https://youtu.be/6hgBFDTTwEk" target="_blank" class="video-link-highlight">شاهد الفيديو التعريفي اضغط هنا</a>
                        </div>
                    </li>
                    <li>
                        <span class="icon-badge">📝</span>
                        <div><strong>الخطوة الثانية:</strong> التسجيل بالاسم الثلاثي الحقيقي لضمان استلام الجائزة.</div>
                    </li>
                    <li>
                        <span class="icon-badge">⏳</span>
                        <div><strong>الخطوة الثالثة:</strong> الانتظار حتى اكتمال العد التنازلي لإعلان الفائز آلياً.</div>
                    </li>
                </ul>
                
                <div class="supervisor-badge-box">
                    <img src="" alt="مشرف المسابقة" class="supervisor-avatar">
                    <div class="supervisor-info">
                        <span class="label">إشراف وتنظيم:</span>
                        <span class="name">فراس القرني</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- قسم نموذج التسجيل (تم إعادة تصميمه بالكامل) -->
        <section class="registration-card-wrapper">
            <div class="card-header">
                <h2>نموذج التسجيل في السحب</h2>
                <p>أدخل بياناتك الشخصية بدقة للانضمام لقائمة المشاركين</p>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="modern-form-layout">
                    
                    <div class="input-group-row">
                        <div class="field-container">
                            <label for="firstName">الاسم الأول</label>
                            <input type="text" id="firstName" name="firstName" placeholder="مثال: علي" value="<?php echo htmlspecialchars($firstName ?? '') ?>">
                            <span class="error-msg"><?php echo $errors['firstNameError'] ?? '' ?></span>
                        </div>
                        
                        <div class="field-container">
                            <label for="lastName">الاسم الأخير</label>
                            <input type="text" id="lastName" name="lastName" placeholder="مثال: أحمد" value="<?php echo htmlspecialchars($lastName ?? '') ?>">
                            <span class="error-msg"><?php echo $errors['lastNameError'] ?? '' ?></span>
                        </div>
                    </div>

                    <div class="field-container full-width">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($email ?? '') ?>">
                        <span class="error-msg"><?php echo $errors['emailError'] ?? '' ?></span>
                    </div>

                    <div class="terms-checkbox-row">
                        <input type="checkbox" id="agree" name="agree" required>
                        <label for="agree">أقر بأنني شاهدت الفيديو وأوافق على شروط المسابقة وسياسة استخدام البيانات.</label>
                    </div>

                    <button type="submit" name="submit" id="submit" class="submit-action-btn">تأكيد التسجيل وإرسال البيانات</button>
                </form>
            </div>
        </section>

    </main>

    <!-- قسم لوحة التحكم أو سحب الفائز (في الأسفل بشكل بارز ومستقل) -->
    <div class="winner-control-footer-area">
        <button id="winner-btn" class="trigger-draw-btn">🎲 سحب واختيار الفائز العشوائي الآن</button>
    </div>

    <!-- نافذة منبثقة (Modal) لإعلان الفائز -->
    <div id="modal" class="custom-modal-overlay">
        <div class="custom-modal-container">
            <div id="loader" class="advanced-loader-spinner"></div>
            <div id="winner-details" class="winner-announcement-box" style="display: none;">
                <div class="trophy-icon">🏆</div>
                <h2>تهانينا للمحظوظ!</h2>
                <div class="fancy-divider"></div>
                <h1 class="winner-full-name">
                    <?php echo $winner ? htmlspecialchars($winner['firstName'] . ' ' . $winner['lastName']) : "عذراً، لا يوجد مسجلين حالياً"; ?>
                </h1>
                <p class="congrats-subtext">تم اختيار الفائز عشوائياً وبكل شفافية</p>
                <button class="close-modal-custom-btn" onclick="location.reload()">إغلاق النافذة وتحديث الصفحة</button>
            </div>
        </div>
    </div>

    <!-- ملف الجافاسكريبت الخارجي -->
    <script src="./js/script.js"></script>

    <!-- سكربت التحكم بالنافذة والاحتفالات بتنسيق محسن -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const winBtn = document.getElementById("winner-btn");
            const modalView = document.getElementById("modal");
            const loaderCircle = document.getElementById("loader");
            const infoWinner = document.getElementById("winner-details");

            if (winBtn) {
                winBtn.addEventListener("click", function() {
                    modalView.style.display = "flex";
                    loaderCircle.style.display = "block";
                    infoWinner.style.display = "none";

                    setTimeout(function() {
                        loaderCircle.style.display = "none";
                        infoWinner.style.display = "block";

                        var duration = Date.now() + (2.5 * 1000);
                        (function frame() {
                            confetti({ particleCount: 7, angle: 60, spread: 60, origin: { x: 0 }, zIndex: 10001 });
                            confetti({ particleCount: 7, angle: 120, spread: 60, origin: { x: 1 }, zIndex: 10001 });
                            if (Date.now() < duration) { requestAnimationFrame(frame); }
                        }());
                        confetti({ particleCount: 120, spread: 80, origin: { y: 0.5 }, zIndex: 10001 });
                    }, 2200);
                });
            }
        });
    </script>

</body>
</html>
<?php include './inc/db_close.php'; ?>