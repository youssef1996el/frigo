$(document).ready(function () 
{
    $(function ()
    {
        /* if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        } */
        initializeDataTable('.Table_Information', Info);
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
                    {data: 'phone'                   , name: 'phone'},
                    {data: 'ice'             , name: 'ice'},
                    {data: 'if'                 , name: 'id'},
                    {data: 'capital'                 , name: 'capital'},
                    {data: 'cb'                 , name: 'cb'},
                    {data: 'companie'                 , name: 'companie'},
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
            $(selector + ' tbody').on('click', '.EditInfo', function(e)
            {
                e.preventDefault();
                $('#ModalEditInfo').modal("show");
                var idinfo             = $(this).attr('data-id');
                var name              = $(this).closest('tr').find('td:eq(0)').text();
                var phone              = $(this).closest('tr').find('td:eq(1)').text();
                var ice                = $(this).closest('tr').find('td:eq(2)').text();
                var ifs                = $(this).closest('tr').find('td:eq(3)').text();
                var capital            = $(this).closest('tr').find('td:eq(4)').text();
                var cb                 = $(this).closest('tr').find('td:eq(5)').text();
                var companie           = $(this).closest('tr').find('td:eq(6)').text();
               
                
                
                $('#titleEdit').val(name);
                $('#ICEEdit').val(ice);
                $('#SOCIETEEdit').val(companie);
                $('#phoneEdit').val(phone);
                $('#IFEdit').val(ifs);
                $('#CAPITALEdit').val(capital);
                $('#CARTEBANCAIREEdit').val(cb);
                
                $('#EditInformation').attr('data-value',idinfo);
                

            });


            $(selector + ' tbody').on('click', '.DeleteInformation', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id'); // جلب ID من الزر

                new AWN().confirm(
                    "Êtes-vous sûr de vouloir supprimer ?", 
                    function() {
                        $.ajax({
                            type: "POST",
                            url: DeleteInformation, // الرابط
                            data: {
                                _token: csrf_token,
                                id: id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.status == 200) {
                                    new AWN().success("Suppression effectuée avec succès !", { durations: { success: 5000 } });
                                    $('.Table_Information').DataTable().ajax.reload(); // إعادة تحميل الجدول
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
    
    function phoneFormatter() {
        $('#phone').on('input', function() {
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

    $('#AddInfo').on('click',function(e)
    {
        e.preventDefault();
       
        let formData = new FormData($('#FormAddInfo')[0]);
        formData.append('_token', csrf_token);

        $('#AddInfo').prop('disabled', true).text('Enregistrement...');

        
        $.ajax({
            type: "POST",
            url: AddInformation,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) 
            {
                $('#AddInfo').prop('disabled', false).text('Sauvegarder');
                if(response.status == 200)
                {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalAddInformation').modal('hide');
                    $('.Table_Information').DataTable().ajax.reload();
                    $('#FormAddInfo')[0].reset();
                }  
                else if(response.status == 404)
                {
                    new AWN().warning(response.message, {durations: {warning: 5000}});
                }
                else if(response.status == 400)
                {
                    $('.ValidationAddInformation').html("");
                    $('.ValidationAddInformation').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.ValidationAddInformation').append('<li>' + list_err + '</li>');
                    });
    
                    setTimeout(() => {
                        $('.ValidationAddInformation').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }  
                else if (response.status == 404 || response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function() {
                $('#AddInfo').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });

    $('#EditInformation').on('click',function(e)
    {
        e.preventDefault();
        
        let id = $(this).attr('data-value'); // idclient
        
        let formData = new FormData($('#FormInfoUpdate')[0]);
        formData.append('_token', csrf_token);
        formData.append('id',id);
        $('#EditInformation').prop('disabled', true).text('Enregistrement...');

        $.ajax({
            type: "POST",
            url: UpdateInformation,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) 
            {
                $('#EditInformation').prop('disabled', false).text('Sauvegarder');
                if(response.status == 200)
                {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalEditInfo').modal('hide');
                    $('.Table_Information').DataTable().ajax.reload();
                    $('#FormInfoUpdate')[0].reset();
                }  
                else if(response.status == 404)
                {
                    new AWN().warning(response.message, {durations: {warning: 5000}});
                }
                else if(response.status == 400)
                {
                    $('.ValidationEditInfo').html("");
                    $('.ValidationEditInfo').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.ValidationEditInfo').append('<li>' + list_err + '</li>');
                    });
    
                    setTimeout(() => {
                        $('.ValidationEditInfo').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }  
                else if (response.status == 404 || response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function() {
                $('#EditInformation').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });
});