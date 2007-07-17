<form name="collections" method="post" action={concat( '/collectexport/doexport/', $object.id )|ezurl}>

{let number_of_items=min( ezpreference( 'admin_infocollector_list_limit' ), 3)|choose( 10, 10, 25, 50 )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Information collected by <%object_name> [%collection_count]'|i18n( 'design/admin/infocollector/collectionlist',, hash( '%object_name', $object.name, '%collection_count', $collection_count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<fieldset>
        <legend>{'Export'|i18n( 'design/collectexport/export' )}</legend>
<div align="right"> 
{section show=$collection_array}
<input class="button" style="position:relative;top:-10px;" type="submit" name="DoExport" value="{'Do export'|i18n( 'design/col
lectexport/export' )}" title="{'Do export.'|i18n( 'design/collectexport/export' )}" />
{section-else}
<input class="button-disabled" type="submit" name="DoExport" value="{'Do export'|i18n( 'd
esign/collectexport/export' )}" disabled="disabled" />
{/section}
</div>
</fieldset>


{section show=$collection_array}
{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="break"></div>
</div>
</div>

{* Collection table. *}
{let counter=0}

{let class=fetch( 'content', 'class', hash( 'class_id', $object.contentclass_id ) ) }
        <fieldset>
        <legend>{'Field #%counter'|i18n('design/collectexport/export',, hash( '%counter', $counter) )}</legend>
            <select name="field_{$counter}">
                <option selected "value="contentobjectid">{'Content object id'|i18n('design/collectexport/export')}</option>
                {section loop=$class.data_map}
                        {let current_inner_attribute=$:item}
                                {section show=$current_inner_attribute.is_information_collector }
                                        <option value="{$current_inner_attribute.id}">{$current_inner_attribute.name} [{$current_inner_attribute.id}] </option>
                                {/section}
                        {/let}
                {/section}
                <option value="-1"> {'Leave empty (note: field still gets created)'|i18n('design/collectexport/export')} </option>
                <option value="-2"> {"Ignore (note: field doesn't get created at all)"|i18n('design/collectexport/export')} </option>
            </select>
        </fieldset>
        {set counter=inc( $counter )}

        {section loop=$class.data_map}
                {let current_attribute=$:item}
                        {section show=$current_attribute.is_information_collector }
                                <fieldset>
                                <legend>{'Field #%counter'|i18n('design/collectexport/export',, hash( '%counter', $counter) )}</legend>
                                    <select name="field_{$counter}">
                                        <option selected "value="contentobjectid">Content object id</option>
                                
                                        {section loop=$class.data_map}
                                                {let current_inner_attribute=$:item}
                                                        {section show=$current_inner_attribute.is_information_collector }
                                                                {section show=$current_inner_attribute.id|eq($current_attribute.id)}
                                                                        <option selected value="{$current_inner_attribute.id}">{$current_inner_attribute.name} [{$current_inner_attribute.id}] </option>
                                                                {section-else}
                                                                        <option value="{$current_inner_attribute.id}">{$current_inner_attribute.name} [{$current_inner_attribute.id}] </option>
                                                                {/section}
                                                        {/section}
                                                {/let}
                                        {/section}
                                        <option value="-1"> {'Leave empty (note: field still gets created)'|i18n('design/collectexport/export')} </option>
                                        <option value="-2"> {"Ignore (note: field doesn't get created at all)"|i18n('design/collectexport/export')} </option>
                                     </select>
                                </fieldset>
                                {set counter=inc( $counter )}
                        {/section}
                {/let}
                
        {/section}
{/let}
{/let}
<fieldset>
    <legend>{'Export type'|i18n('design/collectexport/export')}</legend>
    <select name="export_type">
        <option value="csv">CSV</option>
        <option value="sylk">SYLK (Excel)</option>
    </select>
</fieldset>
<fieldset>
	<legend>{'Separation char for CVS export'|i18n('design/collectexport/export')}</legend>
	<input type="radio" name="separation_char" checked="true" value=";"/> Semicolon (';') <br/>
	<input type="radio" name="separation_char" value=","/> Comma (',') <br/>
	<input type="radio" name="separation_char" value=":"/> Colon (':') <br/>
	<input type="radio" name="separation_char" value="|"/> Pipe ('|') <br/>
	<input type="radio" name="separation_char" value="#"/> Hash ('#') <br/>
</fieldset>

{section-else}
<div class="block">
<p>{'No information has been collected by this object.'|i18n( 'design/admin/infocollector/collectionlist' )}</p>
</div>

{/section}

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
{section show=$collection_array}
<input class="button" type="submit" name="DoExport" value="{'Do export'|i18n( 'design/collectexport/export' )}" title="{'Do export.'|i18n( 'design/collectexport/export' )}" />
{section-else}
<input class="button-disabled" type="submit" name="DoExport" value="{'Do export'|i18n( 'design/collectexport/export' )}" disabled="disabled" />
{/section}
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

{/let}

</form>
