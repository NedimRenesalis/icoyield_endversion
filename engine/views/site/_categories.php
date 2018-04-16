<div class="search-categories-click">
    <div class="container">
        <?php $categoriesCount = count($categories);
        foreach ($categories as $key => $cat) {
            if (!($key % 4)) {
                $childCats = [];
                $catArray = []; ?>
            <div class="row">
            <?php } if (!empty($cat->children)) {
                    $childCats[$cat->category_id]               = $cat->children;
                    $catArray[$cat->category_id]['name']        = $cat->name;
                    $catArray[$cat->category_id]['slug']        = $cat->slug;
                } ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <a href="<?= empty($cat->children) ? url(['category/index', 'slug' => $cat->slug]) : '#main-categ-' . (int)$cat->category_id ?>" data-toggle="" class="categ-item"><span><i class="fa <?= html_encode($cat->icon); ?>" aria-hidden="true"></i></span><span><?= html_encode($cat->name); ?></span></a>
            </div>

            <?php if (!(($key + 1) % 4) || ($key == $categoriesCount - 1)) { ?>
                    <?php if (!empty($childCats)) { ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="subcategories-box tab-content">
                                <?php foreach ($childCats as $parentId => $cats) { ?>
                                    <div  id="main-categ-<?= (int)$parentId ?>" class="subcategories-box-wrapper tab-pane">
                                        <div class="subcategories-title"><a href="<?= url(['category/index', 'slug' => $catArray[$parentId]['slug']]) ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i><?=t('app','See all');?></a> <?=t('app','categories in ');?><?= html_encode($catArray[$parentId]['name']); ?></div>

                                        <div class="container-fluid">
                                            <?php $catsCount = count($cats);
                                                foreach ($cats as $childKey => $childCat) {
                                                    if (!($childKey % 4)) {?>
                                                    <div class="row">
                                                    <?php } ?>

                                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 item"><a href="<?= url(['category/index', 'slug' => $childCat->slug]) ?>"><i class="fa <?= html_encode($childCat->icon); ?>" aria-hidden="true"></i> <?= html_encode($childCat->name); ?></a> </div>

                                                <?php if (!(($childKey + 1) % 4) || ($childKey == $catsCount - 1)) { ?>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>

                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<div class="categories-mobile">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a href="javascript:;" class="browse" data-toggle="collapse" data-target="#category-list"><?=t('app','Browse categories');?></a>
                <div id="category-list" class="collapse">
                    <?php foreach ($categories as $category) { ?>
                    <div class="panel">
                        <div class="panel-heading">
                            <?php if (!empty($category->children)) { ?>
                            <a href="#main-categ-<?=html_encode($category->slug);?>" data-toggle="collapse" data-parent="#category-list"><span><i class="fa <?=html_encode($category->icon);?>" aria-hidden="true"></i></span><span><?=html_encode($category->name);?></span></a>
                            <?php } else { ?>
                                <a href="<?=url(['/category', 'slug' => $category->slug]);?>"><span><i class="fa <?=html_encode($category->icon);?>" aria-hidden="true"></i></span><span><?=html_encode($category->name);?></span></a>
                            <?php } ?>
                        </div>
                        <?php if (!empty($category->children)) { ?>
                        <div id="main-categ-<?=html_encode($category->slug);?>" class="panel-collapse collapse">
                            <ul>
                                <li><a href="<?=url(['/category', 'slug' => $category->slug]);?>"><?=t('app', 'All');?></a></li>
                                <?php foreach ($category->children as $child) {?>
                                <li><a href="<?=url(['/category', 'slug' => $child->slug]);?>"><?=html_encode($child->name);?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

