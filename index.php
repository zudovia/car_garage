<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Garage</title>
    </head>

	<body>
		<h1>Garage</br></h1>

		<div id="Recherche">
			<h2>Recherche</h2>
			<form action="index.php" method="post">
				<label>Quelle est la réfèrence de la pièce?</label>
				<input type="text" name="ref">
				<input type="hidden" name="rech" value="1">
				<input type="submit" name="Rechercher">
			</form>


			<?php
			try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=garage;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			}
			catch (Exception $e)
			{
			    die('Erreur : ' . $e->getMessage());
			}

			if(isset($_POST['rech']) and $_POST['rech'] == 1)
			{
				$requete = "SELECT * FROM pieces WHERE reference = '" . strval($_POST['ref']) . "'";
				$reponse = $bdd->query($requete);

				while ($donnees = $reponse->fetch())
				{
					$piece = $donnees['piece'];
					$idmarque = $donnees['marque'];
					$ref = $donnees['reference'];
				}

				$requete = "SELECT * FROM liste_pieces WHERE id= '" . $piece . "'";
				$reponse = $bdd->query($requete);

				while ($donnees = $reponse->fetch())
				{
					$nom = $donnees['nom'];
				}

				$requete = "SELECT * FROM marque WHERE id = '" . $idmarque . "'";
				$reponse = $bdd->query($requete);

				while ($donnees = $reponse->fetch())
				{
					$marque = $donnees['nom'];
				}

				$requete = "SELECT * FROM stock WHERE reference = '" . strval($_POST['ref']) . "'";
				$reponse = $bdd->query($requete);

				while ($donnees = $reponse->fetch())
				{
					echo $nom . ', ' . $marque . ': '. $ref . '<br>Quantité: ' . $donnees['quantite'] . '<br>';
				}
			}
			?>
		</div> 

		<br>

		<a href="historique.php">Accèder à l'historique</a><br>
		<a href="stock.php">Consulter les stocks</a><br>
		<a href="maj.php">Mettre à jour les stocks</a><br>

	</body>
</html>