<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Garage</title>
    </head>

	<body>
		<h1>Stocks</br></h1>
		<a href="index.php">Retour</a><br><br>

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

		<div id ="stock">
			<h2>Stock</h2>
			<table>
				<tr>
					<th>catégorie</th>
					<th>sous-catégorie</th>
					<th>pièce</th>
					<th>marque</th>
					<th>référence</th>
					<th>état</th>
					<th>quantité</th>
				</tr>
				<?php
					$reponse = $bdd->query('SELECT * FROM categorie');
					while ($donnees = $reponse->fetch())
					{
						$categorie = $donnees['id'];
						$nomcat = $donnees['categorie'];
					}

					$reponse1 = $bdd->query("SELECT * FROM sous_categorie where categorie='". $categorie . "'");
					while ($donnees1 = $reponse1->fetch())
					{
						$souscat = $donnees1['nom']; 
					}
					
					$reponse2 = $bdd->query('SELECT * FROM liste_pieces');
					while ($donnees2 = $reponse2->fetch())
					{
						$nomp = $donnees2['nom'];
					}

					$reponse3 = $bdd->query("SELECT * FROM marque");
					while ($donnees3 = $reponse3->fetch())
					{
						$mrq = $donnees3['nom']; 
					}

					$reponse4 = $bdd->query("SELECT * FROM stock ");
					while ($donnees4 = $reponse4->fetch())
					{
						?>
						<tr>
							<td><?php echo $nomcat; ?></td> <!-- categ-->
							<td><?php echo $souscat;?></td> <!-- sous categorie-->
							<td><?php echo $nomp;?></td><!--piece-->
							<td><?php echo $mrq;?></td><!--marque-->
							<td><?php echo $donnees4['reference'];?></td><!--ref-->
							<td><?php echo $donnees4['usure'];?></td><!-- usure-->
							<td><?php echo $donnees4['quantite'];?></td><!--qtt-->
						</tr>
						<?php
					}
				?> 
			</table>
		</div>
	</body>
</html>