<div class="summary">
	<div class="intro hidden-sm-down">
		<p><?=get_option('klantenvertellen_intro', 'Intro')?></p>
		<a href="<?=get_option('klantenvertellen_review_url')?>" target="_blank">Schrijf een review</a>
	</div>
	<div class="details">
		<div class="grade">
			<?=$averageTotalGrade?>
		</div>
		<div class="description">
			<ul>
				<li><b>Klantvriendelijkheid:</b> <?=$averageServiceGrade?></li>
				<li><b>Deskundigheid:</b> <?=$averageKnowledgeGrade?></li>
				<li><b>Prijs-kwaliteit:</b> <?=$averagePriceQualityGrade?></li>
			</ul>
			<ul>
				<li><b><?=$recommendedPercentage?> beveelt ons aan</b></li>
			</ul>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
	<hr class="hidden-sm-up">
	<div class="intro hidden-sm-up">
		<p><?=get_option('klantenvertellen_intro', 'Intro')?></p>
		<a href="<?=get_option('klantenvertellen_review_url')?>" target="_blank">Schrijf een review</a>
	</div>
</div>

<?php foreach ($reviews as $review): ?>
	<div class="review">
		<div class="average">
			<span><?= $review['gemiddelde'] ?></span>
		</div>
		<div class="stars">
			<span><?= $review['starHtml'] ?></span>
		</div>
		<hr class="hidden-sm-up">
		<div class="content">
			<div class="name">
				<b><span itemprop="author"><?= $review['voornaam'] ?> <?= $review['achternaam'] ?></span> <?= $review['woonplaats'] ?></b>
			</div>
			<div class="reason">
				Reden: <?= $review['redenbezoek'] ?>
			</div>
			<div class="description">
				<?= $review['beschrijving'] ?>
			</div>
			<div class="clearfix"></div>
			<div class="grades">
				<table>
					<tbody>
						<tr>
							<td>Service</td>
							<td><?= $review['service'] ?></td>
						</tr>
						<tr>
							<td>Deskundigheid</td>
							<td><?= $review['deskundigheid'] ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="grades">
				<table>
					<tbody>
						<tr>
							<td>Prijs / Kwaliteit</td>
							<td><?= $review['prijskwaliteit'] ?></td>
						</tr>
						<tr>
							<td>Totaal</td>
							<td><?= $review['gemiddelde'] ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="grades">
				<table>
					<tbody>
						<tr>
							<td>Aanbeveling?</td>
							<td><?= $review['aanbeveling'] ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
<?php endforeach; ?>

<div class="center">
	<div class="pagination">
		<a href="?pag=1">Eerste</a>
		<a href="?pag=<?=$previousPage?>">&laquo;</a>

		<?php
		for ($i = (int) (($currentPage - $paginationOffset <= 0) ? 1 : ($currentPage - $paginationOffset)); $i <= (int) (($currentPage + $paginationOffset >= $maxPage) ? $maxPage : ($currentPage + $paginationOffset)); $i++) {
			$class = ((int) $currentPage === $i) ? 'class="active"' : '';
		?>
			<a <?=$class?> href="?pag=<?=$i?>"><?=$i?></a>
		<?php } ?>

		<a href="?pag=<?=$nextPage?>">&raquo;</a>
		<a href="?pag=<?=$maxPage?>">Laatste</a>
	</div>
</div>
<div class="center">
	<p><?=$currentPage?> van de <?=$maxPage?> pagina's (<?=$reviewCount?> reviews)</p>
</div>
