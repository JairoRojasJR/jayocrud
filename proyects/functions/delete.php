<?php
function Delete($userid, $connection)
{
  try {
    $proyectId = $_POST['id'];

    $sql = "SELECT image FROM proyect WHERE id = $proyectId AND user_id = $userid";
    $image = $connection->query($sql);

    if (!$image) throw new Exception('Error');

    $sql = "DELETE FROM proyect WHERE id = $proyectId AND user_id = $userid";
    $connection->executedb($sql);
    unlink('../images/userproyects/' . $image[0]['image']);

    echo json_encode(['id' => $proyectId, 'success' => true]);
  } catch (Exception $e) {
    echo json_encode(['success' => false]);
  }
}
