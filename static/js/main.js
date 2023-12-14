// Enable Bootstrap tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

$(document).ready(function() {
	$("[data-href-decode]").each(function() {
		var link = $(this).data("href-decode");
		link = "mailto:" + atob(link),
		$(this).attr("href", link);
	})

	$(".post-article img").addClass("img-fluid");
	$(".post-article a").attr("target", "_blank");
})