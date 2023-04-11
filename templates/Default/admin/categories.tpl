<h1>Create</h1>
<form method="post">
    <div><p>Name:</p><input name="name" type="text" value=""/></div>
    <div><p>Parent:</p><select name="parent">
            <option value="0" selected>none</option>
            {foreach $admin_cats_list as $category1}
                <option value="{$category1->getId()}">{$category1->getDisplayName()}</option>
            {/foreach}
        </select></div>
    <div><p>Display name:</p><input name="display_name" value=""/></div>

    <input type="hidden" name="type" value="add"/>
    <div><input type="submit" name="submit" value="Create"/></div>
</form>
<hr/>
<h1>Edit</h1>
{foreach $admin_cats_edit as $category}
    <div>
        <form method="post">

            <input name="id" type="hidden" value="{$category.id}"/>
            <input name="name" type="text" value="{$category.name}"/>
            <select name="parent">
                <option value="0" {if $category.parent_id == 0} selected {/if}>none</option>
                {foreach $admin_cats_list as $category1}
                    <option value="{$category1->getId()}" {if $category.parent_id == $category1->getId()} selected {/if}>{$category1->getDisplayName()}</option>
                {/foreach}
            </select>
            <input name="display_name" value="{$category.display_name}"/>
            <button name="type" value="edit">Edit</button>
            <button name="type" value="remove">Remove</button>
        </form>
    </div>
{/foreach}