<h2><?=html_encode($title);?></h2>
<?php if (!empty($pages)) {?>
    <ul class="links two-columns">
        <?php foreach ($pages as $page) {
            $url = 'pages/index';
            if($page['page_id'] == 'contact-form'){ ?>
                <li><a href="<?= $page['slug']; ?>"><?=html_encode($page['title']);?></a></li>
            <?php } else { ?>
                <li><a href="<?= url([$url, 'slug' => $page['slug']]); ?>"><?=html_encode($page['title']);?></a></li>
            <?php } ?>
        <?php } ?>
    </ul>
<?php } ?>
