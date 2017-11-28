<h1>$Title</h1>
$Content
Article
<% if $Author %>
    $Author.Name
<% end_if %>

<% if $RelatedArticles %>
    <% loop $RelatedArticles %>
        <h2>$Title</h2>
    <% end_loop %>
<% end_if %>
