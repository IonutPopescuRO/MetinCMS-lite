<?php
	include 'config/site.php';
	include 'config/db.php';
?>
<!doctype html>
<html>
	
<head>
		<meta charset="utf-8" />
		<title><?php print $name; ?></title>
		
		<meta name="author" content="Metin2 CMS">
		<meta name="viewport" content="width=device-width, initial-scale=1,  maximum-scale=1, user-scalable=no">
		
		<link rel="shortcut icon" href="img/favicon.png" type="image/png" />
		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="css/perfect-scrollbar.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.fullpage.css" />
		<link rel="stylesheet" type="text/css" href="css/lightbox.min.css" />
		
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.slimscroll.js"></script>
		<script src="js/perfect-scrollbar.jquery.js"></script>
		<script src="js/perfect-scrollbar.js"></script>
		<script src="js/jquery.fullpage.js"></script>
		<script src="js/main.js"></script>
	</head>
	<body>
		<nav id="mainnav">
			<ul>
				<li><a class="first" href="#intro">Intro</a></li>
				<li><a href="#registration">Înregistrare</a></li>
				<li><a href="#download">Download</a></li>
				<li><a href="#ranking">Clasament</a></li>
				<li><a href="#presentation">Prezentare</a></li>
				<?php if($forum) { ?>
				<li><a href="#">Forum</a></li>
				<?php } ?>
			</ul>
		</nav>
		<nav id="footnav">
			<ul>
				<li><a class="first" target="_blank" href="http://metin2cms.cf/">Metin2CMS</a></li>
			</ul>
		</nav>
		<div id="social">
			<?php
				if($facebook_page)
					print "<a href='$facebook_page' class='scircle'><span class='fa fa-facebook'></span></a>";
				if($youtube_canal)
					print "<a href='$youtube_canal' class='scircle'><span class='fa fa-youtube-play'></span></a>";
				if($twitter_page)
					print "<a href='$twitter_page' class='scircle'><span class='fa fa-twitter'></span></a>";
			?>
		</div>
		<div id="getContent"></div>
		
		<div id="houses"></div>
		<div id="border"></div>
		<?php if($online_players) {
				print '<div id="sec_down">';
					$result = $player->prepare("SELECT count(*) FROM player WHERE DATE_SUB(NOW(), INTERVAL $update_players MINUTE) < last_play"); 
					$result->execute(); 
					$number_of_rows = $result->fetchColumn(); 
					print $number_of_rows.' jucători online';
				print '</div>';
			}
		?>
		<main>
			<div id="fullpage">
			
				<div class="section" id="section0">
					<div class="content start">
						<div class="title">
							<?php print $name; ?>
						</div>
						<hr />
						<div class="subline">
							Joacă acum, gratuit!
						</div>
						
						<a href="#registration" class="btn">
							Înregistrează-te!
						</a>
					</div>
				</div>
				
				<div class="section" id="section1">
					<div class="content">
						<h2>Înregistrare</h2>
						<?php
							if($register) {
								if (isset($_POST['username'])) {
									$check_login = $account->prepare("SELECT count(*) FROM account WHERE login = '".$_POST['username']."'"); 
									$check_login->execute(); 
									$check_login = $check_login->fetchColumn();
									
									$check_email = $account->prepare("SELECT count(*) FROM account WHERE email = '".$_POST['email']."'"); 
									$check_email->execute(); 
									$check_email = $check_email->fetchColumn(); 
									
									if($check_login>0)
										print '<div class="headline">
													<center><font color="red">Nume de utilizator indisponibil.</font></center>
												</div>';
									else if($check_email>0)
										print '<div class="headline">
													<center><font color="red">Acest e-mail este folosit deja de un</br></br> alt cont.</font></center>
												</div>';
									else {
										if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
											if($_POST['pw'] == $_POST['repeat_pw']) {
												
												$hash = "*" . sha1(sha1($_POST['pw'], true));
												$password = strtoupper($hash);
												
												if($bonus)
													$expire = "20221218131717"; //Data expirarii sanselor (2022-12-18 13:17:17)
												else
													$expire = "0";
												$sql = "INSERT INTO account(login,
															password,
															social_id,
															email,
															create_time,
															status,
															gold_expire, 
															silver_expire, 
															safebox_expire, 
															autoloot_expire, 
															fish_mind_expire, 
															marriage_fast_expire, 
															money_drop_rate_expire) VALUES (
															:login,
															:password,
															:social_id,
															:email,
															NOW(),
															:status,
															:gold_expire, 
															:silver_expire, 
															:safebox_expire, 
															:autoloot_expire, 
															:fish_mind_expire, 
															:marriage_fast_expire, 
															:money_drop_rate_expire)";
																						  
												$stmt = $account->prepare($sql);
																							  
												$stmt->bindParam(':login', $_POST['username'], PDO::PARAM_STR);       
												$stmt->bindParam(':password', $password, PDO::PARAM_STR);       
												$stmt->bindParam(':social_id', $_POST['delcode'], PDO::PARAM_STR);       
												$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);       
												$stmt->bindParam(':status', $status_register, PDO::PARAM_STR);       
												
												$stmt->bindParam(':gold_expire', $expire, PDO::PARAM_STR); 
												$stmt->bindParam(':silver_expire', $expire, PDO::PARAM_STR); 
												$stmt->bindParam(':safebox_expire', $expire, PDO::PARAM_STR); 
												$stmt->bindParam(':autoloot_expire', $expire, PDO::PARAM_STR); 
												$stmt->bindParam(':fish_mind_expire', $expire, PDO::PARAM_STR); 
												$stmt->bindParam(':marriage_fast_expire', $expire, PDO::PARAM_STR); 
												$stmt->bindParam(':money_drop_rate_expire', $expire, PDO::PARAM_STR); 

												$stmt->execute();
												print '<div class="headline">
															<center><font color="green">Contul tău a fost creat cu succes! </br></br>Te poți loga în joc.</font></center>
														</div>';
											}
											else
												print '<div class="headline">
															<center><font color="red">Parolele nu se potrivesc.</font></center>
														</div>';
										}
										else
											print '<div class="headline">
														<center><font color="red">Adresa de e-mail este invalidă.</font></center>
													</div>';
									}
								}
						?>
						<form action="#registration" method="post">
							<input type="text" pattern=".{5,16}" maxlength="16" required="required" name="username" placeholder="Nume utilizator" />
							<div class="reg_info">
								Între 5 și 16 caractere. Trebuie să conțină litere și numere.
							</div>
							<input type="email" required="required" name="email" placeholder="E-mail" />
							<div class="reg_info">
								Trebuie să fie o adresă de e-mail validă.
							</div>
							<input id="reg_pw" pattern=".{5,}" type="password" required="required" name="pw" placeholder="Parolă" />
							<div class="reg_info">
								Cel puțin 5 caractere. Cel mai bun caz, cu litere mari și mici, numere și caractere speciale.
							</div>
							<input id="reg_pw2" pattern=".{5,}" type="password" required="required" name="repeat_pw" placeholder="Repetă parola" />
							<div class="reg_info">
								Scrie din nou parola de mai sus.
							</div>
							<input type="number" min="1000000" max="9999999" required="required" name="delcode" placeholder="Cod ștergere" id="delcodeInput"/>
							<div class="reg_info">
								Trebuie de 7 cifre. Litere și caractere speciale nu sunt permise.
							</div>
							<button class="btn" type="submit" name="submit">Înregistrare!</button>
						</form>
						<?php
							}
							else
								print '<div class="headline">
											<center><font color="red">Înregistrările sunt momentan</br></br> dezactivate.</font></center>
										</div>';
						?>
					</div>
				</div>

				
				<div class="section" id="section2">
					<div class="content">
						<h2>Descărcare</h2>
						<?php if($download_browser)
								print "<a href='$download_browser' class='btn'>
									Browser
								</a>";
							  if($download_torrent)
								print "<a href='$download_torrent' class='btn'>
									Torrent
								</a>";
						?>						
					</div>
				</div>
				
				<div class="section" id="section3">
					<div class="content">
						<h2>Clasament</h2>
						
						<table class="table table-inverse">
						  <thead>
							<tr>
							  <th>#</th>
							  <th>Nume</th>
							  <th>Regat</th>
							  <th>Breaslă</th>
							  <th>Nivel</th>
							  <th>EXP</th>
							</tr>
						  </thead>
						  <tbody>
							<?php
								$stmt  = $player->query("SELECT COUNT(*) as rows FROM player")->fetch(PDO::FETCH_OBJ);
								$total  = $stmt->rows;
								$pages  = ceil($total / $players_rank_on_page);
								
								$get_pages = isset($_GET['page']) ? $_GET['page'] : 1;
								if($get_pages>$pages)
									$get_pages = 1;
								$x = ($get_pages-1)*$players_rank_on_page;

								$stmt = $player->query("SELECT * FROM player WHERE name NOT LIKE '[%]%' order by level desc,exp desc limit $x ,$players_rank_on_page"); 

								$rank = $x;
								
									while($user = $stmt->fetchObject()) {
										$rank++;
										
										$empire = $player->query("SELECT empire FROM player_index WHERE id = $user->account_id");
										$empire = $empire->fetch(PDO::FETCH_ASSOC);
										$empire = $empire['empire'];
										
										$guild_id = $player->query("SELECT guild_id FROM guild_member WHERE pid = $user->id");
										$guild_id = $guild_id->fetch(PDO::FETCH_ASSOC);
										$guild_id = $guild_id['guild_id'];

										if($guild_id)
										{
											$guild = $player->query("SELECT name FROM guild WHERE id = $guild_id");
											$guild = $guild->fetch(PDO::FETCH_ASSOC);
											$guild = $guild['name'];
										}
										else
											$guild = '-';
										
										print "<tr>
													<th scope='row'>$rank</th>
													<td>$user->name</td>
													<td><img src='img/empire/$empire.jpg'></td>
													<td>$guild</td>
													<td>$user->level</td>
													<td>$user->exp</td>
												</tr>";
									};
							?>
						  </tbody>
						</table>
						<?php
							if($get_pages>1)
								echo '<a href="?page='.($get_pages-1).'#ranking" class="btn pull-left"> << </a>';
							
							if($get_pages<$pages)
								echo '<a href="?page='.($get_pages+1).'#ranking" class="btn pull-right"> >> </a>';
						?>
					</div>
				</div>
				
				<div class="section" id="section4">
					<div class="content">
						<div class="slide">
							<h2>Scopul jocului</h2>
							<div class="cols">
								<div class="text_col">
									In vremuri străvechi răsuflarea Zeului Dragon veghea asupra regatelor Shinsoo, Chunjo şi Jinno. Dar aceasta lume fascinanta a magiei se afla în fata unui pericol imens: Impactul Pietrelor Metin care au cauzat haos şi distrugere pe continent şi intre locuitori. Au izbucnit războaie intre continente, animalele sălbatice s-au transformat în bestii terifiante. Lupta împotriva influentei negative a Pietrelor Metin în postura unui aliat al Zeului Dragon. Aduna-ţi toate puterile şi armele pentru a salva regatul.
								</div>
								<div class="img_col">
									<div class="imgborder">
										<a href="img/G_15_metin2stadt.jpg" data-lightbox="wallpaper" data-title="Click the right half of the image to move forward."><img src="img/G_15_metin2stadt.jpg" alt="Lorem Ipsum dolor sit amet"></a>
									</div>
									<div class="imgborder">
										<a href="img/G_19_metin2_buch_cover.jpg" data-lightbox="wallpaper" data-title="Click the right half of the image to move forward."><img src="img/G_19_metin2_buch_cover.jpg" alt="Lorem Ipsum dolor sit amet"></a>
									</div>
									<div class="imgborder">
										<a href="img/G_21_metin2_lion.jpg" data-lightbox="wallpaper" data-title="Click the right half of the image to move forward."><img src="img/G_21_metin2_lion.jpg" alt="Lorem Ipsum dolor sit amet"></a>
									</div>
									<div class="imgborder">
										<a href="img/G_26_Metin2_motiv.jpg" data-lightbox="wallpaper" data-title="Click the right half of the image to move forward."><img src="img/G_26_Metin2_motiv.jpg" alt="Lorem Ipsum dolor sit amet"></a>
									</div>
								</div>
							</div>
						</div>
						<?php
							if($youtube_presentation) {
						?>
						<div class="slide">
							<h2>Prezentare server</h2>
							<center><iframe width="640" height="395" src="https://www.youtube.com/embed/<?php print $youtube_presentation;?>" frameborder="0" allowfullscreen></iframe></center>
						</div>
						<?php
							}
							if($staff_list) {
						?>
						<div class="slide">
							<h2>Echipa serverului</h2>

							<table class="table table-inverse">
							  <thead>
								<tr>
								  <th>#</th>
								  <th>Nume</th>
								  <th>Regat</th>
								  <th>Breaslă</th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								$stmt = $player->query("SELECT * FROM player WHERE name LIKE '[%]%'"); 

									$rank = 0;
									
										while($user = $stmt->fetchObject()) {
											$rank++;
											
											$empire = $player->query("SELECT empire FROM player_index WHERE id = $user->account_id");
											$empire = $empire->fetch(PDO::FETCH_ASSOC);
											$empire = $empire['empire'];
											
											$guild_id = $player->query("SELECT guild_id FROM guild_member WHERE pid = $user->id");
											$guild_id = $guild_id->fetch(PDO::FETCH_ASSOC);
											$guild_id = $guild_id['guild_id'];

											if($guild_id)
											{
												$guild = $player->query("SELECT name FROM guild WHERE id = $guild_id");
												$guild = $guild->fetch(PDO::FETCH_ASSOC);
												$guild = $guild['name'];
											}
											else
												$guild = '-';
											
											print "<tr>
														<th scope='row'>$rank</th>
														<td>$user->name</td>
														<td><img src='img/empire/$empire.jpg'></td>
														<td>$guild</td>
													</tr>";
										};
								?>
							  </tbody>
							</table>
						</div>
						<?php
							}
						?>
					</div>
				</div>

			</div>
		</main>
		
		<script>
			delcodeInput.oninput = function () {
				if (this.value.length > 7)
					this.value = this.value.slice(0,7); 
			}
		</script>
		
		<script src="js/lightbox.js"></script>
	</body>

</html>