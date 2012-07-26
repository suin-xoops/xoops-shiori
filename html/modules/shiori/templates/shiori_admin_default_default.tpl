<div class="adminnavi">
  <a href="index.php"><{"Shiori"|shiori_msg}></a>
  &raquo;&raquo; <span class="adminnaviTitle"><a href="index.php"><{"Statistics"|shiori_msg}></a></span>
</div>

<h3 class="admintitle"><{"Statistics"|shiori_msg}></h3>

<form action="index.php" method="get" name="">
<table class="outer" cellspacing="1" style="width:100%">
<tr>
<td class="head" style="text-align:center;">
<a href="index.php?order=<{if $shiori.order == 1}>-<{/if}>1" style="display:block;color:none;">
<{if $shiori.order == 1}><img src="<{$shiori.url}>/images/up.png" style="vertical-align:middle;" /><{/if}>
<{if $shiori.order == -1}><img src="<{$shiori.url}>/images/down.png" style="vertical-align:middle;" /><{/if}>
<{"Page"|shiori_msg}></a>
</td>
<td class="head" style="text-align:center;">
<a href="index.php?order=<{if $shiori.order == 2}>-<{/if}>2" style="display:block;color:none;">
<{if $shiori.order == 2}><img src="<{$shiori.url}>/images/up.png" style="vertical-align:middle;" /><{/if}>
<{if $shiori.order == -2}><img src="<{$shiori.url}>/images/down.png" style="vertical-align:middle;" /><{/if}>
<{"Module"|shiori_msg}></a>
</td>
<td class="head" style="text-align:center;">
<a href="index.php?order=<{if $shiori.order == 3}>-<{/if}>3" style="display:block;color:none;">
<{if $shiori.order == 3}><img src="<{$shiori.url}>/images/up.png" style="vertical-align:middle;" /><{/if}>
<{if $shiori.order == -3}><img src="<{$shiori.url}>/images/down.png" style="vertical-align:middle;" /><{/if}>
<{"Enrollment"|shiori_msg}></a>
</td>
<td class="head" style="text-align:center;">
<a href="index.php?order=<{if $shiori.order == 4}>-<{/if}>4" style="display:block;color:none;">
<{if $shiori.order == 4}><img src="<{$shiori.url}>/images/up.png" style="vertical-align:middle;" /><{/if}>
<{if $shiori.order == -4}><img src="<{$shiori.url}>/images/down.png" style="vertical-align:middle;" /><{/if}>
<{"Clicks"|shiori_msg}></a>
</td>
</tr>
<{foreach from=$shiori.bookmarks item="bookmark"}>
<tr class="<{cycle values="odd,even"}>">
<td><a href="<{$bookmark.url}>"><{$bookmark.name}></a></td>
<td><{$bookmark.module_name}></td>
<td style="text-align:right;"><{$bookmark.users}></td>
<td style="text-align:right;"><{$bookmark.clicks}></td>
</tr>
<{foreachelse}>
<tr>
<td colspan="4" class="odd"><{"No bookmarks"|shiori_msg}></td>
</tr>
<{/foreach}>
<tr>
<td colspan="4" class="foot" style="text-align:right;">
<{"Results per page:"|shiori_msg}>
<select name="limit" onchange="submit(this.form)">
<option value="20"<{if $shiori.limit == 20}> selected="selected"<{/if}>><{"20"|shiori_msg}></option>
<option value="50"<{if $shiori.limit == 50}> selected="selected"<{/if}>><{"50"|shiori_msg}></option>
<option value="100"<{if $shiori.limit == 100}> selected="selected"<{/if}>><{"100"|shiori_msg}></option>
<option value="250"<{if $shiori.limit == 250}> selected="selected"<{/if}>><{"250"|shiori_msg}></option>
<option value="500"<{if $shiori.limit == 500}> selected="selected"<{/if}>><{"500"|shiori_msg}></option>
<option value="0"<{if $shiori.limit == 0}> selected="selected"<{/if}>><{"All"|shiori_msg}></option>
</select>
<input type="hidden" name="order" value="<{$shiori.order}>" />
<input type="submit" value="<{"GO"|shiori_msg}>" />
</td>
</tr>
</table>

<div style="text-align:center;">
<{$shiori.navi_raw}>
</div>