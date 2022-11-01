
function suggetion() {

    $('#sug_input').keyup(function (e) {
        var formData = {
            'product_name': $('input[name=title]').val()

        };

        if (formData['product_name'].length >= 1) {

            // process the form
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: formData,
                dataType: 'json',
                encode: true
            })
                .done(function (data) {
                    $('#result').html(data).fadeIn();
                    $('#result li').click(function () {

                        $('#sug_input').val($(this).text());
                        $('#result').fadeOut(500);

                    });

                    $("#sug_input").blur(function () {
                        $("#result").fadeOut(500);
                    });

                });

        } else {
            $("#result").hide();

        };

        e.preventDefault();
    });

}

function findProductButton() {
    alert("No result found!")
    return false;
}

$('#search-form').submit(function (e) {

    var formData = {
        'subunit_id': $('select[name=subunit]').val(),
        'subdivision_id': $('select[name=subdivision]').val(),
        'location_id': $('select[name=location]').val(),
        'dep_point_id': $('select[name=dep_point]').val(),
        'categorie_id': $('select[name=categorie]').val(),
        'brand_id': $('select[name=brand]').val(),
        'model_id': $('select[name=model]').val(),
        'availability_id': $('select[name=availability]').val(),
        'status_id': $('select[name=status]').val()
    };
    console.log(formData);
    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: formData,
        dataType: 'json',
        encode: true
    })
        .done(function (data) {
            //console.log(data);
            $('#product_info').html(data).show();

            /* table = $('#data_table').DataTable( {
                scrollX: true,
                searching: false,
            } );
 */
            $('.datePicker').datepicker('update', new Date());

        }).fail(function (data) {
            $('#product_info').html(data).show();
        });
    e.preventDefault();
});

/*
$('#sug-form').submit(function(e) {
  var formData = {
      'p_name' : $('input[name=title]').val()
  };
     //process the form
    $.ajax({
        type        : 'POST',
        url         : 'ajax.php',
        data        : formData,
        dataType    : 'json',
        encode      : true
    })
        .done(function(data) {
            //console.log(data);
            $('#product_info').html(data).show();
            total();
            $('.datePicker').datepicker('update', new Date());

        }).fail(function() {
            $('#product_info').html(data).show();
        });
  e.preventDefault();
});
*/
$(document).ready(function () {

    //tooltip
    $('[data-toggle="tooltip"]').tooltip();

    $('.submenu-toggle').click(function () {
        $(this).parent().children('ul.submenu').toggle(200);
    });
    //suggetion for finding product names
    suggetion();
    // Callculate total ammont

    $('.datepicker')
        .datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
});
