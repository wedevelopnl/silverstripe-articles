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

<% if $PaginatedArticles.MoreThanOnePage %>
    <% if $PaginatedArticles.NotFirstPage %>
        <a class="prev" href="$PaginatedArticles.PrevLink">Prev</a>
    <% end_if %>
    <% loop $PaginatedArticles.Pages %>
        <% if $CurrentBool %>
            $PageNum
        <% else %>
            <% if $Link %>
                <a href="$Link">$PageNum</a>
            <% else %>
                ...
            <% end_if %>
        <% end_if %>
    <% end_loop %>
    <% if $PaginatedArticles.NotLastPage %>
        <a class="next" href="$PaginatedArticles.NextLink">Next</a>
    <% end_if %>
<% end_if %>
