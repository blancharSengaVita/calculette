<?php

//include moins utilisé.
//include 'config.php';
// si le fichier n'existe pas dans le require, php va envoyer une erreure fatale.
require 'config.php';

function validated(){
    // permet de vérifier si l'item est dans le tableau
    //var_dump permet de voir le contenu
    //var_dump(in_array('add', OPERATIONS));
    //fonction qui test si la clé existe dans le tableau
    //var_dump(array_key_exists($_GET['operation'], OPERATIONS));

    if(!array_key_exists($_GET['operation'], OPERATIONS)){
        return ['message' => 'L´opération '. $_GET['operation'] . ' n´est pas prévue par le système'];
    }

    if(!is_numeric($_GET['nbr1']) && !is_numeric($_GET['nbr2'])){
        return ['message' => 'les deux nombres doivent être des nombres, or vous m´avez envoyé ' . $_GET['nbr1'] . ' et ' .  $_GET['nbr2']];
    }

    // test si la chaine est équvalente à un nombre. Renvoi true si c'est le cas, renvoi false si c'est le cas.
    // ! inverse la valeur du booleen. Quand c'est false -> cela devient true et entre dans le if.
    if(!is_numeric($_GET['nbr1'])){
        return ['message' => 'Le premier nombre doit être un nombre, or vous m´avez envoyé ' . $_GET['nbr1']];
    }

    if(!is_numeric($_GET['nbr2'])){
        return ['message' => 'Le premier nombre doit être un nombre, or vous m´avez envoyé ' . $_GET['nbr2']];
    }

    if($_GET['operation'] === 'div' && 0.0 === (float)$_GET['nbr2']){
        return ['message' => 'La division par 0 n´est pas possible'];
        // ne change pas le type de nbr1, le change juste pour l'affichage
        //return (float)$_GET['nbr1'];
    }

    if($_GET['operation'] === 'mod' && 0.0 === (float)$_GET['nbr2']){
        return ['message' => 'Le calcul du reste de la division par 0 n´est pas possible'];
    }

    // quand il n'y a pas d'erreurs :
    return $_GET;
}

function getResultMessage($operation, $nbr1, $nbr2){
    switch($operation){
        case 'add': return [
            'message'=>'L`addition de '.$nbr1.' et de '.$nbr2.' vaut '.($nbr1+$nbr2)
        ];

        case 'sub': return [
            'message'=>'La soustraction de '.$nbr1.' et de '.$nbr2.' vaut '.($nbr1-$nbr2)
        ];

        case 'mul': return [
            'message'=>'La multiplication de '.$nbr1.' et de '.$nbr2.' vaut '.($nbr1*$nbr2)
        ];

        case 'div': return [
            'message'=>'La division de '.$nbr1.' et de '.$nbr2.' vaut '.($nbr1/$nbr2)
        ];

        case 'mod': return [
            'message'=>'Le modulo de '.$nbr1.' et de '.$nbr2.' vaut '.($nbr1%$nbr2)
        ];

        case 'pow': return [
            'message'=>'La puissance de '.$nbr1.' et de '.$nbr2.' vaut '.($nbr1**$nbr2)
        ];
    };
}

// structure de contrôle
// vérifier que les variables sont bien définies.
if (isset( $_GET['nbr1'], $_GET['nbr2'], $_GET['operation'])) {
       $nbr1 = $_GET['nbr1'];
        $nbr2 =  $_GET['nbr2'];
        $operation =  $_GET['operation'];

    // reçoit soit un tableau dont la clé vaut message, soit un tableau avec 3 valeurs
    $data = validated();
    // sert à extraire les données pour écrire les varaible "$nbr2" sinon on écrit : $data['nbr2']
    /*  l'alternative :
        $nbr1 = $data['nbr1']
        $nbr2 = $data['nbr2']
        $operation = $data['operation']
    */
    extract($data);
    if(!array_key_exists('message', $data)){
        $result = getResultMessage($operation,$nbr1, $nbr2);
    }
    else{
        $error = $data;
    }
};
?>

<!-- VUE -->
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet"
          href="./assets/main.css">
    <title><?= SITE_TITLE; ?></title>
</head>
<body>
<h1>
    <?= $message = error;
    $message ; ?>
</h1>

<?php // si il y a un résultat?>

<?php if (isset ($result)): ?>
    <section class="result">
        <h2>Résultat de votre calcul</h2>
        <p><?= $result['message']?></p>
    </section>
<?php elseif(isset($error)): ?>
    <section class="error">
        <h2>Il y a un problème avec vos données</h2>
        <p> <?= $error['message']?></p>
    </section>
<?php endif; ?>

<form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>">
    <fieldset>
        <legend> Entrez vos nombres</legend>
        <div>
            <label for="nbr1">Entrez un nombre</label>
            <input type="text" name="nbr1" value="<?= $nbr1 ?>" placeholder="4 ou 4.3 par exemple">
        </div>
        <div>
            <label for="nbr1">Entrez un nombre</label>
            <input type="text" name="nbr2" value="<?= $nbr2 ?>" placeholder="4 ou 4.3 par exemple">
        </div>
    </fieldset>
    <fieldset>
        <legend> Choisissez une opération </legend>
        <?php foreach(OPERATIONS as $operation_name => $operation_symbol): // récupère la CLE et la VALEUR ?>
            <button
                <?php if($operation === $operation_name): ?>
                    style="color:red"
                <?php endif; ?>
                type="submit"
                name="operation"
                value="<?= $operation_name ?>"> <?= $operation_symbol ?>
            </button>
        <?php endforeach; ?>

    </fieldset>
</form>
</body>
</html>

