<form method="post">

    <div><p>Key:</p><input name="key" value=""/></div>
    <div><p>Value</p><textarea name="value"></textarea></div>
    <input type="hidden" name="type" value="add"/>
    <div><input type="submit" name="submit" value="Add"/></div>
</form>

<table>
    <tr>
        <td>Key</td>
        <td>Value</td>
    </tr>
    {foreach $admin_lang_edit as $lang}
        <tr>
            <form method="post">
                <th><input name="key" value="{$lang['key']}"/></th>
                <th><textarea name="value">{$lang['value']}</textarea></th>

                <input type="hidden" name="type" value="edit"/>
                <th><input type="submit" name="submit" value="Edit"/></th>
            </form>
            <th>
                <form method="post">
                    <input name="key" type="text" value="{$lang['key']}" hidden/>
                    <input type="hidden" name="type" value="remove"/>
                    <input type="submit" name="submit" value="Remove"/>
                </form>
            </th>
        </tr>
    {/foreach}
</table>