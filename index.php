<?php
    if(isset($_GET['q'])){
	$shortcut = htmlspecialchars($_GET['q']);
	$bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8', 'root', '');
	$req =$bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
	$req->execute(array($shortcut));

	while($result = $req->fetch()){

		if($result['x'] != 1){
			header('location: ../?error=true&message=Adresse url non connue');
			exit();
		}

	}
	$req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
	$req->execute(array($shortcut));

	while($result = $req->fetch()){

		header('location: '. $result['url']);
		exit();

	}

}

    if(isset($_POST['url']))
    {
        $url=$_POST['url'];
        if(!filter_var($url, FILTER_VALIDATE_URL))
        {
            header('location: ?error=true&message=Adresse url non valide');
            exit();
        }
        $shortcut = crypt($url, rand());
        //$shortcut = crypt($url, time());

        $bdd =new PDO('mysql:host=localhost;dbname=bitly;charset=utf8' , 'root' , '');
        $req = $bdd->prepare('SELECT COUNT(*) AX x FROM links WHERE url=?');
        $req->execute(array($url));
        while($result = $req -> fetch())
        {
            if($result['x'] != 0)
            {
                header('location: ?error=true&message=Adresse url deja racourcie');
                exit;
            }
        }
        $req = $bdd-> prepare('INSERT INTO links(url,shortcut) VALUES(?,?)');
        $req->execute(array($url,$shortcut));
        header('location: ?short='. $shortcut);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Racourcie d'url express</title>
    <link rel="stylesheet" href="designe/default.css">
    <link rel="icon" type="image/png" href="pictures/favico.png">
</head>
<body>
    <section id="debut">
        <div class="container">
            <header>
                <img src="pictures/logo.png" id="logo" alt="logo">
                <h1>
                    Une URL longe ? Racourssez-là ?
                </h1>
                <h2>
                    Largement meilleur et plus court que les autres.
                </h2>
                <form action="index.php" method="POST">
                    <input type="url" name="url">
                    <input type="submit" value="Racourcier">
                </form>
                <?php
                    if(isset($_GET['error']) && isset($_GET['message']))
                    {?>
                        <div class="center">
                            <div id="result">
                                <b>
                                    <?php
                                        echo htmlspecialchars($_GET['message']);
                                    ?>
                                </b>
                            </div>
                        </div>
                    <?php }
                    else if(isset($_GET['short']))
                    {
                        ?>
                            <div class="center">
                            <div id="result">
                                <b>
                                    URL RACOURCIE :
                                </b>
                                <b>
                                    http://localhost:8080/PROJECT1/?q=<?php echo htmlspecialchars($_GET['short']); ?>
                                </b>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </header>
        </div>
    </section>
    <section id="brands">
        <div class="container">
            <h3>
                Ces marques nous font confiance
                <div id="container">
                    <img src="pictures/1.png" class="picture">
                    <img src="pictures/2.png" class="picture">
                    <img src="pictures/3.png" class="picture">
                    <img src="pictures/4.png" class="picture">
                </div>
            </h3>
        </div>
    </section>
    <footer>
        <img src="pictures/logo2.png" alt="logo" id="logo">
        <br>
        2022 © Bitly
        <br>
        <a href="#">Contact</a> - <a href="#">A propo</a>
    </footer>
</body>
</html>