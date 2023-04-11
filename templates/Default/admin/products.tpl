<div id="adm-modal-product" class="modal">
    <div class="progress" style="display: none">
        <div class="indeterminate"></div>
    </div>

    <form id="admin-product-form">
        <div class="modal-content">
            <h4>Create product</h4>

            <div class="red-text center-align error-message"></div>
            <div class="row margin-0">
                <div class="input-field col s12">
                    <input id="display_name" name="display_name" type="text" class="validate">
                    <label for="display_name">Display name</label>
                </div>
            </div>
            <div class="row margin-0">
                <div class="input-field col s12">
                    <textarea id="description" name="description" class="materialize-textarea"></textarea>
                    <label for="description">Description</label>
                </div>
            </div>
            <div class="row margin-0">
                <div class="input-field col s6">
                    <input id="cost" name="cost" type="number" class="validate">
                    <label for="cost">Cost</label>
                </div>
                <div class="input-field col s6">
                    <input id="balance" name="balance" type="text" class="validate">
                    <label for="balance">Balance</label>
                </div>
            </div>
            <div class="row margin-0">
                <div class="input-field col s6">
                    <input id="art" name="art" type="number" class="validate">
                    <label for="art">Code</label>
                </div>
                <div class="input-field col s6">
                    <input id="code" name="code" type="number" class="validate">
                    <label for="code">Barcode</label>
                </div>
            </div>

            <div class="row margin-0">
                <div class="input-field col s12">
                    <select name="category">
                        <option value="0" selected>none</option>
                        {foreach $admin_cats_list as $category1}
                            <option value="{$category1->getId()}">{$category1->getDisplayName()}</option>
                        {/foreach}
                    </select>
                    <label for="category">Category</label>
                </div>
            </div>
            <div class="row margin-0">
                <div class="input-field col s12">
                    <div class="dropzone" id="mydropzone"></div>
                </div>
            </div>
            <div class="row margin-0">
                <div class="input-field col s6">
                    <button class="btn waves-effect waves-block waves-light center-block" name="cancel" type="button">
                        Cancel
                        <i class="material-icons right">cancel</i>
                    </button>
                </div>
                <div class="input-field col s6">
                    <button class="btn waves-effect waves-block waves-light center-block" type="submit" name="create">
                        Create
                        <i class="material-icons right">add</i>
                    </button>
                </div>
            </div>
            <input name="id" type="hidden" value="0">
            <input name="photos" type="hidden" value='' class="photos">
        </div>
    </form>
</div>
<div class="row margin-0">
    <div class="col s11"></div>
    <button class="btn create-product col s1">Create</button>
</div>
<div class="row margin-0">
    <form class="col s12 margin-0" id="adm-products-search">
        <div class="row margin-0">
            <div class="input-field col s12 margin-0">
                <i class="material-icons prefix">search</i>
                <input id="icon_prefix" name="search" type="text" class="validate">
                <label for="icon_prefix">Search</label>
            </div>
        </div>
    </form>
</div>

<table id="adm-product-list">
    <thead>
    <tr>
        <th>ID</th>
        <th>Display Name</th>
        <th>Category</th>
        <th>Cost</th>
        <th>Description</th>
        <th>Code</th>
        <th>Barcode</th>
        <th>Sold</th>
        <th>Balance</th>
    </tr>
    <tr class="preloader" style="display: none;">
        <th colspan="10">
            <div class="preloader-wrapper big active margin-0">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
    </tr>
    </thead>
    <tbody>
    {foreach $admin_products_edit as $product}
        <tr>
            <td>{$product->getId()}</td>
            <td>{$product->getDisplayName()}</td>
            <td>{$admin_cats_list[$product->getCategory()]->getDisplayName()}</td>
            <td>{$product->getCost()}</td>
            <td>{$product->getDescription()}</td>
            <td>{$product->getArt()}</td>
            <td>{$product->getCode()}</td>
            <td>{$product->getSold()}</td>
            <td>{$product->getBalance()}</td>
            <td><a href="#" data-id="{$product->getId()}"
                   class="waves-effect waves-light btn-small product-edit"><i class="material-icons center">mode_edit
                    </i></a></td>
            <td><a href="/product/{$product->getId()}" target="_blank"
                   class="waves-effect waves-light btn-small"><i class="material-icons center">open_in_new
                    </i></a></td>
            <td><a href="#" data-id="{$product->getId()}"
                   class="waves-effect waves-light btn-small product-remove ie23s-red-gb"><i
                            class="material-icons center">cancel
                    </i></a></td>

        </tr>
    {/foreach}

    </tbody>
</table>
{literal}
    <div class="ie23s-hidden" id="adm-product-template">
        <table>
            <tbody>
            <tr>
                <td>{id}</td>
                <td>{display_name}</td>
                <td>{category}</td>
                <td>{cost}</td>
                <td>{description}</td>
                <td>{art}</td>
                <td>{code}</td>
                <td>{sold}</td>
                <td>{balance}</td>
                <td><a href="#" data-id="{id}"
                       class="waves-effect waves-light btn-small product-edit"><i class="material-icons center">mode_edit
                        </i></a></td>
                <td><a href="/product/{id}" target="_blank"
                       class="waves-effect waves-light btn-small"><i class="material-icons center">open_in_new
                        </i></a></td>
                <td><a href="#" data-id="{id}"
                       class="waves-effect waves-light btn-small product-remove ie23s-red-gb"><i
                                class="material-icons center">cancel
                        </i></a></td>

            </tr>
            </tbody>
        </table>
    </div>
{/literal}