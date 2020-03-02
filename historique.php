<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Garage</title>
    </head>

	<body>
		<h1>Historique</br></h1>
		<a href="index.php">Retour</a><br><br>
		<div id = "historique">

			<?php
			try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=garage;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			}
			catch (Exception $e)
			{
			    die('Erreur : ' . $e->getMessage());
			}
			?>

			<table>
				<tr>
					<th>réfèrence pièce</th>
					<th>usure</th>
					<th>date d'entrée</th>
					<th>date de sortie</th>
				</tr>
				<tr>
					<td>
					<?php
						$reponse = $bdd->query('SELECT * FROM historique');

						while ($donnees = $reponse->fetch())
						{
							echo $donnees['ref_piece'] . '<br>';
						}
					?>
					</td>

					<td>
					<?php
						$reponse = $bdd->query('SELECT * FROM historique');

						while ($donnees = $reponse->fetch())
						{
							echo $donnees['usure'] . '<br>';
						}
					?>
					</td>

					<td>
					<?php
						$reponse = $bdd->query('SELECT * FROM historique');

						while ($donnees = $reponse->fetch())
						{
							echo $donnees['date_entree'] . '<br>';
						}
					?>
					</td>

					<td>
					<?php
						$reponse = $bdd->query('SELECT * FROM historique');

						while ($donnees = $reponse->fetch())
						{
							echo $donnees['date_sortie'] . '<br>';
						}
					?>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>