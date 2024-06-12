<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>
<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
	<ul class="pagination pagination-rounded justify-content-center">
		<?php foreach ($pager->links() as $link) : ?>
			<li <?= $link['active'] ? 'class="active page-item"' : 'class="page-item"' ?>>
				<a class="page-link" href="?<?= explode("?", $link['uri'])[1] ?>">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>
	</ul>
</nav>