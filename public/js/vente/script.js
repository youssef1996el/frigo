$(document).ready(function () 
{

    $('#AddMarchandiseVente').on('click',function(e)
    {
        e.preventDefault();
        let formData = new FormData($('#FormAddMarchandiseVente')[0]);
        formData.append('_token', csrf_token);

         $('#AddMarchandiseVente').prop('disabled', true).text('Enregistrement...');

         $.ajax({
            type: "POST",
            url: AddVente,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                $('#AddMarchandiseVente').prop('disabled', false).text('Sauvegarder');

                if (response.status == 200) {
                    new AWN().success(response.message, { durations: { success: 5000 } });
                    $('#ModalAddMarchandiseVente').modal('hide');
                    $('.Table_Marchandis_Sortie').DataTable().ajax.reload();
                    $('#FormAddMarchandiseVente')[0].reset();
                } else if (response.status == 400) {
                    $('.ValidationVente').html("").addClass('alert alert-danger');
                    $.each(response.errors, function(key, error) {
                        $('.ValidationVente').append('<li>' + error + '</li>');
                    });
                    setTimeout(() => {
                        $('.ValidationVente').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                } else if (response.status == 404 || response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function() {
                $('#AddMarchandiseVente').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });   


    /* $(function ()
    {

        initializeDataTable('.TableVente', Vente);
        function initializeDataTable(selector, url)
        {
            var tableVente = $(selector).DataTable({
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

                    {data: 'number_box'            , name: 'number_box'     },
                    {data: 'acheteur'              , name: 'acheteur'       },
                    {data: 'vendeur'               , name: 'vendeur'        },
                    {data: 'name_livreur'          , name: 'name_livreur'   },
                    {data: 'user_created'          , name: 'user_created'   },
                    {data: 'created_at'            , name: 'created_at'     },
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
            


           
        }
    }); */
});