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
        html = `<p> Variation ${attr + 1}</p> 
        <div class="row">`;
        if (list[0] != "") {
            for (i of list) {
                html += `<div class='form-floating col-3'>
                <input name='variations[${attr}][${i.trim()}]' class='form-control' placeholder="x" type='text'>
                <label>&nbsp;&nbsp;${i.trim()}</label>
            </div>`;
            }
            html += `<div class='form-floating col-3'>
            <input required name='variations[${attr}][price]' class='form-control' placeholder="x" type='text'>
            <label>&nbsp;&nbsp;Price</label>
        </div>
        <div class='col-2'><button class='btn btn-danger' id="rmAttr">Remove</button></div>
        </div>`;
            $("#attr").append(
                `<div class='varItem m-3 p-2 border'>${html}</div>`
            );
            $("#varList").val("");
            attr++;
        }
    });
    $(document).on("click", "#rmAttr", function (e) {
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
    text = "<p class='fs-5 mb-0 fw-bolder'> Meta:</p><span>";
    for (i in item.meta) {
        text += i + " : " + item.meta[i] + "<br>";
    }
    text += `<p class='fs-5 mb-0 fw-bolder'>Variations</p><div class='row'> `;
    for (i in item.variations) {
        text+="<div class='container border rounded border-secondary p-2 col-3'>";
        for(j in item.variations[i]){
            text+=`<span>${j} : ${item.variations[i][j]}</span><br>`;
        }
        text+="</div>";
    }
    text += "</div>";
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
