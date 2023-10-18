
/** avisa("Teste de dialog"); */
function avisa(txt, title = "Atenção!") {
  _dialog(txt, null, title, false);
}

/** confirma("Vai?", ()=>avisa("Vai sim!")); */
function confirma(txt, onOk, title = "Atenção!", simNao = false) {
  _dialog(txt, onOk, title, true, null, simNao);
}

/** pergunta("Profissão", (s) => alert(s)); */
function pergunta(txt, onOk, val = "", title = "") {
  title = title || txt;
  let html = `<label class="text-start">${txt} <input type="text" id="txt" placeholder="${txt}" class="form-control" value="${val}" autocomplete="off"></label>`;

  let _onOk = () => {
    let s = ($("#dlg #txt").val()).trim();
    $("#dlg").modal("hide");
    $("#dlg-body #txt").unbind("keyup");
    onOk(s);
  };

  let onShow = () => {
    $("#dlg-footer .btn-primary").prop("disabled", !val);
    $("#dlg-body #txt").bind('input', () => $("#dlg-footer .btn-primary").prop("disabled", !($("#dlg #txt").val()).trim()));
    $("#dlg-body #txt").trigger("focus")
  };

  _dialog(html, _onOk, title, true, onShow);
}

function mask(s, mask) {
  if (!s) return "";
  let x = "";
  let k = 0;
  let n = mask.length;
  for (i = 0; i < n; i++) {
    if (mask[i] == '#') {
      if (k < s.length) x += s[k++];
    } else {
      x += mask[i];
    }
  }
  return x;
}

$(document).ready(function () {
  /*
    checkbox $("[type='checkbox' ]").each(function() { let el=$(this); let name=el.attr("name"); if (!name) return; let val=el.prop("checked") ? 1 : 0; el.attr("name", name + "-chk" ); el.parent().append(`<input type="hidden" id="${name}" name="${name}" value="${val}">`);
    el.click(() => {
      let id = "#" + $(this).attr("name").slice(0, -4);
      $(id).val($(this).prop("checked") ? 1 : 0);
    })
  */

  //data-confirm
  $("a[data-confirm]").each((i, e) => {
    let el = $(e);
    let url = el.attr("href");
    let msg = el.attr("data-confirm");
    el.attr("href", "#");
    el.click(() => {
      confirma(msg, () => location.href = url, "Confirmação", true);
    });
  });

  //modal
  $("#dlg *[data-dismiss='modal']").click(() => $('#dlg').modal('hide'));

  //mask
  $(":input[data-mask]").each(function () {
    let el = $(this);
    let m = el.attr("data-mask").replaceAll("#", "9");
    el.attr("data-inputmask", `'mask': '${m}'`);
    el.removeAttr("data-mask");
  });
  $(":input").inputmask(); //or Inputmask().mask(document.querySelectorAll("input"));
  $("span[data-mask]").each(function () {
    let el = $(this);
    let s = el.text();
    let m = el.attr("data-mask");
    el.text(mask(s, m));
  });

  $(document).on({
    ajaxStart: function () {
      $("body").addClass("loading");
    },
    ajaxStop: function () {
      $("body").removeClass("loading");
    }
  });

  //paginators
  if ($("nav").length > 1) {
    debugger;
    let el = $("nav:last span");
    el.text(el.text().replaceAll("«", " ").replaceAll("»", " "));
    el.addClass("disabled btn btn-mini");

    $("nav:last a").addClass('btn btn-mini');

    $("nav:last a, nav:last span").each(function () {
      let el = $(this);
      if (el.text().indexOf("Anterior") >= 0)
        el.text('Anterior').prepend("<i class='bi bi-caret-left-fill'></i>");
      else if (el.text().indexOf("Próximo") >= 0)
        el.text('Próximo').append("<i class='bi bi-caret-right-fill'></i>");
      else
        el.html(el.text()
          .replaceAll("pagination.previous", "<i class='bi bi-caret-left-fill'></i> Anterior")
          .replaceAll("pagination.next", "Próximo <i class='bi bi-caret-right-fill'></i>"));
    });
  }

  $(document).ready(function () {
    setTimeout(() => {
      $(".container").removeClass("d-none");
    }, 1);
  });
});



function _dialog(txt, onOk, title = "", twoButtons = false, onShow = null, simNao = false) {
  $("#dlg").unbind("keyup");
  $("#dlg-footer .btn-primary").unbind();

  if (simNao) {
    $("#dlg-footer .btn-primary").text("Sim");
    $("#dlg-footer .btn-secondary").text("Não");
  } else {
    $("#dlg-footer .btn-primary").text("OK");
    $("#dlg-footer .btn-secondary").text("Cancelar");
  }

  if (twoButtons) {
    $("#dlg-footer .btn-secondary").removeClass("d-none");
    $("#dlg-footer").css("justify-content", "flex-end");
  }
  else {
    $("#dlg-footer .btn-secondary").addClass("d-none");
    $('#dlg-footer').css("justify-content", "center");
  }
  $("#dlg-body").html(txt);
  $("#dlg-title").text(title);

  if (onShow)
    $("#dlg").on("shown.bs.modal", () => onShow());

  $("#dlg").on("keyup", (e) => { if (e.key === "Enter") $("#dlg-footer .btn-primary").click() });

  $("#dlg-footer .btn-primary").click(() => {
    $("#dlg").modal("hide");
    if (onOk)
      setTimeout(() => onOk(), 1);
  });

  $("#dlg").modal("show");
}
