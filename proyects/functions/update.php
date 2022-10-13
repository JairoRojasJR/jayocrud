<?php
function Update($userid, $connection)
{
  try {
    $proyectid = (int)$_POST['proyectid'];
    $name = $_POST['name'];
    $tmp_image = $_FILES['image']['tmp_name'];
    $image = $_FILES['image']['name'];
    $description = $_POST['description'];

    $sql = "SELECT image FROM proyect WHERE id = $proyectid AND user_id = $userid";
    $queryImage = $connection->query($sql);

    if (!$queryImage) throw new Exception('Error');
    if (!$image) $image = $queryImage[0]['image'];
    else {
      $fecha = new DateTime();
      $image = $fecha->getTimestamp() . '_' . $image;
      $oldImage = $queryImage[0]['image'];

      unlink('../images/userproyects/' . $oldImage);
      move_uploaded_file($tmp_image, '../images/userproyects/' . $image);
    }

    $sql = "UPDATE proyect 
						SET name = '$name', image = '$image', description = '$description' 
						WHERE id = $proyectid AND user_id = $userid";
    $currentUrlImage = '../images/userproyects/' . $image;

    $connection->executedb($sql);
    echo json_encode(['success' => true, 'currentUrlImage' => $currentUrlImage]);
  } catch (Exception $e) {
    echo json_encode(['success' => false]);
  }
}
