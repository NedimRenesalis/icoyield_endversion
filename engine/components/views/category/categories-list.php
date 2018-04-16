<h2><?=html_encode($title);?></h2>
<?php if (!empty($categories)) {?>
    <ul class="links two-columns">
        <?php foreach ($categories as $cat) { ?>
            <li><a href="<?= url(['category/index', 'slug' => $cat->slug]); ?>"><?=html_encode($cat->name);?></a></li>
        <?php } ?>
    </ul>
<?php } ?>