$(document).ready(function () {
    
        
  
    



    // fetch data company
    
    $(function ()
    {
        /* if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        } */
        initializeDataTable('.Table_Comptabilite', Comptabilite);
        function initializeDataTable(selector, url)
        {
            var tablecCompany = $(selector).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    dataSrc: function (json) {
                        if (json.data.length === 0) {
                            $('.paging_full_numbers').css('display', 'none');
                        }
                        return json.data;
                    }
                },
                columns:
                [

                    {data: 'name'             , name: 'name'},
                    {data: 'status'             , name: 'status'},
                    {data: 'nameuser'        , name: 'nameuser'},
                    {data: 'created_at'     , name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}

                ],
                language: {
                    "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                    "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ",",
                    "sLengthMenu": "Afficher _MENU_ éléments",
                    "sLoadingRecords": "Chargement...",
                    "sProcessing": "Traitement...",
                    "sSearch": "Rechercher :",
                    "sZeroRecords": "Aucun élément correspondant trouvé",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sLast": "Dernier",
                        "sNext": "Suivant",
                        "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        }
                    }
                }
            });
            $(selector + ' tbody').on('click', '.editComptabilite', function(e)
            {
                e.preventDefault();
                var IdcComptabilite   = $(this).attr('data-id');
                var nameComptabilite  = $(this).closest('tr').find('td:eq(0)').text();
                var statusComptabilite  = $(this).closest('tr').find('td:eq(1)').text();
                var isActive = statusComptabilite === 'Active' ? 1 : 0;
                $('#nameComptabiliteEdit').val(nameComptabilite);
                $('#statusComptabiliteEdit').val(isActive);
                $('#EditComptabilite').attr('data-value',IdcComptabilite);
                

            });

            

        }
    });
        
   
    // Add Company 
    $('#AddComptabilite').on('click',function(e) {
        e.preventDefault(); // هذه السطر مهم لمنع الإرسال الافتراضي للنموذج.
        
        let nameComptabilite = $('#nameComptabilite').val();
        let statusComptabilite = $('#statusComptabilite').val();
        $.ajax({
            type: "post",
            url: AddComptabilite,
            data: {
                'name': nameComptabilite,
                'status': statusComptabilite,
                '_token': csrf_token,
            },
            dataType: "json",
            success: function(response) {
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalAddComptabilite').modal('hide');
                    $('.Table_Comptabilite').DataTable().ajax.reload();
                    // هنا يمكن تحديث الجدول أو البيانات المعروضة.
                } else if(response.status == 400) {
                    $('.ValidationAddComptabilite').html("");
                    $('.ValidationAddComptabilite').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.ValidationAddComptabilite').append('<li>' + list_err + '</li>');
                    });
    
                    setTimeout(() => {
                        $('.ValidationAddComptabilite').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }    
            }
        });
    }); // end Add company


    // Edit Company

    $('#EditComptabilite').on('click',function(e)
    {
        e.preventDefault();
        var data =
        {
            'name'          : $('#nameComptabiliteEdit').val(),
            '_token'        : csrf_token,
            'status'         : $('#statusComptabiliteEdit').val(),
            'id'            : $(this).attr('data-value'),

        };

        $.ajax({
            type: "post",
            url: UpdateComptabilite,
            data: data,
            dataType: "json",
            success: function (response)
            {

                if(response.status == 200)
                {
                    new AWN().success(response.message, {durations: {success: 0}});
                    $('.ValidationAddComptabilite').html("");
                    $('#ModalEditComptabilite').modal("hide");
                    $('.Table_Comptabilite').DataTable().ajax.reload();
                }
                else if(response.status == 422)
                {
                    $('.ValidationAddComptabilite').html("");
                    $('.ValidationAddComptabilite').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.ValidationAddCompany').append('<li>' + list_err + '</li>');
                    });
    
                    setTimeout(() => {
                        $('.ValidationAddComptabilite').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }
                else if(response.status == 404)
                {
                    new AWN().warning(response.message, {durations: {warning: 5000}})
                    /* setTimeout(() => {
                        
                    }, 5000); */
                    
                }
            }
        });
    });


    
    



    

    
});