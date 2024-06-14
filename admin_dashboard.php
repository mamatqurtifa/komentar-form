<?php
session_start();

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'db_komentar');

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'muttify_' && $password === 'rahasia') {
        $_SESSION['admin'] = $username;
    } else {
        $error = "Invalid credentials";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_dashboard.php");
    exit();
}

// Handle comment deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM tbl_komentar WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch comments
$query = "SELECT * FROM tbl_komentar ORDER BY id DESC";
$result = $db->query($query);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="container mx-auto p-4">
    <?php if (isset($_SESSION['admin'])): ?>
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Admin Dashboard</h1>
            <a href="admin_dashboard.php?logout" class="text-red-600">Logout</a>
        </div>
        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="py-2">ID</th>
                <th class="py-2">Nama</th>
                <th class="py-2">Komentar</th>
                <th class="py-2">Tanggal</th>
                <th class="py-2">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $row['id']; ?></td>
                    <td class="border px-4 py-2"><?php echo $row['nama']; ?></td>
                    <td class="border px-4 py-2"><?php echo $row['komentar']; ?></td>
                    <td class="border px-4 py-2"><?php echo $row['tanggal_komen']; ?></td>
                    <td class="border px-4 py-2">
                        <a href="admin_dashboard.php?delete=<?php echo $row['id']; ?>" class="text-red-600">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="flex justify-center items-center h-screen">
            <div class="w-full max-w-xs">
                <form method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                            Username
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="username" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Password
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="login">
                            Login
                        </button>
                    </div>
                    <?php if (isset($error)): ?>
                        <p class="text-red-500 text-xs italic mt-4"><?php echo $error; ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
