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
                <% if $Pinned %><strong>Pinned:</strong> <% end_if %>
                <a href="$Link">$Title</a> $PublicationDate.Nice
            </li>
        <% end_loop %>
    </ul>
<% else %>
    <p><%t Articles.NoArticlesFound "No articles found" %></p>
<% end_if %>
<% if $ShowMoreArticlesButton %>
    <a href="$ArticlesPage.Link" class="btn button is-primary is-small btn-sm btn-primary">
        $ShowMoreArticlesButtonText
    </a>
<% end_if %>
