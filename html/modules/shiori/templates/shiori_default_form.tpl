<form id="shiori_form" action="index.php?action=save" method="post">
<table style="width:100%;" border="0" class="outer" cellspacing="1" summary="bookmark registration format">
<tr>
<th colspan="2"><{"Bookmark"|shiori_msg}></th>
</tr>
<tr>
<td class="head"><{"Icon"|shiori_msg}></td>
<td class="even">
<{foreach from=$shiori.icons item="icon" name="icon"}>
<label>
<input type="radio" name="icon" value="<{$icon}>"<{if $smarty.foreach.icon.first}> checked="checked"<{/if}> />
<img src="<{$xoops_url}>/images/subject/<{$icon}>" />
</label>
<{/foreach}>
</td>
</tr>
<tr>
<td class="head"><{"Title"|shiori_msg}></td>
<td class="even"><input type="text" size="40" name="title" value="<{$shiori.title}>" /></td>
</tr>
<tr>
<td class="head"><{"Module"|shiori_msg}></td>
<td class="even"><{$shiori.module.name}><input type="hidden" name="mid" value="<{$shiori.module.id}>" /></td>
</tr>
<tr>
<td class="head"><{"URL"|shiori_msg}></td>
<td class="even"><{$shiori.url}><input type="hidden" name="url" value="<{$shiori.url}>" /></td>
</tr>
<tr>
<td colspan="2" class="foot"><input type="submit" name="submit" value="<{"Save"|shiori_msg}>" /></td>
</tr>
</table>
<input type="hidden" name="ticket" value="<{$shiori.ticket}>" />
</form>