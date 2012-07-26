<h4><{"Bookmarks"|shiori_msg}></h4>
<p><a href="<{$xoops_url}>/userinfo.php?uid=<{$shiori.uid}>"><{"Profile"|shiori_msg}></a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;<{"Bookmarks"|shiori_msg}></p>

<div class="odd">
<ul>
<li><{"You can bookmark {1} pages."|shiori_msg:$shiori.config.capacity}></li>
<{if $shiori.config.bookmark_other_sites == 1}>
<li><{"You can bookmark other sites."|shiori_msg}></li>
<{else}>
<li><{"You cannot bookmark other sites."|shiori_msg}></li>
<{/if}>
</ul>
</div>

<{if $shiori.navi_raw}>
<div style="text-align:center;"><{$shiori.navi_raw}></div>
<{/if}>

<form name="bookmark" action="index.php?action=delete" method="post">
<table border="0" class="outer" cellspacing="1" cellpadding="4" summary="<{"Bookmarks"|shiori_msg}>" style="width:100%">

<tr>
<th><input id="allbox" onclick="xoopsCheckGroup('bookmark', 'allbox', 'del_bok[]');" type="checkbox" value="<{"check all"|shiori_msg}>" name="allbox" /></th>
<th>&nbsp;</th>
<th><{"Link"|shiori_msg}></th>
<th><{"Module"|shiori_msg}></th>
</tr>

<{foreach item=bookmark from=$shiori.bookmarks}>
<tr>
<td class="even"><input id="del_bok[]" type="checkbox" value="<{$bookmark.id}>" name="del_bok[]" /></td>
<td class="even"><img src="<{$xoops_url}>/images/subject/<{$bookmark.icon}>" alt="bookmark icon" /></td>
<td class="even"><a href="index.php?controller=load&id=<{$bookmark.id}>"><{$bookmark.title}></a></td>
<td class="even"><{$bookmark.module_name}></td>
</tr>
<{/foreach}>

<tr>
<{if count($shiori.bookmarks) > 0}>
<td class="foot" colspan="5"><input id="del" name="del" type="submit" value="<{"Delete"|shiori_msg}>" /></td>
<{else}>
<td class="odd" colspan="5"><{"No bookmarks"|shiori_msg}></td>
<{/if}>
</tr>

</table>

<input type="hidden" name="ticket" value="<{$shiori.ticket}>" />
</form>

<{if $shiori.navi_raw}>
<div style="text-align:center;"><{$shiori.navi_raw}></div>
<{/if}>

<{if $shiori.config.free_input_url == 1}>
<form name="addbookmark" action="index.php?action=form" method="post">
<table border="0" class="outer" cellspacing="1" cellpadding="4" summary="table of addition of bookmark" style="width:100%">
<tr>
<th colspan="2"><{"Add bookmark from URL"|shiori_msg}></td>
</tr>
<tr>
<td class="head"><{"URL"|shiori_msg}></td>
<td class="even"><input type="text" name="url" id="url" value="" size="30" style="width:80%" />&nbsp;&nbsp;<input id="add" name="add" type="submit" value="<{"Add"|shiori_msg}>" /></td>
</tr>
</table>
</form>
<{/if}>