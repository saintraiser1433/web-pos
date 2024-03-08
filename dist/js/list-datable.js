document.addEventListener("DOMContentLoaded", function () {
  const options = {
    sortClass: "table-sort",
    listClass: "table-tbody",
    searchClass: "listjs-search",

    valueNames: [
      "sort-id",
      "sort-name",
      "sort-gender",
      "sort-address",
      "sort-contact",
      {
        attr: "data-dob",
        name: "sort-date",
      },
    ],
    page: 10,
    pagination: [
      {
        name: "pagination",
        paginationClass: "pagination",
        left: 1,
        right: 1,
        item: '<li class="page-item"><a class="page-link page" href="#"></a></li>',
      },
    ],
  };

  var listjs = new List("listjs", options);

  function update_entries_label(listjs) {
    var total_items = listjs.items.length;
    var visible_items = listjs.visibleItems.length;
    var showing_items_label = total_items + " entries found";
    document.getElementById("listjs-showing-items-label").innerHTML =
      showing_items_label;
  }

  update_entries_label(listjs);

  listjs.on("updated", function (list) {
    update_entries_label(list);
  });

  document
    .getElementById("listjs-items-per-page")
    .addEventListener("change", function (e) {
      var items = this.value;
      listjs.page = items;
      listjs.update();
    });
});
