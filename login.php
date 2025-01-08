<?php
// Memulai session
session_start();

// Menyertakan file koneksi
include "koneksi.php";

//check jika sudah ada user yang login arahkan ke halaman admin
if (isset($_SESSION['username'])) { 
	header("location:admin.php"); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['users']; // Pastikan nama input sesuai dengan ini
    $password = md5($_POST['password']); // Enkripsi password dengan md5

    // Prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT username FROM user WHERE username=? AND password=?");

    // Parameter binding
    $stmt->bind_param("ss", $username, $password);

    // Eksekusi query
    $stmt->execute();
    
    // Menampung hasil query
    $hasil = $stmt->get_result();

    // Ambil baris hasil
    $row = $hasil->fetch_array(MYSQLI_ASSOC);

    // Jika ada hasil cocok
    if (!empty($row)) {
        $_SESSION['username'] = $row['username']; // Set session
        header("location:admin.php"); // Redirect ke halaman admin
    } else {
        header("location:login.php"); // Redirect ke login jika gagal
    }

    // Menutup koneksi
    $stmt->close();
    $conn->close();
} else {
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | My Daily Journal</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link rel="icon" href="img/logo.png" />
  </head>
  <body class="bg-danger-subtle">
  <div class="container mt-5 pt-5">
  <div class="row">
    <div class="col-12 col-sm-8 col-md-6 m-auto">
      <div class="card border-0 shadow rounded-5">
        <div class="card-body">
          <div class="text-center mb-3">
            <i class="bi bi-person-circle h1 display-4"></i>
            <p>My Daily Journal</p>
            <hr />
          </div>
          <form method="post">
            <input
              type="text"
              name="users"
              id="username"
              class="form-control my-4 py-2 rounded-4"
              placeholder="Username"
            />
            <input
              type="password"
              name="password"
              id="password"
              class="form-control my-4 py-2 rounded-4"
              placeholder="Password"
            />
            <div class="text-center my-3 d-grid">
              <button type="submit" class="btn btn-danger rounded-4">Login</button>
            </div>
          </form>
          <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger mt-3" role="alert">
                  <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']); 
                  ?>
                </div>
              <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"
    ></script>
  </body>
</html>

<?php
}
?>