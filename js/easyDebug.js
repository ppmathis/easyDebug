var easyDebug_activeMenu;

function easyDebug_toggle() {
	$('#easyDebug_window').slideToggle('fast');
}
function easyDebug_toggleMenu(menuId) {
	if(menuId == easyDebug_activeMenu) {
		return;
	}
	$('#easyDebug_button_' + easyDebug_activeMenu).removeClass('easyDebug menuButton active').addClass('easyDebug menuButton');
	$('#easyDebug_window_' + easyDebug_activeMenu).fadeOut('fast');
	$('#easyDebug_button_' + menuId).removeClass('easyDebug menuButton').addClass('easyDebug menuButton active');
	$('#easyDebug_window_' + menuId).fadeIn('fast');
	easyDebug_activeMenu = menuId;
	return;
}
function easyDebug_toggleVar(blockId) {
	$('#easyDebug_variableDetail_id' + blockId).slideToggle('fast');
	return;
}