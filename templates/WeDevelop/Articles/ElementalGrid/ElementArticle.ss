<% if $ShowTitle %>
    <$TitleTag class="$TitleSizeClass">$Title.RAW</$TitleTag>
<% end_if %>
<hr />
<% if $ArticlePage %>
    <% with $ArticlePage %>
        <h4>$Title</h4>
        Reading Time: $ArticleAuthor.ReadingTime<br />
        Author: $ArticleAuthor.Name<br />
    <% end_with %>
<% else %>
    <p><%t WeDevelop\Articles\ElementalGrid\ElementArticle.NOARTICLEFOUND "No article found" %></p>
<% end_if %>
<% if $ShowMoreArticlesButton %>
    <a href="$ArticlesPage.Link" class="btn button is-primary btn-sm btn-primary">
        $ShowMoreArticlesButtonText
    </a>
<% end_if %>
