$(document).ready(function () {







    // fetch data company

    $(function ()
    {
        /* if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        } */
        initializeDataTable('.Table_Company', company);
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
            $(selector + ' tbody').on('click', '.editCompany', function(e)
            {
                e.preventDefault();
                var IdcCompany   = $(this).attr('data-id');
                var nameCompany  = $(this).closest('tr').find('td:eq(0)').text();
                var statusCompany  = $(this).closest('tr').find('td:eq(1)').text();
                var isActive = statusCompany === 'Active' ? 1 : 0;
                $('#nameCompanyEdit').val(nameCompany);
                $('#statusCompanyEdit').val(isActive);
                $('#EditCompany').attr('data-value',IdcCompany);


            });

            /* $(selector + ' tbody').on('click', '.trash', function(e)
            {
                e.preventDefault();
                var IdCharge  = $(this).attr('value');
                swal({
                    title: "es-tu sûr de supprimer cette charge",
                    text: "Une fois supprimée, vous ne pourrez plus récupérer cette charge !",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                    if (willDelete)
                    {
                        var data =
                        {
                            'id'         : IdCharge,
                            '_token'     : csrf_token,
                        };
                        $.ajax({
                            type: "post",
                            url: TrashCharge,
                            data: data,

                            dataType: "json",
                            success: function (response)
                            {
                                if(response.status == 200)
                                {
                                    swal("Votre charge a été supprimée !", {
                                        icon: "success",
                                    });
                                    $('.TableCharge').DataTable().ajax.reload();
                                }
                                else if(response.status ==400)
                                {
                                    swal("Oops !", response.message, "error");
                                }
                                else if(response.status ==404)
                                {
                                    swal("Oops !", response.message, "error");
                                }
                            }
                        });

                    }
                    else
                    {
                        swal("Votre charge est sécurisée !");
                    }
                    });
            });

            $(selector + ' tbody').on('click', '.ChangeDate', function(e)
            {
                e.preventDefault();
                var idcharge = $(this).attr('value');
                $('#idCharge').val(idcharge);
                $('#ModelChargeEditDate').modal('show');
            }); */

        }
    });


    // Add Company
    $('#AddCompany').on('click',function(e) {
        e.preventDefault(); // هذه السطر مهم لمنع الإرسال الافتراضي للنموذج.

        let nameCompany = $('#nameCompany').val();
        let statusCompany = $('#statusCompany').val();
        $.ajax({
            type: "post",
            url: AddCompany,
            data: {
                'name': nameCompany,
                'status': statusCompany,
                '_token': csrf_token,
            },
            dataType: "json",
            success: function(response) {
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalAddCompany').modal('hide');
                    $('.Table_Company').DataTable().ajax.reload();
                    // هنا يمكن تحديث الجدول أو البيانات المعروضة.
                } else if(response.status == 400) {
                    $('.ValidationAddCompany').html("");
                    $('.ValidationAddCompany').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.ValidationAddCompany').append('<li>' + list_err + '</li>');
                    });

                    setTimeout(() => {
                        $('.ValidationAddCompany').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }
            }
        });
    }); // end Add company


    // Edit Company

    $('#EditCompany').on('click',function(e)
    {
        e.preventDefault();
        var data =
        {
            'name'          : $('#nameCompanyEdit').val(),
            '_token'        : csrf_token,
            'status'         : $('#statusCompanyEdit').val(),
            'id'            : $(this).attr('data-value'),

        };

        $.ajax({
            type: "post",
            url: UpdateCompany,
            data: data,
            dataType: "json",
            success: function (response)
            {

                if(response.status == 200)
                {
                    new AWN().success(response.message, {durations: {success: 0}});
                    $('.ValidationAddCompany').html("");
                    $('#ModalEditCompany').modal("hide");
                    $('.Table_Company').DataTable().ajax.reload();
                }
                else if(response.status == 422)
                {
                    $('.ValidationAddCompany').html("");
                    $('.ValidationAddCompany').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.ValidationAddCompany').append('<li>' + list_err + '</li>');
                    });

                    setTimeout(() => {
                        $('.ValidationAddCompany').fadeOut('slow', function() {
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



    //$('#TableClientByCompany').DataTable();


    $(function ()
    {

        TableClientByCompany('#TableClientByCompany');
        function TableClientByCompany(selector)
        {
            var TableClientByCompany = $(selector).DataTable({



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
    });

    $('#TableClientByCompany').on('change', 'input[type="checkbox"]', function () {
        let $row = $(this).closest('tr'); // احصل على الصف الحالي

        // الحصول على جميع الـ checkboxات في هذا الصف
        let $checkboxes = $row.find('input[type="checkbox"]');

        // تحقق كم عددها التي تم تحديدها
        let checkedCount = $checkboxes.filter(':checked').length;

        // إذا تم اختيار checkboxين في نفس الصف، عرض رسالة
        if (checkedCount === 2) {
            //alert('Erreur : Vous ne pouvez pas sélectionner Ajouter et Supprimer pour le même client.');

            new AWN().warning('Erreur : Vous ne pouvez pas sélectionner Ajouter et Supprimer pour le même client.', {durations: {warning: 5000}});

            // اختياري: إلغاء تحديد هذا الـ checkbox الأخير
            $(this).prop('checked', false);
        }
    });

    $('#TableLivreurByCompany').on('change', 'input[type="checkbox"]', function () {
        let $row = $(this).closest('tr'); // احصل على الصف الحالي

        // الحصول على جميع الـ checkboxات في هذا الصف
        let $checkboxes = $row.find('input[type="checkbox"]');

        // تحقق كم عددها التي تم تحديدها
        let checkedCount = $checkboxes.filter(':checked').length;

        // إذا تم اختيار checkboxين في نفس الصف، عرض رسالة
        if (checkedCount === 2) {
            //alert('Erreur : Vous ne pouvez pas sélectionner Ajouter et Supprimer pour le même client.');

            new AWN().warning('Erreur : Vous ne pouvez pas sélectionner Ajouter et Supprimer pour le même livreur.', {durations: {warning: 5000}});

            // اختياري: إلغاء تحديد هذا الـ checkbox الأخير
            $(this).prop('checked', false);
        }
    });

    $('#TableProductByCompany').on('change', 'input[type="checkbox"]', function () {
        let $row = $(this).closest('tr'); // احصل على الصف الحالي

        // الحصول على جميع الـ checkboxات في هذا الصف
        let $checkboxes = $row.find('input[type="checkbox"]');

        // تحقق كم عددها التي تم تحديدها
        let checkedCount = $checkboxes.filter(':checked').length;

        // إذا تم اختيار checkboxين في نفس الصف، عرض رسالة
        if (checkedCount === 2) {
            //alert('Erreur : Vous ne pouvez pas sélectionner Ajouter et Supprimer pour le même client.');

            new AWN().warning('Erreur : Vous ne pouvez pas sélectionner Ajouter et Supprimer pour le même produit.', {durations: {warning: 5000}});

            // اختياري: إلغاء تحديد هذا الـ checkbox الأخير
            $(this).prop('checked', false);
        }
    });

    $('#SaveClientByCompany').on('click', function (e)
    {
        e.preventDefault();

        let ajouterIds = [];
        let supprimerIds = [];

        $('#TableClientByCompany tbody tr').each(function ()
        {
            let row = $(this);
            let ajouterCheckbox = row.find('input.ajouter');
            let supprimerCheckbox = row.find('input.supprimer');

            if (ajouterCheckbox.is(':checked') && supprimerCheckbox.is(':checked')) {
                new AWN().warning("Erreur : Vous ne pouvez pas sélectionner Ajouter et Supprimer pour le même client.", {durations: {warning: 5000}});
                ajouterCheckbox.prop('checked', false);
                supprimerCheckbox.prop('checked', false);
                return;
            }

            if (ajouterCheckbox.is(':checked')) {
                ajouterIds.push(ajouterCheckbox.val());
            }

            if (supprimerCheckbox.is(':checked')) {
                supprimerIds.push(supprimerCheckbox.val());
            }
        });

        // ✅ التحقق هل لا شيء محدد
        if (ajouterIds.length === 0 && supprimerIds.length === 0) {
            new AWN().warning("Veuillez sélectionner au moins un client à ajouter ou à supprimer.", {durations: {warning: 5000}});
            return;
        }

        let idCompany = $("#select-company").val();

        $.ajax({
            url: SaveClientByCompany,
            method: "POST",
            data: {
                _token: csrf_token,
                idcompany: idCompany,
                ajouter: ajouterIds,
                supprimer: supprimerIds,
                role: 'Client'
            },
            success: function (response) {
                new AWN().success('Opération effectuée avec succès !', {durations: {success: 5000}});
                location.reload();
            },
            error: function () {
                new AWN().warning('Erreur lors de la sauvegarde.', {durations: {warning: 5000}});
            }
        });
    });



    $('#SaveLivreurByCompany').on('click', function (e)
    {
        e.preventDefault();

        let ajouterIds = [];
        let supprimerIds = [];

        $('#TableLivreurByCompany tbody tr').each(function ()
        {
            let row = $(this);
            let ajouterCheckbox = row.find('input.ajouter');
            let supprimerCheckbox = row.find('input.supprimer');

            if (ajouterCheckbox.is(':checked') && supprimerCheckbox.is(':checked')) {
                new AWN().warning("Erreur : Vous ne pouvez pas sélectionner Ajouter et Supprimer pour le même livreur.", {durations: {warning: 5000}});
                ajouterCheckbox.prop('checked', false);
                supprimerCheckbox.prop('checked', false);
                return;
            }

            if (ajouterCheckbox.is(':checked')) {
                ajouterIds.push(ajouterCheckbox.val());
            }

            if (supprimerCheckbox.is(':checked')) {
                supprimerIds.push(supprimerCheckbox.val());
            }
        });

        // ✅ التحقق هل لا شيء محدد
        if (ajouterIds.length === 0 && supprimerIds.length === 0) {
            new AWN().warning("Veuillez sélectionner au moins un livreur à ajouter ou à supprimer.", {durations: {warning: 5000}});
            return;
        }

        let idCompany = $("#CompanyLivreur").val();

        $.ajax({
            url: SaveLivreurByCompany,
            method: "POST",
            data: {
                _token: csrf_token,
                idcompany: idCompany,
                ajouter: ajouterIds,
                supprimer: supprimerIds,
                role: 'Livreur'
            },
            success: function (response) {
                new AWN().success('Opération effectuée avec succès !', {durations: {success: 5000}});
                location.reload();
            },
            error: function () {
                new AWN().warning('Erreur lors de la sauvegarde.', {durations: {warning: 5000}});
            }
        });
    });




    $('#SaveProductByCompany').on('click', function (e)
    {
        e.preventDefault();

        let ajouterIds = [];
        let supprimerIds = [];

        $('#TableProductByCompany tbody tr').each(function ()
        {
            let row = $(this);
            let ajouterCheckbox = row.find('input.ajouter');
            let supprimerCheckbox = row.find('input.supprimer');

            if (ajouterCheckbox.is(':checked') && supprimerCheckbox.is(':checked')) {
                new AWN().warning("Erreur : Vous ne pouvez pas sélectionner Ajouter et Supprimer pour le même produit.", {durations: {warning: 5000}});
                ajouterCheckbox.prop('checked', false);
                supprimerCheckbox.prop('checked', false);
                return;
            }

            if (ajouterCheckbox.is(':checked')) {
                ajouterIds.push(ajouterCheckbox.val());
            }

            if (supprimerCheckbox.is(':checked')) {
                supprimerIds.push(supprimerCheckbox.val());
            }
        });

        // ✅ التحقق هل لا شيء محدد
        if (ajouterIds.length === 0 && supprimerIds.length === 0) {
            new AWN().warning("Veuillez sélectionner au moins un produit à ajouter ou à supprimer.", {durations: {warning: 5000}});
            return;
        }

        let idCompany = $("#CompanyProduct").val();

        $.ajax({
            url: SaveProductByCompany,
            method: "POST",
            data: {
                _token: csrf_token,
                idcompany: idCompany,
                ajouter: ajouterIds,
                supprimer: supprimerIds,
                role: 'Product'
            },
            success: function (response) {
                new AWN().success('Opération effectuée avec succès !', {durations: {success: 5000}});
                location.reload();
            },
            error: function () {
                new AWN().warning('Erreur lors de la sauvegarde.', {durations: {warning: 5000}});
            }
        });
    });


    $('#BtnDisplayClient').on('click',function(e)
    {
        e.preventDefault();
        $.ajax({
            type: "get",
            url: DisplayClientBycompany,
            data: "data",
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    $('#select-company').val(response.IdCompany).change();
                    let clientIds = response.DataClient.map(item => item.idpermission);
                    $('#TableClientByCompany tbody tr').each(function () {
                        let checkboxValue = $(this).find('.ajouter').val();
                        if (clientIds.includes(parseInt(checkboxValue))) {
                            $(this).find('.ajouter').prop('checked', true);
                        } else {
                            $(this).find('.ajouter').prop('checked', false);
                        }
                    });
                }
            }
        });
    });

    $('#select-company').on('change',function(e)
    {
        e.preventDefault();
        let idcompany = $(this).val();
        $.ajax({
            type: "get",
            url: DisplayClientBycompany,
            data:
            {
                idcompany : idcompany,
            },
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //$('#select-company').val(response.IdCompany).change();
                    let clientIds = response.DataClient.map(item => item.idpermission);
                    $('#TableClientByCompany tbody tr').each(function () {
                        let checkboxValue = $(this).find('.ajouter').val();
                        if (clientIds.includes(parseInt(checkboxValue))) {
                            $(this).find('.ajouter').prop('checked', true);
                        } else {
                            $(this).find('.ajouter').prop('checked', false);
                        }
                    });
                }
            }
        });
    });

    $('#BtnDisplayLivreur').on('click',function(e)
    {
        e.preventDefault();
        $.ajax({
            type: "get",
            url: DisplayLivreurBycompany,
            data: "data",
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    $('#CompanyLivreur').val(response.IdCompany).change();
                    let LivreurIds = response.DataLivreur.map(item => item.idpermission);
                    $('#TableLivreurByCompany tbody tr').each(function () {
                        let checkboxValue = $(this).find('.ajouter').val();
                        if (LivreurIds.includes(parseInt(checkboxValue))) {
                            $(this).find('.ajouter').prop('checked', true);
                        } else {
                            $(this).find('.ajouter').prop('checked', false);
                        }
                    });
                }
            }
        });
    });

    $('#CompanyLivreur').on('change',function(e)
    {
        e.preventDefault();
        let idcompany = $(this).val();
        $.ajax({
            type: "get",
            url: DisplayLivreurBycompany,
            data:
            {
                idcompany : idcompany,
            },
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //$('#CompanyLivreur').val(response.IdCompany).change();
                    let LivreurIds = response.DataLivreur.map(item => item.idpermission);
                    $('#TableLivreurByCompany tbody tr').each(function () {
                        let checkboxValue = $(this).find('.ajouter').val();
                        if (LivreurIds.includes(parseInt(checkboxValue))) {
                            $(this).find('.ajouter').prop('checked', true);
                        } else {
                            $(this).find('.ajouter').prop('checked', false);
                        }
                    });
                }
            }
        });
    });


    $('#BtnDisplayProduct').on('click', function (e) {
        e.preventDefault();

        // Disable the button to prevent multiple clicks
        $(this).prop('disabled', true);

        $.ajax({
            type: "get",
            url: DisplayProductBycompany,
            data: "data",
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    $('#CompanyProduct').val(response.IdCompany).change();
                    let ProductIds = response.DataProduct.map(item => item.idpermission);
                    $('#TableProductByCompany tbody tr').each(function () {
                        let checkboxValue = $(this).find('.ajouter').val();
                        if (ProductIds.includes(parseInt(checkboxValue))) {
                            $(this).find('.ajouter').prop('checked', true);
                        } else {
                            $(this).find('.ajouter').prop('checked', false);
                        }
                    });
                }
            },
            complete: function () {
                // Re-enable the button after the request completes
                $('#BtnDisplayProduct').prop('disabled', false);
            }
        });
    });


    $('#CompanyProduct').on('change',function(e)
    {
        e.preventDefault();
        let idcompany = $(this).val();
        $.ajax({
            type: "get",
            url: DisplayProductBycompany,
            data:
            {
                idcompany : idcompany,
            },
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //$('#CompanyProduct').val(response.IdCompany).change();
                    let ProductIds = response.DataProduct.map(item => item.idpermission);
                    $('#TableProductByCompany tbody tr').each(function () {
                        let checkboxValue = $(this).find('.ajouter').val();
                        if (ProductIds.includes(parseInt(checkboxValue))) {
                            $(this).find('.ajouter').prop('checked', true);
                        } else {
                            $(this).find('.ajouter').prop('checked', false);
                        }
                    });
                }
            }
        });
    });




});
