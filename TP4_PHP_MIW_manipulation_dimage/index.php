
<!DOCTYPE html>
<html>
<head>
    <title>Laragon</title>

    <link href="https://fonts.googleapis.com/css?family=Karla:400" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Karla';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
        }

        .opt {
            margin-top: 30px;
        }

        .opt a {
            text-decoration: none;
            font-size: 150%;
        }

        a:hover {
            color: red;
        }
    </style>
</head>
<body>
<?php

function mkmap($dir){
    echo "<ul>";
    $folder = opendir ($dir);

    while ($file = readdir ($folder)) {
        if ($file != "." && $file != "..") {
            $pathfile = $dir.'/'.$file;
            echo '<li><a href='.$pathfile.'>'.$file.'</a></li>';
            if(filetype($pathfile) == 'dir'){
                mkmap($pathfile);
            }
        }
    }
    closedir ($folder);
    echo "</ul>";
}
mkmap('fichiers');
?>
<form enctype="multipart/form-data" action="upload.php" method="post">
    Photo : <input type="file" name="photo"><br />
    <input type="text" name="taille">
    <input type="submit" value="Envoyer">
</form>

</body>
</html>