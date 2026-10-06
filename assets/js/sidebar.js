$(".has-submenu > .menu-item").on("click", function () {
  $(this).parent().toggleClass("active");
});
$("#menu").on("click", function () {
  $("body").toggleClass("sidebar-collapsed");
  $(".sidebar").toggleClass("closed");
});
function checkSidebar() {
  if ($(window).width() <= 768) {
    $("body").addClass("sidebar-collapsed");
    $(".sidebar").addClass("closed");
  } else {
    $("body").removeClass("sidebar-collapsed");
    $(".sidebar").removeClass("closed");
  }
}

$(document).ready(checkSidebar);
$(window).on("resize", checkSidebar);




async function logout() {

  const response = await fetch(ruta + "user/logout", {
    method: "POST",
    credentials: "include" // 🔐 IMPORTANTE para enviar cookie
  });

  const data = await response.json();

  if (data.success) {
    window.location = "/login";
  }
}
