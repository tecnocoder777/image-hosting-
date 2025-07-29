<?php
header('Content-Type: application/json');

if (!isset($_FILES['image'])) {
  echo json_encode(["success" => false, "error" => "No file uploaded"]);
  exit;
}

$targetDir = "uploads/";
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

$file = $_FILES['image'];
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid() . "." . $ext;
$targetFile = $targetDir . $filename;

if (move_uploaded_file($file['tmp_name'], $targetFile)) {
  $url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/uploads/$filename";
  echo json_encode(["success" => true, "url" => $url]);
} else {
  echo json_encode(["success" => false, "error" => "Upload failed"]);
}