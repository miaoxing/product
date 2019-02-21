<div class="form-group">
  <label class="col-lg-2 control-label">
    视频
  </label>
  <div class="col-lg-4">
    <span class="btn btn-default fileinput-button">
      <span>选择文件</span>
      <input class="js-config-video-upload" type="file" name="file">
    </span>
    <span class="js-video-value"></span>
  </div>
</div>

<script type="text/html" id="js-video-value-tpl">
  <input type="hidden" class="js-config-video" name="config[video]" value="<%= url %>">
  <% if (url) { %>
    <a href="<%= url %>" target="_blank">查看</a>
    <a class="js-video-value-remove text-danger">删除</a>
  <% } %>
</script>

<?= $block->js() ?>
<script>
  require.config({
    paths: {
      'jquery-ui/ui/widget': 'plugins/admin/libs/jquery-ui/ui/minified/jquery.ui.widget.min'
    }
  });
  require([
    'template',
    'css!comps/blueimp-file-upload/css/jquery.fileupload',
    'comps/blueimp-file-upload/js/jquery.fileupload'
  ], function (template) {
    $('.js-config-video-upload').fileupload({
      url: $.url('admin/files/video-upload'),
      dataType: 'json',
      loading: true,
      done: function (e, data) {
        $.msg(data.result);
        if (data.result.code === 1) {
          renderVideoValue(data.result.url);
        }
      }
    });

    function renderVideoValue(url) {
      $('.js-video-value').html(template.render('js-video-value-tpl',{url: url}));
    }
    renderVideoValue(<?= json_encode($product['config']['video']) ?>);

    $(document).on('click', '.js-video-value-remove', function () {
      renderVideoValue('');
    });
  })
</script>
<?= $block->end() ?>
