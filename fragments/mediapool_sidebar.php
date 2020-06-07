<?php
$collapse_id = 'collapse-'.random_int(100000, 999999);

$attributes = [];
$attributes['class'][] = 'panel-heading';
if ($this->collapsed) {
	$attributes['class'][] = 'collapsed';
}
$attributes['data-toggle'] = 'collapse';
$attributes['data-target'] = '#'.$collapse_id;
?>

<br />
<div id="rex-js-main-sidebar">
	<section class="rex-page-section">
		<div class="panel panel-default">
			<header <?= rex_string::buildAttributes($attributes); ?>><div class="panel-title"><i class="rex-icon rex-icon-info"></i> <?= $this->title; ?></div></header>
			<div id="<?= $collapse_id ?>" class="panel-collapse collapse<?= $this->collapsed ? '' : ' in' ?>">
				<div class="panel-body">
					<?= $this->lines; ?>
				</div>
			</div>
		</div>
	</section>
</div>
