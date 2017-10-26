$(document).ready(function () {
    console.log("hi");
    $('.button').click(function (e) {
        console.log("hi");
	  $.ajax({
            type: "POST",
            url: '/mesimatges',
            success: function (response) {
			console.log(response);
			var data_array = $.parseJSON(response);
			console.log(data_array.imgs.length);

			document.getElementById();

		}
	});
  });
});
