<div class="summary">
	<div class="grade">
		8,0
	</div>
	<div class="description">
	</div>
	<div class="clearfix"></div>
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
