<?php
function Insert($userid, $connection)
{
  try {
    $name = $_POST['name'];
    $fecha = new DateTime();
    $imageName = $fecha->getTimestamp() . '_' . $_FILES['image']['name'];
    $description = $_POST['description'];
    $tmp_name = $_FILES['image']['tmp_name'];

    $imgSaved = move_uploaded_file($tmp_name, "../images/userproyects/" . $imageName);

    if ($imgSaved) {
      $sql = "INSERT INTO proyect (name,image,description,user_id)
              VALUES ('$name','$imageName','$description',$userid)";
      $proyectId = $connection->executedb($sql);

      echo json_encode([
        'success' => true,
        'proyectid' => $proyectId,
        'image' => $imageName,
      ], JSON_UNESCAPED_UNICODE);
    } else throw new Exception('Error');
  } catch (Exception $e) {
    echo json_encode(['success' => false]);
  }
}
