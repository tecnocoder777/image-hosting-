<?php
header('Content-Type: application/json');
ini_set('display_errors',1);
error_reporting(E_ALL);

if (!isset($_FILES['image'])) {
  echo json_encode(["success"=>false,"error"=>"No file uploaded"]);
  exit;
}

if ($_FILES['image']['size'] > 10 * 1024 * 1024) {
  echo json_encode(["success"=>false,"error"=>"File size exceeds 10 MB"]);
  exit;
}

$targetDir = __DIR__ . "/uploads/";
if (!is_dir($targetDir)) mkdir($targetDir);

$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$filename = uniqid() . "." . $ext;
$targetFile = $targetDir . $filename;

if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
  $url = (isset($_SERVER['HTTPS'])?'https':'http') . "://"
         . $_SERVER['HTTP_HOST']
         . dirname($_SERVER['SCRIPT_NAME']) . "/uploads/$filename";
  echo json_encode(["success"=>true, "url"=>$url]);
} else {
  echo json_encode(["success"=>false,"error"=>"Failed to move uploaded file"]);
}
