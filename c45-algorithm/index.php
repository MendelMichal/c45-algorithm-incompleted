<?php include 'partials/header.php'; ?>

<body>
	<div class="container">
		<div class="intro">
			<h1>Implementacja algorytmu C4.5</h1>
			<p class="lead">
				Autorem strony jest Michał Mendel. Implementacja przy użyciu
				PHP oraz JavaScript.
			</p>
		</div>

		<div id="step1-div">
			<section id="step1" class="container">
				<div class="col-md-12">
					<h3 class="text-info">Krok 1. Wgrywanie zestawu danych do uczenia</h3>
					<span class="control-fileupload"> <label for="file">Wybierz zestaw
							danych (.csv, .txt, .data)</label> <input type="file"
						id="dataset" />
					</span>
					<p id="result"></p>
				</div>
			</section>
		</div>

		<div id="rest">

			<div id="dataset-preview" class="container">
				<h4>Podgląd zbioru danych</h4>
				<div id="table-preview"></div>
			</div>

			<div id="step2-div">
				<section id="step2" class="container">
					<div class="col-md-12">
						<h3 class="text-info">Krok 2. Deklaracja atrybutów</h3>
						<p>Czy pierwszy wiersz (zaznaczony na szary kolor) zawiera nazwy
							atrybutów?</p>
						<form id="isFirstLaneAttribute">
							<ul>
								<li><input type="radio" id="radio-yes" name="firstLineAttribute"
									value="yes"> <label for="radio-yes">Tak</label>

									<div class="check"></div></li>

								<li><input type="radio" id="radio-no" name="firstLineAttribute"
									value="no"> <label for="radio-no">Nie</label>

									<div class="check">
										<div class="inside"></div>
									</div></li>

							</ul>
						</form>
					</div>
				</section>
			</div>

			<div id="dataset-final-preview" class="container">
				<h4>Finalny podgląd zbioru danych</h4>
				<div id="table-final-preview"></div>
			</div>

			<div id="step3-div">
				<section id="step3" class="container">
					<div class="col-md-12">
						<h3 class="text-info">Krok 3. Wskazanie atrybutu decyzyjnego</h3>
						<p>Który z atrybutów jest atrybutem decyzyjnym?</p>
						<form id="decisionAttribute"></form>
					</div>
				</section>
			</div>
			
			<div id="calculations-div">
				<section id="calculations" class="container">
					<div class="col-md-12">
						<h3 class="text-info margin-bottom-40">Wyniki wstępnego działania algorytmu</h3>
						<h4>Globalna entropia</h4>
						<div id="globalEntropy" class="margin-bottom-40">Globalna entropia dla owego zestawu danych wyniosła - <b><span id="gEntropy"></span></b></div>
						
						<h4 class="margin-bottom-40">Wyniki dla poszczególnych atrybutów</h4>
						<div id="calculationsTable"></div>
					</div>
				</section>
			</div>

		</div>
	</div>

	<div class="modal"></div>
</body>

<?php include 'partials/footer.php'; ?>