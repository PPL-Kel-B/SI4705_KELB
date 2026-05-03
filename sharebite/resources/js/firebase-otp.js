import { initializeApp } from 'firebase/app';
import { getAuth, RecaptchaVerifier, signInWithPhoneNumber } from 'firebase/auth';

// INISIALISASI FIREBASE (Wajib diganti config aslinya)
const firebaseConfig = {
    apiKey: "AIzaSyAdzfNYOtvXUJNoKwIyAnjrH0cLWjZ9qns",
    authDomain: "sharebite-99484.firebaseapp.com",
    projectId: "sharebite-99484",
    storageBucket: "sharebite-99484.firebasestorage.app",
    messagingSenderId: "1091213043078",
    appId: "1:1091213043078:web:6c294f15a16d8a85a63807"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
auth.languageCode = 'id';

document.addEventListener("DOMContentLoaded", function () {
    const registForm = document.getElementById('registForm');
    if (!registForm) return;

    const submitButton = document.getElementById('submitButton');
    const otpSection = document.getElementById('otp-section');
    const registrationSection = document.getElementById('registration-section');
    const otpInputs = document.querySelectorAll('#otp-inputs input');
    const btnConfirmOtp = document.getElementById('btn-confirm-otp');
    const timerDisplay = document.getElementById('otp-timer');
    const btnResend = document.getElementById('btn-resend-otp');
    const btnBackToNumber = document.getElementById('btn-back-to-number');

    if (!submitButton || !otpSection || !registrationSection) return;

    let isOtpVerified = false;
    let timerInterval;
    let confirmationResultObj = null;
    let recaptchaVerifier = null;
    const originalBtnText = submitButton.innerHTML;

    recaptchaVerifier = new RecaptchaVerifier(auth, 'recaptcha-container', {
        'size': 'invisible'
    });

    registForm.addEventListener('submit', function (e) {
        if (!isOtpVerified) {
            e.preventDefault();

            const phoneInput = document.querySelector('input[name="no_hp"]');
            if (!phoneInput) return;

            let formattedNumber = phoneInput.value.replace(/\D/g, '');
            if (formattedNumber.startsWith('0')) {
                formattedNumber = '+62' + formattedNumber.substring(1);
            } else if (formattedNumber.startsWith('62')) {
                formattedNumber = '+' + formattedNumber;
            } else {
                formattedNumber = '+62' + formattedNumber;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = 'Mengirim OTP...';

            signInWithPhoneNumber(auth, formattedNumber, recaptchaVerifier)
                .then((confirmationResult) => {
                    confirmationResultObj = confirmationResult;
                    registrationSection.classList.add('hidden');
                    otpSection.classList.remove('hidden');
                    startOtpTimer();
                }).catch((error) => {
                    console.error("SMS Error", error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Gagal mengirim OTP.', 'error');
                    } else {
                        alert('Gagal mengirim OTP.');
                    }
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalBtnText;
                    if (recaptchaVerifier) {
                        recaptchaVerifier.render().then(function (widgetId) {
                            grecaptcha.reset(widgetId);
                        });
                    }
                });
        }
    });

    if (otpInputs.length > 0) {
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });
    }

    if (btnConfirmOtp) {
        btnConfirmOtp.addEventListener('click', function () {
            const otpCode = Array.from(otpInputs).map(i => i.value).join('');
            if (otpCode.length === 6) {
                btnConfirmOtp.disabled = true;
                btnConfirmOtp.innerHTML = 'Memverifikasi...';

                if (confirmationResultObj) {
                    confirmationResultObj.confirm(otpCode).then((result) => {
                        isOtpVerified = true;
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Nomor terverifikasi.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                registForm.submit();
                            });
                        } else {
                            registForm.submit();
                        }
                    }).catch((error) => {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', 'Kode OTP salah.', 'error');
                        } else {
                            alert('Kode OTP salah.');
                        }
                        btnConfirmOtp.disabled = false;
                        btnConfirmOtp.innerHTML = 'Konfirmasi';
                    });
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', 'Harap masukkan 6 digit kode OTP.', 'warning');
                }
            }
        });
    }

    function startOtpTimer() {
        let timeLeft = 119;
        if (btnResend) btnResend.disabled = true;

        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            let m = Math.floor(timeLeft / 60);
            let s = timeLeft % 60;
            if (timerDisplay) timerDisplay.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                if (btnResend) btnResend.disabled = false;
            }
            timeLeft--;
        }, 1000);
    }

    if (btnResend) {
        btnResend.addEventListener('click', function () {
            btnResend.disabled = true;
            btnResend.innerHTML = 'MENGIRIM...';

            const phoneInput = document.querySelector('input[name="no_hp"]');
            let formattedNumber = phoneInput.value.replace(/\D/g, '');
            if (formattedNumber.startsWith('0')) {
                formattedNumber = '+62' + formattedNumber.substring(1);
            } else if (formattedNumber.startsWith('62')) {
                formattedNumber = '+' + formattedNumber;
            } else {
                formattedNumber = '+62' + formattedNumber;
            }

            signInWithPhoneNumber(auth, formattedNumber, recaptchaVerifier)
                .then((confirmationResult) => {
                    confirmationResultObj = confirmationResult;
                    startOtpTimer();
                    btnResend.innerHTML = 'Kirim Ulang';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'OTP berhasil dikirim ulang!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }).catch((error) => {
                    console.error("SMS Resend Error", error);
                    btnResend.innerHTML = 'Kirim Ulang';
                    btnResend.disabled = false;
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Gagal mengirim ulang OTP.', 'error');
                    }
                });
        });
    }

    if (btnBackToNumber) {
        btnBackToNumber.addEventListener('click', function () {
            otpSection.classList.add('hidden');
            registrationSection.classList.remove('hidden');
            submitButton.disabled = false;
            submitButton.innerHTML = originalBtnText;
        });
    }
});