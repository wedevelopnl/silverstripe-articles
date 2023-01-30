<section class="block section">
    <div class="container">
        <h1>$Title</h1>
        $Thumbnail.Fill(200,200)
        <h2>$Subtitle</h2>
        <% if $TeaserText %>
            <div style="font-size: 20px;">$TeaserText</div>
        <% end_if %>
        <ul>
            <% if $UpdatedDate %>
                <li>$PublicationDate.Nice</li>
            <% end_if %>
            <% if $UpdatedDate %>
                <li>$UpdatedDate.Nice</li>
            <% end_if %>
            <% if $ReadingTime %>
                <li>$ReadingTime min.</li>
            <% end_if %>
            <% if $Type %>
                <li><%t Type.Singular "Type" %>: $Type.Title</li>
            <% end_if %>
        </ul>
        <% if $AuthorName %>
            <span>$AuthorName</span><br/>
        <% end_if %>
        <hr />
        $Content
        <hr />
        <h3><%t Tag.Plural "Tags" %></h3>
        <ul>
            <% loop $Tags %>
                <li>
                    <a href="$Up.Parent.Link?tag=$Slug">$Title</a>
                </li>
            <% end_loop %>
        </ul>
        <h3><%t Theme.Plural "Themes" %></h3>
        <ul>
            <% loop $Themes %>
                <li>
                    <a href="$Link">$Title</a>
                </li>
            <% end_loop %>
        </ul>
    </div>
</section>
