<?php
function GetProyects($userId, $connection)
{
  $sql = "SELECT u.id, p.id, p.name, p.image, p.description
				  FROM proyect p INNER JOIN user u
				  on p.user_id = u.id having u.id = $userId";
  $proyects = $connection->query($sql);
  return $proyects;
}
