<section class="glossary-page bg-gray-5">
	
	<div class="container">
		
		<div class="section-header bg-aqua-5">
			<div class="bg overprint-absolute parallax" style="background-image:<?= empty($imgheader) ? 'none' : 'url('.ROOT.$imgheader.')' ?>;"></div>
			<div class="container">
				<h1><?=  $Glossary->data()->name ?></h1>
				<h4><a href="<?= View::url('etiquetas#grupo_'.$Glossary->data()->idgroup) ?>"><?= $Glossary->data()->groupname ?></a></h4>
			</div>
		</div>



		<div class="block-white">

			<div class="questions-wrapper">
				<div class="question-box">
					<div class="icon">
						<i class="fa fa-user"></i>
					</div>
					<div class="message">
						<p data-content="message" class="caption"><?=$_question->message?></p>
					</div>
		
				</div>

				<?php if($_question->responses): ?>
				<!-- RESPONSES -->				
				<h5 class="response-title text-gray-50">Respuestas:</h5>
				<?php foreach($_question->responses as $response): ?>
				<div class="question-box response">
					<div class="thumb thumb-cover" style="background-image:url(<?=$response->client->imagery->logo?>)"></div>
					<div class="message">
						<a href="<?=ROOT.'centros/'.$response->client->permalink ?>" target="_blank"><?=$response->client->name?></a>
						<p><?=$response->message?></p>
						<small>Envidada: <?=$response->creado?> hs.</small>
					</div>
				</div>
				<?php endforeach; endif; ?>
			</div>

		</div>

	</div>

</section>