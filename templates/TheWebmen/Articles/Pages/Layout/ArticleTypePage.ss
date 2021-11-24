<section class="block section">
    <div class="container">
        <h1>Type: $Title</h1>
        <div class="columns row">
            <div class="col-md-4 column is-4-desktop">
                <div class="card card-body">
                    $ArticleFilterForm
                </div>
            </div>
            <div class="col-md-8 column is-8-desktop">
                <h2><%t TheWebmen\Articles\Pages\ArticlePage.PLURALNAME "Articles" %></h2>
                <hr />

                $Content
                <hr />

                <% if $PaginatedArticles %>
                    <ul>
                        <% loop $PaginatedArticles %>
                            <li>
                                <a href="$Link">$Title</a> <% if $PublicationDate %>($PublicationDate.Nice)<% end_if %>
                            </li>
                        <% end_loop %>
                    </ul>
                <% else %>
                    <p>
                        <%t Articles.NoArticlesFound "No articles found" %>
                    </p>
                <% end_if %>

                <% with $PaginatedArticles %>
                    <% if $MoreThanOnePage %>
                        <% if $NotFirstPage %>
                            <a class="prev" href="$PrevLink"><<</a>
                        <% end_if %>
                        <% loop $Pages %>
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
                        <% if $NotLastPage %>
                            <a class="next" href="$NextLink">>></a>
                        <% end_if %>
                    <% end_if %>
                <% end_with %>
            </div>
        </div>
    </div>
</section>
