<ul>
    <% loop $Categories %>
        <li><a href="$Link">$Title</a></li>
    <% end_loop %>
</ul>

<ul>
    <% loop $PaginatedArticles %>
        <li><a href="$Link">$Title</a></li>
    <% end_loop %>
</ul>

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
