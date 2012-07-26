<form action="<{$smarty.const.SHIORI_URL}>/index.php?action=form" method="post">
<{if $shiori.is_bookmarked}>
<{"This page has been bookmarked."|shiori_msg}>
<{else}>
<{"Do you want to bookmark this page?"|shiori_msg}>
<{/if}>
<div style="text-align:center;">
<input type="submit" name="submit" value="<{"Submit"|shiori_msg}>"<{if $shiori.is_bookmarked}> disabled="disabled"<{/if}> />
</div>
<input type="hidden" name="url" value="<{$shiori.url}>" />
<script  type="text/javascript">
document.write('<input type="hidden" name="title" value="' + document.title + '" />');
</script>
</form>