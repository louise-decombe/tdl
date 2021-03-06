<?php

$date = new datetime('now', new DateTimeZone('Europe/Paris'));

 //on inclut le fichier de config qui charge les classes
require "../class/config.php";

// si on souhaite ajouter une tâche à la todolist
if (isset($_GET['ajouter'])) {

  
    if ($_POST["nom_tache"]) {
        $id_utilisateur = $_SESSION['id'];
        $nom = $_POST['nom_tache'];
        $statut = "non";
        $create_at = $date->format('Y-m-d H:i');
        $finished_at = null;
        $description = $_POST['description'];
         $assigned_to = $_POST['select_user'];
        $idlist = $_POST['idlist'];

        $name = $newlist->getListName($idlist);
        
        
        $list_name= json_decode($name);
        $name_list = $list_name[0]->nom;
        $result = $todolist->ajout($id_utilisateur, $nom, $statut, $create_at, $finished_at, $description, $assigned_to, $idlist, $name_list);
        $last_todo = $todolist->select();

        echo '<a href="#" style="' .'" class="list-group-item" id="list-group-item-' .$last_todo[0]["id"] . '" data-id="' . $last_todo[0]["id"] . '"><b>' . $last_todo[0]["nom"] . '</b><span class="badge" data-id="' . $last_todo[0]["id"] . '">X</span>' . '<section>'.'</section>';

    }
}



// si on souhaite surligner la tâche au clique pour dire qu'elle est terminée
if (isset($_GET['maj'])) {

// on sélectionn l'id
//$_POST['id']=9;
//  if ($_POST["id"]) {
//        $id = $_POST['id'];
//        $finished_at= date("Y-m-d H:i:s");
//        $statut= 'oui';
//        $maj = $todolist->maj($id,$statut,$finished_at);

        if($_POST["id"])
        {
         $data = array(
          ':statut'  => 'oui',
          ':id'  => $_POST["id"],
          ':finished_at' => $date->format('Y-m-d H:i')
         );
         $connect = new PDO("mysql:host=localhost;dbname=todolist", "root", "");
         $query = " UPDATE todo
         SET statut = :statut, finished_at = :finished_at
         WHERE id = :id
         ";
         $statement = $connect->prepare($query);
         if($statement->execute($data))
         {
           $last_todo = $todolist->select_last();

          echo 'terminé!';
         }
        }
      }

//si on souhaite supprimer la tâche
if (isset($_GET['supprimer'])) {
  //supprimer une tâche de la todolist
    // si l'utilisateur clique sur la croix pour supprimer on récupère l'id de la tâche pour la supprimer
    if ($_GET["id"]) {

      $connect = new PDO("mysql:host=localhost;dbname=todolist", "root", "");

        $data = array(
            ':id'  => $_GET['id']
        );
        $query = "DELETE FROM todo WHERE id = :id";
        $statement = $connect->prepare($query);
        if ($statement->execute($data)) {
            echo 'correctement supprimé';
        }
    }
}



?>
