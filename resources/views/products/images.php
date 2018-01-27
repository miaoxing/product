<?php

$event->trigger('preProductImagesRender', [&$images])
?>

<div class="js-image-slider swipe">
  <div class="js-images-preview swipe-wrap">
    <?php foreach ($images as $index => $image) : ?>
      <div>
        <img src="<?= $image ?>"/>
      </div>
    <?php endforeach ?>
  </div>
  <ol class="swipe-nav">
    <?php foreach ($images as $index => $image) : ?>
      <li><a class="<?= $index == 0 ? 'swipe-nav-active' : '' ?>"></a></li>
    <?php endforeach ?>
  </ol>
</div>

<?= $block->js() ?>
<script>
  $('.js-image-slider')
    .Swipe({
      auto: 3000,
      callback: function (index, elem) {
        var nav = $(elem).parent().next().find('a');
        nav.removeClass('swipe-nav-active').eq(index).addClass('swipe-nav-active');
      }
    })
    .fixSwipeImgHeight();
</script>
<?= $block->end() ?>
