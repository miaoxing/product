<?php if ($albums && $albums->count() > 0) : ?>
  <div class="js-index-slider swipe">
    <div class="swipe-wrap">
      <?php foreach ($albums as $album) : ?>
        <div>
          <a href="<?= $album['linkTo']['type'] ? $wei->linkTo->getUrl($album['linkTo']) : 'javascript:;' ?>">
            <img src="<?= $album['image'] ?>"/>
          </a>
        </div>
      <?php endforeach ?>
    </div>
    <ol class="swipe-nav">
      <?php foreach ($albums as $index => $album) : ?>
        <li><a class="<?= $index == 0 ? 'swipe-nav-active' : '' ?>"></a></li>
      <?php endforeach ?>
    </ol>
  </div>
<?php endif ?>

<?= $block->js() ?>
<script>
  // 幻灯片
  (function () {
    if($(".js-index-slider").length > 0) {
      $('.js-index-slider').Swipe({
        auto: 3000,
        callback: function (index, elem) {
          var nav = $(elem).parent().next().find('a');
          nav.removeClass('swipe-nav-active').eq(index).addClass('swipe-nav-active');
        }
      }).fixSwipeImgHeight();
    }
  })();
</script>
<?= $block->end() ?>
