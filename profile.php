<?php
include "koneksi.php";  
include "upload_foto.php";

// Cek jika pengguna sudah login, jika belum, arahkan ke halaman login
if (!isset($_SESSION['username'])) { 
    header("location:login.php"); 
}

// Ambil data pengguna berdasarkan session username
$username = $_SESSION['username'];
$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Jika pengguna tidak ditemukan, arahkan ke halaman login
if (!$user) {
    header("location:login.php");
    exit;
}

// Cek jika form disubmit untuk mengganti data
if (isset($_POST['simpan'])) {
    $username = $_POST['username'];  // Update username
    $password = $_POST['password'];
    $gambar = '';
    $nama_gambar = $_FILES['gambar']['name'];

    // Proses upload gambar baru jika ada
    if ($nama_gambar != '') {
        $cek_upload = upload_foto($_FILES["gambar"]);  // Asumsikan fungsi upload_foto sudah dibuat
        if ($cek_upload['status']) {
            $gambar = $cek_upload['message'];
        } else {
            echo "<script>
                alert('" . $cek_upload['message'] . "');
                document.location='profile.php';
            </script>";
            die;
        }
    } else {
        // Jika tidak mengganti gambar, gunakan gambar lama
        $gambar = $_POST['gambar_lama'];
    }

    // Proses mengganti password jika ada
    if ($password != '') {
        // Encrypt password dengan MD5
        $password = md5($password);
    } else {
        // Jika password tidak diubah, gunakan password lama
        $password = $user['password'];
    }
    
    // Update data pengguna
    $stmt = $conn->prepare("UPDATE user SET username = ?, password = ?, foto = ? WHERE username = ?");
    $stmt->bind_param("ssss", $username, $password, $gambar, $user['username']);
    $simpan = $stmt->execute();

    if ($simpan) {
        echo "<script>
            alert('Data berhasil diperbarui');
            document.location='profile.php';
        </script>";
    } else {
        echo "<script>
            alert('Data gagal diperbarui');
            document.location='profile.php';
        </script>";
    }

    $stmt->close();
}

// Cek jika tombol logout ditekan
if (isset($_POST['logout'])) {
    session_destroy();
    header("location:login.php");
    exit;
}

?>

<div class="container">
    <h2 class="text-center fw-bold display-6">Profil Saya</h2>
    
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru (kosongkan jika tidak ingin mengganti)">
                        </div>

                        <div class="mb-3">
                            <label for="gambar" class="form-label">Ganti Foto Profil</label>
                            <input type="file" class="form-control" id="gambar" name="gambar">
                        </div>

                        <div class="mb-3">
                            <label for="gambarLama" class="form-label">Foto Profil Lama</label><br>
                            <?php if ($user['foto']) { ?>
                                <img src="img/<?= $user['foto'] ?>" width="100">
                            <?php } else { ?>
                                <p>Tidak ada foto profil.</p>
                            <?php } ?>
                            <input type="hidden" name="gambar_lama" value="<?= $user['foto'] ?>">
                        </div>

                        <div class="mb-3">
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>

                    <!-- Tombol Logout -->
                    <form method="post" action="">
                        <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>