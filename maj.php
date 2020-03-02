<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Garage</title>
    </head>

	<body>
		<h1>Mise à jour des stocks</br></h1>
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

		<div id="ajout">
			<h2>Ajout stock</h2>
			<?php if(isset($_POST['verif1']))
			{
				$reponse=$bdd->query("SELECT COUNT(*) as nb FROM stock WHERE reference='" . $_POST['ref'] . "' and usure='" . $_POST['usure'] . "'");
				while ($donnees = $reponse->fetch())
					{
						if($donnees['nb']==0)
						{
							$req = $bdd->prepare('INSERT INTO stock values(:qtt, :ref, :usure)');
							$req->execute(array(
							'qtt'=>$_POST['qtt'],
							'ref'=>$_POST['ref'],
							'usure'=>$_POST['usure']
							));
						}
						else
						{
							$reponse1=$bdd->query("SELECT * FROM stock WHERE reference='" . $_POST['ref'] . "' and usure='" . $_POST['usure'] . "'");
							while ($donnees1 = $reponse1->fetch())
							{
								$_POST['qtt'] = $donnees1['quantite'] + $_POST['qtt'];
								$req = $bdd->query("UPDATE stock set quantite=".$_POST['qtt']." where reference=".$_POST['ref']." and usure='".$_POST['usure']."'");
							}
						}
					}
					$req = $bdd->prepare("INSERT INTO historique values(:ref, date(now()), NULL, :usure)");
					$req->execute(array(
					'ref'=>$_POST['ref'],
					'usure'=>$_POST['usure']
					));
			}

			if(isset($_POST['verif5']))
			{
				$reponse=$bdd->query("SELECT quantite as nb FROM stock WHERE reference='" . $_POST['ref'] . "' and usure='" . $_POST['usure'] . "'");
				while ($donnees = $reponse->fetch())
				{
					if($donnees['nb']!=0)
					{
						$qtt = $donnees['nb'] - $_POST['qtt'];
						if($qtt>=0)
						{
							$req = $bdd->query("UPDATE stock set quantite=" . $qtt . " where reference=" . $_POST['ref'] . " and usure='" . $_POST['usure'] . "'");
						}							
					}
				}
				$req = $bdd->prepare("INSERT INTO historique values(:ref, NULL, date(now()), :usure)");
				$req->execute(array(
				'ref'=>$_POST['ref'],
				'usure'=>$_POST['usure']
				));
			}?>
			<form action="maj.php" method="post">
				<select name='cat'>
				<?php
					$reponse = $bdd->query('SELECT * FROM categorie');

					while ($donnees = $reponse->fetch())
					{
						$reponse1 = $bdd->query("SELECT * FROM sous_categorie where categorie ='". $donnees['id'] ."'");

						while ($donnees1 = $reponse1->fetch())
						{
							echo "<option value='".$donnees1['id']."'>". $donnees['categorie']. "/" . $donnees1['nom'] . "</option>";
						}
					}
				?>
				</select>
			<input type="hidden" name="verif">
			<input type="submit" name="Valider">
			</form>
			
			<?php 
			if(isset($_POST['verif']))
			{
				$reponse = $bdd->query("SELECT * FROM marque");
				$reponse1 = $bdd->query("SELECT * FROM liste_pieces where categorie='" . $_POST['cat'] . "'"); 
				?>
					<form action='maj.php' method='post'>
						<label>Quelle est la référence de la pièce?</label>
						<input type='text' name='ref'>
						<input type='hidden' name='verif2'>
						<input type='submit' name='Rechercher'>
					</form>

					<form action='maj.php' method='post'>
						<label>Quelle est la marque?</label>
						<select name='marque'>
							<?php 
							while ($donnees = $reponse->fetch())
							{
								echo "<option value='". $donnees['id'] ."'>". $donnees['nom'] . "</option>";
							}
							?>
						</select>
						<label>Quelle est la pièce?</label>
						<select name='pieces'>
							<?php 
							while ($donnees = $reponse1->fetch())
							{
								echo "<option value='". $donnees['id'] ."'>". $donnees['nom'] . "</option>";
							}
							?>
						</select>
						<input type='hidden' name='verif3'>
						<input type='submit' name='Rechercher'>
					</form>
				<?php
			}

			if(isset($_POST['verif2']) or isset($_POST['verif3']))
			{
				$ref='0';
				if(isset($_POST['verif3']))
				{
					$req = $bdd->query("SELECT * FROM pieces WHERE marque='". $_POST['marque'] . "' and piece='". $_POST['pieces'] ."'");
					while ($donnees = $req->fetch())
					{
						echo "La référence de la pièce est :". $donnees['reference'];
						$ref=$donnees['reference'];
					}
				}

				if(isset($_POST['verif2']))
				{
					$ref=$_POST['ref'];
				}

				if($ref!=='0')
				{
					$reponse=$bdd->query("SELECT sum(quantite) as nb FROM stock WHERE reference='" . $ref . "'");
					while ($donnees = $reponse->fetch())
					{
						if($donnees['nb']==0)
						{
							echo "<br>Il n'y a pas encore de pièce identique en stock.<br>"; 
						}
						else
						{
							echo "<br>Il y a déjà " . $donnees['nb'] . " pièces dans cet état en stock.<br>";
						}	

						echo "		
						<table>
							<tr>
								<th>état d'usure</th>
								<th>quantité</th>
							</tr>
							<form action='maj.php' method='post'>
								<td>
									<select name='usure'>
										<option>neuf</option>
										<option>bon</option>
										<option>moyen</option>
										<option>mauvais</option>
										<option>inutilisable</option>
									</select>
								</td>
								<td><input type='number' min='1' max='100000' name='qtt'></td>
								<td><input type='submit' value='Ajouter'></td>
								<input type='hidden' name='verif1'>
								<input type='hidden' name='ref' value='".$ref."'>
							</form>
						</table>
						<table>
							<tr>
								<th>état d'usure</th>
								<th>quantité</th>
							</tr>
							<form action='maj.php' method='post'>
								<td>
									<select name='usure'>
										<option>neuf</option>
										<option>bon</option>
										<option>moyen</option>
										<option>mauvais</option>
										<option>inutilisable</option>
									</select>
								</td>
								<td><input type='number' min='1' max='100000' name='qtt'></td>
								<td><input type='submit' value='Supprimer'></td>
								<input type='hidden' name='verif5'>
								<input type='hidden' name='ref' value='".$ref."'>
							</form>
						</table>";
					}
				}
			}?>
		</div>
	</body>
</html>