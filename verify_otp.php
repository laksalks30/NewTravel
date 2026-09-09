<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Travel Website</title>
    
    <!-- Bootstrap Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Roboto', sans-serif;
        }
        
        .otp-card {
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .otp-input {
            width: 50px;
            height: 60px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 0 5px;
            border: 2px solid #ddd;
            border-radius: 10px;
        }
        
        .otp-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .timer {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
        }
        
        .debug-box {
            background: #fff3cd;
            border: 2px dashed #ffc107;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .debug-otp {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 10px;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card otp-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                            <h2>Verifikasi OTP</h2>
                            <p class="text-muted">Kode OTP telah dikirim ke email Anda</p>
                            <?php if (isset($_GET['email'])): ?>
                                <p class="text-primary"><strong><?= htmlspecialchars($_GET['email']) ?></strong></p>
                            <?php endif; ?>
                        </div>

                        <!-- DEBUG MODE - Tampilkan OTP (Development Only!) -->
                        <?php if (isset($_GET['debug_otp']) && $_GET['debug_otp'] != ''): ?>
                            <div class="debug-box">
                                <div class="text-center">
                                    <i class="fas fa-bug"></i> <strong>DEBUG MODE</strong>
                                    <p class="mb-2">Kode OTP Anda:</p>
                                    <div class="debug-otp"><?= htmlspecialchars($_GET['debug_otp']) ?></div>
                                    <small class="text-muted">* Kode ini hanya muncul dalam development mode</small>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Alert Success/Error -->
                        <?php if (isset($_GET['otp_sent']) && isset($_GET['resend'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> Kode OTP baru telah dikirim!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_GET['error']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="proses_otp.php" method="POST" id="otpForm">
                            <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                            
                            <!-- OTP Input -->
                            <div class="mb-4">
                                <label class="form-label text-center d-block"><i class="fas fa-keyboard"></i> Masukkan Kode OTP</label>
                                <div class="d-flex justify-content-center">
                                    <input type="text" class="otp-input" maxlength="1" name="otp1" id="otp1" required autocomplete="off">
                                    <input type="text" class="otp-input" maxlength="1" name="otp2" id="otp2" required autocomplete="off">
                                    <input type="text" class="otp-input" maxlength="1" name="otp3" id="otp3" required autocomplete="off">
                                    <input type="text" class="otp-input" maxlength="1" name="otp4" id="otp4" required autocomplete="off">
                                    <input type="text" class="otp-input" maxlength="1" name="otp5" id="otp5" required autocomplete="off">
                                    <input type="text" class="otp-input" maxlength="1" name="otp6" id="otp6" required autocomplete="off">
                                </div>
                                <input type="hidden" name="otp" id="otpFull">
                            </div>

                            <!-- Timer Countdown -->
                            <div class="text-center mb-4">
                                <p class="text-muted mb-1">Kode OTP akan expired dalam:</p>
                                <div class="timer" id="timer">05:00</div>
                            </div>

                            <!-- Password Baru -->
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-lock"></i> Password Baru</label>
                                <input type="password" class="form-control form-control-lg" name="new_password" 
                                       placeholder="Minimal 6 karakter" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label"><i class="fas fa-lock"></i> Konfirmasi Password</label>
                                <input type="password" class="form-control form-control-lg" name="confirm_password" 
                                       placeholder="Ketik ulang password" required>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 mb-3">
                                <button class="btn btn-primary btn-lg" type="submit" name="submitVerifyOTP" id="btnVerify">
                                    <i class="fas fa-check-circle"></i> Verifikasi & Reset Password
                                </button>
                            </div>
                        </form>

                        <!-- Resend OTP -->
                        <div class="text-center mt-4">
                            <p class="text-muted">Tidak menerima kode?</p>
                            <form action="proses_otp.php" method="POST" class="d-inline">
                                <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                                <button type="submit" name="resendOTP" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-redo"></i> Kirim Ulang OTP
                                </button>
                            </form>
                        </div>

                        <div class="text-center mt-3">
                            <a href="login.php" class="text-muted"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-focus & Auto-move OTP inputs
        const otpInputs = document.querySelectorAll('.otp-input');
        
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Only allow numbers
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                
                // Auto-move to next input
                if (e.target.value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                // Update hidden input dengan full OTP
                updateFullOTP();
            });
            
            input.addEventListener('keydown', (e) => {
                // Move to previous input on backspace
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
            
            // Auto-select content on focus
            input.addEventListener('focus', (e) => {
                e.target.select();
            });
        });
        
        // Focus first input on load
        otpInputs[0].focus();
        
        // Update full OTP hidden input
        function updateFullOTP() {
            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });
            document.getElementById('otpFull').value = otp;
        }
        
        // Timer Countdown (5 minutes)
        let timeLeft = 5 * 60; // 5 minutes in seconds
        const timerElement = document.getElementById('timer');
        const btnVerify = document.getElementById('btnVerify');
        
        const countdown = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            if (timeLeft <= 60) {
                timerElement.style.color = '#dc3545'; // Red color saat kurang dari 1 menit
            }
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerElement.textContent = 'EXPIRED';
                timerElement.style.color = '#dc3545';
                btnVerify.disabled = true;
                btnVerify.innerHTML = '<i class="fas fa-times-circle"></i> Kode OTP Expired';
                
                // Show alert
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger mt-3';
                alertDiv.innerHTML = '<i class="fas fa-clock"></i> Kode OTP sudah expired. Silakan kirim ulang kode.';
                document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('form'));
            }
            
            timeLeft--;
        }, 1000);
        
        // Paste OTP from clipboard
        document.addEventListener('paste', (e) => {
            const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
            if (pastedData.length === 6) {
                otpInputs.forEach((input, index) => {
                    input.value = pastedData[index] || '';
                });
                updateFullOTP();
                otpInputs[5].focus();
            }
        });
    </script>
</body>
</html>
