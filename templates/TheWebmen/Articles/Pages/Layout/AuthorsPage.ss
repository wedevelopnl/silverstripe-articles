<ul>
<% loop $PaginatedAuthors %>
    <li><a href="$Link">$Title</a></li>
<% end_loop %>
</ul>

<% if $PaginatedAuthors.MoreThanOnePage %>
    <% if $PaginatedAuthors.NotFirstPage %>
        <a class="prev" href="$PaginatedAuthors.PrevLink">Prev</a>
    <% end_if %>
    <% loop $PaginatedAuthors.Pages %>
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
    <% if $PaginatedAuthors.NotLastPage %>
        <a class="next" href="$PaginatedAuthors.NextLink">Next</a>
    <% end_if %>
<% end_if %>
