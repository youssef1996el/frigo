$(document).ready(function () 
{
    if (window.Laravel && window.Laravel.ErrorsInfos) {
       
        new AWN().alert(window.Laravel.ErrorsInfos, { durations: { alert: 5000 } });
    }
    if (window.Laravel && window.Laravel.ErrorsNumberStartBon) {
       
        new AWN().alert(window.Laravel.ErrorsNumberStartBon, { durations: { alert: 5000 } });
    }
    $(function ()
    {
        
        initializeDataTable('.Table_RetourCaisse', caisseretour);
        function initializeDataTable(selector, url)
        {
            var tableLiveur = $(selector).DataTable({
                dom: 'Bfrtip', // لإظهار الأزرار
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export excel',
                        className: 'btn btn-success',
                        filename: 'table caisse retour'
                    }
                ],
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
                    {data: 'client_name'           , name: 'client_name'},
                    {data: 'number_box'            , name: 'number_box'},
                    {data: 'cumul'                 , name: 'cumul'},
                    {data: 'namelivreur'           , name: 'namelivreur'},
                    {data: 'cin'                   , name: 'cin'},
                    {data: 'matricule'             , name: 'matricule'},
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data, type, row) {
                            return `<span class="badge bg-primary text-uppercase" style="font-size:15px">${data.charAt(0)}</span>`;
                        }
                    },
                    {data: 'name'                  , name: 'name'},
                    {data: 'created_at'            , name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}

                ],
                columnDefs: [
                    {
                        targets: '_all',
                        createdCell: function(td) {
                            $(td).css('white-space', 'nowrap');
                        }
                    }
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
            $(selector + ' tbody').on('click', '.editCaisseRetour', function(e)
            {
                e.preventDefault();
                $('#ModalEditRetourCaisse').modal("show");
                var id                 = $(this).attr('data-id');
                var idclient           = $(this).attr('data-client');
                var idlivreur          = $(this).attr('data-livreur');
                var number_box         = $(this).closest('tr').find('td:eq(1)').text();
               
                
                
                $('#idclientEdit').val(idclient);
                $('#idlivreurEdit').val(idlivreur);
                $('#number_boxEdit').val(number_box);
               
                $('#UpdateCaisseRetour').attr('data-value',id);
                

            });

            $(selector + ' tbody').on('click', '.deleteCaisseRetour', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id'); // جلب ID من الزر

                new AWN().confirm(
                    "Êtes-vous sûr de vouloir supprimer ?", 
                    function() {
                        $.ajax({
                            type: "POST",
                            url: DeleteCaisseRetour, // الرابط
                            data: {
                                _token: csrf_token,
                                id: id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.status == 200) {
                                    new AWN().success("Suppression effectuée avec succès !", { durations: { success: 5000 } });
                                    $('.Table_RetourCaisse').DataTable().ajax.reload(); // إعادة تحميل الجدول
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

    $(document).on('input', 'input[name="number_box"]', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    $('#idclient').on('change', function () {
       
        
        $.ajax({
            type: "get",
            url: GetNomberCaisseByClient,
            data: 
            {
                id : $(this).val(),
            },
            dataType: "json",
            success: function (response) {
                if(response.status == 200)
                {
                    $('#NombreBoxCaisseVide').text(response.Nombre_Caisse_Vide);
                    $('#NombreBoxMarchandisEntree').text(response.Nombre_Marchandise_Entree);
                    $('#NombreBoxMarchandisSortie').text(response.Nombre_Marchandise_Sortie);
                    $('#NombreBoxCaisseRetour').text(response.Nombre_Caisse_Retour);
                }
            }
        });
    
    });

    $('#AddCaisseRetour').on('click',function(e)
    {
        e.preventDefault();
        let formData = new FormData($('#FormAddRetourCaisse')[0]);
        formData.append('_token', csrf_token); 
       
        $('#AddCaisseRetour').prop('disabled', true).text('Enregistrement...');
       
        $.ajax({
            type    : "POST",
            url     : AddCaisseRetour,
            data    : formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) 
            {
                $('#AddCaisseRetour').prop('disabled', false).text('Sauvegarder');
                if(response.status == 200)
                {
                    new AWN().success(response.message, { durations: { success: 5000 } });
                    $('#ModalAddRetourCaisse').modal('hide');
                    $('.Table_RetourCaisse').DataTable().ajax.reload();
                    $('#FormAddRetourCaisse')[0].reset();
                }
                else if (response.status == 400) 
                {
                    $('.ValidationAddRetourCaisses').html("").addClass('alert alert-danger');
                    $.each(response.errors, function(key, error) {
                        $('.ValidationAddRetourCaisses').append('<li>' + error + '</li>');
                    });
                    setTimeout(() => {
                        $('.ValidationAddRetourCaisses').fadeOut('slow', function() {
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
                $('#AddCaisseRetour').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });


    $('#UpdateCaisseRetour').on('click', function(e) {
        e.preventDefault();
        let formData = new FormData($('#FormCaisseRetourUpdate')[0]);
        formData.append('_token', csrf_token);
        formData.append('id',$(this).attr('data-value'));
        $('#UpdateCaisseRetour').prop('disabled', true).text('Mise à jour...');
    
        $.ajax({
            type: "POST",
            url: UpdateCaisseRetour,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                $('#UpdateCaisseRetour').prop('disabled', false).text('Modifier');
    
                if (response.status == 200) {
                    new AWN().success(response.message, { durations: { success: 5000 } });
                    $('#ModalEditRetourCaisse').modal('hide'); // إخفاء المودال
                    $('.Table_RetourCaisse').DataTable().ajax.reload(); // إعادة تحميل الجدول
                    $('#FormCaisseRetourUpdate')[0].reset(); // تفريغ الفورم
                }
                else if (response.status == 400) {
                    $('.ValidationUpdateCaisseRetour').html("").addClass('alert alert-danger');
                    $.each(response.errors, function(key, error) {
                        $('.ValidationUpdateCaisseRetour').append('<li>' + error + '</li>');
                    });
    
                    setTimeout(() => {
                        $('.ValidationUpdateCaisseRetour').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }
                else if (response.status == 404 || response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function() {
                $('#UpdateCaisseRetour').prop('disabled', false).text('Modifier');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });
    
});