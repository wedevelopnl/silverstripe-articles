<section class="block section">
    <div class="container">
        <h1>$Title</h1>
        <div class="columns row">
            <div class="col-md-4 column is-4-desktop">
                <div class="card card-body">
                    $ArticleFilterForm
                </div>
            </div>
            <div class="col-md-8 column is-8-desktop">
                <h2><%t WeDevelop\Articles\Pages\ArticlePage.PLURALNAME "Articles" %></h2>
                <hr />
                <% if $Themes %>
                    <h3>
                        <%t Theme.Plural "Themes" %>
                    </h3>
                    <ul>
                        <% loop $Themes %>
                            <li>
                                <a href="$Link">$Title</a>
                            </li>
                        <% end_loop %>
                    </ul>
                    <hr />
                <% end_if %>
                <% if $Types %>
                    <h3>
                        <%t Type.Plural "Types" %>
                    </h3>
                    <ul>
                        <% loop $Types %>
                            <li>
                                <a href="$Link">$Title</a>
                            </li>
                        <% end_loop %>
                    </ul>
                    <hr />
                <% end_if %>

                <% if $Content %>
                    $Content
                    <hr />
                <% end_if %>

                <% if not $hasActiveFilters %>
                    <h3>Pinned articles</h3>
                    <% if $PinnedArticles %>
                        <ul>
                            <% loop $PinnedArticles.Sort('PinnedSort') %>
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
                    <h3>Highlighted articles</h3>
                    <% if $HighlightedArticles %>
                        <ul>
                            <% loop $HighlightedArticles.Sort('HighlightedSort') %>
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
                <% end_if %>
                <h3>All articles (paginated)</h3>
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
