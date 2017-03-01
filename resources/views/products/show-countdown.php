<script type="text/html" class="js-countdown-tpl">
  即将开始 <span class="js-product-countdown product-countdown"></span>
</script>

<?= $block('js') ?>
<script>
  require(['comps/jQuery.countdown/dist/jquery.countdown.min'], function () {
    var leftTime = <?= strtotime($product['startTime']) - time() ?>;
    var startTime = new Date(new Date().getTime() + leftTime * 1000);
    $('.js-product-action-disabled').html($('.js-countdown-tpl').html());
    $('.js-product-countdown').countdown(startTime)
      .on('update.countdown', function (event) {
        var format = '';
        if (event.offset.totalDays) {
          format = '<span>%D</span> 天 '
        }
        $(this).html(event.strftime(format + '<span>%H</span> : <span>%M</span> : <span>%S</span>'));
      })
      .on('finish.countdown', function() {
        window.location.reload();
      });
  });
</script>
<?= $block->end() ?>
