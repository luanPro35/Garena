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

// Login Forms
document.addEventListener('DOMContentLoaded', function () {
    const loginMethods = document.querySelectorAll(".login-methods");

    loginMethods.forEach(loginMethod => {
        const loginButtons = loginMethod.querySelectorAll("button");
        const loginForms = document.querySelectorAll(".login-form");

        loginButtons.forEach(button => {
            button.addEventListener("click", () => {
                // Bỏ active tất cả các nút
                loginButtons.forEach(btn => btn.classList.remove("active"));
                // Ẩn tất cả các form
                loginForms.forEach(form => form.style.display = "none");

                // Kích hoạt nút được click
                button.classList.add("active");

                // Hiển thị form tương ứng
                const formId = button.id;
                const targetFormId = formId.replace("-btn", "-form");
                const targetForm = document.getElementById(targetFormId);
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
    const closeBtn = loginModal.querySelector(".close-btn");
    const garenaLoginBtn = document.getElementById("garena-login-btn");
    const facebookLoginBtn = document.getElementById("facebook-login-btn");
    const garenaLoginForm = document.getElementById("garena-login-form");
    const facebookLoginForm = document.getElementById("facebook-login-form");
    const loginOptions = loginModal.querySelector(".login-options");

    if (loginBtn) {
        loginBtn.addEventListener('click', function (event) {
            event.preventDefault();
            loginModal.style.display = "block";
            garenaLoginForm.style.display = "none";
            facebookLoginForm.style.display = "none";
            loginOptions.style.display = "flex";
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            loginModal.style.display = "none";
        });
    }

    window.addEventListener('click', function (event) {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
    });

    if (garenaLoginBtn) {
        garenaLoginBtn.addEventListener('click', function (event) {
            event.preventDefault();
            facebookLoginBtn.style.display = "none";
            garenaLoginForm.style.display = "block";
            garenaLoginBtn.style.display = "none";
        });
    }

    if (facebookLoginBtn) {
        facebookLoginBtn.addEventListener('click', function (event) {
            event.preventDefault();
            garenaLoginBtn.style.display = "none";
            facebookLoginForm.style.display = "block";
            facebookLoginBtn.style.display = "none";
        });
    }
    if (loginBtn) {
        loginBtn.addEventListener('click', function (event) {
            event.preventDefault();
            loginModal.style.display = "block";
            garenaLoginForm.style.display = "none";
            facebookLoginForm.style.display = "none";
            loginOptions.style.display = "flex";
            garenaLoginBtn.style.display = "flex";
            facebookLoginBtn.style.display = "flex";
        });
    }

    // Page-specific login forms
    const garenaLoginBtnPage = document.getElementById("garena-login-btn-page");
    const facebookLoginBtnPage = document.getElementById("facebook-login-btn-page");
    const uidLoginBtnPage = document.getElementById("uid-login-btn-page");
    const appleLoginBtnPage = document.getElementById("apple-login-btn-page");
    const vkLoginBtnPage = document.getElementById("vk-login-btn-page");
    const googleLoginBtnPage = document.getElementById("google-login-btn-page");
    const twitterLoginBtnPage = document.getElementById("twitter-login-btn-page");
    
    const garenaLoginFormPage = document.getElementById("garena-login-form-page");
    const facebookLoginFormPage = document.getElementById("facebook-login-form-page");
    const uidLoginFormPage = document.getElementById("uid-login-form");
    const appleLoginFormPage = document.getElementById("apple-login-form");
    const vkLoginFormPage = document.getElementById("vk-login-form");
    const googleLoginFormPage = document.getElementById("google-login-form");
    const twitterLoginFormPage = document.getElementById("twitter-login-form");
    
    const loginMethodsPage = document.querySelector(".login-methods");

    if (garenaLoginBtnPage) {
        garenaLoginBtnPage.addEventListener('click', function (event) {
            event.preventDefault();
            garenaLoginFormPage.style.display = "block";
            facebookLoginFormPage.style.display = "none";
            uidLoginFormPage.style.display = "none";
            appleLoginFormPage.style.display = "none";
            vkLoginFormPage.style.display = "none";
            googleLoginFormPage.style.display = "none";
            twitterLoginFormPage.style.display = "none";
        });
    }

    if (facebookLoginBtnPage) {
        facebookLoginBtnPage.addEventListener('click', function (event) {
            event.preventDefault();
            facebookLoginFormPage.style.display = "block";
            garenaLoginFormPage.style.display = "none";
            uidLoginFormPage.style.display = "none";
            appleLoginFormPage.style.display = "none";
            vkLoginFormPage.style.display = "none";
            googleLoginFormPage.style.display = "none";
            twitterLoginFormPage.style.display = "none";
        });
    }
    
    if (uidLoginBtnPage) {
        uidLoginBtnPage.addEventListener('click', function (event) {
            event.preventDefault();
            uidLoginFormPage.style.display = "block";
            garenaLoginFormPage.style.display = "none";
            facebookLoginFormPage.style.display = "none";
            appleLoginFormPage.style.display = "none";
            vkLoginFormPage.style.display = "none";
            googleLoginFormPage.style.display = "none";
            twitterLoginFormPage.style.display = "none";
        });
    }
    
    // Thêm xử lý cho các nút còn lại
    if (appleLoginBtnPage) {
        appleLoginBtnPage.addEventListener('click', function (event) {
            event.preventDefault();
            appleLoginFormPage.style.display = "block";
            garenaLoginFormPage.style.display = "none";
            facebookLoginFormPage.style.display = "none";
            uidLoginFormPage.style.display = "none";
            vkLoginFormPage.style.display = "none";
            googleLoginFormPage.style.display = "none";
            twitterLoginFormPage.style.display = "none";
        });
    }
    
    // Tương tự cho các nút còn lại
    if (vkLoginBtnPage) {
        vkLoginBtnPage.addEventListener('click', function (event) {
            event.preventDefault();
            vkLoginFormPage.style.display = "block";
            garenaLoginFormPage.style.display = "none";
            facebookLoginFormPage.style.display = "none";
            uidLoginFormPage.style.display = "none";
            appleLoginFormPage.style.display = "none";
            googleLoginFormPage.style.display = "none";
            twitterLoginFormPage.style.display = "none";
        });
    }
    
    if (googleLoginBtnPage) {
        googleLoginBtnPage.addEventListener('click', function (event) {
            event.preventDefault();
            googleLoginFormPage.style.display = "block";
            garenaLoginFormPage.style.display = "none";
            facebookLoginFormPage.style.display = "none";
            uidLoginFormPage.style.display = "none";
            appleLoginFormPage.style.display = "none";
            vkLoginFormPage.style.display = "none";
            twitterLoginFormPage.style.display = "none";
        });
    }
    
    if (twitterLoginBtnPage) {
        twitterLoginBtnPage.addEventListener('click', function (event) {
            event.preventDefault();
            twitterLoginFormPage.style.display = "block";
            garenaLoginFormPage.style.display = "none";
            facebookLoginFormPage.style.display = "none";
            uidLoginFormPage.style.display = "none";
            appleLoginFormPage.style.display = "none";
            vkLoginFormPage.style.display = "none";
            googleLoginFormPage.style.display = "none";
        });
    }
});
