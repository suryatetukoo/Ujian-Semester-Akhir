document.addEventListener("DOMContentLoaded", () => {
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const signInButton = document.getElementById("signin-button");
    const signInGoogleButton = document.getElementById("signin-google-button");

    function validateInputs() {
        const isEmailFilled = emailInput.value.trim() !== "";
        const isPasswordFilled = passwordInput.value.trim() !== "";

        // Aktifkan tombol jika kedua input terisi
        signInButton.disabled = !(isEmailFilled && isPasswordFilled);
        signInGoogleButton.disabled = !(isEmailFilled && isPasswordFilled);
    }

    emailInput.addEventListener("input", validateInputs);
    passwordInput.addEventListener("input", validateInputs);

    signInButton.addEventListener("click", () => {
        console.log("Navigating to after_login.html");
        window.location.href = "after_login.html";
    });

        // Callback saat login berhasil
        function handleCredentialResponse(response) {
            console.log("Encoded JWT ID token: " + response.credential);

            // Kirim token ke server untuk verifikasi
            fetch('/verify-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ token: response.credential }),
            })
            .then((res) => res.json())
            .then((data) => {
                console.log("User data:", data);
            })
            .catch((err) => console.error("Error:", err));
        }

        window.onload = function () {
            google.accounts.id.initialize({
                client_id: "YOUR_GOOGLE_CLIENT_ID",
                callback: handleCredentialResponse,
            });
            google.accounts.id.renderButton(
                document.querySelector(".g_id_signin"),
                { theme: "outline", size: "large" }
            );
        };
});

//carousel

var myCarousel = document.querySelector('#carouselExampleFade');
var carousel = new bootstrap.Carousel(myCarousel, {
  interval: 3000,  // Ganti gambar setiap 3 detik
  ride: 'carousel'
});