console.log("hi");
$(function () {
    meta = 0;
    attr = 0;
    price = 0;
    $("#addMeta").on("click", function (e) {
        e.preventDefault();
        $key = $(`<div class='row mb-1'><div class="col-6">
        <input type='text' class='form-control col-6' name="metaKey[${meta}]" placeholder='Meta Key'>
        </div><div class="col-6">
        <input type='text' class='form-control col-6' name="metaVal[${meta}]" placeholder='Meta Value'>        
        </div></div>`);
        $("#meta").append($key);
        meta++;
    });
    $("#rmMeta").on("click", function (e) {
        e.preventDefault();
        $("#meta").children("div").last().remove();
        meta--;
    });

    $("#addAttr").on("click", function (e) {
        e.preventDefault();
        list = $("#varList").val().split(",");
        console.log(list);
        html = "";
        if (list[0] != "") {
            for (i of list) {
                html += `<div class=" p-2 row">
                <div class='form-floating col'>
                <input name='variations[][${i.trim()}]' class='form-control' placeholder="x" type='text'>
                <label>&nbsp;&nbsp;${i.trim()}</label>
            </div>
            <div class='col-1'>
            </div>
            </div>`;
            }
            html += `<button class='btn btn-danger' id="rmAttr">Remove</button>`;
            $("#attr").append(`<div class='varItem m-3 border'>${html}</div>`);
            $("#varList").val("");
            attr++;
        }
    });
    $(document).on("click","#rmAttr", function (e) {
        e.preventDefault();
        $(this).parents("div.varItem").remove();
        attr--;
    });
    $(document).on("click", ".zbtn-close", function (e) {
        $(this).parents("div.modal").fadeOut();
        $(this).parents("div.modal").remove();
    });
});
function showModal(e) {
    var item = {};
    $.ajax({
        url: "/api/item/" + e,
        method: "POST",
        async: false,
    }).done(function (data) {
        //console.log(data);
        item = JSON.parse(data);
        console.log(item);
    });
    text = "<p class='fs-5 fw-bolder'> Meta:</p><span>";
    for (i in item.meta) {
        text += i + " : " + item.meta[i] + "<br>";
    }
    text += `</span><p class='fs-5 fw-bolder'> Variations:</p>
    <table class='table table-dark table-striped'>
    <tr>
    <th>Attribute</th>
    <th>Value</th>
    <th>Price</th>
    </tr>`;
    for (i in item.variations) {
        text +=
            "<tr><td>" +
            item.variations[i].key +
            " </td><td>" +
            item.variations[i].value +
            " </td><td>" +
            item.variations[i].price +
            "</td></tr>";
    }
    text += "</table>";
    modal = `<div class="modal" style="background-color:rgba(0, 0, 0, .5)" tabindex="-1">
    <div class="modal-dialog text-light">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title">${item.name}</h5>
          <button type="button" class="zbtn-close btn fs-1 p-0 text-light" data-bs-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body bg-dark">
         ${text}
        </div>
        <div class="modal-footer bg-dark">
        </div>
      </div>
    </div>
  </div>`;
    $("body").append(modal);
    $(".modal").fadeIn();
}
