<% if $ShowTitle %>
    <$TitleTag class="$TitleClass">$Title</$TitleTag>
<% end_if %>
<hr />
$Articles.Count <%t Article.Plural "Articles" %>
<hr /><br />
<h3><%t ElementArticles.Results "Results" %></h3>
<% if $Articles %>
    <ul>
        <% loop $Articles %>
            <li>
                <a href="$Link">$Title</a>
            </li>
        <% end_loop %>
    </ul>
<% else %>
    <p><%t Articles.NoArticlesFound "No articles found" %></p>
<% end_if %>
<% if $ShowMoreArticlesButton %>
    <a href="$ArticlesPage.Link" class="btn button is-primary is-small btn-sm btn-primary">
        $ArticlesPage.Title
    </a>
<% end_if %>
