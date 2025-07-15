$(document).ready(function () 
{
    $(function ()
    {
        
        initializeDataTable('.Table_Charge', Charge);
        function initializeDataTable(selector, url)
        {
            var Table_Charge = $(selector).DataTable({
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

                    {data: 'libelle'               , name: 'libelle'},
                    {data: 'name_company'          , name: 'name_company'},
                    {data: 'name'                   , name: 'name'},
                    {data: 'created_at'                   , name: 'created_at'},
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
            $(selector + ' tbody').on('click', '.EditCharge', function(e)
            {
                e.preventDefault();
                $('#EditChargeModal').modal("show");
                var id                 = $(this).attr('data-id');
                var libelle            = $(this).closest('tr').find('td:eq(0)').text();
                $('#libelle').val(libelle);
                $('#BtnUpdateCharge').attr('data-value',id);
            });


            $(selector + ' tbody').on('click', '.DeleteCharge', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id'); // جلب ID من الزر

                new AWN().confirm(
                    "Êtes-vous sûr de vouloir supprimer ?", 
                    function() {
                        $.ajax({
                            type: "POST",
                            url: Destroy, // الرابط
                            data: {
                                _token: csrf_token,
                                id: id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.status == 200) {
                                    new AWN().success("Suppression effectuée avec succès !", { durations: { success: 5000 } });
                                    $('.Table_Charge').DataTable().ajax.reload(); // إعادة تحميل الجدول
                                } else {
                                    new AWN().alert("Erreur : " + response.message, { durations: { alert: 5000 } });
                                }
                            },
                            error: function() {
                                new AWN().alert("Une erreur s'est produite, veuillez réessayer.", { durations: { alert: 5000 } });
                            }
                        });
                    }, 
                    function() {
                        new AWN().info("Suppression annulée");
                    },
                    {
                        labels: {
                            confirm: "Oui, Supprimer !",
                            cancel: "Annuler"
                        }
                    }
                );
            });

        }
    });
    $('#BtnUpdateCharge').on('click',function(e)
    {
        e.preventDefault();
        let formData = new FormData($('#FormEditCharge')[0]);
        let id       = $(this).attr('data-value');
        formData.append('_token', csrf_token);  
        formData.append('id', id);  
        $('#BtnUpdateCharge').prop('disabled', true).text('Enregistrement...');
        $.ajax({
            type    : "POST",
            url     : update,
            data    : formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) 
            {
                $('#BtnUpdateCharge').prop('disabled', false).text('Sauvegarder');
                if(response.status == 200)
                {
                    new AWN().success(response.message, { durations: { success: 5000 } });
                    $('#EditChargeModal').modal('hide');
                    $('.Table_Charge').DataTable().ajax.reload();
                    $('#FormEditCharge')[0].reset();
                }
                else if (response.status == 400) 
                {
                    $('.ValidationCharge').html("").addClass('alert alert-danger');
                    $.each(response.errors, function(key, error) {
                        $('.ValidationCharge').append('<li>' + error + '</li>');
                    });
                    setTimeout(() => {
                        $('.ValidationCharge').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }
                else if (response.status == 404 || response.status == 500) 
                {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function() {
                $('#BtnUpdateCharge').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });

    $('#BtnAddCharge').on('click',function(e)
    {
        e.preventDefault();

         
        let formData = new FormData($('#FormAddCharge')[0]);
        formData.append('_token', csrf_token);   
        $('#BtnAddCharge').prop('disabled', true).text('Enregistrement...');
        $.ajax({
            type    : "POST",
            url     : store,
            data    : formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) 
            {
                $('#BtnAddCharge').prop('disabled', false).text('Sauvegarder');
                if(response.status == 200)
                {
                    new AWN().success(response.message, { durations: { success: 5000 } });
                    $('#ModalAddCharge').modal('hide');
                    $('.Table_Charge').DataTable().ajax.reload();
                    $('#FormAddCharge')[0].reset();
                }
                else if (response.status == 400) 
                {
                    $('.ValidationChargeAdd').html("").addClass('alert alert-danger');
                    $.each(response.errors, function(key, error) {
                        $('.ValidationChargeAdd').append('<li>' + error + '</li>');
                    });
                    setTimeout(() => {
                        $('.ValidationChargeAdd').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }
                else if (response.status == 404 || response.status == 500) 
                {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function() {
                $('#BtnAddCharge').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });

    });
});