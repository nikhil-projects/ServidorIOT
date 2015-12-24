dropdownMenu = function() {
    document.getElementById("myDropdown").classList.toggle("show");
}

displayGraphPopup = function() {
	$("#graph-popup").toggle().siblings('div').hide();

}

displayMaximaTemperatura = function() {
   $("#temperatura-adicionar").toggle().siblings('div').hide();
}

displayRemoverMaximaTemperatura = function() {
	$("#temperatura-remover").toggle().siblings('div').hide();
}

performLogout = function() {
	$(location).attr('href', 'logout.php?logout');
}

displayNormalizarTemperatura = function() {
	$("#normalizar-temperatura").toggle().siblings('div').hide();
}

displayListaDeCamioes = function() {
	$("#lista-camioes").toggle().siblings('div').hide();
}