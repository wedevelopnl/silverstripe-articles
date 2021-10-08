<section class="block section">
    <div class="container">
        <h1>$Title</h1>
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
                <li>Type: $Type.Title</li>
            <% end_if %>
        </ul>
        <% if $AuthorName %>
            <span>$AuthorName</span><br/>
        <% end_if %>
        <% if $UseElementalGrid && $ElementalArea %>
            $ElementalArea
        <% else %>
            $Content
        <% end_if %>
        <h3>Tags</h3>
        <ul>
            <% loop $Tags %>
                <li>$Title</li>
            <% end_loop %>
        </ul>
        <h3>Themes</h3>
        <ul>
            <% loop $Themes %>
                <li>
                    <a href="$Link">$Title</a>
                </li>
            <% end_loop %>
        </ul>
    </div>
</section>
