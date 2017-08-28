var currentdate = new Date(); 
var datetime = currentdate.getFullYear() + "-"
    + ("0" + (currentdate.getMonth() + 1)).slice(-2) + "-" 
    + ("0" + currentdate.getDate()).slice(-2) + " "  
    + ("0" + currentdate.getHours()).slice(-2) + ":"  
    + ("0" + currentdate.getMinutes()).slice(-2)+ ":"
    + ("0" + currentdate.getSeconds()).slice(-2);
$("#startTrackInput").val(datetime);
$("#endTrackInput").val(datetime);
$('.form_datetime').datetimepicker({
    language:  'de',
    weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	forceParse: 0,
    showMeridian: 1,
    format: 'yyyy-mm-dd hh:ii:ss'
});