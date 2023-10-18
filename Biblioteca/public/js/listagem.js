$(document).ready(function () {
  debugger
  const order = (new URLSearchParams(window.location.search)).get("order");

  if (order) {
    $("thead form:first").append(`<input type="hidden" name="order" value="${order}">`);
  }

  $("th[data-sort]").each((i, el) => {
    el = $(el);
    let txt = el.text();
    let prop = el.attr("data-sort");
    let icon = order == prop ? '<i class="bi bi-sort-down h5"></i>' : '<i class="bi bi-arrow-down-up"></i>';
    el
      .css("cursor", order == prop ? "default" : "pointer")
      .addClass(order == prop ? "text-success" : "")
      .attr("title", order == prop ? "" : `Ordernar por ${txt}`)
      .html(`${icon} ${txt}`);

    if (order == prop) return;

    el.click((e) => {
      let searchParams = new URLSearchParams(window.location.search);
      searchParams.set("order", $(e.target).attr("data-sort"));
      window.location.search = searchParams.toString();
    });
  });
});