(function ($) {
    'use strict';
    $(function () {
        if ($('#editable-form').length) {
            // $.fn.editable.defaults.mode = 'inline';
            $.fn.editableform.buttons =
                `<button type="submit" class="btn btn-primary btn-sm editable-submit">
                <i class="fa fa-fw fa-check"></i>
                </button>
                <button type="button" class="btn btn-warning    btn-sm editable-cancel">
                <i class="fa fa-fw fa-times"></i>
                </button>`;

            $('.editHead').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_head.php'
            });

            $('.editKnit').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_knitting.php'
            });

            $('.editFin').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_finishing.php'
            });

            $('.editDye').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_dyeing.php'
            });

            $('.editFin2').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_Fin2.php'
            });

            $('.editBrush').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_Brush.php'
            });

            $('.editPrint').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_Print.php'
            });

            $('.editfin3').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_finishing3.php'
            });

            $('.editqcf').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_qcf.php'
            });

            $('.edittdp').editable({
                type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_tdp.php'
            });

            $('.editlp').editable({
                // type: 'text',
                title: 'Enter value',
                url: 'pages/api/api_editable_lp.php'
            });
        }
    });

    $(document).on("click", "input[type=checkbox]", function () {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/api/check_table.php",
            data: {
                id: $("#id").val(),
                field: $(this).val()
            },
            success: function (response) {
                if (response.session == "LIB_SUCCSS_200") {
                    console.log('true')
                } else {
                    alert("error !")
                }
            },
            error: function () {
                alert("Error");
            }
        });
    })
})(jQuery);