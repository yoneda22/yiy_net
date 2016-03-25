/*hover*/
$(function(){
	var start = "touchstart";
	var end   = "touchend";
$("a,#submuibtn").bind(start,function(){
	$(this).addClass("touchstart");
	});
$("a,#submuibtn").bind(end,function(){
	$(this).removeClass("touchstart");
	});
});
