$(document).ready(function () 
{
    function showSuccess(message, duration = 2000, position = "top-right") 
    {
        new AWN({ position, durations: { success: duration } }).success(message);
    }
    function showAlert(message, duration = 2000, position = "top-right") 
    {
        new AWN({ position, durations: { alert: duration } }).alert(message);
    }
    $(function ()
    {
        /* if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        } */
        initializeDataTable('.Table_Liveurs', livreur);
        function initializeDataTable(selector, url)
        {
            var tableLiveur = $(selector).DataTable({
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

                    {data: 'name'                  , name: 'name'},
                    {data: 'cin'                   , name: 'cin'},
                    {data: 'matricule'             , name: 'matricule'},
                    {data: 'phone'                 , name: 'phone'},
                    {
                        data: 'image_cin',
                        name: 'image_cin',
                        render: function(data, type, full, meta) {
                            if (data) {
                                // فك تشفير HTML entities
                                let decodedData = $('<textarea/>').html(data).text();
                    
                                try {
                                    let images = JSON.parse(decodedData);
                                    if (images.length > 0) {
                                        let imageTags = images.map((img, index) => 
                                            `<img src="${img}" width="50" height="50" class="thumb-md me-2 rounded-circle avatar-border" id="image_${full.id}" />`
                                        ).join('');
                                        return imageTags;
                                    }
                                } catch (e) {
                                    console.log("JSON Error:", e);
                                }
                            }
                            return `<span class="badge bg-warning">Aucune photo</span>`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {data: 'username'              , name: 'username'},
                    {data: 'created_at'            , name: 'created_at'},
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
            $(selector + ' tbody').on('click', '.editLivreur', function(e)
            {
                e.preventDefault();
                $('#ModalEditLivreurs').modal("show");
                var IdcClient          = $(this).attr('data-id');
                var name               = $(this).closest('tr').find('td:eq(0)').text();
                var cin                = $(this).closest('tr').find('td:eq(1)').text();
                var matricule          = $(this).closest('tr').find('td:eq(2)').text();
                var phone              = $(this).closest('tr').find('td:eq(3)').text();
                
                
                $('#nameLivreurEdit').val(name);
                $('#matriculeLivreurEdit').val(matricule);
                $('#cinLivreurEdit').val(cin);
                $('#phoneLivreurEdit').val(phone);
                
                $('#EditLivreurs').attr('data-value',IdcClient);
            });

            $(selector + ' tbody').on('click', '.deleteLivreur', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id'); // جلب ID من الزر

                new AWN().confirm(
                    "Êtes-vous sûr de vouloir supprimer ?", 
                    function() {
                        $.ajax({
                            type: "POST",
                            url: deleteLivreur, // الرابط
                            data: {
                                _token: csrf_token,
                                id: id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.status == 200) {
                                    showSuccess("Suppression effectuée avec succès !");
                                    
                                    $('.Table_Liveurs').DataTable().ajax.reload(); // إعادة تحميل الجدول
                                } else {
                                    showAlert("Erreur : " + response.message);
                                    
                                }
                            },
                            error: function() {
                                
                                showAlert("Erreur : " + "Une erreur s'est livreur, veuillez réessayer");
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
    
    function phoneFormatter() {
        $('#PhoneLiveur, #PhoneLiveur').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, ''); // إزالة أي أحرف غير رقمية
    
            if (number.length <= 10) {
                number = number.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, "$1-$2-$3-$4-$5");
            } else {
                number = number.substring(0, 10).replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, "$1-$2-$3-$4-$5");
            }
    
            $(this).val(number);
        });
    }
    
    
    $(phoneFormatter);

    $('#AddLivreurs').on('click',function(e)
    {
        e.preventDefault();
        let files = $('#PhotoCinLivreur')[0].files;

        if (files.length > 2) {
            new AWN().alert("Vous ne pouvez télécharger que 2 photos maximum", { durations: { alert: 5000 } });
            return; // إيقاف العملية
        }
        let formData = new FormData($('#FormAddLivreur')[0]);
        formData.append('_token', csrf_token);

        $('#AddLivreurs').prop('disabled', true).text('Enregistrement...');

        
        $.ajax({
            type: "POST",
            url: AddLiveurs,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) 
            {
                $('#AddLivreurs').prop('disabled', false).text('Sauvegarder');
                if(response.status == 200)
                {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalAddLivreurs').modal('hide');
                    $('.Table_Liveurs').DataTable().ajax.reload();
                    $('#FormAddLivreur')[0].reset();
                }  
                else if(response.status == 404)
                {
                    new AWN().warning(response.message, {durations: {warning: 5000}});
                }
                else if(response.status == 400)
                {
                    $('.ValidationAddLiveur').html("");
                    $('.ValidationAddLiveur').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.ValidationAddLiveur').append('<li>' + list_err + '</li>');
                    });
    
                    setTimeout(() => {
                        $('.ValidationAddLiveur').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }  
                else if (response.status == 404 || response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function() {
                $('#AddLivreurs').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });

    $('#EditLivreurs').on('click',function(e)
    {
        e.preventDefault();
        let files = $('#PhotoCinLivreurUpdate')[0].files;
        let id = $(this).attr('data-value'); // idclient
        
        if (files.length > 2) {
            new AWN().alert("Vous ne pouvez télécharger que 2 photos maximum", { durations: { alert: 5000 } });
            return; // إيقاف العملية
        }

        let formData = new FormData($('#FormLivreurUpdate')[0]);
        formData.append('_token', csrf_token);
        formData.append('id',id);
        $('#EditLivreurs').prop('disabled', true).text('Enregistrement...');

        $.ajax({
            type: "POST",
            url: UpdateLivreurs,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) 
            {
                $('#EditLivreurs').prop('disabled', false).text('Sauvegarder');
                if(response.status == 200)
                {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalEditLivreurs').modal('hide');
                    $('.Table_Liveurs').DataTable().ajax.reload();
                    $('#FormLivreurUpdate')[0].reset();
                }  
                else if(response.status == 404)
                {
                    new AWN().warning(response.message, {durations: {warning: 5000}});
                }
                else if(response.status == 400)
                {
                    $('.ValidationEditLiveur').html("");
                    $('.ValidationEditLiveur').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.ValidationEditLiveur').append('<li>' + list_err + '</li>');
                    });
    
                    setTimeout(() => {
                        $('.ValidationEditLiveur').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }  
                else if (response.status == 404 || response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function() {
                $('#EditLivreurs').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });
});