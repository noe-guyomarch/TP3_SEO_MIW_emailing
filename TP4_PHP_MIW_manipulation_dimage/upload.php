<?php

require_once 'config.php';
$bdd = getBdd();


function getPath($file){
    $path = new SplFileInfo($file);
    return $path->getPath();
}

function getExt($file){
    $ext= new SplFileInfo($file);
    return strtolower($ext->getExtension());
}

function resizeIMG($sourcePath, $thumbWidth, $thumbHeight,$fichier, $imgWidth, $imgHeight){
    $ext = getExt($_FILES['photo']['name']);
    switch ($ext){
        case 'gif':
            $source_gd_image = imagecreatefromgif($sourcePath);
            break;
        case 'jpeg':
        case 'jpg':
            $source_gd_image = imagecreatefromjpeg($sourcePath);
            break;
        case 'png':
            $source_gd_image = imagecreatefrompng($sourcePath);
            break;
    }
    if ($source_gd_image == false){
        echo 'Erreur lors de la récupération de l\'image';
        echo '<br><button><a href="index.php"> Revenir en arrière</a></button>';
        die();
    }

    $thumbnail = imagecreatetruecolor($thumbWidth,$thumbHeight);

    imagecopyresampled($thumbnail, $source_gd_image, 0, 0, 0, 0, $thumbWidth,
        $thumbHeight, $imgWidth, $imgHeight);


    imagejpeg($thumbnail, getPath($sourcePath).'/thumb_'.$fichier, 90);

    imagedestroy($source_gd_image);
    imagedestroy($thumbnail);

    echo 'L\'image a été envoyé et resize !';
}


function uploadFile($bdd){
    $taille = $_POST['taille'];
    if(isset($_FILES['photo'])){

        if($_FILES['photo']['error'] == UPLOAD_ERR_OK){
            $Année = date('Y');
            $Mois = date('F');

            if (!is_dir('fichiers/'.$Année)){
                mkdir('fichiers/'.$Année);
                if (!is_dir('fichiers/'.$Année.'/'.$Mois)){
                    mkdir('fichiers/'.$Année.'/'.$Mois);
                }
            }else{
                if (!is_dir('fichiers/'.$Année.'/'.$Mois)){
                    mkdir('fichiers/'.$Année.'/'.$Mois, true);
                }
            }

            $exten = getExt($_FILES['photo']['name']);
            $fichier = $_FILES['photo']['name'] = date('mdYhis').rand(100, 999999999).'.'.$exten;;//ou on peu mettre le nom de fichier que l'on veut pour être certain d'éviter les doublons





            $lien = 'fichiers/'.$Année.'/'.$Mois.'/'.$fichier;


            if(move_uploaded_file($_FILES['photo']['tmp_name'], $lien)){

                list($imgWidth, $imgHeight) = getimagesize($lien);

                if ($exten == 'png'||'jpg'||'jpeg'||'gif'){
                    if ($imgWidth > $imgHeight)
                    {
                        resizeIMG($lien, 1*$taille, ($imgHeight/$imgWidth)*$taille, $fichier, $imgWidth, $imgHeight);
                    }elseif ($imgWidth <= $imgHeight) {
                        resizeIMG($lien, $imgWidth/$imgHeight*$taille, 1 * $taille, $fichier, $imgWidth, $imgHeight);
                    }
                }

                //la fonction renvoie true, le fichier a bien été enregistré
                echo 'L\'upload a bien fonctionné !';
                //on traite le formulaire d'ajout ou d'édition
                //c'est un ajout

                $req = $bdd->prepare('INSERT INTO equipe 
            (lien, type) 
            VALUE
            (:lien,:type)
        ');
                $req->bindValue(':lien', $lien);
                $req->bindValue(':type', $exten);
                $req->execute();
                $req->closeCursor();
            }
            echo '<br><button><a href="index.php"> Revenir en arrière</a></button>';
        }else{
            echo 'echec de l\'upload.';
            echo '<br><button><a href="index.php"> Revenir en arrière</a></button>';
        }
    }else{
        echo 'Une erreur est survenue, veuillez réessayer';
        echo '<br><button><a href="index.php"> Revenir en arrière</a></button>';
    }


}

uploadFile($bdd);
?>