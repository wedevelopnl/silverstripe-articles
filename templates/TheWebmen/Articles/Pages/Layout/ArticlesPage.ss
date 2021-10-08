<section class="block section">
    <div class="container">
        <h1>$Title</h1>
        $ArticleFilterForm
        <h2>Articles</h2>
        <% if $PaginatedArticles %>
            <ul>
                <% loop $PaginatedArticles %>
                    <li><a href="$Link">$Title</a></li>
                <% end_loop %>
            </ul>
        <% end_if %>
        $Me.getControllerName
    </div>
</section>
