
<div style="display: table;">
	<dl class="dl-horizontal text-left">
		<?php
		foreach ($this->exif as $key => $value) {
			?>
			<dt><?= $value['key'] ?></dt>
			<dd><?= $value['value'] ?></dd>
			<?php
		}
		?>
	</dl>
</div>
