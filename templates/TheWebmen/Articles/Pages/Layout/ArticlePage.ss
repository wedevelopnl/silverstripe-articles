<h1>$Title</h1>
$Content
Article
<% if $Author %>
    $Author.Name
<% end_if %>

<ul>
    <% loop $Categories %>
        <li><a href="$Link">$Title</a></li>
    <% end_loop %>
</ul>

<% if $RelatedArticles %>
    <% loop $RelatedArticles %>
        <h2>$Title</h2>
    <% end_loop %>
<% end_if %>
