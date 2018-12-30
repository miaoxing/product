<script id="product-tpl" type="text/html">
  <div class="media user-media product-media">
    <a class="media-left" href="<%= url || $.url('products/%s', id) %>" target="_blank">
      <img class="media-object" src="<%= typeof images == 'undefined' ? '' : images[0] %>" style="width: 48px; height: 48px;">
    </a>
    <div class="media-body text-left">
      <h4 class="media-heading">
        <a href="<%= url || $.url('products/%s', id) %>" target="_blank">
          <%== name %>
          <% if (free == '1' || free == '2') { %>
            <span class="label label-success product-gift"><%= free == '1' ? '赠品' : '换购' %></span>
          <% } %>
        </a>
      </h4>
      <span class="media-content text-muted">
        <% specs && $.each (specs, function (name, value) { %>
          <%= name + ': ' + value  %>
        <% }) %>
        <%== content %>
      </span>
    </div>
  </div>
</script>
