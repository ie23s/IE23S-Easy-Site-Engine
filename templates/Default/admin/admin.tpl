<table style="width: 100%">
    <tr>
        <td style="width: 100px;vertical-align: top;">
            <ul>
                {foreach $admin_buttons as $button}
                    <li><a href="{$button.uri}">{$button.name}</a></li>
                {/foreach}
            </ul>

        </td>
        <td>{$admin_content}</td>
    </tr>
</table>